<?php

namespace IPP\Student;

class Frames {
    private static ?Frames $singleInstance = null;
    /**
     * @var array<string, mixed>
    */
    private array $GF = [];

    private function __construct() {}

    public static function getInstance() : Frames{
        if (self::$singleInstance === null) {
            self::$singleInstance = new Frames();
        }
        return self::$singleInstance;
    }

    public function get(string $frame, string $var) : mixed {
        echo ("Get frame: $frame\n");
        switch ($frame) {
            case 'GF':
                if (!isset($this->GF[$var])) {
                    throw new \InvalidArgumentException("Variable '$var' not defined");
                }
                return $this->GF[$var];
            default:
                throw new \InvalidArgumentException("Not implemented yet");
        }
    }

    public function set(string $frame, string $var, mixed $value):void {
        echo ("Set frame: $frame\n");
        switch ($frame) {
            case 'GF':
                $this->GF[$var] = $value;
                break;
            default:
                throw new \InvalidArgumentException("Not implemented yet");
        }
    }

    public function doesExist(string $frame, string $var):bool {
        echo ("does exist frame: $frame\n");
        switch ($frame) {
            case 'GF':
                return isset($this->GF[$var]);
            default:
                throw new \InvalidArgumentException("Not implemented yet");
        }
    }

}