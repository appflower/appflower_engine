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
            if (method_exists($value, '__toString')) {
                $value = (string)$value;
            } else if (method_exists($value, 'getId')) {
                $value = $value->getId();
            } else {
                throw new Exception('I don\'t know how to represent object of class '.get_class($value). ' as string.');
            }
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

