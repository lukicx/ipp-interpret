<?php

namespace IPP\Student;


use IPP\Student\Arithmetic;
use IPP\Core\ReturnCode;
use IPP\Student\Exceptions;


class Idiv extends Arithmetic {
    public function operation(int $firstOperand, int $secondOperand): int {
        if ($secondOperand === 0) {
            $this->stderrWriter->writeString("Can not divide by zero\n");
            exit(ReturnCode::OPERAND_VALUE_ERROR);
        }
        return $firstOperand / $secondOperand;
    } 
}