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
    }

    public function run() {

    }

    private static function getEndpoint() {
        $uri = explode('/', filter_input(INPUT_SERVER, 'REQUEST_URI'));

        switch ($uri[0]) {
            case '':
                return 'index';
            case 'session':
                return 'session';
            case 'callback':
                return 'callback';
            default:
                header("HTTP/1.1 404 Not Found");
                header("Location: " . $this->config->getBlock('server')['host'] . "/");
                return false;
        }
    }
}