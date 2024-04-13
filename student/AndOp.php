<?php
namespace IPP\Student;


class AndOp extends Boolean {

    protected function operation(bool ...$operands): bool {
        return $operands[0] && $operands[1];
    }
}

   