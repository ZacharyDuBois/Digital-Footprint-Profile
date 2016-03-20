<?php
/**
 * File: twitter.php
 * User: zacharydubois
 * Date: 2016-01-04
 * Time: 20:49
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

$denied = filter_input(INPUT_GET, 'denied');

$config = new Config();
$session = new Session($config);

if (!isset($denied) || array_key_exists('oauth_verifier', $_GET)) {
// Create twitter.
    $twitter = new Twitter($config, $session);
    $twitter->accessToken();
    $twitter->getPosts();
    $session->setTMP('allowNext', true);
}

header('Location: ' . Utility::buildFullLink($config, false, 'session'));
