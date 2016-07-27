<?php

/* 
 * This code is part of TinyMVC project
 * Author: Alexander Firsov  * 
 */

class TplSimple {
    use Singleton;
    private $blocks;
    
    public function init() {
        $this->blocks = [];
    }
    
    public function addBlock($block, $path, &$data) {
        $this->blocks[$block][] = ['path'=>$path,'data'=>&$data];
    }
        
    public function renderBlock($block) {
        if (isset($this->blocks[$block])) {
            foreach ($this->blocks[$block] as &$piece){
                $this->render($piece['path'],$piece['data']);
            }
        }
    }
    
    public function render($__path, &$__data) {
        extract($__data);
        if (file_exists($__path)) {
            include 'tpl://'.$__path;
        }
    }
}