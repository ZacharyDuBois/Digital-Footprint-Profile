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
$session = new Session($config);

if ($session->get('twitter') === false) {
    header('Location: ' . Utility::buildFullLink($config, false, 'session'));
}

// View
$nav = new Nav($config, true);
$nav->setActive('list');
$view = new View($config);
$view->tpl('list');
$view->navArray($nav->navArray());


// Parse
$parse = new Parse();

// List array
$list = array();
$total = 0;
$flagged = 0;

foreach ($session->get('twitter') as $post) {
    $parse->parse($post['content']);
    $score = $parse->score();
    $tags = $parse->tags();

    if ($score >= 3) {
        $list[] = array(
            'url'     => $post['url'],
            'score'   => $score,
            'tags'    => $tags,
            'content' => $post['content']
        );

        $flagged++;
    }

    $total++;
}

$view->content(array(
    'title'   => 'Your Posts | Digital Footprint Profile',
    'posts'   => $list,
    'total'   => $total,
    'flagged' => $flagged
));

echo $view->render();