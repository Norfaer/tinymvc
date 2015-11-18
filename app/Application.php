<?php
namespace Tiny\Application;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once "app/Http.php";
include_once "app/Session.php";
include_once "app/Router.php";
include_once "app/Session.php";
include_once "app/Autoloader.php";
//require_once "app/Utils.php";
include_once "app/Spyc.php";

use Tiny\HttpBase\Request;
use Tiny\Router\Router;
use Tiny\Session\Session;

function app_error_handler($errno , $errstr , $errfile="", $errline=0, $errcontext=[]) {
    throw new Exception($errstr, $errno);
}

class Application
{
    private $AutoLoader;
    private $Router;
    private $Request;
    private $Response;
    private $Session;
    private $Debug;
    public function __construct($config_path="config/config.yml") {
        set_error_handler(app_error_handler, E_ALL);
        global $AutoLoader, $Request, $Response, $Session;
        $this->AutoLoader = $AutoLoader;
        $this->Request = $Request;
        $this->Response = $Response;
        $this->Session = $Session;
        ini_set('display_errors',1);
        ini_set('xdebug.var_display_max_depth', 5);
        ini_set('xdebug.var_display_max_children', 256);
        ini_set('xdebug.var_display_max_data', 1024);
    }
    public function Run() {
        $this->Request->GetFromGlobals();
        $this->AutoLoader->LoadConfig();
        $this->Session->Start();
        $this->Router = new Router();
        $this->Router->RouteURI($this->Request->Uri);
    }
    public function Debug($key,$val) {
        $this->Debug[$key]=$val;
    }
    public function ShowDebug() {
        var_dump($this->Debug);
    }
}