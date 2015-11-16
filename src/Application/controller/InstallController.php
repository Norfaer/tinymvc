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
        $this->ViewData["login"]="";
        $this->ViewData["pass"]="";
        $this->ViewData["repass"]="";
        $this->ViewData["host"]="";
        $this->ViewData["dblogin"]="";
        $this->ViewData["dbpass"]="";
        $this->ViewData["dbname"]="";
        $this->ViewData["dbprefix"]="";
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