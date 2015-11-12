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
        $this->Actions = ["DefaultAction","Index","Error404","Error503"];
    }
    public function DefaultAction() {
        $this->Index();
    }
    public function Index() {
        $this->ModelView = new IndexModelView;
        $this->Response->Send();
        $this->ModelView->SetTemplate("MainView");
        $this->ModelView->Send();
    }
}


class IndexModelView extends AbstractModelView{
}