<?php
namespace Tiny\HttpBase;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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
        
    }
    public function GetFromGlobals(){
        $this->RequestMethod=$_SERVER['REQUEST_METHOD'];
        $this->RemoteAddr=$_SERVER['REMOTE_ADDR'];
        $this->RemotePort=$_SERVER['REMOTE_PORT'];
        $this->Uri=$_SERVER['REQUEST_URI'];
        $this->QueryString=$_SERVER['QUERY_STRING'];
        $this->Post=$_POST;
        $this->Get=$GET;
        $this->Cookie=$_COOKIE;
    }
}

class Response {
    
}