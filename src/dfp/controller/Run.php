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
        $route = new Route();
        //$config = new Config();
        $request = $route->guide();
        $view = new View();


        switch ($request) {
            case 'redirect':
                // Make sure redirects pass through with no returned content.
                return true;
            case false:
                header("HTTP/1.1 404 Not Found");
                $view->tpl('404');
                break;
            default:
                $model = APP . 'models' . DS . basename($request) . '.php';
                if (file_exists($model)) {
                    require $model;
                    break;
                }
        }

        echo $view->render();

        return true;
    }
}