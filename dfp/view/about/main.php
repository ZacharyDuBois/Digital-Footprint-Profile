<?php
/**
 * File: main.php
 * User: zacharydubois
 * Date: 2015-12-16
 * Time: 17:32
 * Project: Digital-Footprint-Profile
 */

$view = new \DFP\view();
$view->view('about');

$content = array(
    'title' => 'About | Digital Footprint Profile',
);

$view->set($content);
$view->add(\DFP\util::getExtra('about'));

echo $view->render();
