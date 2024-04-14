<?php

namespace IPP\Student;

use DOMElement;
use IPP\Core\ReturnCode;
use IPP\Core\StreamWriter;

class MemoryManager {
    protected StreamWriter $stderrWriter;
    protected int $numberOfInstructions;
    protected int $positionInCode;
    protected GetValueAndType $getValueAndType;

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
     * @var array{type:string,value:mixed}[]
     */
    private $dataStack = [];

    /**
     * @var array<string,int>
    */
    private array $labels = [];

    private function __construct() {
        $this->stderrWriter = new StreamWriter(STDERR);
        $this->getValueAndType = new GetValueAndType();
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
        $this->TF = $this->LF;
        $this->LF = array_pop($this->stack);
        if (empty($this->stack)) {
            $this->LF = null;
        }
    }
    /**
     * @param array<DOMElement>$args
     */
    public function pushs(array $args): void {
        [$value, $type] = $this->getValueAndType->execute($args[0], $this);
       
        array_push($this->dataStack, ['type' => $type, 'value' => $value]);
    }

    /**
     * @param array<DOMElement>$args
     */
    public function pops(array $args): void {
        if (empty($this->dataStack)) {
            $this->stderrWriter->writeString("Empty data stack\n");
            exit(ReturnCode::VALUE_ERROR);
        }
        [$frame, $varName] = explode('@', $args[0]->nodeValue);
        $value = array_pop($this->dataStack);
        $this->setVariableInFrame($frame, $varName, $value['value'], $value['type']);
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
                    echo "hello";
                    $this->stderrWriter->writeString("Variable '$var' not defined\n");
                    exit(ReturnCode::VARIABLE_ACCESS_ERROR);
                }
                return $this->TF[$var];
            default:
                $this->stderrWriter->writeString("Wrong frame\n");
                exit(ReturnCode::FRAME_ACCESS_ERROR);
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
                $this->stderrWriter->writeString("Wrong frame\n");
                exit(ReturnCode::FRAME_ACCESS_ERROR);
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
                $this->stderrWriter->writeString("Wrong frame\n");
                exit(ReturnCode::FRAME_ACCESS_ERROR);
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
        $frames = ['GF' => $this->GF, 'LF' => $this->LF, 'TF' => $this->TF];
        $framesStatus = "";
        foreach ($frames as $frameName => $frame) {
            if ($frame !== null) { 
                foreach ($frame as $variableName => $variable) {
                    $framesStatus .= "Frame: $frameName \n Variable name: $variableName \n Type: {$variable['type']} \n Value: {$variable['value']}\n";
                }
            }
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