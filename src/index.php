<?php
/**
 * File: index.php
 * User: zacharydubois
 * Date: 2015-12-28
 * Time: 20:34
 * Project: Digital-Footprint-Profile
 */

define('VERSION', '0.0.1');
define('DS', DIRECTORY_SEPARATOR);
define('PATH', dirname(__FILE__) . DS);
define('APP', PATH . 'dfp' . DS);
define('COMPOSER', APP . 'vendor' . DS . 'autoload.php');
define('STORAGE', APP . 'storage' . DS);
define('CONFIGURATION', APP . 'config' . DS . 'config.json');
define('THEME', APP . 'theme' . DS);

require APP . 'boot.php';