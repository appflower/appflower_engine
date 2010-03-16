<?php
class requiredValidator extends sfValidator
{
	public function execute(&$value, &$error)
	{		 
		if (trim($value) == "")
		{
			$error = $this->getParameter('required_error');
			return false;
		}

		return true;
	}

	public function initialize ($context, $parameters = null)
	{
		
		// Initialize parent
		parent::initialize($context);

		// Set default parameters value
		$this->setParameter('required_error', 'This field is required!');

		// Set parameters
		$this->getParameterHolder()->add($parameters);

		return true;
	}
}
?>