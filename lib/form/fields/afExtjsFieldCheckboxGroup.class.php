<?php
/**
 * extJs Form Field Checkbox Group
 */
class afExtjsFieldCheckboxGroup extends afExtjsField
{
	public function __construct($fieldsetObject,$attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
		
		$this->containerObject=$fieldsetObject;
		
		$this->attributes['xtype']='checkboxgroup';
				
		if(count($this->attributes)>0)
		$attributes=array_merge($this->attributes,$attributes);
		
		if(isset($attributes['name']))
		unset($attributes['name']);
		
		$this->attributes['labelStyle']='font-size:11px;font-weight:bold;';
		
		if(isset($attributes['state']))
		{		
			switch ($attributes['state'])
			{
				case "readonly":
					$this->attributes['readOnly']=true;	
				break;
				case "disabled":
					$this->attributes['disabled']=true;	
				break;
				case "editable":
					$this->attributes['disabled']=false;	
					$this->attributes['readOnly']=false;
				break;
			}
			
			unset($attributes['state']);
		}
		
		if(isset($attributes['label']))
		{
			$this->attributes['fieldLabel']=$attributes['label'];
			
			unset($attributes['label']);
		}
		
		if(isset($attributes['handlers'])&&$attributes['handlers']!='')
		{
			if(isset($this->attributes['listeners']))
			{
				foreach ($this->attributes['listeners'] as $type=>$type_params)
				{
					/**
					 * if listener function source is not an array then the string is used directly
					 */
					if (isset($type_params['parameters'])&&$type_params['parameters']!=''&&isset($type_params['source'])&&!is_array($type_params['source']))
					{
						$this->attributes['listeners'][$type]=(array('parameters'=>$type_params['parameters'],'source'=>$type_params['source'].$attributes['handlers'][$type]['source']));
					}
				}
			}
			else {
				$this->attributes['listeners']=$attributes['handlers'];
			}
			
			unset($attributes['handlers']);			
		}
		
		if(isset($attributes['help'])&&$attributes['help']!='')
		{
			if(!isset($this->attributes['listeners']['render']['source']))
			$this->attributes['listeners']['render']['source']='';
			
			if(!isset($this->attributes['listeners']['render']['parameters']))
			$this->attributes['listeners']['render']['parameters']='field';			
			
			$this->attributes['listeners']['render']['source'].="new Ext.ToolTip({target: field.getEl(),html: '".$attributes['help']."'});";			
			unset($attributes['help']);
		}
						
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
	
	public function addMember($item)
	{
		$this->attributes['items'][]=$this->afExtjs->asAnonymousClass($item);		
	}
}
?>