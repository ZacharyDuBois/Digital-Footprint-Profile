<?php
/**
 * File: util.php
 * User: zacharydubois
 * Date: 2015-12-07
 * Time: 11:58
 * Project: Digital-Footprint-Profile
 */

namespace DFP;


class util {
    /**
     * Generates a session token
     *
     * @return string
     */
    public static function genSessionToken() {
        $key = null;

        while (file_exists(SESSIONPATH . '/' . $key . '.json') || $key === null) {
            $key = sha1(microtime(true) . '-' . rand(0, PHP_INT_MAX) . filter_input(INPUT_SERVER, 'HTTP_USER_AGENT'));
        }

        return $key;
    }

    public static function goHome() {
        header("Location: " . DFPSECURE . DFPHOST);

        return true;
    }

    public static function home() {
        return DFPSECURE . DFPHOST;
    }

    public static function nav($active = null) {
        $index = '';
        $about = '';
        $privacy = '';

        switch ($active) {
            case 'index':
                $index = 'active';
                break;
            case 'about':
                $about = 'active';
                break;
            case 'privacy':
                $privacy = 'active';
                break;
            case null:
                break;
            default:
                throw new dfpException("Unknown active nav.");
                break;
        }

        $nav = array(
            array(
                'title'  => 'Start',
                'active' => $index,
                'url'    => util::home()
            ),
            array(
                'title'  => 'About',
                'active' => $about,
                'url'    => util::home() . '/about'
            ),
            array(
                'title' => 'Privacy',
                'active'  => $privacy,
                'url'     => util::home() . '/privacy'
            )

        );

        return $nav;
    }

    public static function getCSS() {
        $home = util::home() . '/dfp/public/css';
        $css = array(
            array('url' => 'http://fonts.googleapis.com/icon?family=Material+Icons'),
            array('url' => $home . '/materialize.min.css')
        );

        return $css;
    }

    public static function getJS() {
        $home = util::home() . '/dfp/public/js';
        $js = array(
            array('url' => $home . '/jquery-2.1.4.min.js'),
            array('url' => $home . '/materialize.min.js')
        );

        return $js;
    }

    public static function getExtra($active = null) {
        $extra = array(
            'nav' => util::nav($active),
            'css' => util::getCSS(),
            'js'  => util::getJS(),
            'home' => util::home(),
            'copyright' => '&copy; Zachary James DuBois, 2015. Open source under the MIT license.',
            'primaryColor' => 'green',
            'secondaryColor' => 'yellow',
            'version' => VERSION
        );

        return $extra;
    }
}
