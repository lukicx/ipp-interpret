<?php

namespace IPP\Student;

class Jump extends Opcode {
    private int $order;

    public function execute(): void {
        $label = $this->args[0]->nodeValue;
        $this->order = $this->memoryManager->getLabelOrder($label);
    }
    public function getOrder(): int {
        return $this->order;
        
    }


}