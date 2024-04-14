<?php

namespace IPP\Student;

class Move extends Opcode{

    public function execute() : void {
  

        $destination = $this->args[0]->nodeValue;
        [$destinationFrame, $destinationVariable] = explode('@', $destination);
        $source = $this->args[1]->nodeValue;
        // source is destinationVariable get value from the destinationFrame
        if (strpos($source, '@') !== false) {
            [$sourceFrame, $sourceVariable] = explode('@', $source);
            $value = $this->memoryManager->getVariableInFrame($sourceFrame, $sourceVariable);
        //  value is constant
        } else {
            $value = $source;
        }

        $this->memoryManager->setVariableInFrame($destinationFrame, $destinationVariable, $value, $this->args[1]->getAttribute('type'));


    }
}