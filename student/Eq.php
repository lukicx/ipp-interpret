<?php
namespace IPP\Student;


class Eq extends Relational {

    protected function operation($firstOperand, $secondOperand): bool {
        return $firstOperand === $secondOperand;
    }
}
