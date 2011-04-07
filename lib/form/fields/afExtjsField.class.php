<?php
/**
 * extJs Form Field
 */
class afExtjsField
{
	public $attributes=array();
	
	public $privateName=null;
	public $afExtjs=null;	
	public $containerObject=null;
	public $commentHtml=false;					
							
	public function __construct($containerObject,$attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
		
		if($this->privateName==null)
		{
			$this->privateName='field_'.Util::makeRandomKey();
		}
		if(isset($attributes['break'])) unset($attributes['break']);
		$this->containerObject=$containerObject;
		
		$this->afExtjs->setAddons(array('js' => array($this->afExtjs->getExamplesDir().'form/Ext.ux.plugins.HelpText.js') ));
		$this->attributes['plugins'][]="Ext.ux.plugins.HelpText";		
		
		//$this->attributes['labelStyle']='width:100px;font-size:11px;font-weight:bold;padding:0 3px 3px 0;';
		
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

		if(isset($attributes['name']))
		{
			$this->attributes['name']=$attributes['name'];
		}
				
		$this->attributes['id']=$this->privateName;
		
		if(isset($attributes['label']))
		{
			$this->attributes['fieldLabel']=$attributes['label'];
			
			unset($attributes['label']);
		}
		
		if(isset($attributes['value'])&&$attributes['value']!=null)
		{
			$this->attributes['value']=$attributes['value'];
			
			unset($attributes['value']);
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
						$this->attributes['listeners'][$type]=(array('parameters'=>$type_params['parameters'],'source'=>$type_params['source'].((isset($attributes['handlers'][$type])) ? $attributes['handlers'][$type]['source'] : "")));
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
		
		/**
		 * TYPES:
		 * comment || inline
		 */
		$this->attributes['helpType']=isset($attributes['helpType'])?$attributes['helpType']:'comment';
		
		if(isset($attributes['comment'])&&$attributes['comment']!='')
		{
			$this->attributes['helpText']='<i>'.$attributes['comment'].'</i>';
			
			unset($attributes['comment']);			
		}
		
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
		
		//process the field and attach it to the current containerObject
		$this->end();
	}
	
	public function end()
	{
		if(isset($this->attributes['listeners'])&&count($this->attributes['listeners'])>0)
		{
			foreach ($this->attributes['listeners'] as $type=>$type_params)
			{
				/**
				 * if listener function source is not an array then the string is used directly
				 */
				if (isset($type_params['parameters'])&&isset($type_params['source'])&&!is_array($type_params['source']))
				{
					$this->attributes['listeners'][$type]=$this->afExtjs->asMethod(array('parameters'=>$type_params['parameters'],'source'=>$type_params['source']));
				}
			}
		}
						
		if(count($this->attributes)>0)		
		$this->containerObject->addMember($this->attributes);
	}
}
?>