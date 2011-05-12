<?php

/**
 * A getter to extract a formatted datetime.
 * The datetime is formatted according to the user's timezone.
 */
class afDatetimeGetter {
    private
        $methodName;

    public function __construct($methodName) {
        $this->methodName = $methodName;
    }

    public function getFrom($object) {
        $timestamp = call_user_func(array($object, $this->methodName), 'U');
        return Tz::formatTime($timestamp);
    }
}

