<?php

/**
 * A getter that cast the result to string.
 * It is needed to convert the objects to string before
 * calling json_encode() on them.
 */
class afStringMethodGetter {
    private
        $methodName;

    public function __construct($methodName) {
        $this->methodName = $methodName;
    }

    public function getFrom($object) {
        return (string)call_user_func(array($object, $this->methodName));
    }
}

