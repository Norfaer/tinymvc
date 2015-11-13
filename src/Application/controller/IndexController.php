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

class IndexController extends AbstractController {
    public $ModelView;
    public function __construct() {
        parent::__construct();
        $this->Actions = ["DefaultAction","Index","ShowContent"];
    }
    public function DefaultAction() {
        $this->Index();
    }
    public function Index() {
        $this->ModelView = new IndexModelView;
        $this->ModelView->ProcessData();
        $this->ModelView->SendHtml("MainView");
    }
    public function ShowContent() {
        $this->ModelView = new IndexModelView;
        $this->ModelView->ProcessData();
        $this->ModelView->SendHtml("ContentView");
    }
}

class IndexModelView extends AbstractModelView{
    public function __construct() {
        parent::__construct();
        $this->Data=[];
    }
    public function ProcessData() {
        ;
    }
    public function ShowMenu() {
        $this->SendHtml("NavView",false);
    }
}