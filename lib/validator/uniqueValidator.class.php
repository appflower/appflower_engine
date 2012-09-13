<?php

class uniqueValidator extends sfValidatorPropelUnique
{
    protected function configure($options = array(), $messages = array())
    {
        parent::configure($options, $messages);
    }

    protected function doClean($value)
    {
        $values = array($this->getOption('column') => $value);

        return parent::doClean($values);
    }
}

?>
