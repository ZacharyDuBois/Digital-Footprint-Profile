<?php
/**
 * File: util.php
 * User: zacharydubois
 * Date: 2015-12-07
 * Time: 11:58
 * Project: Digital-Footprint-Profile
 */

namespace DFP;


class util {
    /**
     * Generates a session token
     * @return string
     */
    public static function genSessionToken() {
        $session = sha1(microtime(true) . '-' . rand(0, PHP_INT_MAX));

        return $session;
    }
}