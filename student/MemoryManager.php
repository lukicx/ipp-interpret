<?php

namespace IPP\Student;

class MemoryManager {
    private static ?MemoryManager $singleInstance = null;
    /**
     * @var array<string, mixed>
    */
    private array $GF = [];

    private function __construct() {}

    public static function getInstance() : MemoryManager{
        if (self::$singleInstance === null) {
            self::$singleInstance = new MemoryManager();
        }
        return self::$singleInstance;
    }

    public function getFrame(string $frame, string $var) : mixed {
        echo ("Get frame: $frame\n");
        echo ("Frame state: " . print_r($this->GF, true) . "\n");
        switch ($frame) {
            case 'GF':
                if (!isset($this->GF[$var])) {
                    throw new \InvalidArgumentException("Variable '$var' not defined");
                }
                return $this->GF[$var] ;
            default:
                throw new \InvalidArgumentException("Not implemented yet");
        }
    }

    public function setFrame(string $frame, string $var, string $value, string $type):void {
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
                throw new \InvalidArgumentException("Not implemented yet");
        }
    }

}