<?php
/**
 * extJs Form Field Double Tree
 */
class afExtjsFieldDoubleTree extends afExtjsField
{
	public function __construct($containerObject,$attributes=array())
	{	
		$this->afExtjs=afExtjs::getInstance();
		
		$this->attributes['xtype']='treeitemselector';

		$this->attributes['imagePath']=$this->afExtjs->getExamplesDir().'multiselect/images/';
		
		if(isset($attributes['fromLegend']))
		{
			$this->attributes['fromRootText']=$attributes['fromLegend'];
			
			unset($attributes['fromLegend']);
		}
		
		if(isset($attributes['toLegend']))
		{
			$this->attributes['toRootText']=$attributes['toLegend'];
			
			unset($attributes['toLegend']);
		}
		
		/**
		 * the options attribute, an assoc array for now
		 */
		if(isset($attributes['options']))
		{
			$this->attributes['fromChildren']=$this->afExtjs->asVar(json_encode($attributes['options']));
			
			unset($attributes['options']);
		}
		
		/**
		 * the selected attribute, an assoc array for now
		 */
		if(isset($attributes['selected']))
		{
			$this->attributes['toChildren']=$this->afExtjs->asVar(json_encode($attributes['selected']));
			
			unset($attributes['selected']);
		}
		
		/**
		 * PROCESS state ATTRIBUTE
		 * state can have "disabled" or "editable" values; if state is "readonly", then it's equal to "disabled"
		 */
		$this->processState($attributes);
			
		parent::__construct($containerObject,$attributes);
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