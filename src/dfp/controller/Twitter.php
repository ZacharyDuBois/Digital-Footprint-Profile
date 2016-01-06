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
     * Generate Authorization URL
     *
     * Generate an authorization URL twitter.
     *
     * @return string
     * @throws Exception
     */
    public function authorizeURL() {
        $twitter = new TwitterOAuth($this->config->get('twitter', 'consumer'), $this->config->get('twitter', 'secret'));
        $twitter->setDecodeJsonAsArray(true);

        $requestToken = $twitter->oauth('oauth/request_token', array('oauth_callback' => Utility::buildFullLink($this->config, false, 'session/callback/twitter')));

        if ($requestToken['oauth_callback_confirmed'] != true) {
            throw new Exception("OAuth Callback was not confirmed.");
        }

        $this->session->setTMP('twitter_request_token', $requestToken);

        $url = $twitter->url('oauth/authorize', array('oauth_token' => $this->session->getTMP('twitter_request_token')['oauth_token']));

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
    public function accessToken() {
        $twitter = new TwitterOAuth($this->config->get('twitter', 'consumer'), $this->config->get('twitter', 'secret'), $this->session->getTMP('twitter_request_token')['oauth_token'], $this->session->getTMP('twitter_request_token')['oauth_token_secret']);
        $twitter->setDecodeJsonAsArray(true);

        if (filter_input(INPUT_GET, 'oauth_token') && filter_input(INPUT_GET, 'oauth_token') !== $this->session->getTMP('twitter_request_token')['oauth_token']) {
            // Something weird happened.
            throw new Exception("OAuth tokens to not match.");
        }

        $accessToken = $twitter->oauth('oauth/access_token', array('oauth_verifier' => filter_input(INPUT_GET, 'oauth_verifier')));

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
        $twitter = new TwitterOAuth($this->config->get('twitter', 'consumer'), $this->config->get('twitter', 'secret'), $this->session->getTMP('twitter_access_token')['oauth_token'], $this->session->getTMP('twitter_access_token')['oauth_token_secret']);
        $twitter->setDecodeJsonAsArray(true);

        // Need twitter ID of most recent tweet.
        $beginingID = $twitter->get("statuses/user_timeline", array("count" => 1, 'screen_name' => $this->session->getTMP('twitter_name')));
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

            $postsRaw = $twitter->get("statuses/user_timeline", array("count" => 200, 'max_id' => $lastID - 1, 'screen_name' => $this->session->getTMP('twitter_name')));

            $count = count($postsRaw);
            $total = $total + $count;
            $lastID = $postsRaw[$count - 1]['id'];

            foreach ($postsRaw as $post => $content) {
                $posts[] = array(
                    'url'     => 'https://twitter.com/statuses/' . $content[$post]['id'],
                    'content' => $content[0]['text'],
                    'network' => 'twitter'
                );
            }
        }

        $this->session->set('twitter', $posts);

        return true;
    }
}