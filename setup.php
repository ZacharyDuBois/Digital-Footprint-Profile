#!/usr/bin/env php
<?php
/**
 * File: setup.php
 * User: zacharydubois
 * Date: 2015-12-07
 * Time: 11:25
 * Project: Digital-Footprint-Profile
 */

namespace DFP;

include __DIR__ . '/load.php';

$dataStore = new dataStore(CONFIGURATION);

$https = readline("Use HTTPS [true/false]: ");
if ($https == 'true') {
    $https = 'https://';
} else {
    $https = 'http://';
}

$requiredParams = array(
    'facebook'  => array(
        'key'    => readline("Facebook Key: "),
        'secret' => readline("Facebook Secret: ")
    ),
    'twitter'   => array(
        'key'    => readline("Twitter Key: "),
        'secret' => readline("Twitter Secret: ")
    ),
    'tumblr'    => array(
        'key'    => readline("Tumblr Key: "),
        'secret' => readline("Tumblr Secret: ")
    ),
    'instagram' => array(
        'key'    => readline("Instagram Key: "),
        'secret' => readline("Instagram Secret: ")
    ),
    'email'     => array(
        'api'      => readline("Sendgrid API Key: "),
        'fromName' => readline("From Name: "),
        'fromAddr' => readline("From Address: ")
    ),
    'server'    => array(
        'host'  => readline("Server Name/Domain Name: "),
        'https' => $https,
        'path'  => readline("Installation Path:") // TODO
    )
);

if ($dataStore->write($requiredParams)) {
    echo("Configuration saved.");
} else {
    echo("Error saving configuration.");
}

// Validate
$config = new config();

if ($config->check()) {
    echo("Something wasn't set correctly. Please start over.");
    unlink(CONFIGURATION);
} else {
    // TODO: Remove before release.
//unlink(__FILE__);
}
