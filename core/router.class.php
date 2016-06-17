<?php

/* 
 * This code is part of TinyMVC project
 * Author: Alexander Firsov  * 
 */

use Http\Request;
use Http\Response;

class Router {
    use Singleton;
    private $registry;
    
    public function init() {
        $this->registry = Registry::getInstance();
    }
    
    public function dispatch() {
        switch ($this->request->server['REQUEST_METHOD']) {
            case 'GET':
                    $this->dispatchGet();
                break;
            case 'POST':
                    $this->dispatchPost();
                break;
            case 'DELETE':
                    $this->dispatchDelete();
                break;
            case 'PUT' :
                    $this->dispatchPut();
                break;
        }
    }
    
    private function dispatchGet() {
        $uri = explode('?', $this->request->server['REQUEST_URI']);
        $re = '/^\/(\w+)\/(\w+(?:\/\w+)?)\/(\w+)?$/U';
        $route = [];
        if (preg_match($re, $uri[0], $route)) {
            list(,$this->application,$this->controller) = $route;
            $action = isset($route[3]) ? $route[3] : 'index';
            $obj = $this->load->controller($this->controller);
            if (method_exists($obj, $action)) {
                $this->layout = TplSimple::getInstance();
                $this->document = HtmlDoc::getInstance();
                $obj->$action();
                $this->load->view('layout','layout',$this->document->getData());
                $this->layout->renderBlock('layout');
            }
        }
    }

    private function dispatchPost() {
        $response = $this->response;
        $uri = explode('?', $this->request->server['REQUEST_URI']);
        $re = '/^\/(\w+)\/(\w+(?:\/\w+)?)\/(\w+)?$/U';
        $route = [];
        if (preg_match($re, $uri[0], $route)) {
            list(,$this->application,$this->controller) = $route;
            $action = isset($route[3]) ? $route[3] : 'index';
            $obj = $this->load->controller($this->controller);
            if (method_exists($obj, $action)) {
                $obj->$action();
            }
        }
    }

    private function dispatchDelete() {
        
    }
    
    public function __get($key) {
            return $this->registry[$key];
    }

    public function __set($key, $value) {
            $this->registry[$key]=$value;
    }
}