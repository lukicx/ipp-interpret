<?php

namespace IPP\Student;

use IPP\Core\ReturnCode;
use IPP\Core\StreamWriter;

class Write extends Opcode {

    public function execute(): void {
        $stdOutWriter = new StreamWriter(STDOUT);
        $valueToWrite = $this->args[0]->nodeValue;

        if ($this->args[0]->getAttribute('type') === 'var'){
            [$frame, $variable] = explode('@', $valueToWrite);
            $value = $this->memoryManager->getVariableInFrame($frame, $variable);

            if (!isset($value['type']) || !isset($value['value'])) {
                $this->stderrWriter->writeString("Type or value is not set\n");
                exit(ReturnCode::VALUE_ERROR);
            }

            $type = $value['type'];
            $value = $value['value'];

            if ($type === 'int') {
                $stdOutWriter->writeInt((int)$value);
            } else if ($type === 'bool') {
                $stdOutWriter->writeBool((bool)$value);
            } else if ($type === 'nil') {
                $stdOutWriter->writeString('');
            } else if ($type === 'string'){
                $stdOutWriter->writeString($value);
            }
            else {
                $this->stderrWriter->writeString("Unknown type\n");
                exit(ReturnCode::SEMANTIC_ERROR);
            }
        }
        else {
            $constantType = $this->args[0]->getAttribute('type');
            $constantValue = $valueToWrite;
            switch ($constantType) {
                case 'int':
                    $stdOutWriter->writeInt((int)$constantValue);
                    break;
                case 'bool':
                    $stdOutWriter->writeBool((bool)$constantValue);
                    break;
                case 'nil':
                    $stdOutWriter->writeString('');
                    break;
                case 'string':
                    $stdOutWriter->writeString($constantValue);
                    break;
                default:
                    $this->stderrWriter->writeString("Unknown type\n");
                    exit(ReturnCode::SEMANTIC_ERROR);
            }
        }
        // echo "\nWrote value: " . $value . "\n";
    }
}