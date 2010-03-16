<?php
/**
 * extJs Form Field Static
 */
class ImmExtjsFieldStatic extends ImmExtjsField
{
	public function __construct($fieldsetObject,$attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		
		$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'form/Ext.ux.form.StaticTextField.js')));
		
		$this->attributes['xtype']='statictextfield';
		
		parent::__construct($fieldsetObject,$attributes);
	}
}
?>