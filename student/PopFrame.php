<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */

namespace IPP\Student;


class PopFrame {


    private MemoryManager $memoryManager;

    public function __construct(MemoryManager $memoryManager) {
        $this->memoryManager = $memoryManager;
    }

    public function execute(): void {
        $this->memoryManager->popFrame();
    }
}

?>