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
                throw new \Exception("At least one of the operands is not bool", ReturnCode::OPERAND_TYPE_ERROR);
            }
            return [$firstOperand, $secondOperand];
        }
        echo $firstOperand . $secondOperandElement;

        if ($firstType !== 'bool'){
            throw new \Exception("The operand is not bool", ReturnCode::OPERAND_TYPE_ERROR);
        }

        settype($firstOperand, $firstType);
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
        echo "Frame state before boolean instruction: ";
        $this->memoryManager->setFrame($destFrame, $destVariable, $result, "bool");
        echo "Frame state after boolean instruction: ";
        print_r($this->memoryManager->getFrame($destFrame, $destVariable));
    }


    abstract protected function operation(bool ...$operands): bool;
}