<?php

namespace IPP\Student;

use IPP\Core\AbstractInterpreter;
use IPP\Core\FileInputReader;
use IPP\Core\ReturnCode;

class Interpreter extends AbstractInterpreter
{
    public function execute(): int
    {
        // TODO: Start your code here
        // Check \IPP\Core\AbstractInterpreter for predefined I/O objects:
        // $dom = $this->source->getDOMDocument();
        // $val = $this->input->readString();
        // $this->stdout->writeString("stdout");
        // $this->stderr->writeString("stderr");
        
        $dom = $this->source->getDOMDocument();
        // $val = $this->input->readString();

        if ($dom->documentElement->getAttribute('language') !== 'IPPcode24') {
            $this->stderr->writeString("Wrong document language");
            exit(ReturnCode::INVALID_SOURCE_STRUCTURE);
        }

        $unorderedInstructions = $dom->getElementsByTagName('instruction');
        $instructions = []; 
        
        foreach ($unorderedInstructions as $instruction) {
            $order = (int) $instruction->getAttribute('order');
            if ($order < 0){
                $this->stderr->writeString("Negative order");
                exit(ReturnCode::INVALID_SOURCE_STRUCTURE);
            }
            $instructions[$order] = $instruction;
        }
        ksort($instructions);

        $memoryManager = MemoryManager::getInstance();


        $numberOfInstructions = end($instructions)->getAttribute('order');
        
        foreach ($instructions as $order => $instruction) {
            $opcode = $instruction->getAttribute('opcode');
            if ($opcode === 'LABEL') {
                $arg = $instruction->getElementsByTagName('arg1');
                $label = new Label($arg[0], $memoryManager, $order);
                $label->execute();
            }
        }

        
       for ($i = 1; $i <= $numberOfInstructions; $i++){
        if (!isset($instructions[$i])) {
            continue;
        }
        $instruction = $instructions[$i];

            $j = 1;
            $allArgs = [];
            while (true) {
                $args = $instruction->getElementsByTagName('arg' . $j);
                if ($args->length == 0) {
                    break;
                }
                foreach ($args as $arg) {
                    $allArgs[] = $arg;
                }
                $j++;
            }

             // Sort the arguments by their node name
            usort($allArgs, function($first, $second) {
                return strcmp($first->nodeName, $second->nodeName);
            });


            $order = (int)$instruction->getAttribute('order');
            $memoryManager->setPositionInCode($order);
            $memoryManager->setOrderOfInstruction($order);

            $opcode = null;

            switch ($instruction->getAttribute('opcode')) {
                case 'MOVE':
                    $opcode = new Move($allArgs, $memoryManager);
                    break;
                case 'DEFVAR':
                    $opcode = new Defvar($allArgs, $memoryManager);
                    break;
                case 'WRITE':
                    $opcode = new Write($allArgs, $memoryManager);
                    break;
                case 'ADD':
                    $opcode = new Add($allArgs, $memoryManager);
                    break;
                case 'SUB':
                    $opcode = new Sub($allArgs, $memoryManager);
                    break;
                case 'MUL':
                    $opcode = new Mul($allArgs, $memoryManager);
                    break;
                case 'IDIV':
                    $opcode = new Idiv($allArgs, $memoryManager);
                    break;
                case 'AND':
                    $opcode = new AndOp($allArgs, $memoryManager);
                    break;
                case 'OR':
                    $opcode = new OrOp($allArgs, $memoryManager);
                    break;
                case 'NOT':
                    $opcode = new NotOp($allArgs, $memoryManager);
                    break;
                case 'LT':
                    $opcode = new Lt($allArgs, $memoryManager);
                    break;
                case 'GT':
                    $opcode = new Gt($allArgs, $memoryManager);
                    break;
                case 'EQ':
                    $opcode = new Eq($allArgs, $memoryManager);
                    break;
                case 'INT2CHAR':
                    $opcode = new Int2Char($allArgs, $memoryManager);
                    break;
                case 'STRI2INT':
                    $opcode = new Stri2Int($allArgs, $memoryManager);
                    break;
                case 'READ':
                    $opcode = new Read($allArgs,  $memoryManager, $this->input);
                    break;
                case 'CONCAT':
                    $opcode = new Concat($allArgs, $memoryManager);
                    break;
                case 'STRLEN':
                    $opcode = new Strlen($allArgs, $memoryManager);
                    break;
                case 'GETCHAR':
                    $opcode = new Getchar($allArgs, $memoryManager);
                    break;
                case 'SETCHAR':
                    $opcode = new Setchar($allArgs, $memoryManager);
                    break;
                case 'TYPE':
                    $opcode = new Type($allArgs, $memoryManager);
                    break;
                case 'LABEL':
                    break;
                case 'JUMP':
                    $opcode = new Jump($allArgs, $memoryManager);
                    $opcode->execute();
                    $i = $opcode->getOrder();
                    continue 2;
                    // break;
                case 'EXIT':
                    $opcode = new ExitOp($allArgs, $memoryManager);
                    break;
                case 'DPRINT':
                    $opcode = new Dprint($allArgs, $memoryManager);
                    break;
                case 'BREAK':
                    $opcode = new BreakOp($allArgs, $memoryManager);
                    break;
                case 'CREATEFRAME':
                    $memoryManager->createFrame();
                    break;
                case 'PUSHFRAME':
                    $opcode = new PushFrame($memoryManager);
                    break;
                case 'POPFRAME':
                    $opcode = new PopFrame($memoryManager);
                    break;
                case 'POPS':
                    $memoryManager->pops($allArgs);
                    break;
                case 'PUSHS':
                    $memoryManager->pushs($allArgs);
                    break;
                case 'JUMPIFEQ':
                    $opcode = new JumpIfEq($allArgs, $memoryManager, $i);
                    $opcode->execute();
                    $i = $opcode->getOrder();
                    // continue 2;
                    continue 2;
                case 'JUMPIFNEQ':
                    $opcode = new JumpIfNeq($allArgs, $memoryManager, $i);
                    $opcode->execute();
                    $i = $opcode->getOrder();
                    continue 2;
                case 'CALL':
                    $opcode = new Call($allArgs, $memoryManager, $i);
                    $opcode->execute();
                    $i = $opcode->getOrder();
                    continue 2;
                case 'RETURN':
                    $opcode = new ReturnOp($allArgs, $memoryManager, $i);
                    $opcode->execute();
                    $i = $opcode->getOrder();
                    continue 2;
                default:
                    $this->stderr->writeString("Wrong XML structure");
                    exit(ReturnCode::INVALID_SOURCE_STRUCTURE);
            }
            if ($opcode === null){
                continue;
            }
            $opcode->execute();
       
        }
       return ReturnCode::OK;
    }
}