<?php

namespace Tpl;
use Exception;
//Формальная грамматика шаблонизатора:
//  Все команды шаблонизатора заключаются в конструкцию '{% %}'
//  Все что не заключено в эту конструкцию выводится прямо в буфер вывода
//
//  string ::= ^'.*'$
//  numeric ::= ^[0-9]+$
//  literal ::= ^[a-zA-Z_][\w]*$
// 
//  index::= literal | numeric 
//  var ::= literal | var[index]
//  
//  atom ::= var | numeric | string
//  op ::= + | - | * | / | % | == | != | <= | >= | < | >
//  expression :: = atom | atom op atom
//  
//  instruction :: = {% for var as literal | if expression | block literal | expression | endif | endfor | empty | else %}
//
//Структура ДКА(детерменированный конечный автомат)
//  Основной принцип, которым я руководствовался при написании шаблонизатора 
//  состоит в том, что все вычисления должны происходить в модели и контроллере,
//  а на выход в шаблон данные поступают в структурированном и упорядоченном 
//  виде (ассоциативные массивы и простые скалярные величины). Поэтому, в авто-
//  мате шаблонизатора предусмотрены три типа команд перехода: условия (if), 
//  циклы для обхода массивов (for) и команда (block) для вызова другого шабло-
//  на. Все выражения должны быть одно- или двух-операндные. Например шаблониза-
//  тор "поймет" : a + b, но "не поймет" : a + b * c или a >= b + c. Отсюда 
//  структура всех инструкций-микрокоманд шаблонизатора (14 полей):
//      1. instruction      - инструкция (обязательное)
//      2. operand_1        - операнд №1 (необязательное)
//      3. offset_1         - смещение операдна №1 (необязательное)
//      4. operand_type_1   - адресация операнда №1 (необязательное)
//      5. operand_1        - операнд №2 (необязательное)
//      6. offset_1         - смещение операдна №2 (необязательное)
//      7. operand_type_1   - адресация операнда №2 (необязательное)
//      8. operator         - оператор, который выполняется над операндами 1 и 2
//                          (необязательное)
//      9. jump_1           - адрес условного перехода (необязательное)
//     10. jump_2           - адрес альтернативного перехода (необязательное)
//
//Способы адресации :
//  1. Прямая (ADDR_VAR) - в поле operand_# хранится имя переменной, которое со-
//  ответствует ключу массива $gdatapool, (глобальный пул данных шаблона) пере-
//  данному вместе с файлом шаблона. Если такого ключа в массиве $gdatapool нет,
//  то переменная создается в локальном пуле данных - $ldatapool и ей присваива-
//  ется значение null. Например, если в operand_1 хранится "А", то значение та-
//  кого операнда можно получить как: $gdatapool['A']. В случае отсутствия тако-
//  го ключа в $gdatapool, будет проинициализировано значение $ldatapool['A'].
//  
//  2. Прямая со смещением (ADDR_OFFSET) - аналогичная прямой, за тем исключени-
//  ем, что в поле offset_# хранятся индексы (в виде массива) по которым можно 
//  получить значение операнда, применив их к operand_#. Например в operand_1 
//  находится значение "А", а в offset_1 - массив ("key1",0,"key2"). Значение 
//  переменной можно получить как: $gdatapool['A']['key1'][0]['key2'].
//
//  3. Непосредственная (ADDR_STRICT) значение операнда нахоится непосредственно
//  в operand_#. Используется в основном для операций над константами.
//
//  4. Потоковая (ADDR_STREAM) - значение операнда - это подстрока из входного  
//  потока, начиная с operand_# символа и длиной offset_# символов. Используется
//  для вывода чистого html.

// Способы адресации операндов
define('ADDR_STREAM', 0b000);
define('ADDR_VAR', 0b001);
define('ADDR_OFFSET', 0b010);
define('ADDR_STRICT', 0b100);
define('ADDR_ANY', 0b111);

class TplCompiler {
    use \Singleton;
    
    const REG_EX_GRAMMAR = '/^(?:(else)|(endif)|(endfor)|(empty)|(block)\s+(\S+)|(for)\s+(\S+)\s+(as)\s+(\S+)|(if)\s+?(.+?))$/';
    const REG_EX_EXPR = "/^(?:(\\d+)|\\'(.*)\\'|([\\'\\[\\]\\w]+))(?:\\s*(\\*|\\+|\\-|\\/|\\%|!=|==|>=|<=|>|<)\\s*((\\d+)|\\'(.*)\\'|([\\'\\[\\]\\w]+)))?$/";
    const REG_EX_VAR = "/^(?:([a-zA-Z_]\\w*)((?:\\[(?:\\w+)\\])*))$/";

    private $stack;
    private $stacktop;
    private $stream;
    private $nodes;
    private $nodeindex;
    private $gdatapool;
    private $ldatapool;


    private function stack_push(&$value) {
        $this->stack[] = &$value;
        $this->stacktop = &$value;
        end($this->stack);
    }

    private function stack_pop() {
        if (!empty($this->stack)) {
            unset($this->stacktop);
            unset($this->stack[key($this->stack)]);
            if (end($this->stack)) {
                $this->stacktop = &$this->stack[key($this->stack)];
            } else {
                $this->stacktop = null;
            }
        }
    }

    public function compile(TplModule $module) {
        $this->nodes = &$module->getNodes();
        $this->gdatapool = &$module->getGData();
        $this->ldatapool = &$module->getLData();
        $this->stream = &$module->getStream();
        $this->stack = [];
        $this->stacktop = null;
        $this->nodeindex = 0;
        $offset = 0;
        $len = strlen($this->stream);
        while (($offset = $this->nexttoken($offset, $len)) !== false);
        if ($this->stack) {
            throw new Exception('Unclosed statement: "' . $this->stacktop['instruction'] . '"', 707);
        } else {
            $this->nodes[$this->nodeindex] = ['instruction' => 'exit'];
        }
    }   

    private function nexttoken($offset, $len) {
        $offset_start = strpos($this->stream, '{%', $offset);
        $offset_end = strpos($this->stream, '%}', $offset);
        if ($offset_start === false && $offset_end === false) {
            $this->parse_stream($offset, $len - 1);
            return false;
        }
        if ($offset_start === false) {
            throw new Exception('Misplaced "%}"', 707);
        }
        if ($offset_end === false) {
            throw new Exception('Unclosed "{%"', 707);
        }
        if ($offset_start != $offset) {
            if (!empty(trim(substr($this->stream, $offset, $offset_start - $offset)))) {
                $this->parse_stream($offset, $offset_start - 1);
            }
        }
        $this->parse(substr($this->stream, $offset_start + 2, $offset_end - $offset_start - 2));
        return $offset_end + 2;
    }

    private function parse($str) {
        $matches = [];
        if (empty(trim($str))) {
            return;
        }
        $this->nodes[$this->nodeindex] = [];
        if (preg_match(self::REG_EX_GRAMMAR, trim($str), $matches)) {
            array_shift($matches);
            while (empty($cmd = array_shift($matches)));
            $this->parse_instruction($this->nodes[$this->nodeindex], $cmd, $matches);
            $this->nodeindex++;
            return;
        } else {
            $this->nodes[$this->nodeindex]['instruction'] = 'dump';
            $this->parse_expression($this->nodes[$this->nodeindex], trim($str));
            $this->nodeindex++;
            return;
        }
        throw new Exception('Unknown instruction : "' . $str . '"', 707);
    }

    private function parse_instruction(&$node, $cmd, &$options) {
        $node['instruction'] = $cmd;
        $this->{'parse_node_'.$cmd}($node,$options);
    }
    
    private function parse_stream($start, $end) {
        $this->nodes[$this->nodeindex] = ['instruction' => 'dump', 'operand_1' => $start, 'offset_1' => $end - $start + 1, 'operand_type_1' => ADDR_STREAM];
        $this->nodes[$this->nodeindex]['stream'] = substr($this->stream, $this->nodes[$this->nodeindex]['operand_1'], $this->nodes[$this->nodeindex]['offset_1']);
        $this->nodeindex++;
    }

    private function parse_node_for(&$node, &$options) {
        $this->parse_operand($node, $options[0], 1, ADDR_OFFSET | ADDR_VAR);
        $this->parse_operand($node, $options[2], 2, ADDR_VAR);
        $this->stack_push($node);
        $this->nodeindex++;
        $this->nodes[$this->nodeindex] = ['instruction' => 'loop'];
        $this->parse_operand($this->nodes[$this->nodeindex], $options[0], 1, ADDR_OFFSET | ADDR_VAR);
        $this->parse_operand($this->nodes[$this->nodeindex], $options[2], 2, ADDR_VAR);
    }

    private function parse_node_empty(&$node, &$options) {
        if ($this->stacktop && $this->stacktop['instruction'] === 'for') {
            $this->stacktop['jump_2'] = $this->nodeindex;
        } else {
            throw new Exception('Expected "for" before "empty" ', 707);
        }
    }

    private function parse_node_endfor(&$node, &$options) {
        if ($this->stacktop && $this->stacktop['instruction'] === 'for') {
            $this->stacktop['jump_1'] = $this->nodeindex;
            $this->stack_pop();
        } else {
            throw new Exception('Expected "for" before "endfor"', 707);
        }
    }

    private function parse_node_if(&$node, &$options) {
        $this->parse_expression($node, trim($options[0]));
        $this->stack_push($node);
    }

    private function parse_node_else(&$node, &$options) {
        if ($this->stacktop && $this->stacktop['instruction'] === 'if') {
            $this->stacktop['jump_2'] = $this->nodeindex;
        } else {
            throw new Exception('Expected "if" before "else" ', 707);
        }
    }

    private function parse_node_endif(&$node, &$options) {
        if ($this->stacktop && $this->stacktop['instruction'] === 'if') {
            $this->stacktop['jump_1'] = $this->nodeindex;
            $this->stack_pop();
        } else {
            throw new Exception('Expected "if" before "endif" ', 707);
        }
    }

    private function parse_node_block(&$node, &$options) {
        $node['operand_1'] = $options[0];
    }

    private function parse_expression(&$node, $strexpr) {
        $matches = [];
        if (!preg_match(self::REG_EX_EXPR, $strexpr, $matches)) {
            throw new Exception('Error in expression: "' . $strexpr . '"', 707);
        }
        $op = empty($matches[1]) ? null : intval($matches[1]);
        $op = empty($matches[2]) ? $op : $matches[2];
        $node['operand_1'] = $op;
        $node['operand_type_1'] = ADDR_STRICT;
        $node['operator'] = '$';
        if (!empty($matches[3])) {
            $this->parse_operand($node, trim($matches[3]));
        }
        if (!empty($matches[5])) {
            $node['operator'] = trim($matches[4]);
            $op = empty($matches[6]) ? null : intval($matches[6]);
            $op = empty($matches[7]) ? $op : $matches[7];
            $node['operand_2'] = $op;
            $node['operand_type_2'] = ADDR_STRICT;
            if (!empty($matches[8])) {
                $this->parse_operand($node, trim($matches[8]), 2);
            }
        }
    }

    private function parse_operand(&$node, $strop, $opnum = 1, $optype = ADDR_ANY) {
        $matches = [];
        if (!preg_match(self::REG_EX_VAR, $strop, $matches)) {
            throw new Exception('Error in statement: "' . $strop . '"', 707);
        }
        $node['operand_type_' . $opnum] = ADDR_VAR;
        $node['operand_' . $opnum] = $matches[1];
        if (!isset($this->ldatapool[$matches[1]]) && !isset($this->gdatapool[$matches[1]])) {
            $this->ldatapool[$matches[1]] = null;
//              throw new Exception('Variable "' . $matches[2] . '" not defined in current scope', 707);
        }
        if (!empty($matches[2])) {
            $node['operand_type_' . $opnum] = ADDR_OFFSET;
            $node['offset_' . $opnum] = $this->parse_indexes($matches[2]);
        }
        if (!($node['operand_type_' . $opnum] & $optype)) {
            throw new Exception('Unexpected operand type: "' . $strop . '"', 707);
        }
    }

    private function parse_indexes($str) {
        $replace = ['][' => ';', '\'' => '', '[' => '', ']' => ''];
        $indexes = explode(';', strtr($str, $replace));
        foreach ($indexes as &$index) {
            if (is_numeric($index)) {
                $index = intval($index);
            }
        }
        return $indexes;
    }
}