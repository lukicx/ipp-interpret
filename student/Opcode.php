<?php

namespace IPP\Student;

abstract class  Opcode {
    protected mixed $args;
    protected MemoryManager $memoryManager;

    public function __construct(mixed $args, MemoryManager $memoryManager) {
        $this->args = $args;
        $this->memoryManager = $memoryManager;
    }

    public function execute() : void {
    }
}