<?php
/**
 * extJs Tools
 */
class ImmExtjsTools
{
	public $attributes=array('items'=>array());
	
	public $immExtjs=null;	
	
	public function __construct()
	{		
		$this->immExtjs=ImmExtjs::getInstance();
	}
	
	public function addItem($attributes=array())
	{
		if(isset($attributes['handler'])&&isset($attributes['handler']['source']))
		{
			$attributes['handler']=$this->immExtjs->asMethod(
						array(
			          		'parameters'=>(isset($attributes['handler']['parameters'])?$attributes['handler']['parameters']:''),
			          		'source'=>$attributes['handler']['source']
		          		));
		          		
		}
		
		$this->attributes['items'][]=$this->immExtjs->asAnonymousClass($attributes);
	}
	
	public function end()
	{
		return $this->attributes['items'];
	}
}
?>