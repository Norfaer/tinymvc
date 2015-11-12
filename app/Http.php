<?php
namespace Tiny\HttpBase;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
define("HSTATUS_OK","200 OK");
define("HSTATUS_NOTFOUND","404 Not Found");
define("HSTATUS_FORBIDDEN","403 Forbidden");

define("HCTYPE_HTML","text/html");
define("HCTYPE_JSON","application/json");

define("CACHE_OFF","no-store, no-cache, must-revalidate");
define("CACE_STD","public, max-age=86400");

class Request {
//  Параметры, которые нам могут понадобиться
    public $Post;           // Post переменные 
    public $Get;            // Get переменные
    public $Cookie;        // Куки
    public $Files;          // Загружаемые файлы
    public $RequestMethod;  // Метод запроса: Get, Post, Put, Head
    public $RemoteAddr;     // Адрес клиента
    public $RemotePort;     // Порт клиента
    public $Uri;            // Запрашиваемый ресурс
    public $QueryString;    // Строка запроса

//  Конструктор
    public function __construct() {
        $this->Post=[];$this->Get=[];$this->Files=[];$this->Cookie=[];
    }
    public function GetFromGlobals(){
        $this->RequestMethod=$_SERVER['REQUEST_METHOD'];
        $this->RemoteAddr=$_SERVER['REMOTE_ADDR'];
        $this->RemotePort=$_SERVER['REMOTE_PORT'];
        $this->Uri=$_SERVER['REQUEST_URI'];
        $this->QueryString=$_SERVER['QUERY_STRING'];
        $this->Post=$_POST;
        $this->Get=$_GET;
        $this->Cookie=$_COOKIE;
    }
}

class Response {
    private $Status;
    private $CacheControl;
    private $ContentType;
    public function __construct() {
        $this->Status = HSTATUS_OK; 
        $this->ContentType = HCTYPE_HTML;
        $this->CacheControl = CACHE_OFF;
    }
    public function Send(){
        header("Content-Type: ".$this->ContentType);
        header("Cache-Control: ".$this->CacheControl);
        header("HTTP/1.x ".$this->Status);
        header("Status: ".$this->Status);
    }
    public function SetStatus($status) {
        $this->Status = $status;
    }
    public function SetCache($cache) {
        $this->CacheControl = $cache;
    }
    public function SetContentType($ctype) {
        $this->ContentType = $ctype;
    }

}

$Request = new Request();
$Response = new Response();