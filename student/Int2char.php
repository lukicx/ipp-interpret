<?php
namespace IPP\Student;

use IPP\Core\ReturnCode;


class Int2Char extends Opcode {
    public function execute (): void{
        
    [$value, $type] = $this->getValueAndType->execute($this->args[1], $this->memoryManager);
    
    if ($type !== 'int') {
        throw new \Exception("Invalid type", ReturnCode::OPERAND_TYPE_ERROR);
    }

    /**
    * @var string|bool $convertedIntegerToChar
    */
    $convertedIntegerToChar = mb_chr((int)$value, 'UTF-8');

    if ($convertedIntegerToChar === false && is_bool($convertedIntegerToChar)) {
        throw new \Exception("Invalid Unicode value", ReturnCode::STRING_OPERATION_ERROR);
    }

    [$frame, $variable] = explode('@', $this->args[0]->nodeValue);
    $this->memoryManager->setFrame($frame, $variable, $convertedIntegerToChar, 'string');
    }
}