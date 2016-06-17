<?php

define('VERSION', '1.0.0a');
chdir(__DIR__);

require_once 'core/autoloader.class.php';
require_once 'config.php';

use Http\Request;
use Http\Response;

if (is_file('config.php')) {
	require_once('config.php');
}

$autoloader = Autoloader::getInstance();
$registry = Registry::getInstance();

$registry['load'] = $autoloader;
$registry['request'] = Request::getInstance();
$registry['response'] = Response::getInstance();
$registry['language'] = Language::getInstance();

$router = Router::getInstance();

$router->dispatch();

$registry->response->output();