<?php

namespace IPP\Student;


use IPP\Student\Arithmetic;
use IPP\Core\ReturnCode;
use IPP\Student\Exceptions;


class Idiv extends Arithmetic {
    public function operation(int $firstOperand, int $secondOperand): int {
        if ($secondOperand === 0) {
            throw new Exceptions("Can't divide by zero", ReturnCode::OPERAND_VALUE_ERROR);
        }
        return $firstOperand / $secondOperand;
    } 
}