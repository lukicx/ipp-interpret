<?php

namespace IPP\Student;

use IPP\Core\ReturnCode;

class Stri2Int extends Opcode {
    public function execute() : void {
        [$value, $type] = $this->getValueAndType->execute($this->args[1], $this->memoryManager);

        if ($type !== 'string') {
            $this->stderrWriter->writeString("Type has to be string for Stri2Int operation\n");
            exit(ReturnCode::OPERAND_TYPE_ERROR);
        }

        $index = $this->getValueAndType->execute($this->args[2], $this->memoryManager)[0];

        if (!isset($value[$index])) {
            $this->stderrWriter->writeString("Index out of range\n");
            exit(ReturnCode::SEMANTIC_ERROR);
        }

        $ordValue = mb_ord($value[$index], 'UTF-8');

        [$frame, $variable] = explode('@', $this->args[0]->nodeValue);
        $this->memoryManager->setVariableInFrame($frame, $variable, (string)$ordValue, 'int');
    }
}