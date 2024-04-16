<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */
namespace IPP\Student;


class Lt extends Relational {


    protected function operation($firstOperand, $secondOperand): bool {
        if ((is_string($firstOperand)) && (is_string($secondOperand))) {
            return strcmp($firstOperand, $secondOperand) < 0; // compare lexicographically
        }
        return $firstOperand < $secondOperand;
    }
}