<?php
chdir(dirname(__DIR__));
require_once 'app/Application.php';

use Tiny\Application\Application;

$app = new Application();
$app->init();
$app->run();

