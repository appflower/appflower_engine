<?php
/**
 * immValidatorDomain validates Domain name.
 *
 * @author     Tulashi <tulashi@immune.dk>
 */

class immValidatorDomain extends sfValidatorBase  
{
	public function getPattern()
	{
		return '^([a-z0-9-]+\.)+[a-z]{2,6}$';
	}
	
	protected function configure($options = array(), $messages = array())
	{
		$this->setOption('trim',true);		
		$this->addOption('match');
		
		$this->addMessage('match','"%value%" is not a valid Domain Name');
		self::setDefaultMessage('required', 'This field is required!');
	}

	protected function doClean($value)
	{		
		if(!preg_match('/'.$this->getPattern().'/',$value))
		{
			throw new sfValidatorError($this, 'match',array('value' => $value));
		}
		return $value;
	}
}
?>