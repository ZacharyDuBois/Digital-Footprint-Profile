<?php
/**
 * File: Config.php
 * User: zacharydubois
 * Date: 2015-12-30
 * Time: 20:33
 * Project: Digital-Footprint-Profile
 */

namespace dfp;


class Config {
    private
        $config,
        $DataStore;

    /**
     * Config constructor.
     *
     * Sets the file to the configuration and grabs the current configuration contents.
     */
    public function __construct() {
        // Create DataStore object.
        $this->DataStore = new DataStore();
        // Grab the current configuration.
        $this->DataStore->setFile(CONFIGURATION);
        $this->config = $this->DataStore->read();
    }

    /**
     * Retrieves Configuration Parameters
     *
     * Retrieves configuration options from the configuration array.
     *
     * @param string $option
     * @param string|null $param
     * @return array|string|bool
     */
    public function get($option, $param = null) {
        if ((!isset($this->config[$option]) && $param === null) || (!isset($this->config[$option][$param]) && $param !== null)) {
            // Make sure values actually exist.
            return false;
        }

        return $this->config[$option];
    }

    /**
     * Set Configuration
     *
     * Merges new values with existing.
     *
     * @param array $payload
     * @return true
     * @throws Exception
     */
    public function set(array $payload) {
        $config = array_merge($payload, $this->config);

        if (!$this->DataStore->write($config)) {
            throw new Exception("Failed to write config using config->set.");
        }

        return true;
    }
}