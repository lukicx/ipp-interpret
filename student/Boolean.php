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
        echo $firstOperand . $secondOperandElement;

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
            $value = $storedFrameData['value'];
            $type = $storedFrameData['type'];
        } else {
            $value = $operandNode->nodeValue;
            $type = $operandNode->getAttribute('type');
        }

        switch ($type) {
            case 'bool':
                $value = ($value === 'true' || $value === '1') ? true : false;
                break;
            case 'int':
                $value = intval($value);
                break;
            case 'string':
                $value = strval($value);
                break;
        }
        

        return [$value, $type];
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