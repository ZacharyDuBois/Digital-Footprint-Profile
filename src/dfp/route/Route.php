<?php
/**
 * File: Route.php
 * User: zacharydubois
 * Date: 2015-12-30
 * Time: 20:21
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

/**
 * Class Route
 *
 * Used for routing URLs to the right place.
 *
 * @package dfp
 */
class Route {
    private
        $base,
        $endpoint;

    /**
     * Route constructor.
     *
     * Gets the base URL from the config and removes it to create a usable endpoint.
     */
    public function __construct() {
        $config = new Config();
        $this->base = $config->get('server', 'base');

        if (!isset($this->base)) {
            $this->endpoint = filter_input(INPUT_SERVER, 'REQUEST_URI');
        } else {
            $this->endpoint = $this->removeBase();
        }
    }

    /**
     * Remove Base URI
     *
     * Removes the base URI if it is set.
     *
     * @return string
     */
    private function removeBase() {
        $request = filter_input(INPUT_SERVER, 'REQUEST_URI');
        $endpoint = preg_replace('/^\/' . $this->base . '/', '', $request);

        return $endpoint;
    }

    /**
     * Guide Request
     *
     * Guide the request down the right route.
     *
     * @return string|bool
     */
    public function guide() {
        $endpointArray = explode('/', $this->endpoint);
        array_shift($endpointArray);

        switch ($endpointArray[0]) {
            // Basically returns top level pages.
            case '':
            case 'about':
            case 'privacy':
                // Ensure there is no followi
                if (count($endpointArray) === 1) {
                    return $endpointArray[0];
                }

                return false;

            // Session routing is a bit more complex.
            case 'session':
                switch ($endpointArray[1]) {
                    case '':
                        return 'session';
                    case 'authorize':
                    case 'list':
                    case 'end':
                        if (count($endpointArray) === 2) {
                            return $endpointArray[1];
                        }

                        return false;

                    case 'callback':
                        switch ($endpointArray[2]) {
                            case 'twitter':
                            case 'facebook':
                            case 'instagram':
                            case 'tumblr':
                                if (count($endpointArray) === 3) {
                                    return $endpointArray[2];
                                }

                                return false;

                            default:
                                return false;
                        }

                    default:
                        return false;
                }

            default:
                return false;
        }
    }
}