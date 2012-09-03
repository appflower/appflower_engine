<?php

class ipValidator extends sfValidatorBase
{
    protected function configure($options = array(), $messages = array())
    {
        parent::configure($options, $messages);
    }

    protected function doClean($value)
    {
        if (preg_match('/^[0-9]+(\.[0-9]+){3}$/', $value) === 1)
        {
            return true;
        }

        // The value is assumed to be a hostname.
        $ip = gethostbyname($value);
        if ($ip === $value)
        {
            throw new sfValidatorError($this, 'invalid', array('value' => $value));
        }

        return true;
    }
}
