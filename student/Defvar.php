<?php

namespace IPP\Student;

class Defvar extends Opcode {

    public function execute() {

        
        // Split the arg to frame and variable
        list($frame, $variable) = explode('@', $this->args[0]->nodeValue);

        // TODO: Implement other frames

        //Check 
        if ($this->frames->doesExist($frame, $variable)) {
            throw new \RuntimeException("Variable '$variable' already exists in the 'GF' frame", 52);
        }
        $this->frames->set($frame, $variable, null);

        echo "Successfully defined variable $variable in frame $frame\n";
    }
}
?>