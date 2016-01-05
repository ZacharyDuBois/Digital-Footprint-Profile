#!/usr/bin/env php
<?php
/**
 * File: setup.php
 * User: zacharydubois
 * Date: 2016-01-04
 * Time: 21:51
 * Project: Digital-Footprint-Profile
 */

define('VERSION', '0.0.1');
define('DS', DIRECTORY_SEPARATOR);
define('PATH', dirname(__FILE__) . DS);
define('APP', PATH . 'dfp' . DS);
define('COMPOSER', APP . 'vendor' . DS . 'autoload.php');
define('STORAGE', APP . 'storage' . DS);
define('CONFIGURATION', APP . 'config' . DS . 'config.json');

function p($text) {
    if ($text !== false) {
        echo $text . PHP_EOL;
    } else {
        echo "----------------------------------" . PHP_EOL;
    }
}

function r() {
    return readline("> ");
}

p("Welcome to Digital Footprint Profile configurator.");
p("Made by Zachary J. DuBois - https://zacharydubois.me");
p("Checking dependencies...");

require APP . 'inc' . DS . 'checks.php';

if (phpVersionCheck()) {
    p("PHP " . PHP_VERSION . " meets requirement...");
} else {
    p("You need at least PHP 5.6 to run this.");
    p("Exiting...");
    exit();
}

if (composerCheck()) {
    p("Composer vendor autoload.php exists. Assuming all other dependencies have been installed...");
} else {
    p("You did not complete the installation process. Composer dependencies were not installed.");
    p("Exiting...");
    exit();
}

p("Dependency checks passed.");
p("Checking directory permissions...");

$needsWrite = array(
    STORAGE,
    CONFIGURATION,
);

foreach ($needsWrite as $dir) {
    $dir = dirname($dir);
    if (is_writable($dir)) {
        p("Dir " . $dir . " is writable...");
    } else {
        p("Dir " . $dir . " is not writable. Please correct this.");
        p("Exiting...");
        exit();
    }
}

p("Directory permissions are correct.");
p("Loading controllers...");

require APP . 'load.php';

$config = new \dfp\Config();
$cfgArray = array();

p("Controllers loaded.");
p(false);
p("Begin configuration. Please note, you may not go back and change options. Options are also not verified.");
p("Double check before entering. Suggestions are in brackets.");
p(false);
p("Server Configuration.");

p("Set server protocol [https:// || http://]:");
$cfgArray['server']['protocol'] = r();

p("Set server domain name [domain.tld or sub.domain.tld]:");
$cfgArray['server']['domain'] = r();

p("Set the subdirectory. Note this can only be one directory deep for proper function. Do not include directory separators in your entry.");
p("Ex: if your installation resides in https://apps.domain.tld/dfp/, your would enter 'dfp'.");
p("For no subdirectory, just leave it blank.");
$cfgArray['server']['base'] = r();

p(false);
p("Email Configuration");

p("Email gateway [sendgrid || smtp]:");
$cfgArray['email']['gateway'] = r();

if($cfgArray['email']['gateway'] === 'sendgrid') {
    p("Sendgrid API Key:");
    $cfgArray['email']['sendgrid_key'] = r();
} elseif ($cfgArray['email']['gateway'] === 'smtp') {
    p("SMTP Host:[smtp.domin.tld]:");
    $cfgArray['email']['host'] = r();
    
    p("SMTP Port:");
    $cfgArray['email']['port'] = r();
    
    p("SMTP User:");
    $cfgArray['email']['user'] = r();
    
    p("SMTP Password:");
    $cfgArray['email']['password'] = r();
    
    p("SMTP Security [tls || ssl]:");
    $cfgArray['email']['secure'] = r();
} else {
    p("Unknown gateway.");
    p("Exiting...");
    exit();
}

p("Email subject line [Here's a link to your digital footprint.]:");
$cfgArray['email']['subject'] = r();

p("Email from address [dfp@domain.tld]:");
$cfgArray['email']['from_address'] = r();

p("Email from name [Digital Footprint Profile]:");
$cfgArray['email']['from_name'] = r();

p(false);
p("Social Network Configuration");

p("Twitter consumer key:");
$cfgArray['twitter']['consumer'] = r();

p("Twitter consumer secret:");
$cfgArray['twitter']['secret'] = r();

p("Facebook app id:");
$cfgArray['facebook']['id'] = r();

p("Facebook app secret:");
$cfgArray['facebook']['secret'] = r();

p("Tumblr consumer key:");
$cfgArray['tumblr']['consumer'] = r();

p("Tumblr consumer secret:");
$cfgArray['tumblr']['secret'] = r();

p("Instagram client id:");
$cfgArray['instagram']['id'] = r();

p("Instagram client secret:");
$cfgArray['instagram']['secret'] = r();

p("User Input Complete.");
p(false);
p("Your configuration:");
p(json_encode($cfgArray, JSON_PRETTY_PRINT));
p(false);
p("Writing config to " . CONFIGURATION . " ...");
$config->set($cfgArray);

p("Config write successful.");
p(false);
p("Removing setup script for security...");
unlink(__FILE__);
p("Done.");
p("Thanks for using Digital Footprint.");
exit();