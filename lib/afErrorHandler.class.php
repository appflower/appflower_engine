<?php

/**
 * Error handler that converts any error into an exception.
 */
class afErrorHandler {
    private $intro;

    private function __construct($intro) {
        $this->intro = $intro;
    }

    public function handler($errno, $errstr, $errfile, $errline) {
        if (error_reporting() === 0 ) {
            return false;
        }

        throw new afPhpErrorException($this->intro . $errstr,
            $errfile, $errline);
    }

    /**
     * Installs an exception raising error handler.
     */
    public static function raiseExceptionsOnErrors($intro='Error: ') {
        set_error_handler(array(new afErrorHandler($intro), 'handler'));
    }
}

class afPhpErrorException extends Exception {
    public function __construct($message, $file, $line) {
        parent::__construct($message);
        $this->file = $file;
        $this->line = $line;
    }
}
