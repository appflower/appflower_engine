<?php
/**
 * extJs Portal Column
 *
 */
class afExtjsPortalColumn
{
	/**
	 * default attributes for the column
	 */
	public $attributes=array('columnWidth'  => '0.33',
							'style'=>'padding:10px 0 10px 10px;',);
	
	public $afExtjs=null;	
							
	public function __construct($attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();

		if(count($this->attributes)>0)
		$attributes=array_merge($this->attributes,$attributes);
		
		if(isset($attributes['width']))
		{		
			$this->attributes['columnWidth']=$attributes['width'];
			
			unset($attributes['width']);
		}		
		
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
		
	public function addItem($attributes=array())
	{
		if(!isset($this->attributes['items']))
		$this->attributes['items']=array();
		
		if(is_array($attributes))
		{					
			if(isset($attributes['tools']))
			{
				$attributes['tools']=$attributes['tools']->end();
			}
			
			if(!isset($attributes['listeners']))
			$attributes['listeners']=array();
			
			if(isset($attributes['handlers']))
			{
				$attributes['listeners']=array_merge($attributes['listeners'],$attributes['handlers']);
				
				unset($attributes['handlers']);
			}
			
			if(isset($attributes['listeners'])&&count($attributes['listeners'])>0)
			{
				foreach ($attributes['listeners'] as $type=>$type_params)
				{
					/**
					 * if listener function source is not an array then the string is used directly
					 */
					if (isset($type_params['parameters'])&&isset($type_params['source'])&&!is_array($type_params['source']))
					{
						$attributes['listeners'][$type]=$this->afExtjs->asMethod(array('parameters'=>$type_params['parameters'],'source'=>$type_params['source']));
					}
				}
			}		
			
			if(count($attributes)>0)
			array_push($this->attributes['items'],$this->afExtjs->asAnonymousClass($attributes));
		}
		elseif (is_object($attributes))
		{
			array_push($this->attributes['items'],$this->afExtjs->asVar($attributes->privateName));
		}
	}
	
	public function end()
	{
		return $this->afExtjs->asAnonymousClass($this->attributes);
	}
}
?>