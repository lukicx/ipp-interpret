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

        
        [$firstOperand, $firstType]= $this->getValueAndType->execute($firstOperandElement, $this->memoryManager);
        [$secondOperand, $secondType] = $this->getValueAndType->execute($secondOperandElement, $this->memoryManager);

        echo "First operand " . $firstOperand . " Second operand " . $secondOperand . " Type: ";
        echo $firstType . $secondType;



        if ($firstType !== 'int' || $secondType !== 'int'){
            throw new \Exception("At least one of the operands is not int", ReturnCode::OPERAND_TYPE_ERROR);
        }

        return [$firstOperand, $secondOperand];
    }


    public function execute(): void
    {
        [$firstOperand, $secondOperand] = $this->getOperands($this->args[1], $this->args[2]);
        $variable = $this->args[0]->nodeValue;
        $result = $this->operation((int)$firstOperand,(int)$secondOperand);
        $result = var_export($result, true);
        [$destFrame, $destVariable] = explode('@', $variable);
        $this->memoryManager->setFrame($destFrame, $destVariable, $result, "int");
    }

    abstract protected function operation(int $firstOperand, int $secondOperand): int;

}
