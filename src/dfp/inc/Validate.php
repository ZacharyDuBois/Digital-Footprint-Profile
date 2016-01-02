<?php
/**
 * File: Validate.php
 * User: zacharydubois
 * Date: 2016-01-01
 * Time: 23:26
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

/**
 * Class Validate
 *
 * Class containing static functions used to validate strings.
 *
 * @package dfp
 */
class Validate {
    /**
     * Session ID
     *
     * Validates session ID.
     *
     * @param string $sid
     * @return bool
     */
    public static function sid($sid) {
        $pattern = '/^([[:xdigit:]]{40})$/';
            return preg_match($pattern, $sid);
    }
}