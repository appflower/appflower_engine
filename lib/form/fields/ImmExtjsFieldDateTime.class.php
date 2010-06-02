<?php
/**
 * extJs Form Field DateTime+extra features
 */
class ImmExtjsFieldDateTime extends ImmExtjsField
{
	public function __construct($fieldsetObject,$attributes=array())
	{	
		$this->immExtjs=ImmExtjs::getInstance();
				
		if(!isset($attributes['type']))
		$attributes['type']='date';
		
		switch ($attributes['type'])
		{
			case "date":
				$this->immExtjs->setAddons(array ( 'js' => array($this->immExtjs->getExamplesDir().'datetime/Ext.ux.form.DateTime.js') ));		
				$this->attributes['xtype']='xdatetime';
				$this->attributes['width']=isset($attributes['width'])?$attributes['width']:'250';
				$this->attributes['timeActive']=false;
				$this->attributes['timeWidth']=0;
				break;
			case "datetime":
				$this->immExtjs->setAddons(array ( 'js' => array($this->immExtjs->getExamplesDir().'datetime/Ext.ux.form.DateTime.js') ));		
				$this->attributes['xtype']='xdatetime';
				$this->attributes['width']='250';
				$this->attributes['timeActive']=true;
				break;
			case "dayplus":
				$this->immExtjs->setAddons(array ( 'js' => array($this->immExtjs->getExamplesDir().'datepickerplus/Ext.ux.datepickerplus.js'), 'css' => array($this->immExtjs->getExamplesDir().'datepickerplus/datepickerplus.css') ));
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
				$this->immExtjs->setAddons(array ( 'js' => array($this->immExtjs->getExamplesDir().'datepickerplus/Ext.ux.datepickerplus.js'), 'css' => array($this->immExtjs->getExamplesDir().'datepickerplus/datepickerplus.css') ));
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
				$this->immExtjs->setAddons(array ( 'js' => array($this->immExtjs->getExamplesDir().'datepickerplus/Ext.ux.datepickerplus.js'), 'css' => array($this->immExtjs->getExamplesDir().'datepickerplus/datepickerplus.css') ));
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
				$this->immExtjs->setAddons(array ( 'js' => array($this->immExtjs->getExamplesDir().'yearpicker/Ext.YearPicker.js'), 'css' => array($this->immExtjs->getExamplesDir().'yearpicker/Ext.YearPicker.css') ));
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