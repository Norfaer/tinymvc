<?php
namespace Tiny\HttpBase;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Request {
    public $attributes;
    public $query;
    public $cookies;
    public function __construct() {
        $query = [];
        $cookies=  [];
        $attributes = [];
    }
    public function GetFromGlobals(){
        $this->attributes['method']=$_SERVER['REQUEST_METHOD'];
    }
}

class Response {
    
}