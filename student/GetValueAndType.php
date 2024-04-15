<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */

namespace IPP\Student;

use DOMElement;

class GetValueAndType {    
    /**
     *  Expects DOMElement node from DOM document, if the node is variable calls getVariableInFrame to get the value and type from it,
     *  otherwise extracts its value and type from the DOMElement value, after getting the type and value returns it
     *  
     * @param  DOMElement $operandNode
     * @param  MemoryManager $memoryManager
     * @return array<mixed>
     */
    public function execute(DOMElement $operandNode, MemoryManager $memoryManager) : array {

        if ($operandNode->getAttribute('type') === 'var') {
            if ($operandNode->nodeValue !== null) {
                [$frame, $variable] = explode('@', $operandNode->nodeValue);
            } else {
                $frame = '';
                $variable = '';
            }
            $storedFrameData = $memoryManager->getVariableInFrame($frame, $variable);
            if ($storedFrameData['type'] === 'var') {
                $value = $storedFrameData['value']['value'];
                $type = $storedFrameData['value']['type'];
            } else {
                $value = $storedFrameData['value'];
                $type = $storedFrameData['type'];
            }
        } else {
            $value = $operandNode->nodeValue;
            $type = $operandNode->getAttribute('type');
        }

        return [$value, $type];
    }
}