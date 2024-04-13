<?php

namespace IPP\Student;

abstract class  Opcode {
    protected mixed $args;
    protected MemoryManager $memoryManager;
    protected GetValueAndType $getValueAndType;

    public function __construct(mixed $args, MemoryManager $memoryManager) {
        $this->args = $args;
        $this->memoryManager = $memoryManager;
        $this->getValueAndType = new GetValueAndType();
    }

    public function execute() : void {
    }
}