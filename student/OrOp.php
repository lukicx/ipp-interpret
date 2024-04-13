<?php
namespace IPP\Student;


class OrOp extends Boolean {
    protected function operation(bool ...$operands): bool {
    {
        return $operands[0] || $operands[1];
    }
} 
}  