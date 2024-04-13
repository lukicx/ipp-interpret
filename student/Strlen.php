<?php

namespace IPP\Student;

use IPP\Core\ReturnCode;

class Strlen extends Opcode {

    public function execute(): void {
        [$value, $type] = $this->getValueAndType->execute($this->args[1], $this->memoryManager);

        // Check if the operand is a string
        if ($type !== 'string') {
            throw new \Exception("Operand must be a string", ReturnCode::OPERAND_TYPE_ERROR);
        }

        // Get the length of the string
        $length = strlen($value);
        [$frame, $variable] = explode('@', $this->args[0]->nodeValue);


        // Store the result in the variable
        $this->memoryManager->setFrame($frame, $variable, (string)$length, 'int');
    }
}