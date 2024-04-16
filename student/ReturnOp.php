<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */

namespace IPP\Student;


class ReturnOp extends Opcode {


    private int $order;

    function __construct(mixed $args, MemoryManager $memoryManager, int $order)
    {
        parent::__construct($args, $memoryManager);
        $this->order = $order;
    }

    public function execute(): void {
        $position = $this->memoryManager->popCall();
        $this->order = $position;
    }

    public function getOrder(): int {
        return $this->order;
    }
}
