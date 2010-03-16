<?php
class immValidatorRequired extends sfValidatorBase 
{
	protected function configure($options = array(), $messages = array())
	{		
		$this->setOption('trim',true);
		$this->addOption('match');		
		$this->addMessage('match','This field is required!');
		$this->addMessage('required','This field is required!');
	}
	
	protected function doClean($value)
	{
		if(preg_match('/^$/',$value))
		{
			throw new sfValidatorError($this, 'match', array('value' => $value));
		}
		
		return $value;
	}
}
?>