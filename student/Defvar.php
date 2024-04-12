<?php

namespace IPP\Student;

class Defvar extends Opcode {

    public function execute() : void {

        
        // Split the arg to frame and variable
        list($frame, $variable) = explode('@', $this->args[0]->nodeValue);

        // TODO: Implement other frames

        //Check 
        if ($this->memoryManager->doesFrameExist($frame, $variable)) {
            throw new \RuntimeException("Variable '$variable' already exists in the 'GF' frame", 52);
        }
        $this->memoryManager->setFrame($frame, $variable, null);

        echo "Successfully defined variable $variable in frame $frame\n";
    }
}
?>