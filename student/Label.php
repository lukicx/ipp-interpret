<?php

namespace IPP\Student;

class Label extends Opcode{
    public int $order;

    public function __construct(mixed $args, MemoryManager $memoryManager, int $order) {
        parent::__construct($args, $memoryManager);
        $this->order = $order;
    }

    public function execute(): void {
        $label = $this->args->nodeValue;
        $this->memoryManager->setLabel($label, $this->order);
    }
}