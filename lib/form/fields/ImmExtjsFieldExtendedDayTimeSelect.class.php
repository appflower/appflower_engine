<?php
/**
 * extJs Form Field Combo
 */
class ImmExtjsFieldExtendedDayTimeSelect extends ImmExtjsField
{
	public function __construct($containerObject,$attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
				
		/*
		 * Set plugins and default attributes
		 */		
		$this->immExtjs->setAddons(array('js' => array($this->immExtjs->getExamplesDir().'datetime/Ext.ux.plugins.ExtendedDayTimeSelect.js') ));
		//$this->attributes['plugins'][]="Ext.ux.plugins.DayTimeSelect";
		$this->attributes['xtype'] = 'extendedDayTimeSelect';

		parent::__construct($containerObject,$attributes);
	}
}
?>
