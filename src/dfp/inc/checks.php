<?php
/**
 * File: checks.php
 * User: zacharydubois
 * Date: 2016-01-04
 * Time: 22:04
 * Project: Digital-Footprint-Profile
 */


/**
 * Check PHP Version.
 *
 * @return bool
 */
function phpVersionCheck() {
    return version_compare(PHP_VERSION, '5.5.0', '>=');
}

/**
 * Checks for Composer
 *
 * @return bool
 */
function composerCheck() {
    return file_exists(COMPOSER);
}

/*
 * Check to see if DFP has been configured.
 */
function configured() {
    return file_exists(CONFIGURATION);
}
