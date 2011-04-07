<?php
/**
 * extJs Form Field Multi Combo
 */
class afExtjsFieldMultiCombo extends afExtjsField
{
	public function __construct($containerObject,$attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
		
		$this->privateName='field_'.Util::makeRandomKey();
		
		$this->attributes['xtype']='multiselect';
		$this->attributes['width']='250';
		$this->attributes['height']='100';
		if(isset($attributes['plugin']) && $attributes['plugin'] == 'listfield'){
			$this->afExtjs->setAddons(array('js' => array($this->afExtjs->getExamplesDir().'multiselect/ListField.js') ));
			$this->attributes['xtype'] = 'listfield';
			$this->attributes['height'] = 250;
			
		}
		if(isset($attributes['name']))
		{
			$this->attributes['name']=$attributes['name'];
			$this->attributes['id']=$this->privateName;
		}		
		
		/**
		 * add a button to clear the selection
		 */		
		if(isset($attributes['clear']))
		{
			$this->attributes['tbar']=array(
							$this->afExtjs->asAnonymousClass(array(
  								'text'=>'clear',
  								'handler'=>$this->afExtjs->asMethod(array(
  									'parameters'=>'',
  									'source'=>'Ext.getCmp("'.$this->attributes['id'].'").reset();'
  								))
  							))
  			);
  			unset($attributes['clear']);
		}
		
 							
  		/**
		 * the options attribute, an assoc array for now
		 */
		if(isset($attributes['options'])&&count($attributes['options'])>0)
		{
			$options=array();
			foreach ($attributes['options'] as $key=>$value)
			{
				$options[]=array($key, $value);
			}

			$this->attributes['dataFields']=$this->afExtjs->asVar("['key','value']");
			$this->attributes['valueField']="key";
			$this->attributes['displayField']="value";
			$this->attributes['data']=$this->afExtjs->asVar(json_encode($options));
			
			unset($attributes['options']);
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
			foreach ($attributes['selected'] as $value)
			{
				$this->selected_values[]=$value;
			}
		
			unset($attributes['selected']);
		}

		/**
		 * the selected values that came from the value attribute
		 */
		if(isset($attributes['value'])&&is_array($attributes['value'])&&count($attributes['value'])>0)
		{
			foreach ($attributes['value'] as $value)
			{
				$this->selected_values[]=$value;
			}

			unset($attributes['value']);
		}	
		
		if(count($this->selected_values)>0)
		{
			if(!isset($this->attributes['listeners']['render']['source']))
			$this->attributes['listeners']['render']['source']='';
				
			if(!isset($this->attributes['listeners']['render']['parameters']))
			$this->attributes['listeners']['render']['parameters']='field';			
				
			$this->attributes['listeners']['render']['source'].=$this->afExtjs->asVar('field.setValue('.json_encode($this->selected_values).');');
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
