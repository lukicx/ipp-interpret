<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */

namespace IPP\Student;

use DOMElement;
use IPP\Core\ReturnCode;


/**
 * Abstract class used to do boolean operations AND/OR/NOT 
 */
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

        // if second operand exists, (operation AND and OR)
        if ($secondOperandElement) {
            [$secondOperand, $secondType]= $this->getValueAndType->execute($secondOperandElement, $this->memoryManager);
            if ($firstType !== 'bool' || $secondType !== 'bool'){
                $this->stderrWriter->writeString("Arguments should be type of bool\n");
                exit(ReturnCode::OPERAND_TYPE_ERROR);
            }
            // recast each operand to boolean
            $firstOperand = filter_var($firstOperand, FILTER_VALIDATE_BOOLEAN);
            $secondOperand = filter_var($secondOperand, FILTER_VALIDATE_BOOLEAN);
            return [$firstOperand, $secondOperand];
        }
        // operation NOT
        if ($firstType !== 'bool'){
            $this->stderrWriter->writeString("Argument should be type of bool\n");
            exit(ReturnCode::OPERAND_TYPE_ERROR);
        }
        // recast operand to boolean
        $firstOperand = filter_var($firstOperand, FILTER_VALIDATE_BOOLEAN);
        return [$firstOperand];
    }
    
    public function execute(): void
    {
        $secondOperand = null;
        if (isset($this->args[2])) {
            $secondOperand = $this->args[2];
        }
        // get the operands do the operation and store them to result variable
        $operands = $this->getOperands($this->args[1], $secondOperand);
        $result = $this->operation(...$operands);
        // recast the result back to string
        $result = var_export($result, true);
        // get the variable from DOMelement value, split it at @ and store the value in frame that was in the DOMelement value 
        $variable = $this->args[0]->nodeValue;
        [$destFrame, $destVariable] = explode('@', $variable);
        $this->memoryManager->setVariableInFrame($destFrame, $destVariable, $result, "bool");
    }


    abstract protected function operation(bool ...$operands): bool;
}