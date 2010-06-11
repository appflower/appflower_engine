<?php
class immValidatorEmail extends sfValidatorString 
{	
	public function getPattern()
	{
		return '^([^@\s]+)@((?:[-a-zA-Z0-9]+\.)+[a-zA-Z]{2,})$';
	}

	protected function configure($options = array(), $messages = array())
	{
		parent::configure($options,$messages);
		
		$this->setOption('trim',true);
		$this->setOption('min_length',2);
		$this->setOption('max_length',100);
		$this->addOption('match');
		
		$this->addMessage('match','Please try with valid email address');
		$this->addMessage('required','This field is required!');
	}
	
	protected function doClean($value)
	{
		$value = parent::doClean($value);
		if(!preg_match("/".$this->getPattern()."/",$value))
		{
			throw new sfValidatorError($this, 'match', array('value' => $value));
		}
		
		return $value;
	}
}
?>
