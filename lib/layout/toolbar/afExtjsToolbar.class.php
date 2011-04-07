<?php
/**
 * extJs toolbar
 *
 */
class afExtjsToolbar 
{
	/**
	 * default attributes for the toolbar
	 */
	public $attributes=array();
	
	public $afExtjs=null;
								
	public function __construct($attributes=array('id'=>'toolbar'))
	{		
		$this->afExtjs=afExtjs::getInstance();
		
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
		
	public function addMember($item)
	{
		$this->attributes['items'][]=$this->afExtjs->asAnonymousClass($item);		
	}
	
	public function end()
	{
		$this->afExtjs->private['toolbar']=$this->afExtjs->Toolbar($this->attributes);
	}
}
?>