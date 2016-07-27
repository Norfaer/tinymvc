<?php

/* 
 * This code is part of TinyMVC project
 * Author: Alexander Firsov  * 
 */

trait Singleton
{
    protected static $_instance;
    
    final public static function getInstance($param = null) {
        return isset(static::$_instance) ? static::$_instance : static::$_instance = new static($param);
    }
    
    final private function __construct($param) {
        $this->init($param);
    }
    
    protected function init($param) {
        
    }
    
    final private function __wakeup() {
        
    }
    
    final private function __clone() {
        
    }    
}