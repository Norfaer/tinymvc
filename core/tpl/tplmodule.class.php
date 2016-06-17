<?php
namespace Tpl;
/* 
 * This code is part of TinyMVC project
 * Author: Alexander Firsov  * 
 */
use Exception;

class TplModule {
    private $nodes;
    private $gdata;
    private $ldata;
    private $stream;
    private $type;
    private $name;
    
    const MOD_TYPE_MAIN = 0;
    const MOD_TYPE_BLOCK = 1;
    
    public function __construct($source, $name, $data = [], $type = 1) {
        $this->nodes = [];
        $this->gdata = $data;
        $this->ldata = [];
        $this->type = $type;
        $this->name = $name;
        if (($this->stream = file_get_contents($source)) === false) {
            throw new Exception('Cannot read tpl - file ' . $source, 707);
        }
        $compiler = TplCompiler::getInstance();
        $compiler->compile($this);
    }
    
    public function &getGData() {
        return $this->gdata;
    }
    
    public function &getLData() {
        return $this->ldata;
    }

    public function &getStream() {
        return $this->stream;
    }
    
    public function &getNodes() {
        return $this->nodes;
    }
    
    public function getName() {
        return $this->name;
    }

    public function isMain() {
        return $this->type == self::MOD_TYPE_MAIN ? true : false;
    }
    
    public function dump() {
        ini_set("xdebug.var_display_max_depth", 20);
        $str_addr = [];
        $str_addr[ADDR_STRICT] = 'Непосредственная';
        $str_addr[ADDR_STREAM] = 'Из потока';
        $str_addr[ADDR_VAR] = 'Прямая';
        $str_addr[ADDR_OFFSET] = 'Прямая с индексом';
        $index = 0;
        echo '<style> td,th,table {border:1px solid black;} </style>';
        echo '<table>';
        echo '<tr><th rowspan="2">#</th><th rowspan="2">Инструкция</th><th colspan="3">Операнд #1</th><th colspan="3">Операнд #2</th><th rowspan="2">Операция</th><th rowspan="2">Переход #1</th><th rowspan="2">Переход #2</th></tr>';
        echo '<tr><th>Операнд</th><th>Смещение</th><th>Адресация</th><th>Операнд</th><th>Смещение</th><th>Адресация</th></tr>';
        foreach ($this->nodes AS $node) {
            echo '<tr>';
            echo '<td>' . $index . '</td>';
            echo '<td>' . $node['instruction'] . '</td>';
            echo '<td>';
            echo isset($node['operand_1']) ? $node['operand_1'] : '';
            echo '</td>';
            echo '<td>';
            if (isset($node['offset_1'])) {
                echo is_array($node['offset_1']) ? implode(':', $node['offset_1']) : $node['offset_1'];
            } else {
                echo '';
            }
            echo '</td>';
            echo '<td>';
            echo isset($node['operand_type_1']) ? $str_addr[$node['operand_type_1']] : '';
            echo '</td>';
            echo '<td>';
            echo isset($node['operand_2']) ? $node['operand_2'] : '';
            echo '</td>';
            echo '<td>';
            if (isset($node['offset_2'])) {
                echo is_array($node['offset_2']) ? implode(':', $node['offset_2']) : $node['offset_2'];
            } else {
                echo '';
            }
            echo '</td>';
            echo '<td>';
            echo isset($node['operand_type_2']) ? $str_addr[$node['operand_type_2']] : '';
            echo '</td>';
            echo '<td>';
            echo isset($node['operator']) ? $node['operator'] : '';
            echo '</td>';
            echo '<td>';
            echo isset($node['jump_1']) ? $node['jump_1'] : '';
            echo '</td>';
            echo '<td>';
            echo isset($node['jump_2']) ? $node['jump_2'] : '';
            echo '</td>';
            echo '</tr>';
            $index++;
        }
        echo '</table>';
    }
}
