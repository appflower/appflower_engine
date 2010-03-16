<?php
/*
 * @author: Prakash Paudel
 * prakash@immune.dk
 */
class immValidatorNumber extends sfValidatorBase
{
	/**
	 * @see sfValidatorRegex
	 */
	public function getPattern(){
		return '(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)';
	}
	protected function configure($options = array(), $messages = array())
	{			
		$this->setOption('trim', true);
		$this->addOption('match');
		
		$this->addMessage('match','"%value%" is not a valid number');
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