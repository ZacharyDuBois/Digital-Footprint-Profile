<?php
/**
 * File: 404.php
 * User: zacharydubois
 * Date: 2016-01-05
 * Time: 12:06
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

// Create objects.
$config = new Config();
$view = new View($config);
$nav = new Nav($config);

// Tell view the nav array.
$view->navArray($nav->navArray());

// Tell view what template and content.
$view->tpl('404');
$view->content(array(
    'title' => '404 | Digital Footprint Profile',
));

header("HTTP/1.1 404 Not Found");

echo $view->render();
