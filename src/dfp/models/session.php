<?php
/**
 * File: session.php
 * User: zacharydubois
 * Date: 2016-01-04
 * Time: 20:48
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

// Create session instance.
$session = new Session();

// Create config
//$config = new Config();

// Create Nav
$nav = new Nav();
$nav->setActive('session');

// Create view.
$view = new View();
$view->navArray($nav->navArray());
$view->tpl('session');

// Twitter
if ($session->getTMP('twitter_name') === false) {
    $twitter = new Twitter();
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

// Render and return
$view->content(array(
    'title'        => 'Start | Digital Footprint Profile',
    'loginButtons' => array(
        $twitterButton,
        // Others
    )
));