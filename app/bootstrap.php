<?php

// Require the autoloader
require_once(__DIR__ . '/vendor/autoload.php');

// Determine the path
$basePath = (Phar::running())
    ? dirname(Phar::running(false))
    : dirname(__FILE__);

// ...and run the app!
\Iggy\App::main($basePath);

/* EOF: bootstrap.php */