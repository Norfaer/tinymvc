<?php
namespace Module\Application;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once "app/MVCBase.php";

use Tiny\MVCBase\AbstractController;

class IndexController extends AbstractController {
    public function __construct() {
        $this->actions=["DefaultAction","Index","Error404","Error503"];
    }
    public function DefaultAction() {
        $this->Index();
    }
    public function Index() {
        echo "Hello Index Controller Module Application";
    }
}
