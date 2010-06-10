<?php
/**
 * immValidatorHostname validates host name.
 *
 * @author     Tulashi <tulashi@immune.dk>
 */

class immValidatorHostname extends sfValidatorBase
{		
	public function getPattern()
	{
		// Dot is also supported to allow IP as a fallback value.
		return '^[a-zA-Z0-9\-_.]+$';
	}
	
	protected function configure($options = array(), $messages = array())
	{			
		$this->setOption('trim', true);
		$this->addOption('match');
		
		$this->addMessage('match','"%value%" is not a valid Hostname');
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
