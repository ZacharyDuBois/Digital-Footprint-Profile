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
        $items,
        $base;

    /**
     * Nav constructor.
     *
     * Grabs some basic configuration information.
     */
    public function __construct() {
        $config = new Config();
        $this->items = $config->get('nav');
        $this->base = $config->get('server', 'base');
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
        $navArray = array();

        foreach($this->items as $item) {
            if($item === $this->active) {
                $active = 'active';
            } else {
                $active = null;
            }

            $navArray[] = array(
                'name' => ucfirst($item),
                'active' => $active,
                'location' => $this->base . '/' . $item
            );
        }

        return $navArray;
    }
}