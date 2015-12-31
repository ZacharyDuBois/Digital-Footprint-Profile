<?php
/**
 * File: boot.php
 * User: zacharydubois
 * Date: 2015-12-28
 * Time: 20:38
 * Project: Digital-Footprint-Profile
 */


/*
 * Check the PHP version and make sure it is the latest.
 */
if (!version_compare(PHP_VERSION, '5.6.0', '>=')) {
    header("Content-Type: text/plain");
    echo "You need at least PHP 5.6 to run this.";

    return false;
}

/*
 * Check to make sure they installed the Composer dependencies.
 */
if (!file_exists(COMPOSER)) {
    header("Content-Type: text/plain");
    echo "You did not complete the installation process. Composer dependencies were not installed.";

    return false;
}

/*
 * Check to see if DFP has been configured.
 */
if (!file_exists(CONFIGURATION)) {
    header("Content-Type: text/plain");
    echo "Digital Footprint Profile has not been configured yet. Please run the configuration script.";

    return false;
}

/*
 * Load the app.
 */
require APP . 'load.php';
require APP . 'controller' . DS . 'Run.php';
use \dfp\Run;
use \dfp\Exception;

try {
    // Create run object.
    $app = new Run();
    // RUN IT!
    $app->run();
} catch (Exception $e) {
    header("Content-Type: text/plain");
    echo $e;
}