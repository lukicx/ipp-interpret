<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */

namespace IPP\Student;

use IPP\Core\ReturnCode;

class Strlen extends Opcode {

    public function execute(): void {
        [$value, $type] = $this->getValueAndType->execute($this->args[1], $this->memoryManager);
        // strlen expects string
        if ($type !== 'string') {
            $this->stderrWriter->writeString("Type should be string for strlen operation\n");
            exit(ReturnCode::OPERAND_TYPE_ERROR);
        }
        // get the length of the string
        $length = strlen($value);
        [$frame, $variable] = explode('@', $this->args[0]->nodeValue);

        // store the length in the variable
        $this->memoryManager->setVariableInFrame($frame, $variable, (string)$length, 'int');
    }
}