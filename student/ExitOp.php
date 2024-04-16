<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */

namespace IPP\Student;

use IPP\Core\ReturnCode;

class ExitOp extends Opcode {


    public function execute() : void{
        [$value, $type] = $this->getValueAndType->execute($this->args[0], $this->memoryManager);
        if ($type !== 'int') {
            $this->stderrWriter->writeString("Value type is not int");
            exit(ReturnCode::OPERAND_TYPE_ERROR);
        }
        $value = (int)$value;
        if ($value < 0 || $value > 9) {
            $this->stderrWriter->writeString("Exit code is out of range");
            exit(ReturnCode::OPERAND_VALUE_ERROR);
        }
        exit($value);
    }
}
?>