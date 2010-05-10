<?php

class afMethodGetter {
    private
        $methodName;

    public function __construct($methodName) {
        $this->methodName = $methodName;
    }

    public function getFrom($object) {
        return call_user_func(array($object, $this->methodName));
    }
}

