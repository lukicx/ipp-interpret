<?php
namespace IPP\Student;

use DOMElement;
use IPP\Core\ReturnCode;

abstract class Relational extends Opcode {    
    /**
     * getOperands
     *
     * @param  DOMElement $firstOperandElement
     * @param  DOMElement $secondOperandElement
     * @return array<string>
     */
    protected function getOperands(DOMElement $firstOperandElement, DOMElement $secondOperandElement) : array  {
        [$firstOperand, $firstType]= $this->getValueAndType->execute($firstOperandElement, $this->memoryManager);
        [$secondOperand, $secondType]= $this->getValueAndType->execute($secondOperandElement, $this->memoryManager);

        if ($firstType === 'nil' || $secondType === 'nil'){
            if (get_class($this) !== 'Eq') {
                throw new \Exception("Nil can be used only with EQ", ReturnCode::OPERAND_TYPE_ERROR);
            }
            return [$firstOperand, $secondOperand];
        }

        if ($firstType !== 'int' && $firstType !== 'bool' && $firstType !== 'string'){
            throw new \Exception("Not valid type of operands", ReturnCode::OPERAND_TYPE_ERROR);
        }

        if ($firstType !== $secondType){
            throw new \Exception("Operands are not of the same type", ReturnCode::OPERAND_TYPE_ERROR);
        }

        if ($firstType !== 'bool') {
            settype($firstOperand, $firstType);
        }
        if ($secondType !== 'bool') {
            settype($secondOperand, $secondType);
        }

        return [$firstOperand, $secondOperand];
    }
    

    public function execute(): void
    {
        [$firstOperand, $secondOperand] = $this->getOperands($this->args[1], $this->args[2]);
        $result = $this->operation($firstOperand, $secondOperand);
        $result = var_export($result, true);
        $variable = $this->args[0]->nodeValue;
        [$destFrame, $destVariable] = explode('@', $variable);
        $this->memoryManager->setFrame($destFrame, $destVariable, $result, "bool");
        
    }
    
    /**
     * operation
     *
     * @param  mixed $firstOperand
     * @param  mixed $secondOperand
     * @return bool
     */
    abstract protected function operation(mixed $firstOperand, mixed $secondOperand): bool;
}