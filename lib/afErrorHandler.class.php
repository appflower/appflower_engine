<?php

/**
 * Error handler that converts any error into an exception.
 */
class afErrorHandler {
    private $intro;

    public function __construct($intro) {
        $this->intro = $intro;
    }

    public function handler($errno, $errstr, $errfile, $errline) {
        throw new afPhpErrorException($this->intro . $errstr,
            $errfile, $errline);
    }
}

class afPhpErrorException extends Exception {
    public function __construct($message, $file, $line) {
        parent::__construct($message);
        $this->file = $file;
        $this->line = $line;
    }
}
