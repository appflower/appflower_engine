<?php
class uniqueValidator extends sfPropelUniqueValidator
{
	public function execute(&$value, &$error)
	{		 
		$formData = sfContext::getInstance()->getRequest()->getParameter("edit");
		$id = $formData[0]['id'];
		if(!$id) $id = $formData[1]['id'];
		
		if ($id)
		{
			$c = new Criteria();
			$c->add(constant($this->getParameter('class')."Peer::".strtoupper($this->getParameter('column'))),$value);
			$res = call_user_func(array($this->getParameter('class')."Peer","doSelectOne"),$c);
			
			if($res && $res->getId() == $id) {
				return true;	
			}
			
		}
		
		return parent::execute($value,$error);

	}

	public function initialize ($context, $parameters = null)
	{

		// Initialize parent
		parent::initialize($context,$parameters);
		
		$this->setParameter('class', $parameters["class"]);
		$this->setParameter('column', $parameters["column"]);
		$this->setParameter('unique_error', $parameters["unique"]);
			
		// Set parameters
		$this->getParameterHolder()->add($parameters);

		return true;
	}
}
?>