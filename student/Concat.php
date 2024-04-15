<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */


namespace IPP\Student;

use IPP\Core\ReturnCode;

class Concat extends Opcode {

    public function execute(): void {
        $variableToStore = $this->args[0]->nodeValue;
        [$frame, $variable] = explode('@', $variableToStore);

        
        [$firstValue, $firstType] = $this->getValueAndType->execute($this->args[1], $this->memoryManager);
        [$secondValue, $secondType] = $this->getValueAndType->execute($this->args[2], $this->memoryManager);

        // concat expects both operands to be string
        if ($firstType !== 'string' || $secondType !== 'string') {
            $this->stderrWriter->writeString("Arguments should be type of string\n");
            exit(ReturnCode::OPERAND_TYPE_ERROR);
        }

        $value = $firstValue . $secondValue;

        $this->memoryManager->setVariableInFrame($frame,  $variable, $value, 'string');
    }
}