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
        [$firstOperand, $firstType]= $this->getValueAndType->execute($firstOperandElement, $this->memoryManager);

        if ($secondOperandElement) {
            [$secondOperand, $secondType]= $this->getValueAndType->execute($secondOperandElement, $this->memoryManager);
            if ($firstType !== 'bool' || $secondType !== 'bool'){
                $this->stderrWriter->writeString("Arguments should be type of bool\n");
                exit(ReturnCode::OPERAND_TYPE_ERROR);
            }
            $firstOperand = filter_var($firstOperand, FILTER_VALIDATE_BOOLEAN);
            $secondOperand = filter_var($secondOperand, FILTER_VALIDATE_BOOLEAN);
            return [$firstOperand, $secondOperand];
        }

        if ($firstType !== 'bool'){
            $this->stderrWriter->writeString("Argument should be type of bool\n");
            exit(ReturnCode::OPERAND_TYPE_ERROR);
        }
        $firstOperand = filter_var($firstOperand, FILTER_VALIDATE_BOOLEAN);
        return [$firstOperand];
    }
    
    public function execute(): void
    {
        $secondOperand = null;
        if (isset($this->args[2])) {
            $secondOperand = $this->args[2];
        }

        $operands = $this->getOperands($this->args[1], $secondOperand);
        $result = $this->operation(...$operands);
        $result = var_export($result, true);

        $variable = $this->args[0]->nodeValue;
        [$destFrame, $destVariable] = explode('@', $variable);
        $this->memoryManager->setVariableInFrame($destFrame, $destVariable, $result, "bool");
    }


    abstract protected function operation(bool ...$operands): bool;
}