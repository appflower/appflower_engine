<?php
/**
 * extJs Form Field Password
 */
class afExtjsFieldPassword extends afExtjsField
{
	public function __construct($fieldsetObject,$attributes=array())
	{		
		$this->attributes['xtype']='textfield';
		$this->attributes['inputType']='password';
		$this->attributes['width']='250';
		
		parent::__construct($fieldsetObject,$attributes);
	}
}
?>