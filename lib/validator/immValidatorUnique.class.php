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
		$this->addMessage('required', 'This field is required!');
	}
	
	protected function doClean($value)
	{
		$id = NULL;
        $formData = sfContext::getInstance()->getRequest()->getParameter("edit");
                
        if(isset($formData[0]) && isset($formData[0]['id'])){
        	$id = $formData[0]['id'];
        }else if(isset($formData[1]) && isset($formData[1]['id'])){
        	$id = $formData[1]['id'];
        }else if(isset($formData[2]) && isset($formData[2]['id'])){
        	$id = $formData[2]['id'];
        }else if(isset($formData['id'])){
        	$id = $formData['id'];
        }
		
		$c = new Criteria();
		$c->add(constant($this->getOption('class')."Peer::".strtoupper($this->getOption('column'))),$value);
		$res = call_user_func(array($this->getOption('class')."Peer","doSelectOne"),$c);
		
		if($res and $res->getId() === $id){
			return $value;
		}else if($res && $res->getId() !== $id){
			throw new sfValidatorError($this, 'unique', array('value' => $value,'unique' => $this->getOption('unique')));			
		}		
		
		return $value;
	}
}
?>
