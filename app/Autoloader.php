<?php
namespace Tiny\Autoloader;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AutoLoader
{
    private $ClassMap;
    public function LoadClass($classname);
    public function AppendConfig($path);
}