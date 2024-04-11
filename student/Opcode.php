<?php

namespace IPP\Student;

abstract class  Opcode {
    protected $args;
    protected $frames;

    public function __construct($args, $frames) {
        $this->args = $args;
        $this->frames = $frames;
    }

    public function execute() {
    }
}