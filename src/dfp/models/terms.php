<?php
/**
 * File: terms.php
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

// Create nav.
$nav->setActive('terms');

// Tell view the nav array.
$view->navArray($nav->navArray());

// Tell view what template and content.
$view->tpl('terms');
$view->content(array(
    'title' => 'Terms | Digital Footprint Profile',
));

echo $view->render();