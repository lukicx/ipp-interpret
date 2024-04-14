<?php

namespace IPP\Student;

use IPP\Core\ReturnCode;

class Defvar extends Opcode {

    public function execute() : void {

        
        list($frame, $variable) = explode('@', $this->args[0]->nodeValue);

        // TODO: Implement other frames

        if ($this->memoryManager->doesVariableExistInFrame($frame, $variable)) {
            $this->stderrWriter->writeString("Variable already exists in the frame\n");
            exit(ReturnCode::SEMANTIC_ERROR);
        }
        $this->memoryManager->setVariableInFrame($frame, $variable, null, null);

    }
}
