<?php

/**
 * Iggy Rapid Prototyping App
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/caseyamcl/iggy
 * @version 1.0
 * @package caseyamcl/iggy
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * ------------------------------------------------------------------
 */

/*
 * Bootstrap/Index file
 */

// Require the autoloader and run the file
require_once(__DIR__ . '/vendor/autoload.php');

// Execute!
(php_sapi_name() == 'cli') ? Iggy\App::console() : Iggy\App::request(__DIR__ . '/content');
