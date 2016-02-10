<?php
/**
 * File: Run.php
 * User: zacharydubois
 * Date: 2015-12-28
 * Time: 21:20
 * Project: Digital-Footprint-Profile
 */

namespace dfp;


/**
 * Class Run
 *
 * Runs app.
 *
 * @package dfp
 */
class Run {
    /**
     * Runs App
     *
     * Gets route, builds page, and echos rendered content.
     *
     * @return true
     * @throws Exception
     */
    public function letsGo() {
        $config = new Config();
        $route = new Route($config);
        $request = $route->guide();


        $model = APP . 'models' . DS . basename($request) . '.php';
        if (file_exists($model)) {
            require $model;
        }

        return true;
    }
}