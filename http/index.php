<?php

/**
 * The Front Controller
 */
// charset
ini_set('default_charset', "utf-8");

// Reveal errors and exceptions
ini_set('display_errors',1);

// errors display
error_reporting(E_ALL);

// Guest Initialize
session_start();

// Time Zone for Db
date_default_timezone_set( 'Europe/Kiev');

// Define global root for current dir
define('ROOT', __DIR__ . '/..');

// Load the assistant fixtures
require_once(ROOT . '/helpers/loader.php');

// Load and launch the router
require_once(ROOT . '/routes/Router.php');
$r = new Router();
$r->run();
