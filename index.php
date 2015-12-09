<?php
/**
 * File: index.php
 * User: zacharydubois
 * Date: 2015-12-06
 * Time: 20:56
 * Project: Digital-Footprint-Profile
 */

////////////////////
// Pre-start      //
////////////////////
require __DIR__ . '/load.php';
use DFP\dfpException;
use DFP\app;


////////////////////
// Run            //
////////////////////
try {
    $app = new app();
    $app->run();
} catch (dfpException $e) {
    echo $e->getMessage();
}