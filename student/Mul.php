<?php

namespace IPP\Student;

use IPP\Student\Arithmetic;


class Mul extends Arithmetic {
    public function operation(int $firstOperand, int $secondOperand): int {
        return $firstOperand * $secondOperand;
    } 
}
