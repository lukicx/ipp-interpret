<?php

namespace IPP\Student;

use IPP\Core\ReturnCode;

class Stri2Int extends Opcode {
    public function execute() : void {
        [$value, $type] = $this->getValueAndType->execute($this->args[1], $this->memoryManager);

        if ($type !== 'string') {
            throw new \Exception("Invalid type for Stri2Int operation", ReturnCode::OPERAND_TYPE_ERROR);
        }

        $index = $this->getValueAndType->execute($this->args[2], $this->memoryManager)[0];

        if (!isset($value[$index])) {
            throw new \Exception("Index out of range for Stri2Int operation", ReturnCode::STRING_OPERATION_ERROR);
        }

        $ordValue = mb_ord($value[$index], 'UTF-8');

        [$frame, $variable] = explode('@', $this->args[0]->nodeValue);
        $this->memoryManager->setFrame($frame, $variable, (string)$ordValue, 'int');
    }
}