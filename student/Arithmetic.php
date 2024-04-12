<?php
namespace IPP\Student;

use DOMElement;
use IPP\Core\ReturnCode;

abstract class Arithmetic extends Opcode {

    /**
     * getOperands
     *
     * @param  DOMElement $firstOperandElement
     * @param  DOMElement $secondOperandElement
     * @return array<int>
     */
    protected function getOperands(DOMElement $firstOperandElement, DOMElement $secondOperandElement) : array  {

        
        [$firstOperand, $firstType]= $this->getValueAndType($firstOperandElement);
        [$secondOperand, $secondType] = $this->getValueAndType($secondOperandElement);


        if ($firstType !== 'int' || $secondType !== 'int'){
            throw new \Exception("At least one of the operands is not int", ReturnCode::OPERAND_TYPE_ERROR);
        }

        return [$firstOperand, $secondOperand];
    }

         
   
    private function getValueAndType(DOMElement $operandNode) : mixed  {
       

        if ($operandNode->getAttribute('type') === 'var') {

            [$frame, $variable] = explode('@', $operandNode->nodeValue);
            $storedFrameData = $this->memoryManager->getFrame($frame, $variable);
            return [$storedFrameData['value'], $storedFrameData['type']];
            
        } else {
            return [$operandNode->nodeValue, $operandNode->getAttribute('type')];
        }
    }

    public function execute(): void
    {
        [$firstOperand, $secondOperand] = $this->getOperands($this->args[1], $this->args[2]);
        $variable = $this->args[0]->nodeValue;
        $result = $this->operation($firstOperand, $secondOperand);
        [$destFrame, $destVariable] = explode('@', $variable);
        $this->memoryManager->setFrame($destFrame, $destVariable, $result, "int");
    }

    abstract protected function operation(int $firstOperand, int $secondOperand): int;

}
