<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */

namespace IPP\Student;

use IPP\Core\ReturnCode;

class Dprint extends Opcode{

public function execute() : void {
    [$value, $type] = $this->getValueAndType->execute($this->args[0], $this->memoryManager);

    if ($type === 'int') {
        $this->stderrWriter->writeInt($value);
    } else if ($type === 'bool') {
        $this->stderrWriter->writeBool($value);
    } else if ($type === 'nil') {
        $this->stderrWriter->writeString('');
    } else if ($type === 'string') {
        $this->stderrWriter->writeString($value);
    }
    else {
        $this->stderrWriter->writeString("Type is not valid\n");
        exit(ReturnCode::OPERAND_TYPE_ERROR);
    }
}
}
?>