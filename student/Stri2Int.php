<?php

namespace IPP\Student;

use IPP\Core\ReturnCode;

class Stri2Int extends Opcode {
    public function execute() : void {
        [$value, $type] = $this->getValueAndType->execute($this->args[1], $this->memoryManager);

        if ($type !== 'string') {
            $this->stderrWriter->writeString("First type has to be string for Stri2Int operation\n");
            exit(ReturnCode::OPERAND_TYPE_ERROR);
        }

        [$index, $secondType] = $this->getValueAndType->execute($this->args[2], $this->memoryManager);

        if ($secondType !== 'int') {
            $this->stderrWriter->writeString("Second type has to be int for Stri2Int operation\n");
            exit(ReturnCode::OPERAND_TYPE_ERROR);
        }

        if (!isset($value[$index])) {   
            $this->stderrWriter->writeString("Index out of range\n");
            exit(ReturnCode::STRING_OPERATION_ERROR);
        }
        

        $ordValue = mb_ord($value[$index], 'UTF-8');

        [$frame, $variable] = explode('@', $this->args[0]->nodeValue);
        $this->memoryManager->setVariableInFrame($frame, $variable, (string)$ordValue, 'int');
    }
}