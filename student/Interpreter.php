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

        $unorderedInstructions = $dom->getElementsByTagName('instruction');
        $instructions = []; 
        
        foreach ($unorderedInstructions as $instruction) {
            $order = (int) $instruction->getAttribute('order');
            $instructions[$order] = $instruction;
        }
        ksort($instructions);


        foreach ($instructions as $instruction){
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
            echo "Instruction: ", $instruction->getAttribute('opcode'), "\n";

             // Sort the arguments by their node name
            usort($allArgs, function($first, $second) {
                return strcmp($first->nodeName, $second->nodeName);
            });

            $memoryManager = MemoryManager::getInstance();

            $opcode = null;
            $order = intval($instruction->getAttribute('order'));

            switch ($instruction->getAttribute('opcode')) {
                case 'MOVE':
                    echo "MOVE\n";
                    $opcode = new Move($allArgs, $memoryManager);
                    break;
                case 'DEFVAR':
                    echo "DEFVAR\n";
                    $opcode = new Defvar($allArgs, $memoryManager);
                    break;
                case 'WRITE':
                    echo "WRITE\n";
                    $opcode = new Write($allArgs, $memoryManager);
                    break;
                case 'ADD':
                    echo "ADD\n";
                    $opcode = new Add($allArgs, $memoryManager);
                    break;
                case 'SUB':
                    echo "SUB\n";
                    $opcode = new Sub($allArgs, $memoryManager);
                    break;
                case 'MUL':
                    echo "MUL\n";
                    $opcode = new Mul($allArgs, $memoryManager);
                    break;
                case 'IDIV':
                    echo "IDIV\n";
                    $opcode = new Idiv($allArgs, $memoryManager);
                    break;
                case 'AND':
                    echo "opAND\n";
                    $opcode = new AndOp($allArgs, $memoryManager);
                    break;
                case 'OR':
                    echo "Executing OR instruction\n";
                    $opcode = new OrOp($allArgs, $memoryManager);
                    break;
                case 'NOT':
                    echo "NotOp\n";
                    $opcode = new NotOp($allArgs, $memoryManager);
                    break;
                case 'LT':
                    echo "LT\n";
                    $opcode = new Lt($allArgs, $memoryManager);
                    break;
                case 'GT':
                    echo "GT\n";
                    $opcode = new Gt($allArgs, $memoryManager);
                    break;
                case 'EQ':
                    echo "EQ\n";
                    $opcode = new Eq($allArgs, $memoryManager);
                    break;
                case 'INT2CHAR':
                    echo "INT2CHAR\n";
                    $opcode = new Int2Char($allArgs, $memoryManager);
                    break;
                case 'STRI2INT':
                    echo "STRI2INT\n";
                    $opcode = new Stri2Int($allArgs, $memoryManager);
                    break;
                case 'READ':
                    echo "READ\n";
                    $opcode = new Read($allArgs,  $memoryManager, $this->input);
                    break;
                case 'CONCAT':
                    echo "CONCAT\n";
                    $opcode = new Concat($allArgs, $memoryManager);
                    break;
                case 'STRLEN':
                    echo "STRLEN\n";
                    $opcode = new Strlen($allArgs, $memoryManager);
                    break;
                case 'GETCHAR':
                    echo "GETCHAR\n";
                    $opcode = new Getchar($allArgs, $memoryManager);
                    break;
                case 'SETCHAR':
                    echo "SETCHAR\n";
                    $opcode = new Setchar($allArgs, $memoryManager);
                    break;
                case 'TYPE':
                    echo "TYPE\n";
                    $opcode = new Type($allArgs, $memoryManager);
                    break;
                case 'LABEL':
                    echo "LABEL\n";
                    $opcode = new Label($allArgs, $memoryManager, $order);

                    break;
                case 'JUMP':
                    echo "JUMP\n";
                    $opcode = new Jump($allArgs, $memoryManager);
                    $opcode->execute();
                    $i = $opcode->getOrder() - 1;
                    continue 2;
                    
            }
        
            if ($opcode !== null && $opcode !== Jump::class) {
                $opcode->execute();
            }
        }
       return ReturnCode::OK;
    }
}