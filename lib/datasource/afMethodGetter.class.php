<?php

class afMethodGetter {
    private
        $methodName,
        $conversion;

    public function __construct($methodName, $conversion=null) {
        $this->methodName = $methodName;
        $this->conversion = $conversion;
    }

    public function getFrom($object) {
        $value = call_user_func(array($object, $this->methodName));
        if($this->conversion !== null) {
            $value = $this->conversion->convert($value);
        }
        return $value;
    }
}

