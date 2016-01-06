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
if (!isset($denied) || isset($_GET['oauth_verifier'])) {
// Create twitter.
    $twitter = new Twitter();
    $twitter->accessToken();
    $twitter->getPosts();
}

$config = new Config();
header('Location: ' . Utility::buildFullLink($config, false, 'session'));
