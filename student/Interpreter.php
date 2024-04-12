<?php

namespace IPP\Student;

use IPP\Core\AbstractInterpreter;
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


        foreach ($instructions as $instruction) {
            $i = 1;
            $allArgs = [];
            while (true) {
                $args = $instruction->getElementsByTagName('arg' . $i);
                if ($args->length == 0) {
                    break;
                }
                foreach ($args as $arg) {
                    $allArgs[] = $arg;
                }
                $i++;
            }
            echo "Instruction: ", $instruction->getAttribute('opcode'), "\n";

             // Sort the arguments by their node name
            usort($allArgs, function($first, $second) {
                return strcmp($first->nodeName, $second->nodeName);
            });

            $memoryManager = MemoryManager::getInstance();

            $opcode = null;
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
            }
        
            if ($opcode !== null) {
                $opcode->execute();
            }
        }
            return ReturnCode::OK;
    }
}