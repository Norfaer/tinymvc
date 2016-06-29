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
    
    public function controller($mcpath) {
        $path = 'app/' . $this->application . '/controller/' . $mcpath . '.php';
        if (file_exists($path)) {
            require_once $path;
            $split = explode('/', $mcpath);
            $classname = 'Controller' . (isset($split[1]) ? $split[1] : $split[0]);
            $regname = 'controller_' . str_replace('/', '_', $mcpath);
            if (isset($this->$regname)) {
                return $this->$regname;
            }
            if (!class_exists($classname, false)) {
                throw new Exception('Класс "' . $classname . '" не определен', 909);
            }
            if (!is_subclass_of($classname, 'Controller')) {
                throw new Exception('Класс "' . $classname . '" не является контроллером (не наследует Controller)', 910);
            }
            return $this->$regname = new $classname;
        }
        throw new Exception('Не найден файл класса: "'. $path . '"', 903);
    }
    
    public function frontcontroller($mcpath) {
        $path = 'app/' . $this->application . '/controller/' . $mcpath . '.php';
        if (file_exists($path)) {
            require_once $path;
            $split = explode('/', $mcpath);
            $classname = 'Controller' . (isset($split[1]) ? $split[1] : $split[0]);
            $regname = 'controller_' . str_replace('/', '_', $mcpath);
            if (isset($this->$regname)) {
                return $this->$regname;
            }
            if (!class_exists($classname, false)) {
                throw new Exception('Класс "' . $classname . '" не определен', 909);
            }
            if (!is_subclass_of($classname, 'ControllerEnvokable')) {
                throw new Exception('Класс "' . $classname . '" не является откликаемым (не наследует ControllerEnvokable)', 908);
            }
            return $this->$regname = new $classname;
        }
        throw new Exception('Не найден файл класса: "'. $path . '"', 903);
    }

    public function model($mmpath) {
        $path = 'app/'.$this->application.'/model/'.$mmpath.'.php';
        if (file_exists($path)) {
            require_once $path;
            $split = explode('/', $mmpath);
            $classname = 'Model'. (isset($split[1]) ? $split[1] : $split[0]);
            $regname = 'model_' . str_replace('/', '_', $mmpath);
            if (isset($this->$regname)) {
                return $this->$regname;
            }
            if (!class_exists($classname, false)) {
                throw new Exception('Класс "' . $classname . '" не определен', 909);
            }
            if (!is_subclass_of($classname, 'Model')) {
                throw new Exception('Класс "' . $classname . '" не является моделью (не наследует Model)', 911);
            }
            return $this->$regname = new $classname;
        }
        throw new Exception('Не найден файл модели: "'. $path . '"', 904);
    }

    public function view($block, $mvpath, &$data) {
        $path = 'app/'.$this->application . '/view/' . $mvpath . '.tpl';
        if (file_exists($path)) {
            $layout = TplSimple::getInstance();
            $layout->addBlock($block, $path, $data);
            return;
        }
        throw new Exception('Не найден файл шаблона: "'. $path . '"', 905);
    }
    
    public function language($mlpath) {
        $path = 'app/'.$this->application.'/language/'.$this->language->get().'/'.$mlpath.'.php';
        if (file_exists($path)){
            $_=[];
            require_once $path;
            $this->language->load($_);
            return;
        }
        throw new Exception('Не найден языковой файл : "'. $path . '"', 906);
    }


    public function __get($key) {
            return $this->registry[$key];
    }

    public function __set($key, $value) {
            $this->registry[$key]=$value;
    }
}

Autoloader::getInstance();