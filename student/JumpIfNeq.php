<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */

namespace IPP\Student;

use IPP\Core\ReturnCode;

class JumpIfNeq extends Opcode {
    private int $order;

    /**
     * @param array<mixed>$args
     */
    public function __construct(array $args, MemoryManager $memoryManager, int $i) {
        parent::__construct($args, $memoryManager);
        $this->order = $i; 
    }

    public function execute(): void {
        $label = $this->args[0]->nodeValue;
        [$firstOperand, $firstType] = $this->getValueAndType->execute($this->args[1], $this->memoryManager);
        [$secondOperand, $secondType] = $this->getValueAndType->execute($this->args[2], $this->memoryManager);
        
        // if operands are of the same type or one of them is nil, evaluate the not equal, if it is true jump
        if ($firstType == $secondType || $firstOperand == "nil" || $secondOperand == "nil") {
            if ($firstOperand != $secondOperand) {
                $this->order = $this->memoryManager->getLabelOrder($label);
            }
        }
        else {
            $this->stderrWriter->writeString("Operands are not of the same type\n");
            exit(ReturnCode::OPERAND_TYPE_ERROR);
        }

    }

    public function getOrder(): int {
        return $this->order;
    }
}