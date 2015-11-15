<?php
namespace Tiny\Html;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Html {
    static public function html_table() {
    }
    static public function html_enquote($expr) {
        return '"'.$expr.'"';
    }
    static public function html_nav($menu) {
        function html_menu_r($menuitem) {
            echo "<ul>";
                foreach ($menuitem As $menuparams) {
                    echo "<li><a href=".html::html_enquote($menuparams["link"])."><i class=".html::html_enquote($menuparams["icon"])."></i>".$menuparams["item"]."</a>";
                    if (isset($menuparams["submenu"])){
                            html_menu_r($menuparams["submenu"]);
                    }
                    echo "</li>";
                }   
            echo "</ul>";
        }
        echo "<nav>";
        html_menu_r($menu);
        echo "</nav>";
    }
}