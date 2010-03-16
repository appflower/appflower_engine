<?php
/**
 * extJs Form Field Checkbox
 */
class ImmExtjsFieldCheckbox extends ImmExtjsField
{
	public function __construct($containerObject,$attributes=array())
	{		
		$this->attributes['xtype']='checkbox';
		
		if(isset($attributes['label'])&&get_class($containerObject)=='ImmExtjsFieldCheckboxGroup')
		{
			$this->attributes['boxLabel']=$attributes['label'];
			
			unset($attributes['label']);
		}
		elseif (isset($attributes['label'])&&get_class($containerObject)=='ImmExtjsFieldset')
		{
			$this->attributes['fieldLabel']=$attributes['label'];
			
			unset($attributes['label']);
		}
		
		if(isset($attributes['value']))
		{
			$this->attributes['inputValue']=$attributes['value'];
			
			unset($attributes['value']);
		}
				
		parent::__construct($containerObject,$attributes);
	}
}
?>