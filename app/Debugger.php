<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Debugger {
    private $Data;
    public function Debug($key, $val) {
        $this->Data[$key]=$val;
    }
    public function Dump() {
        var_dump($this->Data);
    }
}

$Debugger = new Debugger();