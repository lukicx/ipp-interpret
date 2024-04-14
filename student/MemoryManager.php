<?php

namespace IPP\Student;

use IPP\Core\ReturnCode;
use IPP\Student\Exceptions;
use IPP\Core\StreamWriter;

class MemoryManager {
    protected StreamWriter $stderrWriter;
    protected int $numberOfInstructions;
    protected int $positionInCode;

    private static ?MemoryManager $singleInstance = null;
    /**
     * @var array<string,array{type:string,value:mixed}>
     */
    private array $GF = [];
    /**
     * @var array<string,array{type:string,value:mixed}>|null
     */
    private ?array $LF = null;
    /**
     * @var array<string,array{type:string,value:mixed}>|null
     */
    private ?array $TF = null;
    /**
     * @var array<array<string,array{type:string,value:mixed}>>|null
     */
    private $stack = [];

    /**
     * @var array<string,int>
    */
    private array $labels = [];

    private function __construct() {
        $this->stderrWriter = new StreamWriter(STDERR);
    }

    public static function getInstance() : MemoryManager{
        if (self::$singleInstance === null) {
            self::$singleInstance = new MemoryManager();
        }
        return self::$singleInstance;
    }

    public function createFrame(): void {
        $this->TF = [];
    }

    public function pushFrame(): void {
        if ($this->TF === null) {
            $this->stderrWriter->writeString("Frame not defined\n");
            exit(ReturnCode::FRAME_ACCESS_ERROR );
        }
        array_push($this->stack, $this->TF);
        $this->LF = $this->TF;
        $this->TF = null;
    }

    public function popFrame(): void {
        if ($this->LF === null) {
            $this->stderrWriter->writeString("Frame not defined\n");
            exit(ReturnCode::FRAME_ACCESS_ERROR);
        }
        $this->TF = array_pop($this->stack);
        if (empty($this->stack)) {
            $this->LF = null;
        } else {
            $this->LF = end($this->stack);
        }
    }

    public function getVariableInFrame(string $frame, string $var) : mixed {
        switch ($frame) {
            case 'GF':
                if (!isset($this->GF[$var])) {
                    $this->stderrWriter->writeString("Variable '$var' not defined\n");
                    exit(ReturnCode::VARIABLE_ACCESS_ERROR);
                }
                return $this->GF[$var];
            case 'LF':
                if (!isset($this->LF[$var])) {
                    $this->stderrWriter->writeString("Variable '$var' not defined\n");
                    exit(ReturnCode::VARIABLE_ACCESS_ERROR);
                }
                return $this->LF[$var];
            case 'TF':
                if (!isset($this->TF[$var])) {
                    $this->stderrWriter->writeString("Variable '$var' not defined\n");
                    exit(ReturnCode::VARIABLE_ACCESS_ERROR);
                }
                return $this->TF[$var];
            default:
            throw new \InvalidArgumentException("Not implemented yet");
            }
    }

    public function setVariableInFrame(string $frame, string $var, mixed $value, mixed $type):void {
        switch ($frame) {
            case 'GF':
                $this->GF[$var] = ['type' => $type, 'value' => $value];
                break;
            case 'LF':
                $this->LF[$var] = ['type' => $type, 'value' => $value];
                break;
            case 'TF':
                $this->TF[$var] = ['type' => $type, 'value' => $value];
                break;
            default:
                throw new \InvalidArgumentException("Not implemented yet");
        }
    }                
    


    public function doesVariableExistInFrame(string $frame, string $var):bool {
        switch ($frame) {
            case 'GF':
                return isset($this->GF[$var]);
            case 'LF':
                return isset($this->LF[$var]);
            case 'TF':
                return isset($this->TF[$var]);
            default:
            throw new Exceptions("Not implemented yet", ReturnCode::INTERNAL_ERROR);
        }
    }

    public function doesLabelExist(string $label): bool {
        return isset($this->labels[$label]);
    }

    public function setLabel(string $label, int $order): void {
        if ($this->doesLabelExist($label)) {
            $this->stderrWriter->writeString("Label '$label' does already exist\n");
            exit(ReturnCode::SEMANTIC_ERROR);
        }
        $this->labels[$label] = $order;
    }

    public function getLabelOrder(string $label): int {
        if (!$this->doesLabelExist($label)) {
            $this->stderrWriter->writeString("Label '$label' does not exist\n");
            exit(ReturnCode::VARIABLE_ACCESS_ERROR);
        }
        return $this->labels[$label];
    }

    public function getFramesStatus() : string {
        $framesStatus = "";
        foreach ($this->GF as $variableName => $variable) {
            $framesStatus .= "Variable name: $variableName, Type: {$variable['type']}, Value: {$variable['value']}\n";
        }
        return $framesStatus;
    }

    public function setNumberOfInstructions(int $number) : void {
        $this->numberOfInstructions = $number;
    }

    public function getNumberOfInstructions() : int {
        return $this->numberOfInstructions;
    }

    public function setPositionInCode(int $position) : void {
        $this->positionInCode = $position;
    }

    public function getPositionInCode() : int {
        return $this->positionInCode;
    }

}