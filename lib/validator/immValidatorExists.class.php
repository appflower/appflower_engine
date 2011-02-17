<?php
/*
 * @author: Prakash Paudel
 * prakash@immune.dk
 */
class immValidatorExists extends sfValidatorBase
{
	/**
	 * @see sfValidatorRegex
	 */
	public function getPattern(){
		return '';
	}
	protected function configure($options = array(), $messages = array())
	{			
		$this->setOption('trim', true);
		$this->addOption('unique');
		$this->addOption('class');
		$this->addOption('column');
		
		$this->addMessage('none','"%value%" does not exist!');
		$this->addMessage('required', 'This field is required!');
	}
	
	protected function doClean($value)
	{
	
		$c = new Criteria();
		$c->add(constant($this->getOption('class')."Peer::".strtoupper($this->getOption('column'))),$value);
		$res = call_user_func(array($this->getOption('class')."Peer","doSelectOne"),$c);
		
		if($res){
			return $value;
		} else {
			throw new sfValidatorError($this, 'none', array('value' => $value,'none' => $this->getOption('none')));			
		}		
		
		return $value;
	}
}
?>
