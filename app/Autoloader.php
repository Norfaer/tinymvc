<?php
namespace Tiny\Autoloader;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'app/Spyc.php';

class AutoLoader
{
    private $ModuleMap;
    private $RouteMap;
    private $ClassMap;
    private $ViewMap;
    public function __construct() {
        $this->ClassMap=[];$this->ModuleMap=[];$this->RouteMap=[];$this->ViewMap=[];
    }
    public function LoadClass($classname) {
        
    }
    public function LoadModule($modulename) {
        $temp = spyc_load_file($this->ModuleMap[$modulename]["path"]."/module_config.yml");
        foreach ($temp["Controllers"] As $classname => $classfile){
            $this->ClassMap[$this->ModuleMap[$modulename]["namespace"]."\\".$classname]=$this->ModuleMap[$modulename]["controller_path"]."/".$classfile;
        }
        foreach ($temp["Views"] As $viewname => $viewfile){
            $this->ViewMap[$viewname]=$this->ModuleMap[$modulename]["view_path"]."/".$viewfile;
        }
        foreach ($temp["Routes"] As $route => $controller){
            $this->RouteMap[$modulename."/".$route]=$this->ModuleMap[$modulename]["namespace"]."\\".$controller["controller"];
            if (isset($controller["default"]))  $this->RouteMap[$modulename."/"]=$this->ModuleMap[$modulename]["namespace"]."\\".$controller["controller"];
        }
    }
    public function LoadConfig($path="config/config.yml") {
        $temp = spyc_load_file($path);
        foreach ($temp["modules"] As $modulename => $moduleparams) {
            $this->ModuleMap[$modulename]["path"] = isset($moduleparams["path"]) ? $moduleparams["path"]:"src/".$modulename;
            $this->ModuleMap[$modulename]["controller_path"] = isset($moduleparams["controller_path"]) ? $this->ModuleMap[$modulename]["path"].$moduleparams["controller_path"]:"src/".$modulename."/controller";
            $this->ModuleMap[$modulename]["view_path"] = isset($moduleparams["view_path"]) ? $this->ModuleMap[$modulename]["path"].$moduleparams["view_path"]:"src/".$modulename."/view";
            $this->ModuleMap[$modulename]["default"] = isset($moduleparams["default"]) ? $moduleparams["default"]:false;
            $this->ModuleMap[$modulename]["namespace"]=isset($moduleparams["namespace"]) ? $moduleparams["namespace"]:"Module\\".$modulename;
        }
    }
    public function ValidateConfig($config){
    }
}