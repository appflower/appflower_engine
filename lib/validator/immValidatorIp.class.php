<?php
/**
 * immValidatorIpOrDomain validates IPv4 addresses.
 *
 * @author     Tulashi <tulashi@immune.dk>
 */

class immValidatorIp extends sfValidatorBase
{
	/**
	 * @see sfValidatorRegex
	 */
	public function getPattern(){
		return '^(([01]?\d\d?|2[0-4]\d|25[0-5])\.){3}([01]?\d\d?|2[0-4]\d|25[0-5])$';
	}
	protected function configure($options = array(), $messages = array())
	{			
		$this->setOption('trim', true);
		$this->addOption('match');
		
		
		$this->addMessage('match','"%value%" is not a valid IP address');
		$this->addMessage('required','This field is required!');
		
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