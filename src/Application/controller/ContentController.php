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


class ContentController extends AbstractController {
    public $ModelView;
    public function __construct() {
        parent::__construct();
        $this->Actions = ["DefaultAction","Index"];
    }
    public function DefaultAction() {
        $this->Index();
    }
    public function Index() {
        $this->ModelView = new ContentModelView;
        $this->ModelView->ProcessData();
        $this->ModelView->SendHtml("ContentView");
    }
}

class ContentModelView extends AbstractModelView {
    public function __construct() {
        parent::__construct();
        $this->Data=[
            "Menu" => [
                "Item1" => ["item"=>"Главная","icon"=>"icon-home","link"=>"/Application/"],
                "Item2" => ["item"=>"Заказы","icon"=>"icon-truck","link"=>"#", "submenu" => [
                        "Item21" => ["item"=>"Новый...","icon"=>"icon-doc","link"=>"#"],
                        "Item22" => ["item"=>"Текущие","icon"=>"icon-list","link"=>"#"],
                        "Item23" => ["item"=>"Архив","icon"=>"icon-folder","link"=>"#"]]],
                "Item3" => ["item"=>"Цены","icon"=>"icon-money","link"=>"#"],
                "Item4" => ["item"=>"Инструменты","icon"=>"icon-cog","link"=>"#"],
                "Item5" => ["item"=>"Справка","icon"=>"icon-help","link"=>"#"],
                "Item6" => ["item"=>"Профиль","icon"=>"icon-user","link"=>"#"]
            ]
        ];
    }
    public function InitUI() {
        Html::html_nav($this->Data["Menu"]);
    }
    public function InitData() {
        ;
    }
    public function ProcessData() {
        ;
    }
}