<?php
namespace Tiny\Router;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Exception;

class Router {
    private $RouteMap;
    private $AutoLoader;
    public $ModuleRoute;
    public $ControllerRoute;
    public $Action;
    public $QueryString;
    public function __construct() {
        global $AutoLoader;
        $this->AutoLoader=$AutoLoader;
    }
    private function ParseUri($uri) {
        $route_reg_exp = "/^\\/(?:(\\w+)|(\\w+)\\/(?:(\\w+)|(\\w+)\\/(?:(\\w+)|)|)|)(?:\\?(\\w*)|)$/";  
        if (preg_match($route_reg_exp, $uri, $matches)) {
            $this->ModuleRoute = !empty($matches[1])? $matches[1] : $this->AutoLoader->GetDefaultModule();
            $this->ModuleRoute = !empty($matches[2])? $matches[2] : $this->ModuleRoute;
            $this->ControllerRoute = !empty($matches[3])? $matches[3] : "";
            $this->ControllerRoute = !empty($matches[4])? $matches[4] : $this->ControllerRoute;
            $this->Action = !empty($matches[5])? $matches[5] : "DefaultAction";
            $this->QueryString = !empty($matches[6])? $matches[6] : "";
            return $this->ModuleRoute."/".$this->ControllerRoute;
        }
        else {
            throw new Exception("Неверный маршрут : " . $uri, 404);
        }
    }
    public function StdRoute($route) {
        if ($this->AutoLoader->ModuleExist($this->ModuleRoute)) {
            $this->AutoLoader->LoadModule($this->ModuleRoute);
            $this->RouteMap = $this->AutoLoader->GetRouteMap();
            if ($this->AutoLoader->RouteExist($route)) {
                $ClassName=$this->RouteMap[$route];
                $this->AutoLoader->LoadClass($ClassName);
                $CtrlObj = new $ClassName;
                if ($CtrlObj===null){throw new Exception("Ошибка создания объекта класса" . $ClassName, 404);}
                $ActionName = $this->Action;
                if ($CtrlObj->HasAction($ActionName)) {$CtrlObj->$ActionName();}
                else {throw new Exception("Действие " . $ActionName . " не найдено в " . $ClassName, 404);}
            }
            else {throw new Exception("Маршрут " . $route . " не найден", 404);}
        }
        else {throw new Exception("Модуль ".$this->ModuleRoute." не удалось загрузить", 404);}
    }
    public function RouteURI($uri) {
        try {
            $route = $this->ParseUri($uri);
            $this->StdRoute($route);
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
}