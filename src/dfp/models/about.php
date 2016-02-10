<?php
/**
 * File: about.php
 * User: zacharydubois
 * Date: 2016-01-04
 * Time: 20:47
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

// Create objects.
$view = new View(new Config());
$nav = new Nav();

// Create nav.
$nav->setActive('about');

// Tell view the nav array.
$view->navArray($nav->navArray());

// Tell view what template and content.
$view->tpl('about');
$view->content(array(
    'title' => 'About | Digital Footprint Profile',
));

echo $view->render();