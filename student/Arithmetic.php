<?php
namespace IPP\Student;

use IPP\Core\Exception\IPPException;
use IPP\Core\ReturnCode;


abstract class Arithmetic extends Opcode {
    protected function getOperands($firstOperandNode, $secondOperandNode) {
        $firstOperand = $this->getValue($firstOperandNode);
        $secondOperand = $this->getValue($secondOperandNode);

        if (!is_int($firstOperand) || !is_int($secondOperand)){
            // throw new IPPException("Invalid operand types", ReturnCode::OPERAND_TYPE_ERROR);
        }

        return [$firstOperand, $secondOperand];
    }

    private function getValue($operandNode) {
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
