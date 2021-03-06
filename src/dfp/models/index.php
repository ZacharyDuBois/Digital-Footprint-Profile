<?php
/**
 * File: index.php
 * User: zacharydubois
 * Date: 2016-01-04
 * Time: 20:47
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

// Create objects.
$config = new Config();
$view = new View($config);
$nav = new Nav($config);

// Create nav (Index is empty).
$nav->setActive('');

// Tell view the nav array.
$view->navArray($nav->navArray());

// Tell view what template and content.
$view->tpl('index');
$view->content(array(
    'title'       => 'Welcome | Digital Footprint Profile',
    'sessionLink' => Utility::buildFullLink($config, true, 'session')
));

echo $view->render();