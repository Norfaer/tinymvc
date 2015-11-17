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
        $this->Actions = ["DefaultAction","Index","GetFileStatus","Install"];
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
    public function GetFileStatus() {
        $this->ModelView = new InstallModelView;
        $this->ModelView->GetFileStatus();
    }
}

class InstallModelView extends AbstractModelView{
    public function __construct() {
        parent::__construct();
        $this->Data=[];
    }
    public function InitData() {
        $this->ViewData["login"]= isset($this->Response->Data["login"])? $this->Response->Post["login"] : "admin";
        $this->ViewData["pass"] = isset($this->Response->Data["pass"])? $this->Response->Post["pass"] : "";
        $this->ViewData["repass"] = isset($this->Response->Data["repass"])? $this->Response->Post["repass"] : "";
        $this->ViewData["host"] = isset($this->Response->Data["host"])? $this->Response->Post["host"] : "localhost";
        $this->ViewData["dblogin"] = isset($this->Response->Data["dblogin"])? $this->Response->Post["dblogin"] : "";
        $this->ViewData["dbpass"] = isset($this->Response->Data["dbpass"])? $this->Response->Post["dbpass"] : "";
        $this->ViewData["dbname"] = isset($this->Response->Data["dbname"])? $this->Response->Post["dbname"] : "";
        $this->ViewData["dbprefix"] = isset($this->Response->Data["dbprefix"])? $this->Response->Post["dbprefix"] : "";
        $this->ViewData["fileflag"]= is_writable("config/config_db.yml")? "icon-ok" : "icon-invalid";
    }
    public function GetFileStatus() {
        $json["FileStatus"] = is_writable("config/config_db.yml")? "1":"0";
        $this->SendJson($json);
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