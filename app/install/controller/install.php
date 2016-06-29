<?php

/* 
 * This code is part of TinyMVC project
 * Author: Alexander Firsov  * 
 */

class ControllerInstall extends ControllerEnvokable {
    public function index() {
        $this->load->language('install');
        $this->load->model('install');
        
        $this->layout = TplSimple::getInstance();
        $this->document = HtmlDoc::getInstance();
        $this->document->setTitle($this->language['title']);
        
        $data = $this->model_install->getStage1();
        $data['header_1'] = $this->language['header_1'];
        
        
        $this->load->view('content','stage', $data);
        $this->load->view('layout','layout',$this->document->getData());
        
        $this->layout->renderBlock('layout');
    }

    public function stage2() {
        
    }
    
    public function getFileStatus() {
        
    }
}