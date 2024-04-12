<?php

namespace IPP\Student;
use IPP\Core\StreamWriter;

class Write extends Opcode {

    public function execute(): void {
        $stdOutWriter = new StreamWriter(STDOUT);
        $valueToWrite = $this->args[0]->nodeValue;

        if ($this->args[0]->getAttribute('type') === 'var'){
            [$frame, $variable] = explode('@', $valueToWrite);
            $value = $this->memoryManager->getFrame($frame, $variable);
            if (is_int($value)) {
                $stdOutWriter->writeInt($value);
            } else if (is_bool($value)) {
                $stdOutWriter->writeBool($value);
            } else {
                $stdOutWriter->writeString($value);
            }
        }
        else {
            [$constantType, $constant] = explode('@', $valueToWrite);
            switch ($constantType) {
                case 'int':
                    $stdOutWriter->writeInt((int)$constant);
                    break;
                case 'bool':
                    $stdOutWriter->writeBool($constant === 'true');
                    break;
                case 'nil':
                    $stdOutWriter->writeString('');
                    break;
                default:
                    $stdOutWriter->writeString($constant);
                    break;
            }
        }
        echo "\nWrote value: " . $value . "\n";
    }
}