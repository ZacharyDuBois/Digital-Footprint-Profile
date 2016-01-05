<?php
/**
 * File: Nav.php
 * User: zacharydubois
 * Date: 2016-01-02
 * Time: 03:59
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

/**
 * Class Nav
 *
 * Navigation controller.
 *
 * @package dfp
 */
class Nav {
    private
        $active,
        $config;

    /**
     * Nav constructor.
     *
     * Grabs some basic configuration information.
     */
    public function __construct() {
        $this->config = new Config();
    }

    /**
     * Sets Active Nav
     *
     * Sets the active navigation item.
     *
     * @param string|null $active
     * @return true
     */
    public function setActive($active = null) {
        $this->active = $active;

        return true;
    }

    /**
     * Generate Nav Array
     *
     * Creates an array of navigation items to be used with the mustache templates.
     *
     * @return array
     */
    public function navArray() {
        $navItems = array(
            'index'   => 'Home',
            'session' => 'Start',
            'about'   => 'About',
            'terms'   => 'Terms',
            'privacy' => 'Privacy'
        );

        $navArray = array();

        foreach ($navItems as $k => $v) {
            if ($k === $this->active) {
                // Class for active nav items.
                $active = 'active';
            } else {
                $active = null;
            }

            $navArray[] = array(
                'name'     => $v,
                'active'   => $active,
                'location' => Utility::buildFullLink($this->config, true, $k)
            );
        }

        return $navArray;
    }
}