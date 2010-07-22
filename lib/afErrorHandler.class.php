<?php

/**
 * Error handler that converts any error into an exception.
 */
class afErrorHandler {
    private $intro;
    private $debug;

    public function __construct($intro, $debug=false) {
        $this->intro = $intro;
        $this->debug = $debug;
    }

    public function handler($errno, $errstr, $errfile, $errline) {
        if ($this->debug) {
            $errstr .= "\n in $errfile line $errline:";
            $errstr .= "\n".self::getLine($errfile, $errline);
        }
        throw new Exception($this->intro . $errstr);
    }

    private static function getLine($file, $line) {
        $fd = fopen($file, 'r');
        if ($fd === false) {
            return '';
        }

        for ($i = 1; $i < $line; $i++) {
            fgets($fd);
        }

        $line = fgets($fd);
        fclose($file);
        return $line;
    }
}
