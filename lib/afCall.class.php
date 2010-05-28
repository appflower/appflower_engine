<?php

class afCall {
    public static function funcArray($callback, $args) {
        // A workaround to prevent "Segmentation fault"
        if(is_array($callback) && isset($callback[0]) && is_string($callback[0])) {
            class_exists($callback[0]);
        }

        return call_user_func_array($callback, $args);
    }
}
