<?php

namespace IPP\Student;

use IPP\Core\ReturnCode;

class Getchar extends Opcode {

    public function execute(): void {
        [$firstValue, $firstType] = $this->getValueAndType->execute($this->args[1], $this->memoryManager);
        [$secondValue, $secondType] = $this->getValueAndType->execute($this->args[2], $this->memoryManager);

        if ($firstType !== 'string' || $secondType !== 'int') {
            $this->stderrWriter->writeString("Operands should be string and int\n");
            exit(ReturnCode::OPERAND_TYPE_ERROR);
        }

        if ($secondValue < 0 || $secondValue >= strlen($firstValue)) {
            throw new \Exception("Index out of range", ReturnCode::STRING_OPERATION_ERROR);
        }
        $charToStore = $firstValue[$secondValue];
        [$frame, $variable] = explode('@', $this->args[0]->nodeValue);

        $this->memoryManager->setVariableInFrame($frame, $variable, $charToStore, 'string');
    }
}