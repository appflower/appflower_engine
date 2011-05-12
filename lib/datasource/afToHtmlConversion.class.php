<?php

/**
 * A conversion to an escaped HTML.
 */
class afToHtmlConversion {
    private static
        $instance = null;

    private function __construct() {
    }

    public function convert($value) {
        if($value === null) {
            return null;
        }

        if($value instanceof sfOutputEscaperSafe) {
            return (string)$value;
        }

        if(is_object($value)) {
            $value = (string)$value;
        }
        if(is_string($value)) {
            $value = htmlspecialchars($value);
        }
        return $value;
    }

    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

