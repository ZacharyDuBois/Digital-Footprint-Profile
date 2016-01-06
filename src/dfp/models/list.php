<?php
/**
 * File: list.php
 * User: zacharydubois
 * Date: 2016-01-04
 * Time: 20:49
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

// Config
$config = new Config();

// Session
$session = new Session();

if ($session->get('twitter') === false) {
    header('Location: ' . Utility::buildFullLink($config, false, 'session'));
}

// View
$nav = new Nav();
$nav->setActive('list');
$view = new View();
$view->tpl('list');
$view->navArray($nav->navArray());


// Parse
$parse = new Parse();

// List array
$list = array();

foreach ($session->get('twitter') as $post) {
    $parse->parse($post['content']);

    $list[] = array(
        'url'     => $post['url'],
        'score'   => $parse->score(),
        'tags'    => $parse->tags(),
        'content' => $post['content']
    );
}

$view->content(array(
    'title' => 'Your Posts | Digital Footprint Profile',
    'posts' => $list
));