<?php
namespace Tiny\Application;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once "app/Http.php";
require_once "app/Session.php";
require_once "app/Utils.php";
require_once "app/Spyc.php";

use Tiny\HttpBase\Request;

class Application
{
    private $config;
    public function __construct() {
        $this->config=["Main"=>[],"ClassMap"=>[],"Routemap"=>[]];
    }
    public function init() {
        $this->config["Main"] = spyc_load_file("config/config.yml");
        foreach($this->config["Main"]["modules"] As $modulename => &$moduleconfig) {
            if (!isset($moduleconfig["default"])) $moduleconfig["default"]=false;
            if (!isset($moduleconfig["path"])) $moduleconfig["path"]="src/".$modulename.'/';
        }
    }
    public function run() {
        $request= new Request();
        $request->GetFromGlobals();
        print_r($this->config["Main"]);
        var_dump($this->config["Main"]["modules"]);
    }
}
