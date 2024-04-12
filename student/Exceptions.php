<?php

namespace IPP\Student;

use IPP\Core\Exception\IPPException;

class Exceptions extends IPPException {
    public function __construct(string $message, int $code) {
        parent::__construct($message, $code);
    }
}