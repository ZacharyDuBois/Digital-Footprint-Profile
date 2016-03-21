<?php
/**
 * File: boot.php
 * User: zacharydubois
 * Date: 2015-12-28
 * Time: 20:38
 * Project: Digital-Footprint-Profile
 */

require APP . 'inc' . DS . 'Checks.php';

if (!phpVersionCheck()) {
    header("Content-Type: text/plain");
    echo "You need at least PHP 5.5 to run this.";
    die();
}

if (!composerCheck()) {
    header("Content-Type: text/plain");
    echo "You did not complete the installation process. Composer dependencies were not installed.";
    die();
}

if (!configured()) {
    header("Content-Type: text/plain");
    echo "Digital Footprint Profile has not been configured yet. Please run the configuration script.";
    die();
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
    $app->letsGo();
} catch (Exception $e) {
    header("Content-Type: text/plain");
    echo $e;
}