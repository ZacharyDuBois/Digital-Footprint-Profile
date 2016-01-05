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

    /**
     * Build Full Link
     *
     * Builds full link using protocol, host and base.
     *
     * @param Config $config
     * @param string $endpoint
     * @return string
     */
    public static function buildFullLink(Config $config, $relative = false, $endpoint = null) {
        $proto = $config->get('server', 'protocol');
        $host = $config->get('server', 'domain');
        $base = $config->get('server', 'base');

        if ($relative === true) {
            if ($base === false) {
                $link = '/' . $endpoint;
            } else {
                $link = '/' . $base . '/' . $endpoint;
            }
        } else {
            if ($base === false) {
                $link = $proto . $host . '/' . $endpoint;
            } else {
                $link = $proto . $host . '/' . $base . '/' . $endpoint;
            }
        }


        return $link;
    }

    /**
     * Brings User Home
     *
     * Sends location header to send user to root.
     *
     * @param Config $config
     * @return bool
     */
    public static function goHome(Config $config) {
        header("Location: " . static::buildFullLink($config));

        return true;
    }
}