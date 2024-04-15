<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */

namespace IPP\Student;

use IPP\Core\ReturnCode;

class Setchar extends Opcode {

    public function execute(): void {
        // get value and type of the variable 
        $variableToChange = $this->args[0]->nodeValue;
        
        [$firstValue, $firstType] = $this->getValueAndType->execute($this->args[1], $this->memoryManager);
        [$secondValue, $secondType] = $this->getValueAndType->execute($this->args[2], $this->memoryManager);

        // setchar expects first operand to be int and second to be string
        if ($firstType !== 'int' || $secondType !== 'string') {
            $this->stderrWriter->writeString("Operands should be int and string\n");
            exit(ReturnCode::OPERAND_TYPE_ERROR);
        }

        [$stringToChange, $stringType] = $this->getValueAndType->execute($this->args[0], $this->memoryManager);

         if ($firstValue < 0 || $firstValue >= strlen($stringToChange) || strlen($secondValue) == 0) {
            $this->stderrWriter->writeString("Wrong index\n");
            exit(ReturnCode::STRING_OPERATION_ERROR);
        }
        // change the character at the index
        $stringToChange = substr_replace($stringToChange, $secondValue[0], $firstValue, 1);
        // get the variable from DOMelement value, split it at @ and store the value in frame that was in the DOMelement value
        [$frame, $variable] = explode('@', $variableToChange);
        $this->memoryManager->setVariableInFrame($frame, $variable,  $stringToChange, 'string');
        
    }
}