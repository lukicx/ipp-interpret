<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */

namespace IPP\Student;


class Add extends ArithmeticT {
    
    public function operation(int $firstOperand, int $secondOperand): int {
        return $firstOperand + $secondOperand;
    } 
}