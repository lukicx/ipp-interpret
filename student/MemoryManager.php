<?php

namespace IPP\Student;

use IPP\Core\ReturnCode;
use IPP\Student\Exceptions;

class MemoryManager {
    private static ?MemoryManager $singleInstance = null;
     /**
     * @var array<mixed,mixed>
    */
    private array $GF = [];
    /**
     * @var array<string,int>
    */
    private array $labels = [];

    private function __construct() {}

    public static function getInstance() : MemoryManager{
        if (self::$singleInstance === null) {
            self::$singleInstance = new MemoryManager();
        }
        return self::$singleInstance;
    }

    public function getFrame(string $frame, string $var) : mixed {
        echo ("Get frame: $frame\n");
        print_r($this->GF);
        switch ($frame) {
            case 'GF':
                if (!isset($this->GF[$var])) {
                    throw new Exceptions("Variable '$var' not defined", ReturnCode::VARIABLE_ACCESS_ERROR);
                }
                return $this->GF[$var] ;
                default:
                throw new \InvalidArgumentException("Not implemented yet");
            }
    }

    public function setFrame(string $frame, string $var, mixed $value, mixed $type):void {
        switch ($frame) {
            case 'GF':
                $this->GF[$var] =  ['type' => $type, 'value' => $value];
                break;
            default:
                throw new \InvalidArgumentException("Not implemented yet");
        }
    }

    public function doesFrameExist(string $frame, string $var):bool {
        echo ("does exist frame: $frame\n");
        switch ($frame) {
            case 'GF':
                return isset($this->GF[$var]);
            default:
            throw new Exceptions("Not implemented yet", ReturnCode::INTERNAL_ERROR);
        }
    }

    public function doesLabelExist(string $label): bool {
        return isset($this->labels[$label]);
    }

    public function setLabel(string $label, int $order): void {
        if ($this->doesLabelExist($label)) {
            throw new Exceptions("Label '$label' already defined", ReturnCode::SEMANTIC_ERROR);
        }
        $this->labels[$label] = $order;
    }

    public function getLabelOrder(string $label): int {
        if (!$this->doesLabelExist($label)) {
            throw new Exceptions("Label '$label' not defined", ReturnCode::VARIABLE_ACCESS_ERROR);
        }
        return $this->labels[$label];
    }

}