<?php
/**
 * File: kiosk.php
 * User: zacharydubois
 * Date: 2016-02-01
 * Time: 19:28
 * Project: Digital-Footprint-Profile
 */

/*
 * Just something to track the kiosks.
 * If you want to use this, just modify the defines below.
 */

// Time kiosk session is valid.
define('DFP_KIOSK_LIFE', 1800);

// Domain/Hostname
define('DFP_KIOSK_DOMAIN', 'dfp.zacharydubois.moe');

// Redirect to?
define('DFP_KIOSK_REDIRECT', 'https://dfp.zacharydubois.moe/');

if(explode(':', DFP_KIOSK_REDIRECT)[0] === 'https') {
    $https = true;
} else {
    $https = false;
}

// Time
setcookie('KIOSK_ID', filter_input(INPUT_GET, 'kiosk'), (time() + DFP_KIOSK_LIFE), '/', DFP_KIOSK_DOMAIN, $https, true);

header('Location: ' . DFP_KIOSK_REDIRECT);
