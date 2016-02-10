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
        $endpoint,
        $config;

    /**
     * Route constructor.
     *
     * Gets the base URL from the config and removes it to create a usable endpoint.
     *
     * @param Config $config
     */
    public function __construct(Config $config) {
        $this->config = $config;
        $this->base = $this->config->get('server', 'base');

        if ($this->base === false) {
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
     * @return string|false
     */
    public function guide() {
        $endpointArray = explode('/', $this->endpoint);

        array_shift($endpointArray);

        if (isset($endpointArray[2])) {
            $endpointArray[2] = explode('?', $endpointArray[2])[0];
        }

        switch ($endpointArray[0]) {
            // Basically returns top level pages.
            case '':
                return 'index';
            case 'about':
            case 'terms':
            case 'privacy':
                // Ensure there is no following
                if (count($endpointArray) === 1) {
                    return $endpointArray[0];
                }

                return 404;

            // Session routing is a bit more complex.
            case 'session':
                if (!isset($endpointArray[1])) {
                    return 'session';
                } else {
                    switch ($endpointArray[1]) {
                        case 'authorize':
                        case 'list':
                        case 'email':
                        case 'end':
                            if (count($endpointArray) === 2) {
                                return $endpointArray[1];
                            }

                            return 404;

                        case 'callback':
                            switch ($endpointArray[2]) {
                                case 'twitter':
                                case 'facebook':
                                case 'instagram':
                                case 'tumblr':
                                    if (count($endpointArray) === 3) {
                                        return $endpointArray[2];
                                    }

                                    return 404;

                                default:
                                    return 404;
                            }

                        default:
                            return 404;
                    }
                }

            default:
                return 404;
        }
    }
}