<?php
/**
 * File: load.php
 * User: zacharydubois
 * Date: 2015-12-06
 * Time: 21:15
 * Project: Digital-Footprint-Profile
 */

//***********************************************************//
// DO NOT MODIFY THIS FILE UNLESS YOU KNOW WHAT YOU'RE DOING //
//***********************************************************//

////////////////////
// Defines        //
////////////////////
define('VERSION', '0.0.1');
define('DATADIR', __DIR__ . '/data');
define('CONFIGURATION', DATADIR . '/config.json');
define('PROJECT', __DIR__ . '/dfp');
define('TEMPLATE', PROJECT . '/template');
define('SESSIONPATH', DATADIR . '/sessions');
define('COMPOSER', __DIR__ . '/vendor/autoload.php');
define('COOKIENAME', 'DFPSESS');

////////////////////
// Requires       //
////////////////////
require_once COMPOSER;
require_once PROJECT . '/inc/dfpException.php';
require_once PROJECT . '/inc/dataStore.php';
require_once PROJECT . '/inc/app.php';
require_once PROJECT . '/inc/config.php';
require_once PROJECT . '/inc/session.php';
require_once PROJECT . '/inc/util.php';
require_once PROJECT . '/inc/view.php';
