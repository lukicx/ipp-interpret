<?php
namespace IPP\Student;

use IPP\Core\ReturnCode;


class Int2Char extends Opcode {
    public function execute (): void{
        
    [$value, $type] = $this->getValueAndType->execute($this->args[1], $this->memoryManager);
    
    if ($type !== 'int') {
        throw new \Exception("Invalid type", ReturnCode::OPERAND_TYPE_ERROR);
    }

    $convertedIntegerToChar = mb_chr($value, 'UTF-8');

    // This is message from phpstan, that is why I am ignoring the next line, it is evaluating it wrong.
    //  Strict comparison using === between string and false will always evaluate to false.                             
    //  Because the type is coming from a PHPDoc, you can turn off this check by setting treatPhpDocTypesAsCertain:  
    //  false in your phpstan.neon. 
    /** @phpstan-ignore-next-line */
    if ($convertedIntegerToChar === false) {
        throw new \Exception("Invalid Unicode value", ReturnCode::STRING_OPERATION_ERROR);
    }

    [$frame, $variable] = explode('@', $this->args[0]->nodeValue);
    $this->memoryManager->setFrame($frame, $variable, $convertedIntegerToChar, 'string');
    }
}