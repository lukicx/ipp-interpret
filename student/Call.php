<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */


namespace IPP\Student;


class Call extends Opcode {
    
    private int $order;

    function __construct(mixed $args, MemoryManager $memoryManager, int $order)
    {
        parent::__construct($args, $memoryManager);
        $this->order = $order;
    }

    public function execute(): void {
        $label = $this->args[0]->nodeValue;
        $this->memoryManager->pushCall($this->order);
        $this->order = $this->memoryManager->getLabelOrder($label);
    }

    public function getOrder(): int {
        return $this->order;
    }
}

