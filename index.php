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


////////////////////
// Run            //
////////////////////
try {
} catch (dfpException $e) {
    echo $e->getMessage();
}