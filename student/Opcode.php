<?php

namespace IPP\Student;

abstract class  Opcode {
    protected mixed $args;
    protected Frames $frames;

    public function __construct(mixed $args, Frames $frames) {
        $this->args = $args;
        $this->frames = $frames;
    }

    public function execute() : void {
    }
}