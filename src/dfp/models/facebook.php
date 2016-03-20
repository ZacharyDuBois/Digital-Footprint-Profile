<?php
/**
 * File: facebook.php
 * User: zacharydubois
 * Date: 2016-01-04
 * Time: 20:49
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

$config = new Config();
$session = new Session($config);

// Create facebook.
$facebook = new Facebook($config, $session);
$facebook->accessToken();
$facebook->getPosts();
$session->setTMP('allowNext', true);

header('Location: ' . Utility::buildFullLink($config, false, 'session'));
