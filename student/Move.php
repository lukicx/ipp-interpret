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
            $value = $this->memoryManager->getFrame($sourceFrame, $sourceVariable);
        //  value is constant
        } else {
            $value = $source;
        }

        $this->memoryManager->setFrame($destinationFrame, $destinationVariable, $value, $this->args[1]->getAttribute('type'));
        print_r($this->memoryManager->getFrame($destinationFrame, $destinationVariable));


        echo "Successfully moved data from $value to $destination\n";
    }
}