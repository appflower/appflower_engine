<?php
/**
 * extJs Tools
 */
class afExtjsTools
{
	public $attributes=array('items'=>array());
	
	public $afExtjs=null;	
	
	public function __construct()
	{		
		$this->afExtjs=afExtjs::getInstance();
	}
	
	public function addItem($attributes=array(), $return = "key")
	{
		if(isset($attributes['handler'])&&isset($attributes['handler']['source']))
		{
			$attributes['handler']=$this->afExtjs->asMethod(
						array(
			          		'parameters'=>(isset($attributes['handler']['parameters'])?$attributes['handler']['parameters']:''),
			          		'source'=>$attributes['handler']['source']
		          		));
		          		
		}
		
		$this->attributes['items'][]=$this->afExtjs->asAnonymousClass($attributes);	
		
		$key = sizeof($this->attributes['items'])-1;
		
		return ($return === "key") ? $key : $this->attributes["items"][$key];
		
	}
	
	public function getAsExtJsVar($attributes) {
		return $this->afExtjs->asAnonymousClass($attributes);
	}
	
	
	public function end()
	{
		return $this->attributes['items'];
	}
}
?>