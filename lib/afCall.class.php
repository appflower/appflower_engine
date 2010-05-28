<?php

class afCall {
    /**
     * Calls call_user_func_array() with a safety net.
     */
    public static function funcArray($callback, $args) {
        // A workaround to prevent "Segmentation fault"
        // See http://bugs.php.net/bug.php?id=51329
        if(is_array($callback) && isset($callback[0]) && is_string($callback[0])) {
            class_exists($callback[0]);
        }

        return call_user_func_array($callback, $args);
    }

    /**
     * Evaluates the given expression in the given context.
     * It returns the evaluted value.
     */
    public static function evalute($_expression, $_vars) {
        foreach($_vars as $_var => $_value) {
            $$_var = $_value;
        }
        if(eval('$_return = ('.$_expression.');') === false) {
            throw new Exception('Invalid expression: '.$_expression);
        }
        return $_return;
    }
}
