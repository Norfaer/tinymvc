<?php
namespace Tiny\Router;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Router {
    private $RouteMap;
    public $Controller;
    public $Action;
    public $QueryString;
    public function __construct() {
        ;
    }
    public function init($RouteMap) {
        $this->RouteMap=$RouteMap;
    }
    public function RouteURI($URI) {
        $re = "/^(?:(?:\\/(?<module>\\w*))(?:(?:\\/(?<controller>\\w*))|(?:))(?:(?:\\/(?<action>\\w*))|(?:)))(?:\\?(?<query>.*)|)$/"; 
        preg_match($re, $URI, $matches);
        $ModuleName = empty($matches['module'])?'default':$matches['module'];
        $ControllerName = empty($matches['controller'])?'default':$matches['controller'];
        $this->Action = empty($matches['action'])?'DefaultAction':$matches['action'];
        $this->QueryString = empty($matches['query'])?"":$matches['query'];
        $this->Module=$ModuleName;
        $this->Controller=$this->RouteMap[$ModuleName."/".$ControllerName];
    }
}
