<?php

namespace IPP\Student;

class PushFrame {
    private MemoryManager $memoryManager;

    public function __construct(MemoryManager $memoryManager) {
        $this->memoryManager = $memoryManager;
    }

    public function execute(): void {
        $this->memoryManager->pushFrame();
    }
}

?>