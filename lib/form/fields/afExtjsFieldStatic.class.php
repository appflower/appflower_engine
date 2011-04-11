<?php
/**
 * extJs Form Field Static
 */
class afExtjsFieldStatic extends afExtjsField
{
	public function __construct($fieldsetObject,$attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
		
		$this->afExtjs->setAddons(array('js'=>array($this->afExtjs->getPluginsDir().'form/Ext.ux.form.StaticTextField.js')));
		
		$this->attributes['xtype']='statictextfield';
		
		parent::__construct($fieldsetObject,$attributes);
	}
}
?>