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
			$this->attributes['height'] = isset($attributes['height'])?$attributes['height']:150;
			$this->attributes['msgTarget']='side';
		}
		else {
			$this->attributes['xtype']='textarea';
			
			$this->attributes['autoHeight']=true;
			$this->attributes['grow']=true;
			$this->attributes['growMin']=60;
		}
		$this->attributes['anchor']="95%";
		$this->attributes['boxMinHeight']=60;		
		
		parent::__construct($fieldsetObject,$attributes);
	}
}
?>