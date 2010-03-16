<?php
/**
 * extJs toolbar
 *
 */
class ImmExtjsToolbar 
{
	/**
	 * default attributes for the toolbar
	 */
	public $attributes=array();
	
	public $immExtjs=null;
								
	public function __construct($attributes=array('id'=>'toolbar'))
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
		
	public function addMember($item)
	{
		$this->attributes['items'][]=$this->immExtjs->asAnonymousClass($item);		
	}
	
	public function end()
	{
		$this->immExtjs->private['toolbar']=$this->immExtjs->Toolbar($this->attributes);
	}
}
?>