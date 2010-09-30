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
		self::setDefaultMessage('required', 'This field is required!');
	}
	
	protected function doClean($value)
	{
        $formData = sfContext::getInstance()->getRequest()->getParameter("edit");
		$id = $formData[0]['id'];
		if(!$id) $id = $formData[1]['id'];
		if(!$id) $id = $formData[2]['id'];
		if(!$id) $id = $formData['id'];
		
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
