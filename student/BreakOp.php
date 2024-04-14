<?php

namespace IPP\Student;

class BreakOp extends Opcode {
    public function execute() : void {
        $positionInCode = $this->memoryManager->getPositionInCode();
        $frames = $this->memoryManager->getFramesStatus();
        $numberOfInstructions = $this->memoryManager->getNumberOfInstructions();

        $this->stderrWriter->writeString("Position in code: $positionInCode\n");
        $this->stderrWriter->writeString("Frames: " . print_r($frames, true) . "\n");
        $this->stderrWriter->writeString("Number of instructions: $numberOfInstructions \n");
    }
}
?>