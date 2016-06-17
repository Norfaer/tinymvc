<?php
namespace Tpl;

use Exception;

class TplLayout {
    use \Singleton;
    private $modules;
    
    private $nodes;
    private $gdata;
    private $ldata;
    private $stream;
    private $stack;
    private $namespace;    
    private $execindex;
    private $exitflag;
    private $mainmodule;
    
    protected function init() {
        $this->modules = [];
        $this->stack =[];
        $this->mainmodule = '';
    }
    
    private function &get_valref(&$node, $opnum = 1) {
        if (isset($this->ldata[$node['operand_' . $opnum]])) {
            $link = &$this->ldata[$node['operand_' . $opnum]];
        } else {
            $link = &$this->gdata[$node['operand_' . $opnum]];
        }
        if (!empty($node['offset_' . $opnum])) {
            foreach ($node['offset_' . $opnum] as $index) {
                if (!isset($link[$index])) {
                    return null;
                }
                $link = &$link[$index];
            }
        }
        return $link;
    }

    private function get_val(&$node, $opnum = 1) {
        if ($node['operand_type_' . $opnum] == ADDR_STRICT) {
            return $node['operand_' . $opnum];
        }
        if (isset($this->ldata[$node['operand_' . $opnum]])) {
            $link = &$this->ldata[$node['operand_' . $opnum]];
        } else {
            $link = &$this->gdata[$node['operand_' . $opnum]];
        }
        if (!empty($node['offset_' . $opnum])) {
            foreach ($node['offset_' . $opnum] as $index) {
                if (!isset($link[$index])) {
                    return 'Undefined';
                }
                $link = &$link[$index];
            }
        }
        return $link;
    }

    private function get_expr(&$node) {
        switch ($node['operator']) {
            case '$' : return $this->get_val($node);
            case '==' : return $this->get_val($node) == $this->get_val($node, 2);
            case '>' : return $this->get_val($node) > $this->get_val($node, 2);
            case '>=' : return $this->get_val($node) >= $this->get_val($node, 2);
            case '<' : return $this->get_val($node) < $this->get_val($node, 2);
            case '<=' : return $this->get_val($node) <= $this->get_val($node, 2);
            case '!=' : return $this->get_val($node) != $this->get_val($node, 2);
            case '+' : return $this->get_val($node) + $this->get_val($node, 2);
            case '-' : return $this->get_val($node) - $this->get_val($node, 2);
            case '*' : return $this->get_val($node) * $this->get_val($node, 2);
            case '/' : return $this->get_val($node) / $this->get_val($node, 2);
            case '%' : return $this->get_val($node) % $this->get_val($node, 2);
        }
    }

    private function exec_dump(&$node) {
        if ($node['operand_type_1']==ADDR_STREAM){
            echo rtrim(substr($this->stream, $node['operand_1'], $node['offset_1']));
        }
        else {
            echo $this->get_expr($node);
        }
        $this->execindex++;
    }

    private function exec_if(&$node) {
        $flag = boolval($this->get_expr($node));
        $jump = $node['jump_1'];
        $this->stack[]=[$flag,$jump];
        if ($flag) {
            $this->execindex ++;
        } else {
            $this->execindex = isset($node['jump_2']) ? $node['jump_1'] : $node['jump_2'];
        }
    }

    private function exec_else(&$node) {
        list($flag,$jump) = array_pop($this->stack);
        $this->execindex = $flag ? $jump : $this->execindex + 1;
        $this->stack[] = [$flag,$jump];
    }

    private function exec_endif(&$node) {
        $this->execindex++;
        array_pop($this->stack);
    }

    private function exec_for(&$node) {
        $arr = &$this->get_valref($node);
        $val = &$this->get_valref($node, 2);
        if (is_array($arr) &&  count($arr) > 0) {
            $val = reset($arr);
            $counter = count($arr);
            $this->stack[] = [true, $node['jump_1'], $this->execindex + 1, $counter];
            $this->execindex += 2;
        } else {
            $this->stack[] = [false, $node['jump_1'], $this->execindex + 1, 0];
            $this->execindex = isset($node['jump_2']) ? $node['jump_2'] : $node['jump_1'];
        }
    }
    
    private function exec_loop(&$node) {
        $arr = &$this->get_valref($node);
        $val = &$this->get_valref($node, 2);
        $val = next($arr);
        $this->execindex++;
    }

    private function exec_empty(&$node) {
        list($flag, $end, $loop, $counter) = array_pop($this->stack);
        $this->execindex = $flag ? $end : $this->execindex + 1;
        $this->stack[] = [$flag, $end, $loop, $counter];
    }

    private function exec_endfor(&$node) {
        list($flag, $end, $loop, $counter) = array_pop($this->stack);
        $counter--;
        if ($flag && $counter > 0) {
            $this->execindex = $loop;
            $this->stack[] = [$flag, $end, $loop, $counter];
        }
        else {
            $this->execindex++;
        }
    }
    
    private function exec_block(&$node) {
        $this->stack[]=[$this->namespace, $this->execindex+1];
        $this->load_module($node['operand_1']);
        $this->execindex = 0;
    }
    
    private function exec_exit(&$node) {
        if (empty($this->stack)) {
            $this->exitflag=true;
        }
        else {
            list($name,$addr) = array_pop($this->stack);
            $this->load_module($name);
            $this->execindex = $addr;
        }
    }

    private function exec(&$node) {
        $this->{'exec_'.$node['instruction']}($node);
    }
    
    private function load_module($name) {
        if (key_exists($name, $this->modules)){
            $this->gdata = &$this->modules[$name]->getGData();
            $this->ldata = &$this->modules[$name]->getLData();
            $this->nodes = &$this->modules[$name]->getNodes();
            $this->stream = &$this->modules[$name]->getStream();
            $this->namespace = $this->modules[$name]->getName();
        }
        else {
            throw new Exception('Module "'.$name.'" is not defined');
        }
    }

    public function render() {
        $this->execindex = 0;
        $this->exitflag = false;
        if (empty($this->mainmodule)) {
            throw new Exception('Main module not found',707);
        }
        $this->load_module($this->mainmodule);
        ob_start();
        while (!$this->exitflag) {
            $node = &$this->nodes[$this->execindex];
            $this->exec($node);
        }
	$output = ob_get_contents();
	ob_end_clean();
        return $output;
    }
    
    public function addModule(TplModule $module) {
        $name = $module->getName();
        if (empty($name)){ 
            throw new Exception('Invalid module name', 707);
        } 
        if (isset($this->modules[$name])){
            throw new Exception('Module "'.$name.'" already exists', 707);
        }
        if ($module->isMain()) {
            if (!empty($this->mainmodule)) {
                throw new Exception('Duplicate main module', 707);
            }
            $this->mainmodule = $module->getName();
        } 
        $this->modules[$name] = $module;
    }
}

