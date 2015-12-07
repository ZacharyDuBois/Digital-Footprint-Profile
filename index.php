<?php
/**
 * File: index.php
 * User: zacharydubois
 * Date: 2015-12-06
 * Time: 20:56
 * Project: Digital-Footprint-Profile
 */


////////////////////
// Defines        //
////////////////////
define('VERSION', '0.0.1');
define('DATADIR', __DIR__ . '/data');
define('CONFIGURATION', DATADIR . '/config.json');
define('PROJECT', __DIR__ . '/dfp');
define('COMPOSER', __DIR__ . '/vendor/autoload.php');


////////////////////
// Pre-start      //
////////////////////
require COMPOSER;
require PROJECT . '/inc/dfpException.php';
require PROJECT . '/inc/dataStore.php';


////////////////////
// Run            //
////////////////////