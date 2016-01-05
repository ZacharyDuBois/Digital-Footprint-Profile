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
$view = new View();
$nav = new Nav();
$config = new Config();

// Create nav.
$nav->setActive('index');

// Tell view the nav array.
$view->navArray($nav->navArray());

// Tell view what template and content.
$view->tpl('index');
$view->content(array(
    'title'       => 'Start',
    'sessionLink' => Utility::buildFullLink($config, true, 'session')
));

echo $view->render();