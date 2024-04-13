<?php

namespace IPP\Student;

use DOMElement;

class GetValueAndType {    
    /**
     * execute
     *
     * @param  DOMElement $operandNode
     * @param  MemoryManager $memoryManager
     * @return array<mixed>
     */
    public function execute(DOMElement $operandNode, MemoryManager $memoryManager) : array {

        if ($operandNode->getAttribute('type') === 'var') {
            [$frame, $variable] = explode('@', $operandNode->nodeValue);
            $storedFrameData = $memoryManager->getFrame($frame, $variable);
            $value = $storedFrameData['value'];
            $type = $storedFrameData['type'];
        } else {
            $value = $operandNode->nodeValue;
            $type = $operandNode->getAttribute('type');
        }

        return [$value, $type];
    }
}