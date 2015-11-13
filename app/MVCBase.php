<?php
namespace Tiny\MVCBase;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
abstract class AbstractController {
    public $Response;
    public $AutoLoader;
    public $Actions=[];
    abstract public function DefaultAction();
    public function __construct() {
        global $Response, $AutoLoader;
        $this->AutoLoader = $AutoLoader;
        $this->Response = $Response;        
    }
    public function HasAction($action){
        foreach($this->Actions As $value) {
            if (($value==$action)&&(method_exists($this, $value))) return true;
        }
        return false;
    }
}

abstract class AbstractModelView {
    public $Data;
    public $ViewPath;
    public $Response;
    public $AutoLoader;
    abstract public function ProcessData();
    public function __construct() {
        global $Response, $AutoLoader;
        $this->AutoLoader = $AutoLoader;
        $this->Response = $Response;        
    }
    public function SendHtml($view_template,$send_header = true){
        $this->ViewPath=$this->AutoLoader->GetViewPath($view_template);
        if ($send_header){
            $this->Response->SetCache(CACHE_OFF);
            $this->Response->SetContentType(HCTYPE_HTML);
            $this->Response->Send();
        }
        require($this->ViewPath);
    }
    public function SendJson(){
        $this->Response->SetCache(CACHE_OFF);
        $this->Response->SetContentType(HCTYPE_JSON);
        $this->Response->Send();
        echo json_encode($this->Data);
    }
}

