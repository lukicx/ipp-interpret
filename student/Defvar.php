<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */

namespace IPP\Student;


use IPP\Core\ReturnCode;

class Defvar extends Opcode {
    

    public function execute() : void {

        
        [$frame, $variable] = explode('@', $this->args[0]->nodeValue);

        // Checks if the variable exists in the frame, if it does program exits with semantic error 
        if ($this->memoryManager->doesVariableExistInFrame($frame, $variable)) {
            $this->stderrWriter->writeString("Variable already exists in the frame $frame\n");
            exit(ReturnCode::SEMANTIC_ERROR);
        }
        // store the variable in the frame, setting it's value and type to null
        $this->memoryManager->setVariableInFrame($frame, $variable, null, null);

    }
}
