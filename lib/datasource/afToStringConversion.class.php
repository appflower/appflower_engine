<?php

class afToStringConversion {
    private static
        $instance = null;

    private function __construct() {
    }

    public function convert($value) {
        return (string)$value;
    }

    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new afToStringConversion();
        }
        return self::$instance;
    }
}

