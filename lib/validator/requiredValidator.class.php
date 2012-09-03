<?php

class requiredValidator extends sfValidatorBase
{
    protected function configure($options = array(), $messages = array())
    {
        parent::configure($options, $messages);
    }

    protected function doClean($value)
    {
		if (trim($value) == "")
		{
			throw new sfValidatorError($this, 'required', array('value' => $value));
		}

        return true;
    }
}

?>
