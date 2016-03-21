<?php
/**
 * File: Facebook.php
 * User: zacharydubois
 * Date: 2016-02-29
 * Time: 11:55
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

use \Facebook\Exceptions\FacebookSDKException;
use \Facebook\Exceptions\FacebookResponseException;


/**
 * Class Facebook
 *
 * Used for Facebook login and content pulling.
 *
 * @package dfp
 */
class Facebook {
    private
        $config,
        $fb;

    /**
     * Facebook constructor.
     *
     * Creates Facebook SDK object and initializes Config and Session objects.
     *
     * @param Config $config
     * @param Session $session
     */
    public function __construct(Config $config, Session $session) {
        $this->config = $config;
        $this->session = $session;
        $this->fb = new \Facebook\Facebook(array(
            'app_id'                  => $this->config->get('facebook', 'id'),
            'app_secret'              => $this->config->get('facebook', 'secret'),
            'persistent_data_handler' => new FacebookPersistentDataHandler($session),
            'default_graph_version'   => 'v2.5'
        ));
    }

    /**
     * Authorize URL
     *
     * Generates an authorization URL to start OAuth 2 flow.
     *
     * @return string
     */
    public function authorizeURL() {
        $helper = $this->fb->getRedirectLoginHelper();
        $permissions = array(
            'user_posts',
            'public_profile',
            'email'
        );

        $url = $helper->getLoginUrl(Utility::buildFullLink($this->config, false, 'session/callback/facebook'), $permissions);

        return $url;
    }

    /**
     * Access Token
     *
     * Generates an access token to be used in pulling content.
     *
     * @return true
     * @throws Exception
     * @throws FacebookSDKException
     */
    public function accessToken() {
        $helper = $this->fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookResponseException $e) {
            // When Graph returns an error
            throw new Exception('Graph returned an error: ' . $e->getMessage());
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            throw new Exception('Facebook SDK returned an error: ' . $e->getMessage());
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                throw new Exception('Error: ' . $helper->getError() . ' ' . $helper->getErrorCode() . ' ' . $helper->getErrorReason() . ' ' . $helper->getErrorDescription());
            } else {
                throw new Exception('Facebook: Bad request.');
            }
        }

        $client = $this->fb->getOAuth2Client();
        $tokenMetadata = $client->debugToken($accessToken);

        // Validate app
        $tokenMetadata->validateAppId($this->config->get('facebook', 'id'));
        // Validate expiration
        $tokenMetadata->validateExpiration();

        if (!$accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $client->getLongLivedAccessToken($accessToken);
            } catch (FacebookSDKException $e) {
                throw new Exception('Error getting long-lived access token: ' . $e->getMessage());
            }
        }

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $this->fb->get('/me?fields=name', $accessToken);
        } catch (FacebookResponseException $e) {
            throw new Exception('Graph returned an error: ' . $e->getMessage());
        } catch (FacebookSDKException $e) {
            throw new Exception('Facebook SDK returned an error: ' . $e->getMessage());
        }

        $user = $response->getGraphUser();

        $this->session->setTMP('facebook_name', $user['name']);
        $this->session->setTMP('facebook_access_token', (string)$accessToken);

        return true;
    }

    /**
     * Get Posts
     *
     * Gets post content and puts it into the session storage for later use.
     *
     * @return bool
     * @throws Exception
     */
    public function getPosts() {
        try {
            $raw = $this->fb->get('/me/feed?fields=message,id&limit=3200', $this->session->getTMP('facebook_access_token'));
        } catch (FacebookResponseException $e) {
            throw new Exception('Graph returned an error: ' . $e->getMessage());

        } catch (FacebookSDKException $e) {
            throw new Exception('Facebook SDK returned an error: ' . $e->getMessage());
        }
        $payload = $raw->getDecodedBody();
        unset($raw);

        $posts = array();
        foreach ($payload['data'] as $post => $details) {
            if (array_key_exists('message', $details)) {
                $posts[] = array(
                    'url'     => 'https://www.facebook.com/' . $details['id'],
                    'content' => $details['message']
                );
            }
        }

        unset($payload);

        $this->session->addPosts($posts);

        return true;
    }

}