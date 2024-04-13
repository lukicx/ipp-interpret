<?php
namespace IPP\Student;

class Lt extends Relational {
    protected function operation($firstOperand, $secondOperand): bool {
        if (is_bool($firstOperand) && is_bool($secondOperand)) {
            return $firstOperand && !$secondOperand; // false is less than true
        }

        if (is_string($firstOperand) && is_string($secondOperand)) {
            return strcmp($firstOperand, $secondOperand) < 0; // compare lexicographically
        }

        return $firstOperand < $secondOperand;
    }
}