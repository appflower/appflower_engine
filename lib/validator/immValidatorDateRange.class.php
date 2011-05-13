<?php
/*
 * @author: Prakash Paudel
 * prakash@immune.dk
 */
class immValidatorDateRange extends sfValidatorBase
{
	/**
	 * @see sfValidatorRegex
	 */
	
	protected function configure($options = array(), $messages = array())
	{			
		$this->setOption('trim', true);
		$this->addOption('other');
		$this->addOption('op');
		
		$this->addMessage('other','Invalid date range!');
		$this->setDefaultMessage('required', 'This field is required!');
	}
	
	protected function doClean($value)
	{
		
		$post = sfContext::getInstance()->getRequest()->getParameterHolder()->getAll();
		$op = $this->getOption("op");
		
		$other_value =  strtotime($post["edit"][2][$this->getOption("other")]);
		$value = strtotime($value);
		
		$error = false;
		
		switch($op) {
			case "LT":
				$error = true;
				$msg = "This date must be less than the other date";
				$cond = ($value >= $other_value);
				break;
			case "LTE":
				$error = true;
				$msg = "This date must be less than or equal to the other date";
				$cond = ($value > $other_value);
				break;
			case "GT":
				$error = true;
				$msg = "This date must be greater than the other date";
				$cond = ($value <= $other_value);
				break;
			case "GTE":
				$error = true;
				$msg = "This date must be greater than or equal to the other date";
				$cond = ($value < $other_value);
				break;
		}
		
		$this->setMessage("other",$this->getMessage("other")." ".$msg);
		
		if($cond)
		{
			throw new sfValidatorError($this, 'other', array('value' => $value));			
		}
		
		return $value;
	}
}
?>