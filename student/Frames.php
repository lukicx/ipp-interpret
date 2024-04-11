<?php

namespace IPP\Student;

class Frames {
    private static $singleInstance = null;
    private $GF = [];

    private function __construct() {}

    public static function getInstance() {
        if (self::$singleInstance === null) {
            self::$singleInstance = new Frames();
        }
        return self::$singleInstance;
    }

    public function get($frame, $var):string {
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

    public function set($frame, $var, $value):void {
        echo ("Set frame: $frame\n");
        switch ($frame) {
            case 'GF':
                $this->GF[$var] = $value;
                break;
            default:
                throw new \InvalidArgumentException("Not implemented yet");
        }
    }

    public function doesExist($frame, $var):bool {
        echo ("does exist frame: $frame\n");
        switch ($frame) {
            case 'GF':
                return isset($this->GF[$var]);
            default:
                throw new \InvalidArgumentException("Not implemented yet");
        }
    }

}