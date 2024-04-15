<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */
namespace IPP\Student;

use IPP\Core\ReturnCode;


class Int2Char extends Opcode {
    public function execute (): void{
        
    [$value, $type] = $this->getValueAndType->execute($this->args[1], $this->memoryManager);
    
    if ($type !== 'int') {
        $this->stderrWriter->writeString("Operand should be int\n");
        exit(ReturnCode::OPERAND_TYPE_ERROR);
    }

    /**
    * @var string|bool $convertedIntegerToChar
    */
    $convertedIntegerToChar = mb_chr((int)$value, 'UTF-8');

    if ($convertedIntegerToChar === false && is_bool($convertedIntegerToChar)) {
        $this->stderrWriter->writeString("Operands should be string and int\n");
        exit(ReturnCode::STRING_OPERATION_ERROR);
    }
    
    [$frame, $variable] = explode('@', $this->args[0]->nodeValue);
    $this->memoryManager->setVariableInFrame($frame, $variable, $convertedIntegerToChar, 'string');
    }
}