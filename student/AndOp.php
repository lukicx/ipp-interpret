<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */


namespace IPP\Student;


class AndOp extends Boolean {

    public function operation(bool ...$operands): bool {
        return $operands[0] && $operands[1];
    }
}

   