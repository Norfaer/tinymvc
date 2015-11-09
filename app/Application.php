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
//require_once "app/Utils.php";
require_once "app/Spyc.php";

use Tiny\HttpBase\Request;
use Tiny\Router\Router;


class Application
{
    private $config;
    public function __construct() {
        $this->config=["Main"=>[],"ClassMap"=>[],"RouteMap"=>[]];
    }
    public function Init($config_file="config/config.yml") {
        ini_set('xdebug.var_display_max_depth', 5);
        ini_set('xdebug.var_display_max_children', 256);
        ini_set('xdebug.var_display_max_data', 1024);
        $this->config["Main"] = spyc_load_file($config_file);
        foreach($this->config["Main"]["modules"] As $modulename => &$moduleconfig) {
            if (!isset($moduleconfig["default"])) $moduleconfig["default"]=false;
            if (!isset($moduleconfig["path"])) $moduleconfig["path"]="src/".$modulename.'/';
            $tempconfig = spyc_load_file($moduleconfig["path"]."module_config.yml");
            foreach ($tempconfig['routes'] As $key => $val){
                $this->config["RouteMap"][$modulename."/".$key]=$modulename."/".$val["controller"];
                if (isset($val['default']) && ($val['default']===true)) {
                    $this->config["RouteMap"][$modulename.'/default']=$modulename."/".$val["controller"];
                    if ($moduleconfig["default"]) $this->config["RouteMap"]["default/default"]=$modulename."/".$val["controller"]; 
                }
            }
            foreach ($tempconfig['classmap'] As $key => $val){
                $this->config["ClassMap"][$modulename."/".$key]=$moduleconfig["path"].$val;
            }
        }
    }
    public function Run() {
        $request = new Request();
        $request->GetFromGlobals();
        $router = new Router();
        $router->Init($this->config["RouteMap"]);
        $router->RouteURI($request->Uri);
    }
}
