<?php
namespace IPP\Student;

use DOMElement;
use IPP\Core\Exception\IPPException;
use IPP\Core\ReturnCode;



abstract class Arithmetic extends Opcode {

    /**
     * getOperands
     *
     * @param  \DOMElement $firstOperandElement
     * @param  \DOMElement $secondOperandElement
     * @return array<int>
     */
    protected function getOperands(DOMElement $firstOperandElement, DOMElement $secondOperandElement) : array  {

        
        $firstOperand = $this->getValue($firstOperandElement);
        $secondOperand = $this->getValue($secondOperandElement);
        if (!is_int($firstOperand) || !is_int($secondOperand)){
            // throw new IPPException("Invalid operand types", ReturnCode::OPERAND_TYPE_ERROR);
            echo "Invalid operand types";
        }

        return [$firstOperand, $secondOperand];
    }

         
   
    private function getValue(DOMElement $operandNode) : mixed  {
        echo "Operand value " . $operandNode->nodeValue . "\n";

        if ($operandNode->getAttribute('type') === 'var') {
            [$frame, $variable] = explode('@', $operandNode->nodeValue);
            return $this->memoryManager->getFrame($frame, $variable);
        } else {
            return $operandNode->nodeValue;
        }
    }

    public function execute(): void
    {
        [$firstOperand, $secondOperand] = $this->getOperands($this->args[1], $this->args[2]);
        $result = $this->operation($firstOperand, $secondOperand);

        $variable = $this->args[0]->nodeValue;
        $this->args[0]->nodeValue = $result;
        [$destFrame, $destVariable] = explode('@', $variable);
        $this->memoryManager->setFrame($destFrame, $destVariable, $result);
    }

    abstract protected function operation(int $firstOperand, int $secondOperand): int;

}
