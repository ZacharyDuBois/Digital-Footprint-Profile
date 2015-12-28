<?php
/**
 * File: app.php
 * User: zacharydubois
 * Date: 2015-12-07
 * Time: 11:23
 * Project: Digital-Footprint-Profile
 */

namespace DFP;


class app {
    private
        $config;

    public function __construct() {
        $this->config = new config();

        define('DFPHOST', $this->config->getBlock('server')['host']);
        define('DFPSECURE', $this->config->getBlock('server')['https']);
    }

    public function run() {
        require PROJECT . '/view/route.php';
    }
}