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
if (!isset($uri[1]) || !count($uri) >= 1) {
    require_once PROJECT . '/view/index/main.php';
} else {
    \DFP\util::home();
}