<?php
/**
 * extJs Form Field Combo
 */
class afExtjsFieldExtendedDayTimeSelect extends afExtjsField
{
	public function __construct($containerObject,$attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
				
		/*
		 * Set plugins and default attributes
		 */		
		$this->afExtjs->setAddons(array('js' => array($this->afExtjs->getPluginsDir().'datetime/Ext.ux.plugins.ExtendedDayTimeSelect.js') ));
		//$this->attributes['plugins'][]="Ext.ux.plugins.DayTimeSelect";
		$this->attributes['xtype'] = 'extendedDayTimeSelect';

		parent::__construct($containerObject,$attributes);
	}
}
?>
