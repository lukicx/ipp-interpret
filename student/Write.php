<?php

namespace IPP\Student;

use IPP\Core\ReturnCode;
use IPP\Core\StreamWriter;

class Write extends Opcode {

    public function execute(): void {
        $stdOutWriter = new StreamWriter(STDOUT);
        $valueToWrite = $this->args[0]->nodeValue;
        echo "Value to write: " . $valueToWrite . "\n";

        if ($this->args[0]->getAttribute('type') === 'var'){
            [$frame, $variable] = explode('@', $valueToWrite);
            $value = $this->memoryManager->getFrame($frame, $variable);
            echo "Value to write: " . $value['value'] . " type: " . $value['type'] ."\n";

            if (!isset($value['type']) || !isset($value['value'])) {
                throw new \Exception("Value or type is not set", ReturnCode::INTERNAL_ERROR);
            }

            $type = $value['type'];
            $value = $value['value'];

            if ($type === 'int') {
                $stdOutWriter->writeInt((int)$value);
            } else if ($type === 'bool') {
                $stdOutWriter->writeBool((bool)$value);
            } else if ($type === 'nil') {
                $stdOutWriter->writeString('');
            } else {
                $stdOutWriter->writeString($value);
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
                default:
                    $stdOutWriter->writeString($constantValue);
                    break;
            }
        }
        echo "\nWrote value: " . $value . "\n";
    }
}