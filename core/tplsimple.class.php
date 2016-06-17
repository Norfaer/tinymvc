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
        $this->blocks[$block]['path'] = $path;
        $this->blocks[$block]['data'] = &$data;
    }
    
    private function block($block) {
        if (isset($this->blocks[$block])) {
            $this->render($this->blocks[$block]['path'],$this->blocks[$block]['data'], true);
        }
    }
    
    public function renderBlock($block) {
        if (isset($this->blocks[$block])) {
            $this->render($this->blocks[$block]['path'],$this->blocks[$block]['data'], true);
        }
    }
    
    public function render($__path, $__data = [], $__echo = false) {
        $__short = ini_get('short_open_tag');
        extract($__data);
        ob_start();
        if (file_exists($__path)) {
            include $__path;
        }
        return $__echo ? ob_end_flush() : ob_get_clean();
    }
}