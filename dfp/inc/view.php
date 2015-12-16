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

    public function __construct() {
        $this->mustache = new \Mustache_Engine(array(
            'loader'          => new \Mustache_Loader_FilesystemLoader(TEMPLATE),
            'partials_loader' => new \Mustache_Loader_FilesystemLoader(TEMPLATE . '/part')
        ));
    }

    public function set(array $payload) {
        if (is_array($payload)) {
            $this->payload = $payload;

            return true;
        }

        throw new dfpException("view->set expects array.");
    }

    public function add(array $payload) {
        if (is_array($payload)) {
            $this->payload = array_merge($payload, $this->payload);

            return true;
        }

        throw new dfpException("view->add expects array.");
    }

    public function view($view) {
        if (is_string($view) && file_exists(TEMPLATE . '/' . $view . '.mustache')) {
            $this->view = $view;
            $this->mustache->loadTemplate($view);

            return true;
        }
        throw new dfpException("view->view expects string or view does not exist.");
    }

    public function render() {
        if (isset($this->payload) && isset($this->view)) {
            return $this->mustache->render($this->payload);
        }

        throw new dfpException("view->render has not been setup correctly");
    }
}