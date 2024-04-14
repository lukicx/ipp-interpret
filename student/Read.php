<?php

namespace IPP\Student;

use IPP\Core\ReturnCode;

class Read extends Opcode {
    private mixed $input;

    public function __construct(mixed $args, MemoryManager $memoryManager, mixed $input) {
        parent::__construct($args, $memoryManager);
        $this->args = $args;
        $this->memoryManager = $memoryManager;
        $this->input = $input;
    }
    
    public function execute(): void {

        $variableToStore = $this->args[0]->nodeValue;
        $typeToRead = $this->args[1]->nodeValue;

        if ($typeToRead != "int" && $typeToRead != "string" && $typeToRead != "bool"){
            $this->stderrWriter->writeString("Wrong XML structure\n");
            exit(ReturnCode::INVALID_SOURCE_STRUCTURE);
        }


        [$frame, $variable] = explode('@', $variableToStore);

        $value = null;
        switch ($typeToRead) {
        case 'int':
            $value = $this->input->readInt();
            break;
        case 'bool':
            $value = $this->input->readBool();
            break;
        case 'string':
            $value  = $this->input->readString();
            break;
        default:
            $this->stderrWriter->writeString("Invalid value type for read instruction\n");
            exit(ReturnCode::OPERAND_TYPE_ERROR);
        }

       
                    
        if ($value === null) {
           $value = "nil";
           $typeToRead = "nil";
        }

        // if($typeToRead === "int"){
        //     if(!is_numeric($value)){
        //         $this->stderrWriter->writeString("Invalid value type for read instruction\n");
        //         exit(ReturnCode::SEMANTIC_ERROR);
        //     }
        // }
        // else if($typeToRead === "bool"){
        //     if($value !== "true" && $value !== "false"){
        //         $this->stderrWriter->writeString("Invalid value type for read instruction\n");
        //         exit(ReturnCode::SEMANTIC_ERROR);
        //     }
        // }
        // else if ($typeToRead === "string"){
        //     if(!is_string($value)){
        //         $this->stderrWriter->writeString("Invalid value type for read instruction\n");
        //         exit(ReturnCode::SEMANTIC_ERROR);
        //     }
        // }
        // else{
        //     $this->stderrWriter->writeString("Invalid value type for read instruction\n");
        //     exit(ReturnCode::OPERAND_TYPE_ERROR);
        // }

        $this->memoryManager->setVariableInFrame($frame, $variable, $value, $typeToRead);
    }
}