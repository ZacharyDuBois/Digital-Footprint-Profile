<?php
/**
 * File: index.php
 * User: zacharydubois
 * Date: 2015-12-28
 * Time: 20:34
 * Project: Digital-Footprint-Profile
 */

define('VERSION', '1.0.0');
define('DS', DIRECTORY_SEPARATOR);
define('PATH', dirname(__FILE__) . DS);
define('APP', PATH . 'dfp' . DS);
define('COMPOSER', APP . 'vendor' . DS . 'autoload.php');
define('STORAGE', APP . 'storage' . DS);
define('CONFIGURATION', APP . 'config' . DS . 'config.json');
define('THEME', APP . 'theme' . DS);
define('KEYWORDS', APP . 'config' . DS . 'keywords.json');
define('PUBLIC_URI', 'public/');
define('DFP_SESSION_NAME', 'DFPSID');
define('DFP_SESSION_LIFE', 1800);

require APP . 'boot.php';
