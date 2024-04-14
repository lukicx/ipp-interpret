<?php

namespace IPP\Student;

use IPP\Core\ReturnCode;

class Strlen extends Opcode {

    public function execute(): void {
        [$value, $type] = $this->getValueAndType->execute($this->args[1], $this->memoryManager);

        if ($type !== 'string') {
            $this->stderrWriter->writeString("Type should be string for strlen operation\n");
            exit(ReturnCode::OPERAND_TYPE_ERROR);
        }

        $length = strlen($value);
        [$frame, $variable] = explode('@', $this->args[0]->nodeValue);


        $this->memoryManager->setVariableInFrame($frame, $variable, (string)$length, 'int');
    }
}