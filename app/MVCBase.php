<?php
namespace Tiny\MVCBase;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
abstract class AbstractController {
    public $actions=[];
    abstract public function DefaultAction();
    public function HasAction($action){
        foreach($this->actions As $value) {
            if (($value==$action)&&(method_exists($this, $value))) return true;
        }
        return false;
    }
}

