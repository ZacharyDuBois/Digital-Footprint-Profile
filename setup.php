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
    )
);

if ($dataStore->write($requiredParams)) {
    echo("Configuration saved.");
} else {
    echo("Error saving configuration.");
}

// TODO: Remove before release.
unlink(__FILE__);
