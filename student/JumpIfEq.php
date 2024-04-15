<?php

namespace IPP\Student;

use IPP\Core\ReturnCode;


class JumpIfEq extends Opcode {
    private int $order;

    /**
     * @param array<mixed>$args
     */
    public function __construct(array $args, MemoryManager $memoryManager, int $i) {
        parent::__construct($args, $memoryManager);
        $this->order = $i; 
    }

    public function execute(): void {
        $label = $this->args[0]->nodeValue;
    [$firstOperand, $firstType] = $this->getValueAndType->execute($this->args[1], $this->memoryManager);
    [$secondOperand, $secondType] = $this->getValueAndType->execute($this->args[2], $this->memoryManager);

    if ($firstType != $secondType && $firstOperand != "nil" && $secondOperand != "nil") {
        $this->stderrWriter->writeString("Operands are not of the same type\n");
        exit(ReturnCode::OPERAND_TYPE_ERROR);
    }

    if ($firstOperand == $secondOperand) {
        $this->order = $this->memoryManager->getLabelOrder($label);
    }
    

}
    public function getOrder(): int {
        return $this->order;
    }
}