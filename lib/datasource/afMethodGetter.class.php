<?php

class afMethodGetter {
    private
        $methodName,
        $conversion,
        $specialColumn;

    public function __construct($methodName, $conversion=null, $specialColumn = false) {
        $this->methodName = $methodName;
        $this->conversion = $conversion;
        $this->specialColumn = $specialColumn;
    }

    public function getFrom($object) {
        if($this->specialColumn&&!method_exists($object,$this->methodName))
        {
            return false;
        }
        $value = call_user_func(array($object, $this->methodName));
        if($this->conversion !== null) {
            $value = $this->conversion->convert($value);
        }
        return $value;
    }
}

