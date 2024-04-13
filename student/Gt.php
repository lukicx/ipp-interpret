<?php
namespace IPP\Student;

class Gt extends Relational {
    protected function operation($firstOperand, $secondOperand): bool {
        if (is_bool($firstOperand)) {
            return !$firstOperand && $secondOperand; // false is less than true
        }

        if (is_string($firstOperand) && is_string($secondOperand)) {
            return strcmp($firstOperand, $secondOperand) > 0; // compare lexicographically
        }
        return $firstOperand > $secondOperand;
    }
}