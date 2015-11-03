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

class Application
{
    private $config;
    public function __construct() {
        $config=[];
    }
    public function init() {
//      $this->config=(include "config/config.php");
        $this->config=yaml_parse(include "config/config.php");
        $modules=$this->config["modules"];
        foreach ($modules as $modulename) {
            $this->config=  array_merge_r($this->config, include ("src/".$modulename."/module_config.php"));
        }
    }
    public function run() {
        
        print_r($this->config);
    }
}
