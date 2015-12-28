<?php
/**
 * File: director.php
 * User: zacharydubois
 * Date: 2015-12-09
 * Time: 12:17
 * Project: Digital-Footprint-Profile
 */

$uri = explode('/', filter_input(INPUT_SERVER, 'REQUEST_URI'));
// Make sure there are no trailing bits.
if (!isset($uri[2]) && count($uri) === 2) {
    require_once PROJECT . '/view/about/main.php';
} else {
    \DFP\util::goHome();
}