<?php

/* 
 * This code is part of TinyMVC project
 * Author: Alexander Firsov  * 
 */


class Router {
    use Singleton;
    private $registry;
    
    public function init() {
        $this->registry = Registry::getInstance();
    }
    
    public function dispatch() {
        $uri = explode('?', $this->request->server['REQUEST_URI']);
        $re = '/^\/([a-z]{2})\/(\w+)\/(\w+(?:\/\w+)?)\/(\w+)?$/U';
        $route = [];
        if (preg_match($re, $uri[0], $route)) {
            list(, $this->locale, $this->application, $this->front) = $route;
            $this->language->set($this->locale);
            if (!file_exists('app/' . $this->application . '/autoexec.php')) {
                throw new Exception('Приложение "'. $this->application . '" не найдено или настроено неверно', 907);
            }
            include_once('app/' . $this->application . '/autoexec.php');
            $action = isset($route[4]) ? $route[4] : 'index';
            $front = $this->load->frontcontroller($this->front);
            if (!method_exists($front, $action)) {
                throw new Exception('У контроллера "' . $this->front . '" не определено действие "' . $action . '"', 902);
            }
            $front->$action();
        }
        else {
            throw new Exception('Неверно указан ресурс REST :"' . $uri[0] . '"', 901);
        }
    }
        
    public function __get($key) {
            return $this->registry[$key];
    }

    public function __set($key, $value) {
            $this->registry[$key]=$value;
    }
}