<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */

namespace IPP\Student;


use IPP\Student\Arithmetic;
use IPP\Core\ReturnCode;


class Idiv extends Arithmetic {
    public function operation(int $firstOperand, int $secondOperand): int {
        if ($secondOperand === 0) {
            $this->stderrWriter->writeString("Can not divide by zero\n");
            exit(ReturnCode::OPERAND_VALUE_ERROR);
        }
        return $firstOperand / $secondOperand;
    } 
}