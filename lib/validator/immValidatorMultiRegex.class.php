<?php
class immValidatorMultiRegex extends sfValidatorBase 
{
	protected function configure($options = array(), $messages = array())
	{
		$this->addOption('match');
		$this->addOption('or');		
		$this->addMessage('match','Invalid input.');
		$this->addMessage('required','This field is required!');		
	}
	
	protected function doClean($value)
	{		
		$flag = false;
		
		if($this->hasOption('or') && $this->getOption('or') !='')
		{
			$options = $this->getOption('or');
			$classes = explode(',',$options);
			
			$orPattern = '/';
			foreach($classes as $class)
			{
				if($class == 'immValidatorName'){
					$flag = true;
					continue;
				}
				
				$class = trim($class);				
				$regex = '';
				if(preg_match('/regex:/',$class)){
					$regex = substr($class,6,strlen($class)); 
				}				
				if($regex == '' and class_exists($class))
				$object = new $class;
				if($orPattern == '/')
				{
					if($regex == "")
					$orPattern  .= '('.call_user_func(array($object,'getPattern')).')';
					else
					$orPattern .= '('.$regex.')';
				}
				else
				{
					if($regex == "")
					$orPattern .= '|('.call_user_func(array($object,'getPattern')).')';
					else
					$orPattern .= '|('.$regex.')';
				}
			}
			
			$orPattern .= '/'; 		
			
			if(!preg_match($orPattern,$value))
			{
				if($flag)
				{
					$options = $this->getOptions();
					unset($options['or']);
					$class = new immValidatorName($options,$this->getMessages());
					$value = $class->clean($value);
					return $value;
				}
				
				throw new sfValidatorError($this, 'match', array('value' => $value));
			}
			
			return $value;
		}
	}
}
?>