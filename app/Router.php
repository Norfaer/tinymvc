<?php
namespace Tiny\Router;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Router {
    public $RouteMap;
    public function __construct() {
        ;
    }
    public function init($RouteMap) {
        $this->RouteMap=$RouteMap;
    }
    public function RouteURI($URI) {
        
    }
}
