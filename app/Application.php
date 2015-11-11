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
use Tiny\Autoloader\AutoLoader;

class Application
{
    private $AutoLoader;
    private $Router;
    private $Module;
    private $Controller;
    private $Action;
    private $Request;
    private $Response;
    public function __construct() {
        $this->config=["Main"=>[],"ClassMap"=>[],"RouteMap"=>[]];
    }
    public function Init($config_file="config/config.yml") {
        ini_set('display_errors',1);
        ini_set('xdebug.var_display_max_depth', 5);
        ini_set('xdebug.var_display_max_children', 256);
        ini_set('xdebug.var_display_max_data', 1024);
        $session = new Session();
        $session->start();
    }
    public function Run() {
        $this->AutoLoader=new AutoLoader;
        $this->AutoLoader->LoadConfig();
        $this->AutoLoader->LoadModule("Application");
        var_dump($this->AutoLoader);
//        $request = new Request();
//        $request->GetFromGlobals();
//        $router = new Router();
//        $router->Init($this->config["RouteMap"]);
//        $router->RouteURI($request->Uri);
//        require_once($this->config["ClassMap"][$router->Controller]);
//        $controller = new $router->Controller;
//        $action = $router->Action;
//        $controller->$action();
    }
}