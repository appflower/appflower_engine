<?php
/*
 * @author: Prakash Paudel
 * prakash@immune.dk
 */
class immValidatorInteger extends sfValidatorBase
{
	/**
	 * @see sfValidatorRegex
	 */
	public function getPattern(){
		return '^[0-9]+$';
	}
	protected function configure($options = array(), $messages = array())
	{			
		$this->setOption('trim', true);
		$this->addOption('match');
		
		$this->addMessage('match','"%value%" is not a valid integer');
		$this->setRequiredMessage('This field is required!');
	}
	
	protected function doClean($value)
	{
		if(!preg_match('/'.$this->getPattern().'/',$value))
		{
			throw new sfValidatorError($this, 'match', array('value' => $value));			
		}
		
		return $value;
	}
}
?>