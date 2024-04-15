<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */

namespace IPP\Student;

use IPP\Core\ReturnCode;

class Stri2Int extends Opcode {
    public function execute() : void {
        [$value, $type] = $this->getValueAndType->execute($this->args[1], $this->memoryManager);

        // Stri2Int expects first operand to be string
        if ($type !== 'string') {
            $this->stderrWriter->writeString("First type has to be string for Stri2Int operation\n");
            exit(ReturnCode::OPERAND_TYPE_ERROR);
        }

        [$index, $secondType] = $this->getValueAndType->execute($this->args[2], $this->memoryManager);
        
        // Stri2Int expects second operand to be int
        if ($secondType !== 'int') {
            $this->stderrWriter->writeString("Second type has to be int for Stri2Int operation\n");
            exit(ReturnCode::OPERAND_TYPE_ERROR);
        }

        // check if index is in range
        if (!isset($value[$index])) {   
            $this->stderrWriter->writeString("Index out of range\n");
            exit(ReturnCode::STRING_OPERATION_ERROR);
        }
        
        // get the ASCII value of the character at the index
        $ordValue = mb_ord($value[$index], 'UTF-8');
        // get the variable from DOMelement value, split it at @ and store the value in frame that was in the DOMelement value
        [$frame, $variable] = explode('@', $this->args[0]->nodeValue);
        $this->memoryManager->setVariableInFrame($frame, $variable, (string)$ordValue, 'int');
    }
}