<?php
/**
 * extJs Form Field CodePress
 */
class ImmExtjsFieldCodePress extends ImmExtjsField
{
	public function __construct($fieldsetObject,$attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		
		$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'codepress/Ext.ux.CodePress.js')));
		
		$this->attributes['xtype']='codepress';
		
		parent::__construct($fieldsetObject,$attributes);
	}
}
?>