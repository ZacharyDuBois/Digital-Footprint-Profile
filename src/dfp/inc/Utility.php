<?php
/**
 * File: Utility.php
 * User: zacharydubois
 * Date: 2016-01-02
 * Time: 00:57
 * Project: Digital-Footprint-Profile
 */

namespace dfp;


class Utility {

    /**
     * Generates Session ID
     *
     * Generates a session ID to use. Checks existing sessions to avoid conflict.
     *
     * @return string
     */
    public static function generateSID() {
        $key = null;

        while (!Session::isSession($key) || $key === null) {
            $key = sha1(time() . '-' . microtime(true) . '-' . rand(0, PHP_INT_MAX) . filter_input(INPUT_SERVER, 'HTTP_USER_AGENT'));
        }

        return $key;
    }
}