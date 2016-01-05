<?php
/**
 * File: privacy.php
 * User: zacharydubois
 * Date: 2016-01-04
 * Time: 20:48
 * Project: Digital-Footprint-Profile
 */

namespace dfp;

// Create objects.
$view = new View();
$nav = new Nav();

// Create nav.
$nav->setActive('privacy');

// Tell view the nav array.
$view->navArray($nav->navArray());

// Tell view what template and content.
$view->tpl('privacy');
$view->content(array(
    'title'       => 'Privacy | Digital Footprint Profile',
));

echo $view->render();