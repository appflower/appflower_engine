<?php
/**
 * extJs Form Field Textarea
 */
class ImmExtjsFieldTextarea extends ImmExtjsField
{
	public function __construct($fieldsetObject,$attributes=array())
	{		
		if((isset($attributes['rich'])&&$attributes['rich'])||!isset($attributes['rich']))
		{		
			$this->attributes['xtype']='htmleditor';
		}
		else {
			$this->attributes['xtype']='textarea';
		}
		$this->attributes['anchor']='97%';
		$this->attributes['height']='200';
		
		parent::__construct($fieldsetObject,$attributes);
	}
}
?>