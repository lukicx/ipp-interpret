<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */

namespace IPP\Student;


use IPP\Core\ReturnCode;
use DOMelement;
use DOMDocument;
use IPP\Core\Interface\OutputWriter;


class SortInstructionsAndGetLabels {

        /**
         * @var array<DOMelement>$instructions
         */
        private $instructions = [];
     
        private MemoryManager $memoryManager;
        private OutputWriter $stderrWriter;
    
        public function __construct(DOMDocument $dom, OutputWriter $stderrWriter) {
            $this->stderrWriter = $stderrWriter;
            $this->memoryManager = MemoryManager::getInstance();
            $this->getInstructions($dom);
        }
    
        private function getInstructions(DOMDocument $dom): void {

            $unorderedInstructions = $dom->getElementsByTagName('instruction');
            
            foreach ($unorderedInstructions as $instruction) {
                $order = (int) $instruction->getAttribute('order');
                if ($order <= 0 || isset($this->instructions[$order])){
                    $this->stderrWriter->writeString("Negative or zero order");
                    exit(ReturnCode::INVALID_SOURCE_STRUCTURE);
                }
                $this->instructions[$order] = $instruction;
            }
            // Sorts the instructions array by key in ascending order
            ksort($this->instructions);

        }
        
         /**
         * Get all labels in first pass and store them in instructions array
         * @return array<string,int|DOMElement[]>
         */
        public function execute() : array {

            if(end($this->instructions) === false){
                exit(ReturnCode::OK);
            }
    
            foreach ($this->instructions as $order => $instruction) {
                $opcode = $instruction->getAttribute('opcode');
                if ($opcode === 'LABEL') {
                    $arg = $instruction->getElementsByTagName('arg1');
                    $label = new Label($arg[0], $this->memoryManager, $order);
                    $label->execute();
                }
            }

            $highestInstructionOrder = (int)(end($this->instructions)->getAttribute('order'));

            return ['number' => $highestInstructionOrder, 'instructions' => $this->instructions];
        }
}
