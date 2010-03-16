<?php
/**
 * extJs Toolbar Component
 */
class ImmExtjsToolbarComponent
{
	public $attributes=array();
	
	public $immExtjs=null;	
	public $containerObject=null;		
							
	public function __construct($containerObject,$attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		
		$this->containerObject=$containerObject;

		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
	
	public function end()
	{
		if(isset($this->attributes['listeners'])&&count($this->attributes['listeners'])>0)
		{
			foreach ($this->attributes['listeners'] as $type=>$type_params)
			{
				/**
				 * if listener function source is an array then implode into one string
				 */
				if(isset($type_params['parameters'])&&isset($type_params['source'])&&is_array($type_params['source']))
				{
					$this->attributes['listeners'][$type]=$this->immExtjs->asMethod(array('parameters'=>$type_params['parameters'],'source'=>implode(null,$type_params['source'])));
				}
				/**
				 * if listener function source is not an array then the string is used directly
				 */
				elseif (isset($type_params['parameters'])&&isset($type_params['source'])&&!is_array($type_params['source']))
				{
					$this->attributes['listeners'][$type]=$this->immExtjs->asMethod(array('parameters'=>$type_params['parameters'],'source'=>$type_params['source']));
				}
			}
		}
						
		if(count($this->attributes)>0)		
		{
			//addButton in grid!
			if(method_exists($this->containerObject,'addButton'))
			{
				$this->containerObject->addButton($this->attributes);
			}
			else {
				$this->containerObject->addMember($this->attributes);
			}
		}
	}
}
?>