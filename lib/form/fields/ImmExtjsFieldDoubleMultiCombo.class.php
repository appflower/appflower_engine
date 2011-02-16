<?php
/**
 * extJs Form Field Double Multi Combo
 */
class ImmExtjsFieldDoubleMultiCombo extends ImmExtjsField
{
	public function __construct($containerObject,$attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		$this->immExtjs->setAddons(array('js' => array($this->immExtjs->getExamplesDir().'multiselect/ItemSelector.js') ));					
		$this->immExtjs->setAddons(array('css' => array($this->immExtjs->getExamplesDir().'multiselect/multiselect.css') ));					
		$this->attributes['xtype']='itemselector';
		$this->attributes['msWidth']='250';
		$this->attributes['msHeight']='200';
		$this->attributes['imagePath']=$this->immExtjs->getExamplesDir().'multiselect/images/';
						
		if(isset($attributes['name']))
		{
			$this->attributes['name']=$attributes['name'];
			$this->attributes['id']=$attributes['name'];
			
			unset($attributes['name']);
		}
		
		
		/**
		 * add a button to clear the selection
		 */		
		if(isset($attributes['clear']))
		{
			$this->attributes['toTBar']=array(
							$this->immExtjs->asAnonymousClass(array(
  								'text'=>'clear',
  								'handler'=>$this->immExtjs->asMethod(array(
  									'parameters'=>'',
  									'source'=>'Ext.getCmp("'.$this->attributes['id'].'").clear();'
  								))
  							))
  			);
  			unset($attributes['clear']);
		}

		
		$this->attributes['dataFields']=$this->immExtjs->asVar("['key','value']");
		$this->attributes['valueField']="key";
		$this->attributes['displayField']="value";
 							
  		/**
		 * the options attribute, an assoc array for now
		 */
		if(isset($attributes['options'])&&count($attributes['options'])>0)
		{
			$options=array();
			foreach ($attributes['options'] as $key=>$value)
			{
				$options[]="['".$key."','".$value."']";
			}
			
			$this->attributes['fromData']=$this->immExtjs->asVar("[ ".implode(',',$options)." ]");
			
			unset($attributes['options']);
		}
		else {
			$this->attributes['fromData']=$this->immExtjs->asVar("[]");
		}
		
		/**
		 * PROCESS selected values FROM value & selected ATTRIBUTES
		 * you can use either selected and/or value attributes arrays to select some values from options attribute
		 */
		$this->processSelectedValues($attributes);
		/**
		 * PROCESS state ATTRIBUTE
		 * state can have "disabled" or "editable" values; if state is "readonly", then it's equal to "disabled"
		 */
		$this->processState($attributes);
		
		parent::__construct($containerObject,$attributes);
	}
	
	public function processSelectedValues(&$attributes=array())
	{
		$this->selected_values=array();
		/**
		 * the selected values that came from the selected attribute
		 */
		if(isset($attributes['selected'])&&is_array($attributes['selected'])&&count($attributes['selected'])>0)
		{			
			foreach ($attributes['selected'] as $key=>$value)
			{
				$this->selected_values[$key]="'".$value."'";
			}
		
			unset($attributes['selected']);
		}

		/**
		 * the selected values that came from the value attribute
		 */
		if(isset($attributes['value'])&&is_array($attributes['value'])&&count($attributes['value'])>0)
		{
			foreach ($attributes['value'] as $key=>$value)
			{
				$this->selected_values[$key]="'".$value."'";
			}

			unset($attributes['value']);
		}	
		
		if(count($this->selected_values)>0)
		{
			$options=array();
			
			foreach ($this->selected_values as $key=>$value)
			{
				$options[]="['".$key."',".$value."]";
			}
			
			$this->attributes['toData']=$this->immExtjs->asVar("[ ".implode(',',$options)." ]");
		}
		else {
			$this->attributes['toData']=$this->immExtjs->asVar("[]");
		}
	}
	
	public function processState(&$attributes=array())
	{
		if(isset($attributes['state']))
		{		
			if(!isset($this->attributes['listeners']['render']['source']))
			$this->attributes['listeners']['render']['source']='';
				
			if(!isset($this->attributes['listeners']['render']['parameters']))
			$this->attributes['listeners']['render']['parameters']='field';			
						
			switch ($attributes['state'])
			{
				case "readonly":
				case "disabled":
					$this->attributes['listeners']['render']['source'].="field.disable();";
				break;
				case "editable":
				default:					
				break;
			}
			
			unset($attributes['state']);
		}	
	}
}
?>