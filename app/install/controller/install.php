<?php

/* 
 * This code is part of TinyMVC project
 * Author: Alexander Firsov  * 
 */

class ControllerInstall extends Controller {
    public function index() {
        $this->load->language('install');
        $this->load->model('install');
        $data = $this->model_install->getStage1();
        $data['header_1'] = $this->language['header_1'];
        $this->load->view('content','stage', $data);
    }

    public function stage2() {
        
    }
    
    public function getFileStatus() {
        
    }
}
/*
require_once 'core/autoloader.class.php';

use Tpl\TplLayout;
use Tpl\TplModule;

function getStage1Data() {
    return [
        'strings' => [
            'Настройте пожалуйста PHP для удовлетворения требованиям:',
            'Установите и настройте следующие расширения PHP:',
            'Следующие файлы должны иметь разрешение на запись:',
            'Следующие каталоги должны иметь разрешение на запись:'
        ],
        'php_settings_header'=>['Название опции','Настройка','Требования','Статус'],
        'php_settings' => [
            ['Версия PHP',  phpversion(),'5.4.0',  version_compare(phpversion(), '5.4.0')===-1?'Ошибка':'ОК'],
            ['Magic Quotes GPC', get_magic_quotes_gpc()?'Вкл':'Выкл','Выкл',get_magic_quotes_gpc()?'Ошибка':'OK'],
            ['Загрузка файлов',ini_get('file_uploads')==1?'Вкл':'Выкл','Вкл',ini_get('file_uploads')==1?'OK':'Ошибка'],
            ['Автозапуск сессии',ini_get('session.auto_start')==0?'Выкл':'Вкл','Выкл',ini_get('session.auto_start')=='0'?'OK':'Ошибка']
        ]
    ];
}

if (isset($_REQUEST['action'])) {
    
}
else {
    $layout = TplLayout::getInstance();
    $layout->addModule(new TplModule('install/install.tpl','main',['title'=>'Установка TinyMVC', 'header_title'=>'Установка (Шаг 1 из 2)'],0));
    $layout->addModule(new TplModule('install/stage1.tpl','stage',  getStage1Data()));
    $layout->addModule(new TplModule('install/status.tpl','status',  []));
    $layout->run();
}*/