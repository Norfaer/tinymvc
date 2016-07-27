<?php

/* 
 * This code is part of TinyMVC project
 * Author: Alexander Firsov  * 
 */
class Language implements ArrayAccess {
    use Singleton;
    
    private $container=[];
    private $lang;
    
    public function init() {
        $this->lang = 'ru';
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }
    
    public function __set($name, $value) {
        $this->container[$name] = $value;
    }
    
    public function __get($name) {
        return isset($this->container[$name]) ? $this->container[$name] : null;
    }
    
    public function load(&$data) {
        $this->container = array_merge($this->container, $data);
    }
    
    public function set($lang) {
        $this->lang = $lang;
    }
    
    public function get() {
        return $this->lang;
    }
}