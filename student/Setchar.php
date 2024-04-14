<?php

namespace IPP\Student;

use IPP\Core\ReturnCode;

class Setchar extends Opcode {

    public function execute(): void {
        $variableToChange = $this->args[0]->nodeValue;
        
        [$firstValue, $firstType] = $this->getValueAndType->execute($this->args[1], $this->memoryManager);
        [$secondValue, $secondType] = $this->getValueAndType->execute($this->args[2], $this->memoryManager);

        if ($firstType !== 'int' || $secondType !== 'string') {
            $this->stderrWriter->writeString("Operands should be int and string\n");
            exit(ReturnCode::OPERAND_TYPE_ERROR);;
        }

        [$stringToChange, $stringType] = $this->getValueAndType->execute($this->args[0], $this->memoryManager);

         if ($firstValue < 0 || $firstValue >= strlen($stringToChange) || strlen($secondValue) == 0) {
            throw new \Exception("Index out of range", ReturnCode::STRING_OPERATION_ERROR);
        }

        $stringToChange = substr_replace($stringToChange, $secondValue[0], $firstValue, 1);
        
        [$frame, $variable] = explode('@', $variableToChange);
        $this->memoryManager->setVariableInFrame($frame, $variable,  $stringToChange, 'string');
        
    }
}