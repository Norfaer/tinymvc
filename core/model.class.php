<?php

/* 
 * This code is part of TinyMVC project
 * Author: Alexander Firsov  * 
 */

abstract class Model {
    
    protected $registry;
    
    public function __construct() {
        $this->registry = Registry::getInstance();
    }
    
    final public function __get($key) {
            return $this->registry[$key];
    }

    final public function __set($key, $value) {
            $this->registry[$key]=$value;
    }
    
}