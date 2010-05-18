<?php
class immValidatorCompanyName extends immValidatorName 
{	
	public function getPattern()
	{
		return '/^([\s\x{00c0}-\x{01ff}a-zA-Z\'\-0-9_.\/@&])+$/u';
	}

	protected function configure($options = array(), $messages = array())
	{
		parent::configure($options,$messages);
		
	}
	
	protected function doClean($value)
	{
		
		return parent::doClean($value);
	}
}
?>
