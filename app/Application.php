<?php
namespace Tiny\Application;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once "app/Http.php";
require_once "app/Session.php";
require_once "app/Router.php";
require_once "app/Session.php";
require_once "app/Autoloader.php";
//require_once "app/Utils.php";
require_once "app/Spyc.php";

use Tiny\HttpBase\Request;
use Tiny\Router\Router;
use Tiny\Session\Session;

class Application
{
    private $AutoLoader;
    private $Router;
    private $Request;
    private $Response;
    public function __construct($config_path="config/config.yml") {
        global $AutoLoader;
        $this->AutoLoader=$AutoLoader;
        ini_set('display_errors',1);
        ini_set('xdebug.var_display_max_depth', 5);
        ini_set('xdebug.var_display_max_children', 256);
        ini_set('xdebug.var_display_max_data', 1024);
    }
    public function Init() {
    }
    public function Run() {
        $session = new Session();
        $session->start();
        $this->AutoLoader->LoadConfig();
        $request = new Request();
        $request->GetFromGlobals();
        $router = new Router();
        $router->RouteURI($request->Uri);
    }
}