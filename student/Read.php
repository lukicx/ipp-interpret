<?php

namespace IPP\Student;

use IPP\Core\ReturnCode;

class Read extends Opcode {
    private mixed $input;

    public function __construct(mixed $args, MemoryManager $memoryManager, mixed $input) {
        $this->args = $args;
        $this->memoryManager = $memoryManager;
        $this->input = $input;
    }
    
    public function execute(): void {

        $variableToStore = $this->args[0]->nodeValue;
        $typeToRead = $this->args[1]->nodeValue;

        [$frame, $variable] = explode('@', $variableToStore);

        switch ($typeToRead) {
            case 'int':
                $value = $this->input->readInt();
                break;
            case 'bool':
                $value = $this->input->readBool();
                break;
            case 'string':
                $value = $this->input->readString();
                break;
            default:
            $this->stderrWriter->writeString("Invalid value type for read instruction\n");
            exit(ReturnCode::OPERAND_TYPE_ERROR);
        }


        if ($value === null) {
            return;
        }

        $this->memoryManager->setVariableInFrame($frame, $variable, $value, $typeToRead);
    }
}
?>