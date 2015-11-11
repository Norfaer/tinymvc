<?php
namespace Tiny\Router;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Router {
    private $RouteMap;
    public $Module;
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
        $re = "/^(?:(?:(?:\\/([^\\/\\?]\\w*))|(?:))(?:(?:\\/([^\\/\\?]\\w*))|(?:))(?:(?:\\/([^\\/\\?]\\w*))|(?:)))(?:(?:(?:\\?|\\/\\?)(.*))|)$/"; 
////    $re = "/^(\\/[\\w\\/]*)(?:(\\?.*)|)$/"; 
        if (preg_match($re, $URI, $matches)) {
            $this->Module=isset($matches[1])? $matches[1]:"";;
            $this->Controller=isset($matches[2])? $matches[2]:"";
            $this->Action = isset($matches[3])? $matches[3]:"";
            $this->QueryString = isset($matches[4])? $matches[4]:"";
            return true;
        }
        else return false;
    }
}
