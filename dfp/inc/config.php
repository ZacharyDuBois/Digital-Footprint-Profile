<?php
/**
 * File: config.php
 * User: zacharydubois
 * Date: 2015-12-07
 * Time: 11:43
 * Project: Digital-Footprint-Profile
 */

namespace DFP;


class config {
    private
        $config;

    /**
     * config constructor.
     */
    public function __construct() {
        $dataStore = new dataStore(CONFIGURATION);
        $this->config = $dataStore->read();
    }

    /**
     * Gets service keys block.
     *
     * Retrives the service keys from the configuration file.
     * Returns array of key => value in format of 'secret' and 'key'.
     *
     * @param $service
     * @return array
     * @throws dfpException
     */
    public function getBlock($service) {
        $services = array(
            'facebook',
            'twitter',
            'instagram',
            'tumblr',
            'email',
            'server'
        );

        if (in_array($service, $services) && is_array($this->config[$service])) {
            return $this->config[$service];
        }

        throw new dfpException("Unrecognized or non-existent service: " . $service);
    }

    /**
     * Checks if config is set.
     *
     * @return bool
     */
    public function check() {
        $networks = array(
            'facebook',
            'twitter',
            'tumblr',
            'instagram'
        );

        $email = array(
            'api',
            'fromName',
            'fromAddr'
        );

        $server = array(
            'host',
            'https',
            'path'
        );

        foreach ($networks as $param) {
            if (!isset($this->config[$param]['key']) && !isset($this->config[$param]['secret'])) {
                return false;
            }
        }

        foreach ($email as $param) {
            if (!isset($this->config['email'][$param])) {
                return false;
            }
        }

        foreach ($server as $param) {
            if (!isset($this->config['server'][$param])) {
                return false;
            }
        }

        return true;

    }
}