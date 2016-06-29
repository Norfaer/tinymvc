<?php
try {
    define('VERSION', '1.0.0a');
    chdir(__DIR__);

    require_once 'core/helper/error.php';
    require_once 'core/autoloader.class.php';

    if (file_exists('config.php')) {
        require_once 'config.php';
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
} catch (Exception $e) {
    ob_end_clean();
    $backtrace = $e->getTrace();
    include 'core/helper/error.tpl';
}
