<?php
/**
 * File: Twitter.php
 * User: zacharydubois
 * Date: 2016-01-05
 * Time: 21:04
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

use Abraham\TwitterOAuth\TwitterOAuth;


/**
 * Class Twitter
 *
 * Used for clean Twitter interactions.
 *
 * @package dfp
 */
class Twitter {
    private
        $twitter,
        $session,
        $config;

    /**
     * Twitter constructor.
     *
     * Creates config and session objects.
     */
    public function __construct() {
        $this->config = new Config();
        $this->session = new Session();
    }

    /**
     * Generate TwitterOAuth Object
     *
     * Generates a TwitterOAuth object based on what is in the session data.
     *
     * @return true
     * @throws Exception
     */
    private function twitterObject() {
        if ($this->session->getTMP('twitter_request_token') === false) {
            // Generate request token (Pre-authorization).
            $this->twitter = new TwitterOAuth($this->config->get('twitter', 'consumer'), $this->config->get('twitter', 'secret'));
        } elseif ($this->session->getTMP('twitter_access_token') === false) {
            // Used to get access tokens (During callback)
            if ($this->session->getTMP('twitter_request_token') === false) {
                throw new Exception("twitter_request_token session token does not exist.");
            }

            $this->twitter = new TwitterOAuth($this->config->get('twitter', 'consumer'), $this->config->get('twitter', 'secret'), $this->session->getTMP('twitter_request_token')['oauth_token'], $this->session->getTMP('twitter_request_token')['oauth_token_secret']);
        } elseif ($this->session->getTMP('twitter_access_token') !== false) {
            // Used to make requests to their profile.
            $this->twitter = new TwitterOAuth($this->config->get('twitter', 'consumer'), $this->config->get('twitter', 'secret'), $this->session->getTMP('twitter_access_token')['oauth_token'], $this->session->getTMP('twitter_access_token')['oauth_token_secret']);
        } else {
            throw new Exception("Something went wrong creating a twitter object.");
        }

        return true;
    }

    /**
     * Generate Request Token
     *
     * Generate a request token to make an OAuth authorize URL.
     *
     * @return bool
     * @throws Exception
     */
    private function requestToken() {
        $this->twitterObject();
        $requestToken = $this->twitter->oauth('oauth/request_token', array('oauth_callback' => Utility::buildFullLink($this->config, false, 'callback/twitter')));

        // Use only double equals because documentation says 'true' is returned as a string.
        if ($requestToken['oauth_callback_confirmed'] == true) {
            return true;
        }

        $this->session->setTMP('twitter_request_token', $requestToken);

        return false;
    }

    /**
     * Generate Authorization URL
     *
     * Generate an authorization URL twitter.
     *
     * @return string
     * @throws Exception
     */
    public function authorizeURL() {
        if (!$this->requestToken()) {
            throw new Exception("Request token returned false.");
        };

        $url = $this->twitter->url('oauth/authorize', array('oauth_token' => $this->session->get('twitter_request_token')['oauth_token']));

        return $url;
    }

    /**
     * Generate Access Token
     *
     * Generates an access token using the request token.
     *
     * @return true
     * @throws Exception
     */
    private function accessToken() {
        $this->twitterObject();

        if (filter_input(INPUT_REQUEST, 'oauth_token') && filter_input(INPUT_REQUEST, 'oauth_token') !== $this->session->getTMP('twitter_request_token')['oauth_token']) {
            // Something weird happened.
            throw new Exception("OAuth tokens to not match.");
        }

        $accessToken = $this->twitter->oauth("oauth/access_token", array('oauth_verifier' => filter_input(INPUT_REQUEST, 'oauth_verifier')));

        if (!isset($accessToken['screen_name'])) {
            throw new Exception("Access token did not receive screen_name.");
        }

        $this->session->setTMP('twitter_name', $accessToken['screen_name']);
        $this->session->setTMP('twitter_access_token', $accessToken);

        return true;
    }

    /**
     * Fetch Max Posts
     *
     * Fetch the maximum number of posts allowed and parse it to get rid of unneeded content.
     *
     * @return bool
     * @throws Exception
     */
    public function getPosts() {
        $this->accessToken();
        $this->twitterObject();

        // Need twitter ID of most recent tweet.
        $beginingID = $this->twitter->get("statuses/user_timeline", array("count" => 1, 'screen_name' => $this->session->getTMP('twitter_name')));
        // Add one because it will be subtracted later.
        $beginingID = $beginingID[0]['id'] + 1;

        // Create array for rendered posts.
        $posts = array();
        // Twitter API max is 3200.
        $max = 3200;
        // Starting params.
        $total = 0;
        $count = 200;
        while ($count === 200 && $total < $max) {
            if (!isset($lastID)) {
                $lastID = $beginingID;
            }

            $postsRaw = $this->twitter->get("statuses/user_timeline", array("count" => 200, 'max_id' => $lastID - 1, 'screen_name' => $this->session->getTMP('twitter_name')));

            $count = count($postsRaw);
            $total = $total + $count;
            $lastID = $postsRaw[$count - 1]['id'];

            foreach ($postsRaw as $post => $content) {
                $posts[] = array(
                    'url'     => 'https://twitter.com/statuses/' . $content[$post]['id'],
                    'content' => $content[0]['text']
                );
            }
        }

        $this->session->set('twitter', $posts);

        return true;
    }
}