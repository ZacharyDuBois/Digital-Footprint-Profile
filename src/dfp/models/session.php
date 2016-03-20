<?php
/**
 * File: session.php
 * User: zacharydubois
 * Date: 2016-01-04
 * Time: 20:48
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

// Create config
$config = new Config();

// Create session instance.
$session = new Session($config);

// Create Nav
$nav = new Nav($config);
$nav->setActive('session');

// Create view.
$view = new View($config);
$view->navArray($nav->navArray(true));
$view->tpl('session');

// Twitter
if ($session->get('twitter') === false) {
    $twitter = new Twitter($config, $session);
    $twitterButton = array(
        'url'     => $twitter->authorizeURL(),
        'text'    => 'Login with Twitter',
        'classes' => ''
    );
} else {
    $twitterButton = array(
        'url'     => '#',
        'text'    => 'Twitter: @' . $session->getTMP('twitter_name'),
        'classes' => 'disabled'
    );
}

// Facebook
if($session->get('facebook') === false) {
    $facebook = new Facebook($config, $session);
    $facebookButton = array(
        'url'     => $facebook->authorizeURL(),
        'text'    => 'Login with Facebook',
        'classes' => ''
    );
} else {
    $facebookButton = array(
        'url'     => '#',
        'text'    => 'Facebook: ' . $session->getTMP('facebook_name'),
        'classes' => 'disabled'
    );
}

// Render and return
$view->content(array(
    'title'        => 'Start | Digital Footprint Profile',
    'listURL'      => Utility::buildFullLink($config, false, 'session/list'),
    'loginButtons' => array(
        $twitterButton,
        $facebookButton
        //others
    )
));

echo $view->render();