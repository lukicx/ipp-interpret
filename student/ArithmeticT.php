<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */


namespace IPP\Student;


use DOMElement;
use IPP\Core\ReturnCode;

/**
 * Abstract class that is used to perform ArithmeticT operations on two operands of type int
 */
abstract class ArithmeticT extends Opcode {
    

    /**
     *
     * @param  DOMElement $firstOperandElement
     * @param  DOMElement $secondOperandElement
     * @return array<int>
     */
    protected function getOperands(DOMElement $firstOperandElement, DOMElement $secondOperandElement) : array  {

        // get two operands and their type from custom function
        [$firstOperand, $firstType]= $this->getValueAndType->execute($firstOperandElement, $this->memoryManager);
        [$secondOperand, $secondType] = $this->getValueAndType->execute($secondOperandElement, $this->memoryManager);


        if ($firstType !== 'int' || $secondType !== 'int'){
            $this->stderrWriter->writeString("Argument should be type of int\n");
            exit(ReturnCode::OPERAND_TYPE_ERROR);
        }

        return [$firstOperand, $secondOperand];
    }


    public function execute(): void
    {
        // Get the operands from class method, do the operation with values casted to int, cast the result back to string
        [$firstOperand, $secondOperand] = $this->getOperands($this->args[1], $this->args[2]);
        $result = $this->operation((int)$firstOperand,(int)$secondOperand);
        $result = var_export($result, true);
        // get the variable from dom element value, split it at @, and save the result using method that takes frame, name of variable, value, and type as parameters
        $variable = $this->args[0]->nodeValue;
        [$destFrame, $destVariable] = explode('@', $variable);
        $this->memoryManager->setVariableInFrame($destFrame, $destVariable, $result, "int");
    }

    abstract protected function operation(int $firstOperand, int $secondOperand): int;

}
