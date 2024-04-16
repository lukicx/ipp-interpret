<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */

namespace IPP\Student;


use IPP\Core\ReturnCode;

class Move extends Opcode{



    public function execute() : void {
  

        $destination = $this->args[0]->nodeValue;
        [$destinationFrame, $destinationVariable] = explode('@', $destination);
        $source = $this->args[1]->nodeValue;
        // source is destinationVariable get value from the destinationFrame
        if (strpos($source, '@') !== false) {
            [$sourceFrame, $sourceVariable] = explode('@', $source);
            $value = $this->memoryManager->getVariableInFrame($sourceFrame, $sourceVariable);
        //  value is constant
        } else {
            $value = $source;
        }
        // check if the variable exists in the frame 
        if (!$this->memoryManager->doesVariableExistInFrame($destinationFrame, $destinationVariable)) {
            $this->stderrWriter->writeString("Variable does not exist in the frame\n");
            exit(ReturnCode::VARIABLE_ACCESS_ERROR);
        }

        $this->memoryManager->setVariableInFrame($destinationFrame, $destinationVariable, $value, $this->args[1]->getAttribute('type'));


    }
}