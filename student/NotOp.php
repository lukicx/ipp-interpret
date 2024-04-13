<?php
namespace IPP\Student;


class NotOp extends Boolean {

    protected function operation(bool ...$operands): bool {
        return !$operands[0];
    }
}

   