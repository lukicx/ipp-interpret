<?php
 /**
* IPP - PHP Project Student
 * @author Lukas Selicky xselic00
 */

namespace IPP\Student;

use IPP\Core\ReturnCode;
use IPP\Core\StreamWriter;

class Write extends Opcode {

    public function execute(): void {
        $stdOutWriter = new StreamWriter(STDOUT);
        $valueToWrite = $this->args[0]->nodeValue;

        /* check the type of the instruction if it is var get the type and value from stored associative array, 
        * else get the type from DOMelement attribute type, and value from nodeValue.
        */
        if ($this->args[0]->getAttribute('type') === 'var'){
            [$frame, $variable] = explode('@', $valueToWrite);
            $value = $this->memoryManager->getVariableInFrame($frame, $variable);
            
            $type = $value['type'];
            $value = $value['value'];

            $reTypeBoolValue = false;
            $reTypeIntValue = 0;

            // recast the string value to bool or int if needed
            if ($type === 'bool'){
                $reTypeBoolValue = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }
            if($type === 'int'){
                $reTypeIntValue = filter_var($value, FILTER_VALIDATE_INT);
                if ($reTypeIntValue === false) {
                    $reTypeIntValue = 0;
                }
            }
            
            if ($type === 'int') {
                $stdOutWriter->writeInt($reTypeIntValue);
            } else if ($type === 'bool') {
                $stdOutWriter->writeBool($reTypeBoolValue);
            } else if ($type === 'nil') {
                $stdOutWriter->writeString('');
            } else if ($type === 'string'){
                $value = $this->handleEscapeSeq($value);
                $stdOutWriter->writeString($value);
            }
            else if ($type === 'var'){
                $value = $value['value'];
                $stdOutWriter->writeString($value);
            }
            else {
                $this->stderrWriter->writeString("Unknown type var writer\n");
                exit(ReturnCode::SEMANTIC_ERROR);
            }

        }
        else {
            $constantType = $this->args[0]->getAttribute('type');
            $constantValue = $valueToWrite;
            $reTypeBoolValue = false;
            $reTypeIntValue = 0;

            // recast the string value to bool or int if needed
            if ($constantType === 'bool'){
                $reTypeBoolValue = filter_var($constantValue, FILTER_VALIDATE_BOOLEAN);
            }
            if($constantType === 'int'){
                $reTypeIntValue = filter_var($constantValue, FILTER_VALIDATE_INT);
                if ($reTypeIntValue === false) {
                    $reTypeIntValue = 0;
                }
            }
            switch ($constantType) {
                case 'int':
                    $stdOutWriter->writeInt($reTypeIntValue);
                    break;
                case 'bool':
                    $stdOutWriter->writeBool($reTypeBoolValue);
                    break;
                case 'nil':
                    $stdOutWriter->writeString('');
                    break;
                case 'string':
                    $constantValue = $this->handleEscapeSeq($constantValue);
                    $stdOutWriter->writeString($constantValue);
                    break;
                default:
                    $this->stderrWriter->writeString("Unknown type const writer\n");
                    exit(ReturnCode::SEMANTIC_ERROR);
            }
        }
    }

     /* This method processes a string and replaces escape sequences with their corresponding characters.
     * Correct escape sequence is backslash and three numeric characters.
     * If the escape sequence is valid, it is replaced with the corresponding character.
     * If the escape sequence is not valid, return the original string.
     */
    public function handleEscapeSeq(string $string) : string{
        $resultString = '';
        for ($i = 0; $i < strlen($string); $i++) {
            if ($string[$i] === '\\') {
                $nextThreeChars = substr($string, $i + 1, 3);
                if (is_numeric($nextThreeChars) && strlen($nextThreeChars) === 3) {
                    $resultString .= chr((int)$nextThreeChars);
                    $i += 3;
                } else {
                    $resultString .= $string[$i];
                }
            } else {
                $resultString .= $string[$i];
            }
        }
        return $resultString;
    }
}