<?php
/**
 * File: end.php
 * User: zacharydubois
 * Date: 2016-01-04
 * Time: 20:49
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

$config = new Config();
$session = new Session($config);
$session->endSession();

header('Location: ' . Utility::buildFullLink($config, false, ''));
