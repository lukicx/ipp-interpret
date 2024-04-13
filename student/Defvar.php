<?php

namespace IPP\Student;

use IPP\Core\ReturnCode;

class Defvar extends Opcode {

    public function execute() : void {

        
        // Split the arg to frame and variable
        list($frame, $variable) = explode('@', $this->args[0]->nodeValue);

        // TODO: Implement other frames

        //Check 
        if ($this->memoryManager->doesFrameExist($frame, $variable)) {
            throw new \IPP\Student\Exceptions("Variable '$variable' exists in $frame", ReturnCode::SEMANTIC_ERROR);
        }
        $this->memoryManager->setFrame($frame, $variable, '', '');

        echo "Successfully defined variable $variable in frame $frame\n";
    }
}
?>