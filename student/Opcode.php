<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */

namespace IPP\Student;

use IPP\Core\StreamWriter;


abstract class  Opcode {
    protected mixed $args;
    protected MemoryManager $memoryManager;
    protected GetValueAndType $getValueAndType;
    protected StreamWriter $stderrWriter;


    public function __construct(mixed $args, MemoryManager $memoryManager) {
        $this->args = $args;
        $this->memoryManager = $memoryManager;
        $this->getValueAndType = new GetValueAndType();
        $this->stderrWriter = new StreamWriter(STDERR);

    }
    public function execute() : void {
    }
}