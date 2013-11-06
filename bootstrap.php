<?php
error_reporting(-1);
ini_set('display_errors', 1);

// Define our APP_BASE for convenience later.
define('APP_BASE', __DIR__);

// Required by the Kafka Client.
define('PRODUCE_REQUEST_ID', 0);

// Load the PSR-0 Class Loader and setup Kafka namespace
require __DIR__.'/libraries/psrloader.php';
$loader = new \SplClassLoader;
$loader->add('', __DIR__.'/libraries/');
$loader->register();

// Grisgris, Informatio and F1
require_once __DIR__ . '/libraries/grisgris/src/import.php';
\Grisgris\Loader::registerNamespace('MOSC', __DIR__ . '/libraries/mosc');

/**
 * Return the current time in milliseconds.
 * Convenient wrapper around microtime to be JS friendly.
 */
function millitime()
{
	return (int) (microtime(1) * 1000);
}