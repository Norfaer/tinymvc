<?php
namespace Module\Application;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once "app/MVCBase.php";
include_once "app/HtmlTools.php";

use Tiny\MVCBase\AbstractController;
use Tiny\MVCBase\AbstractModelView;
use Tiny\Html\Html;

class IndexController extends AbstractController {
    public $ModelView;
    public function __construct() {
        parent::__construct();
        $this->Actions = ["DefaultAction","Index"];
    }
    public function DefaultAction() {
        $this->Index();
    }
    public function Index() {
        $this->ModelView = new IndexModelView;
        $this->ModelView->ProcessData();
        $this->ModelView->UpdateAll();
    }
}

class IndexModelView extends AbstractModelView{
    public function __construct() {
        parent::__construct();
        $this->Data=[
            "MainMenu" => [
                "Item1" => ["item"=>"Главная","icon"=>"icon-home","link"=>"/Application/"],
                "Item2" => ["item"=>"Заказы","icon"=>"icon-truck","link"=>"#", "submenu" => [
                        "Item21" => ["item"=>"Новый...","icon"=>"icon-doc","link"=>"#"],
                        "Item22" => ["item"=>"Текущие","icon"=>"icon-list","link"=>"#"],
                        "Item23" => ["item"=>"Архив","icon"=>"icon-folder","link"=>"#"]]],
                "Item3" => ["item"=>"Цены","icon"=>"icon-money","link"=>"#"],
                "Item4" => ["item"=>"Инструменты","icon"=>"icon-cog","link"=>"#"],
                "Item5" => ["item"=>"Справка","icon"=>"icon-help","link"=>"#"],
                "Item6" => ["item"=>"Профиль","icon"=>"icon-user","link"=>"#"]
            ],
            "NavMenu" =>[
                "Item1" => ["item"=>"Новый...","icon"=>"icon-doc","link"=>"#"],
                "Item2" => ["item"=>"Текущие","icon"=>"icon-list","link"=>"#"],
                "Item3" => ["item"=>"Архив","icon"=>"icon-folder","link"=>"#"],
                "Item4" => ["item"=>"Справка","icon"=>"icon-help","link"=>"#"]
            ]
        ];
    }
    public function InitData() {
        ;
    }
    public function ProcessData() {
        ;
    }
    public function UpdateAll() {
        $this->SendHtml("MainView");
    }
    public function UpdateContent() {
        $this->SendHtml("ContentView",false);
    }
    public function InitMainMenu() {
        Html::html_nav($this->Data["MainMenu"]);
    }
    public function InitVertMenu() {
        Html::html_nav($this->Data["NavMenu"]);
    }
}