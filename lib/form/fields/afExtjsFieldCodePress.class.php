<?php
/**
 * extJs Form Field CodePress
 */
class afExtjsFieldCodePress extends afExtjsField
{
	public function __construct($fieldsetObject,$attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
		
		$this->afExtjs->setAddons(array('js'=>array($this->afExtjs->getExamplesDir().'codepress/Ext.ux.CodePress.js')));
		
		$this->attributes['xtype']='codepress';
		
		parent::__construct($fieldsetObject,$attributes);
	}
}
?>