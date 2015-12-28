<?php
/**
 * File: view.php
 * User: zacharydubois
 * Date: 2015-12-09
 * Time: 12:25
 * Project: Digital-Footprint-Profile
 */

namespace DFP;

class view {
    private
        $mustache,
        $payload,
        $view;

    /**
     * view constructor.
     *
     * Starts Mustache engine with proper paths to template files.
     */
    public function __construct() {
        $this->mustache = new \Mustache_Engine(array(
            'loader'          => new \Mustache_Loader_FilesystemLoader(TEMPLATE),
            'partials_loader' => new \Mustache_Loader_FilesystemLoader(TEMPLATE . '/part')
        ));
    }

    /**
     * Sets the payload.
     *
     * Sets payload for the template files use upon render.
     *
     * @param array $payload
     * @return bool
     * @throws dfpException
     */
    public function set(array $payload) {
        if (is_array($payload)) {
            $this->payload = $payload;

            return true;
        }

        throw new dfpException("view->set expects array.");
    }

    /**
     * Add to the payload
     *
     * Adds to the current payload array using array_merge().
     *
     * @param array $payload
     * @return bool
     * @throws dfpException
     */
    public function add(array $payload) {
        if (is_array($payload)) {
            $this->payload = array_merge($payload, $this->payload);

            return true;
        }

        throw new dfpException("view->add expects array.");
    }

    /**
     * Sets the template file.
     *
     * Sets the template file in the Mustache engine.
     *
     * @param $view
     * @return bool
     * @throws dfpException
     */
    public function view($view) {
        if (is_string($view) && file_exists(TEMPLATE . '/' . $view . '.mustache')) {
            $this->view = $this->mustache->loadTemplate($view);

            return true;
        }
        throw new dfpException("view->view expects string or view does not exist.");
    }

    /**
     * Render Templates
     *
     * Renders the template and returns the rendered data.
     *
     * @return string
     * @throws dfpException
     */
    public function render() {
        if (isset($this->payload) && isset($this->view)) {
            return $this->view->render($this->payload);
        }

        throw new dfpException("view->render has not been setup correctly");
    }
}