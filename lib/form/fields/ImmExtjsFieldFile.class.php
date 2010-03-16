<?php
/**
 * extJs Form Field File
 */
class ImmExtjsFieldFile extends ImmExtjsField
{
	public function __construct($fieldsetObject,$attributes=array())
	{		
		$this->attributes['xtype']='textfield';
		$this->attributes['inputType']='file';
		
		parent::__construct($fieldsetObject,$attributes);
	}
}
?>