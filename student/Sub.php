<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */

namespace IPP\Student;



class Sub extends ArithmeticT {


    public function operation(int $firstOperand, int $secondOperand): int {
        return $firstOperand - $secondOperand;
    } 
}