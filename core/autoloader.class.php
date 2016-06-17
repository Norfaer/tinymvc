<?php
/* 
 * This code is part of TinyMVC project
 * Author: Alexander Firsov  * 
 */

require_once 'singleton.class.php';

class Autoloader {
    private $registry;
    use Singleton;

    public function init() {
        define('CLASS_DIR', 'core/');
        set_include_path(get_include_path().PATH_SEPARATOR.CLASS_DIR);
        spl_autoload_extensions('.class.php');
        spl_autoload_register();
        $this->registry = Registry::getInstance();
    }
    
    public function controller($mcpath, $data=[]) {
        $path = $this->application.'/controller/'.$mcpath.'.php';
        if (file_exists($path)) {
            require_once $path;
            $split = explode('/', $mcpath);
            $classname = 'Controller'. (isset($split[1]) ? $split[1] : $split[0]);
            $regname = 'controller_' . str_replace('/', '_', $mcpath);
            return class_exists($classname,false) ? $this->$regname = new $classname : null;
        }
    }

    public function model($mmpath, $data=[]) {
        $path = $this->application.'/model/'.$mmpath.'.php';
        if (file_exists($path)) {
            require_once $path;
            $split = explode('/', $mmpath);
            $classname = 'Model'. (isset($split[1]) ? $split[1] : $split[0]);
            $regname = 'model_' . str_replace('/', '_', $mmpath);
            return class_exists($classname,false) ? $this->$regname = new $classname : null;
        }
    }

    public function view($block, $mvpath, &$data) {
        $path = $this->application . '/view/' . $mvpath . '.tpl';
        $layout = TplSimple::getInstance();
        $layout->addBlock($block, $path, $data);
    }
    
    public function language($mlpath) {
        $path = $this->application.'/language/'.$this->language->get().'/'.$mlpath.'.php';
        if (file_exists($path)){
            $_=[];
            require_once $path;
            $this->language->load($_);
        }
    }


    public function __get($key) {
            return $this->registry[$key];
    }

    public function __set($key, $value) {
            $this->registry[$key]=$value;
    }
}

Autoloader::getInstance();