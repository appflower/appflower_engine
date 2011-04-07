<?php
/**
 * extJs Form Field Input
 */
class afExtjsFieldCaptcha extends afExtjsField
{
	public function __construct($fieldsetObject,$attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
		$this->attributes['xtype']='captcha';
		if(!isset($this->attributes['input'])) $this->attributes['input'] = 'true';
		$this->afExtjs->setAddons(array('js' => array($this->afExtjs->getExamplesDir().'form/Ext.ux.plugins.Captcha.js') ));
		
		$this->attributes['width']='250';
		
		parent::__construct($fieldsetObject,$attributes);
	}
}
?>