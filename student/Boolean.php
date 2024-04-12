<?php
namespace IPP\Student;

use DOMElement;
use IPP\Core\ReturnCode;


abstract class Boolean extends Opcode {
    
    /**
     * getOperands
     *
     * @param  DOMElement $firstOperandElement
     * @param  DOMElement $secondOperandElement
     * @return array<bool>
     */
    protected function getOperands(DOMElement $firstOperandElement, DOMElement $secondOperandElement = null) : array  {
        [$firstOperand, $firstType] = $this->getValueAndType($firstOperandElement);

        if ($secondOperandElement) {
            [$secondOperand, $secondType] = $this->getValueAndType($secondOperandElement);
            if ($firstType !== 'bool' || $secondType !== 'bool'){
                throw new \Exception("At least one of the operands is not bool", ReturnCode::OPERAND_TYPE_ERROR);
            }
            return [$firstOperand, $secondOperand];
        }

        if ($firstType !== 'bool'){
            throw new \Exception("The operand is not bool", ReturnCode::OPERAND_TYPE_ERROR);
        }
        return [$firstOperand];
    }
    
    /**
     * getValueAndType
     *
     * @param  DOMElement $operandNode
     * @return array<mixed>
     */
    private function getValueAndType(DOMElement $operandNode) : array  {
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
        $operands = $this->getOperands($this->args[1], $this->args[2] ?? null);
        $result = $this->operation(...$operands);

        $variable = $this->args[0]->nodeValue;
        [$destFrame, $destVariable] = explode('@', $variable);
        $this->memoryManager->setFrame($destFrame, $destVariable, $result, "bool");
    }


    abstract protected function operation(bool ...$operands): bool;
}