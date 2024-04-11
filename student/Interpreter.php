<?php

namespace IPP\Student;

use IPP\Core\AbstractInterpreter;
use IPP\Core\Exception\NotImplementedException;
use IPP\Core\ReturnCode;
use IPP\Student\Defvar;
use IPP\Student\Write;
use IPP\Student\Move;
use IPP\Student\Frames;

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
        
        $source = null;
        $input = null;
        $dom = $this->source->getDOMDocument();
        // $val = $this->input->readString();

        $instructions = $dom->getElementsByTagName('instruction');
        echo "Number of instructions: ", $instructions->length, "\n";
        foreach ($instructions as $instruction) {
            $i = 1;
            $argCounter = 0;
            $allArgs = [];
            while (true) {
                $args = $instruction->getElementsByTagName('arg' . $i);
                if ($args->length == 0) {
                    break;
                }
                foreach ($args as $arg) {
                    $allArgs[] = $arg;
                }
                $argCounter += $args->length;
                $i++;
            }
            echo "Instruction: ", $instruction->getAttribute('opcode'), "\n";
            echo "Number of args in instruction: ", $argCounter, "\n";

             // Sort the arguments by their node name
            usort($allArgs, function($first, $second) {
                return strcmp($first->nodeName, $second->nodeName);
            });

            $frames = Frames::getInstance();

            $opcode = null;
            switch ($instruction->getAttribute('opcode')) {
                case 'MOVE':
                    echo "MOVE\n";
                    $opcode = new Move($allArgs, $frames);
                    break;
                case 'DEFVAR':
                    echo "DEFVAR\n";
                    
                    $opcode = new Defvar($allArgs, $frames);
                    break;
                case 'WRITE':
                    echo "WRITE\n";
                    $opcode = new Write($allArgs, $frames);
                    break;
            }
        
            if ($opcode !== null) {
                $opcode->execute();
            }
        }
            return ReturnCode::OK;
    }
}