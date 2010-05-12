<?php

/**
 * A conversion to a sfOutputEscaperSafe() wrapper.
 */
class afToEscaperSafeConversion {
    private static
        $instance = null;

    private function __construct() {
    }

    public function convert($value) {
        return new sfOutputEscaperSafe($value);
    }

    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

