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
    public static function evaluate($_expression, $_vars) {
        foreach($_vars as $_var => $_value) {
            $$_var = $_value;
        }
        if(eval('$_return = ('.$_expression.');') === false) {
            throw new Exception('Invalid expression: '.$_expression);
        }
        return $_return;
    }

    /**
     * Rewrites the old condition.
     * Example: "MyPeer,isEnabled,extra1,extra2", array('id') ->
     * "MyPeer::isEnabled(array($id, $extra1, $extra2))"
     * 
     * @deprecated Use the PHP syntax for new conditions.
     */
    public static function rewriteIfOldCondition($condition, $params) {
        if(preg_match('/^[a-zA-Z_][a-zA-Z_0-9]*,/', $condition) !== 1) {
            return $condition;
        }

        $parts = explode(',', $condition);
        $class = $parts[0];
        $method = $parts[1];
        $args = array_merge($params, array_slice($parts, 2));

        foreach($args as $i => $name) {
            if(self::isVarName($name)) {
                $args[$i] = '$'.$name;
            }
        }
        // The arguments are passed as an array.
        // The old functions expect that.
        $newCondition = "$class::$method(array(".implode(',', $args).'))';
        return $newCondition;
    }

    private static function isVarName($name) {
        return preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $name) === 1;
    }
}
