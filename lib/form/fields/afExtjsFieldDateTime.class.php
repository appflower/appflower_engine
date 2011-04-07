<?php
/**
 * extJs Form Field DateTime+extra features
 */
class afExtjsFieldDateTime extends afExtjsField
{
	public function __construct($fieldsetObject,$attributes=array())
	{	
		$this->afExtjs=afExtjs::getInstance();
				
		if(!isset($attributes['type']))
		$attributes['type']='date';
		
		switch ($attributes['type'])
		{
			case "date":
				$this->afExtjs->setAddons(array ( 'js' => array($this->afExtjs->getExamplesDir().'datetime/Ext.ux.form.DateTime.js') ));		
				$this->attributes['xtype']='xdatetime';
				$this->attributes['width']='250';
				$this->attributes['timeActive']=false;
				break;
			case "datetime":
				$this->afExtjs->setAddons(array ( 'js' => array($this->afExtjs->getExamplesDir().'datetime/Ext.ux.form.DateTime.js') ));		
				$this->attributes['xtype']='xdatetime';
				$this->attributes['width']='250';
				$this->attributes['timeActive']=true;
				break;
			case "dayplus":
				$this->afExtjs->setAddons(array ( 'js' => array($this->afExtjs->getExamplesDir().'datepickerplus/Ext.ux.datepickerplus.js'), 'css' => array($this->afExtjs->getExamplesDir().'datepickerplus/datepickerplus.css') ));
				$this->attributes['xtype']='datefieldplus';
				$this->attributes['width']='200';
				$this->attributes['renderTodayButton']=false;
				$this->attributes['showToday']=false;
				$this->attributes['multiSelection']=false;
				$this->attributes['format']='d/m/Y';
				$this->attributes['startDay']='1';
				$this->attributes['selectionType']='day';
				if(isset($attributes['url']))
				$this->attributes['url']=$attributes['url'];
				break;
			case "weekplus":
				$this->afExtjs->setAddons(array ( 'js' => array($this->afExtjs->getExamplesDir().'datepickerplus/Ext.ux.datepickerplus.js'), 'css' => array($this->afExtjs->getExamplesDir().'datepickerplus/datepickerplus.css') ));
				$this->attributes['xtype']='datefieldplus';
				$this->attributes['width']='200';
				$this->attributes['renderTodayButton']=false;
				$this->attributes['showToday']=false;
				$this->attributes['multiSelection']=true;
				$this->attributes['format']='d/m/Y';
				$this->attributes['startDay']='1';
				$this->attributes['selectionType']='week';
				$this->attributes['maxSelectionDays']=7;
				if(isset($attributes['url']))
				$this->attributes['url']=$attributes['url'];
				break;
			case "monthplus":
				$this->afExtjs->setAddons(array ( 'js' => array($this->afExtjs->getExamplesDir().'datepickerplus/Ext.ux.datepickerplus.js'), 'css' => array($this->afExtjs->getExamplesDir().'datepickerplus/datepickerplus.css') ));
				$this->attributes['xtype']='datefieldplus';
				$this->attributes['width']='200';
				$this->attributes['renderTodayButton']=false;
				$this->attributes['showToday']=false;
				$this->attributes['multiSelection']=true;
				$this->attributes['format']='d/m/Y';
				$this->attributes['startDay']='1';
				$this->attributes['selectionType']='month';
				if(isset($attributes['url']))
				$this->attributes['url']=$attributes['url'];
				break;
			case "yearplus":
				$this->afExtjs->setAddons(array ( 'js' => array($this->afExtjs->getExamplesDir().'yearpicker/Ext.YearPicker.js'), 'css' => array($this->afExtjs->getExamplesDir().'yearpicker/Ext.YearPicker.css') ));
				$this->attributes['xtype']='yearfield';
				$this->attributes['width']='200';
				$this->attributes['showToday']=false;
				$this->attributes['multiSelection']=true;
				if(isset($attributes['url']))
				$this->attributes['url']=$attributes['url'];
				break;
		}
		
		unset($attributes['type']);
			
		parent::__construct($fieldsetObject,$attributes);
	}
}
?>