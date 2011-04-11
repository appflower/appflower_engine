<?php
/**
 * extJs Form Field Combo
 */
class afExtjsFieldDayTimeSelect extends afExtjsField
{
	public function __construct($containerObject,$attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
				
		/*
		 * Set plugins and default attributes
		 */		
		$this->afExtjs->setAddons(array('js' => array($this->afExtjs->getPluginsDir().'datetime/Ext.ux.plugins.DayTimeSelect.js') ));
		//$this->attributes['plugins'][]="Ext.ux.plugins.DayTimeSelect";
		$this->attributes['xtype'] = 'daytimeselect';

		parent::__construct($containerObject,$attributes);
	}
}
?>
