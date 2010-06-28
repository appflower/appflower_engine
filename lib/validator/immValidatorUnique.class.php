<?php
/*
 * @author: Prakash Paudel
 * prakash@immune.dk
 */
class immValidatorUnique extends sfValidatorBase
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
		
		$this->addMessage('unique','"%value%" already exists.');
		self::setRequiredMessage('This field is required!');
	}
	
	protected function doClean($value)
	{	
		$id = sfContext::getInstance()->getRequest()->getParameter("edit[0][id]",false);
		if(!$id) $id = sfContext::getInstance()->getRequest()->getParameter("edit[1][id]",false);
		if(!$id) $id = sfContext::getInstance()->getRequest()->getParameter("edit[2][id]",false);
		if(!$id) $id = sfContext::getInstance()->getRequest()->getParameter("edit[id]",false);
		
		$c = new Criteria();
		$c->add(constant($this->getOption('class')."Peer::".strtoupper($this->getOption('column'))),$value);
		$res = call_user_func(array($this->getOption('class')."Peer","doSelectOne"),$c);
		
		if($res and $res->getId() == $id)
		{
			return $value;
		}
		else if($res && $res->getId() != $id)
		{
			throw new sfValidatorError($this, 'unique', array('value' => $value,'unique' => $this->getOption('unique')));			
		}		
		
		return $value;
	}
}
?>
