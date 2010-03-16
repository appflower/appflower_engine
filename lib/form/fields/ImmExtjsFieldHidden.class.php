<?php
/**
 * extJs Form Field Hidden
 */
class ImmExtjsFieldHidden extends ImmExtjsField
{
	public function __construct($fieldsetObject,$attributes=array())
	{		
		$this->attributes['xtype']='hidden';
		
		if(isset($attributes['label']))
		unset($attributes['label']);
		
		if(isset($attributes['comment']))
		unset($attributes['comment']);
		
		if(isset($attributes['help']))
		unset($attributes['help']);
		
		parent::__construct($fieldsetObject,$attributes);
	}
}
?>