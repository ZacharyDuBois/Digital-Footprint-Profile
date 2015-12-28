<?php
/**
 * File: director.php
 * User: zacharydubois
 * Date: 2015-12-09
 * Time: 12:17
 * Project: Digital-Footprint-Profile
 */

$uri = explode('/', filter_input(INPUT_SERVER, 'REQUEST_URI'));
switch ($uri[1]) {
    case '':
        require_once PROJECT . '/view/session/authorize.php';
            break;
    case 'email':
        require_once PROJECT . '/view/session/email.php';
        break;
    case 'callback':
        require_once PROJECT . '/view/session/callback/director.php';
}