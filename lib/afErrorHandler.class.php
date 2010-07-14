<?php

/**
 * Error handler that converts any error into an exception.
 */
class afErrorHandler {
    private $intro;

    public function __construct($intro) {
        $this->intro = $intro;
    }

    public function handler($errno, $errstr) {
        throw new Exception($this->intro . $errstr);
    }
}
