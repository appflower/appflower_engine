<?php
/**
 * extJs Form Field Input
 */
class ImmExtjsFieldCaptcha extends ImmExtjsField
{
	public function __construct($fieldsetObject,$attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		$this->attributes['xtype']='captcha';
		if(!isset($this->attributes['input'])) $this->attributes['input'] = 'true';
		$this->immExtjs->setAddons(array('js' => array($this->immExtjs->getExamplesDir().'form/Ext.ux.plugins.Captcha.js') ));
		
		$this->attributes['width']='250';
		
		parent::__construct($fieldsetObject,$attributes);
	}
}
?>