<?php
namespace Module\Application;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once "app/MVCBase.php";

use Tiny\MVCBase\AbstractController;
use Tiny\MVCBase\AbstractModelView;

class InstallController extends AbstractController {
    public $ModelView;
    public function __construct() {
        parent::__construct();
        $this->Actions = ["DefaultAction","Index"];
    }
    public function DefaultAction() {
        $this->Index();
    }
    public function Index() {
        $this->ModelView = new InstallModelView;
        $this->ModelView->InitData();
        $this->ModelView->ProcessData();
        $this->ModelView->UpdateAll();
    }
}

class InstallModelView extends AbstractModelView{
    public function __construct() {
        parent::__construct();
        $this->Data=[];
    }
    public function InitData() {
        $this->ViewData["login"]= isset($this->Response->Data["login"])? $this->Response->Post["login"] : "";
        $this->ViewData["pass"] = isset($this->Response->Data["pass"])? $this->Response->Post["pass"] : "";;
        $this->ViewData["repass"] = isset($this->Response->Data["repass"])? $this->Response->Post["repass"] : "";;
        $this->ViewData["host"] = isset($this->Response->Data["host"])? $this->Response->Post["host"] : "";;
        $this->ViewData["dblogin"] = isset($this->Response->Data["dblogin"])? $this->Response->Post["dblogin"] : "";;
        $this->ViewData["dbpass"] = isset($this->Response->Data[""])? $this->Response->Post[""] : "";;
        $this->ViewData["dbname"] = isset($this->Response->Data[""])? $this->Response->Post[""] : "";;
        $this->ViewData["dbprefix"] = isset($this->Response->Data[""])? $this->Response->Post[""] : "";;
        $this->ViewData[];
    }
    public function ProcessData() {
        ;
    }
    public function UpdateAll() {
        $this->SendHtml("MainView");
    }
    public function UpdateContent() {
        $this->SendHtml("InstallView",false,$this->ViewData);
    }
}