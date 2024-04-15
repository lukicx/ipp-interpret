<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */

namespace IPP\Student;

class Type extends Opcode {

public function execute(): void {
    $setVariable = $this->args[0]->nodeValue;
    [$value, $type] = $this->getValueAndType->execute($this->args[1], $this->memoryManager);


    if ($type === null) {
        $type = '';
    }
    // store the value in the the appropriate frame to variable 
    [$frame, $variable] = explode('@', $setVariable);
    $this->memoryManager->setVariableInFrame($frame, $variable, $type, 'string');
}
}