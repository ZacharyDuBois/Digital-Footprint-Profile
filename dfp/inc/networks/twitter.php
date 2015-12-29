<?php

/**
 * File: twitter.php
 * User: zacharydubois
 * Date: 2015-12-28
 * Time: 15:19
 * Project: Digital-Footprint-Profile
 */

namespace DFP;

use Abraham\TwitterOAuth\TwitterOAuth;

class twitter {
    private
        $config,
        $twitter,
        $session;

    public function __construct() {
        $this->config = new config();
        $this->session = new session();

        if (!isset($this->session->read()['networks']['twitter'])) {
            $this->twitter = new TwitterOAuth($this->config->getBlock('twitter')['consumer'], $this->config->getBlock('twitter')['secret']);
        } else {
            $this->twitter = new TwitterOAuth($this->config->getBlock('twitter')['consumer'], $this->config->getBlock('twitter')['secret']);
        };
    }

    private function getRequestToken() {
        $callback = $this->config->getBlock('server');
        $callback = $callback['https'] . $callback['host'] . '/session/callback/twitter';
        $reqToken = $this->twitter->oauth('oauth/request_token', array('oauth_callback' => $callback));

        $this->session->write(array(
            'networks' => array(
                'twitter' => array(
                    'oauth_token'              => $reqToken['oauth_token'],
                    'oauth_token_secret'       => $reqToken['oauth_token_secret'],
                    'oauth_callback_confirmed' => $reqToken['oauth_callback_confirmed']
                )
            )
        ));

        $this->session->save();

        return $reqToken;
    }

    public function getAuthorizeURL() {
        $reqToken = $this->getRequestToken();

        $url = $this->twitter->url('oauth/authorize', array(
            'oauth_token' => $reqToken['oauth_token']
        ));

        return $url;
    }

public

}