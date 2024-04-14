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
                $this->stderrWriter->writeString("Operand is nil and the operation is not Eq\n");
                exit(ReturnCode::OPERAND_TYPE_ERROR);;
            }
            return [$firstOperand, $secondOperand];
        }

        if ($firstType !== 'int' && $firstType !== 'bool' && $firstType !== 'string'){
            $this->stderrWriter->writeString("Invalid type for relational operation\n");
            exit(ReturnCode::OPERAND_TYPE_ERROR);;
        }

        if ($firstType !== $secondType){
            $this->stderrWriter->writeString("Operands are not of same value\n");
            exit(ReturnCode::OPERAND_TYPE_ERROR);;
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
        $this->memoryManager->setVariableInFrame($destFrame, $destVariable, $result, "bool");
        
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