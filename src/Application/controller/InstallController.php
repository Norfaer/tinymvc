<?php
namespace Module\Application;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once "app/MVCBase.php";
include_once "app/Debugger.php";

use Tiny\MVCBase\AbstractController;
use Tiny\MVCBase\AbstractModelView;
use PDO;

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
        $this->ModelView->SendTemplate();
    }
    public function Install() {
        $this->ModelView = new InstallModelView;
        $this->ModelView->InitData();
        $this->ModelView->Install();
        $this->ModelView->SendTemplate();
    }
    
    public function GetFileStatus() {
        $this->ModelView = new InstallModelView;
        $this->ModelView->GetFileStatus();
    }
}

class InstallModelView extends AbstractModelView {
    public function __construct() {
        parent::__construct();
        $this->Data=[];
    }
    public function InitData() {
        $this->ViewData["login"]= isset($this->Request->Post["login"])? $this->Request->Post["login"] : "admin";
        $this->ViewData["pass"] = isset($this->Request->Post["pass"])? $this->Request->Post["pass"] : "";
        $this->ViewData["repass"] = isset($this->Request->Post["repass"])? $this->Request->Post["repass"] : "";
        $this->ViewData["host"] = isset($this->Request->Post["host"])? $this->Request->Post["host"] : "localhost";
        $this->ViewData["dblogin"] = isset($this->Request->Post["dblogin"])? $this->Request->Post["dblogin"] : "";
        $this->ViewData["dbpass"] = isset($this->Request->Post["dbpass"])? $this->Request->Post["dbpass"] : "";
        $this->ViewData["dbname"] = isset($this->Request->Post["dbname"])? $this->Request->Post["dbname"] : "";
        $this->ViewData["dbprefix"] = isset($this->Request->Post["dbprefix"])? $this->Request->Post["dbprefix"] : "";
        $this->ViewData["fileflag"]= is_writable("config/config_db.yml")? "icon-ok" : "icon-invalid";
        global $Debugger;
        $Debugger->Debug("PDO",PDO::getAvailableDrivers());
    }
    
    public function Install() {
        $db_config=[];
        $login = $this->ViewData["login"];
        $pass = $this->ViewData["pass"];
        $db_config["host"] = $this->ViewData["host"];
        $db_config["dblogin"] = $this->ViewData["dblogin"];
        $db_config["dbpass"] = $this->ViewData["dbpass"];
        $db_config["dbname"] = $this->ViewData["dbname"];
        $db_config["dbprefix"] = $this->ViewData["dbprefix"];
        $fp=fopen("config/config_db.yml","w");
        $str = spyc_dump($db_config);
        fwrite($fp, "---".PHP_EOL.$str);
        fclose($fp);
        $pdo = new PDO("mysql:host=".$db_config["host"].";charset=utf8", $db_config["dblogin"], $db_config["dbpass"]);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("SET NAMES utf8");
        $pdo->query('CREATE DATABASE IF NOT EXISTS '.$db_config["dbname"]);
        $pdo->query("USE ".$db_config["dbname"]);
        $pdo->query('CREATE TABLE IF NOT EXISTS '.$db_config["dbprefix"].'auth
    (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    priv BIGINT UNSIGNED NOT NULL DEFAULT "2",
    login VARCHAR(16) NOT NULL DEFAULT "",
    password VARCHAR(40) NOT NULL DEFAULT "",
    active SMALLINT UNSIGNED DEFAULT 0,
    PRIMARY KEY (id)
    )CHARACTER SET utf8 ENGINE=INNODB');
        $salt = substr(sha1($login), 10, 20)."\3\1\2\6";
        $hashpass = sha1(sha1($pass).$salt);
        $query = 'INSERT INTO '.$db_config["dbprefix"].'auth  (login, password, priv, active) VALUES ("' . $login . '","' . $hashpass  . '",' ."0" . ',1)';
        $pdo->query($query);
        $pdo=null;
    }
    
    public function GetFileStatus() {
        $json["FileStatus"] = is_writable("config/config_db.yml")? "1":"0";
        $this->SendJson($json);
    }
    public function ProcessData() {
        ;
    }
    public function SendTemplate() {
        $this->SendHtml("Template");
    }
    public function SendMainFrame() {
        $this->SendHtml("InstallView",false,$this->ViewData);
    }
    public function SendError() {
        if ($this->ErrorCode!==0) {
            $this->SendHtml("ErrorView",false);
        }
    }
}