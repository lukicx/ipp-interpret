<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */
namespace IPP\Student;


class Eq extends Relational {

    protected function operation($firstOperand, $secondOperand): bool {
        return $firstOperand === $secondOperand;
    }
}
