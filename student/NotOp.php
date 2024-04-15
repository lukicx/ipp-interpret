<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */
namespace IPP\Student;


class NotOp extends Boolean {

    protected function operation(bool ...$operands): bool {
        return !$operands[0];
    }
}

   