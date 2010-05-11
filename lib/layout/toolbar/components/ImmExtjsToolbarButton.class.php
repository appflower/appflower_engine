<?php
/**
 * extJs Toolbar Button
 */
class ImmExtjsToolbarButton extends ImmExtjsToolbarComponent
{
	public $attributes=array();
	
	public $immExtjs=null;	
	public $containerObject=null;		
							
	public function __construct($containerObject,$attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		
		//$this->attributes['xtype']='tbbutton';
		
		if(isset($attributes['label']))
		{
			$this->attributes['text']=$attributes['label'];
			
			unset($attributes['label']);
		}
		
		if(isset($attributes['url']))
		{
			$this->attributes['handler']=$this->immExtjs->asMethod(array(
  									'parameters'=>'',
  									'source'=>'afApp.loadCenterWidget("'.$attributes['url'].'");'
  								));
			
			unset($attributes['url']);
		}
		
		if(isset($attributes['handler']))
		{
			$this->attributes['handler']=$this->immExtjs->asMethod(array(
  									'parameters'=>'',
  									'source'=>$attributes['handler']
  								));
			
			unset($attributes['handler']);
		}
		
		if(isset($attributes['tooltip']))
		{
			if(isset($attributes['tooltip']['text']))
			{
				$this->attributes['tooltip']['text']=$attributes['tooltip']['text'];
				
				if(isset($attributes['tooltip']['title']))
				{
					$this->attributes['tooltip']['title']=$attributes['tooltip']['title'];
				}
			}
			
			unset($attributes['tooltip']);
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
		
		parent::__construct($containerObject,$attributes);
	}
	
	public function addMember($item)
	{
		$this->attributes['menu']=$this->immExtjs->asAnonymousClass($item);		
	}
}
?>