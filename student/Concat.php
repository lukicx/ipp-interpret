<?php

namespace IPP\Student;

use IPP\Core\ReturnCode;

class Concat extends Opcode {

    public function execute(): void {
        $variableToStore = $this->args[0]->nodeValue;
        [$frame, $variable] = explode('@', $variableToStore);

        
        [$firstValue, $firstType] = $this->getValueAndType->execute($this->args[1], $this->memoryManager);
        [$secondValue, $secondType] = $this->getValueAndType->execute($this->args[2], $this->memoryManager);

        if ($firstType !== 'string' || $secondType !== 'string') {
            throw new \Exception("Both operands must be strings", ReturnCode::OPERAND_TYPE_ERROR);
        }

        $value = $firstValue . $secondValue;
        echo "Value concatenated: " . $value . "\n";

        $this->memoryManager->setFrame($frame,  $variable, $value, 'string');
    }
}