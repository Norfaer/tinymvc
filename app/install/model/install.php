<?php

/* 
 * This code is part of TinyMVC project
 * Author: Alexander Firsov  * 
 */

class ModelInstall extends Model {
    public function getStage1() {
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
}