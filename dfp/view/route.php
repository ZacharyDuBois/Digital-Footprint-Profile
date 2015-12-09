<?php
/**
 * File: route.php
 * User: zacharydubois
 * Date: 2015-12-09
 * Time: 12:01
 * Project: Digital-Footprint-Profile
 */

$uri = explode('/', filter_input(INPUT_SERVER, 'REQUEST_URI'));
switch ($uri[0]) {
    case '':
    case 'session':
    case 'privacy':
    case 'about':
        require_once PROJECT . '/view/' . $uri[0] . '/director.php';
        break;
    default:
        header("Location: " . DFPSECURE . DFPHOST . '/');
        break;
}