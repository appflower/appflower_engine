<?php
/**
 * extJs Menu
 */
class ImmExtjsMenu
{
	public $attributes=array();
	
	public $immExtjs=null;		
							
	public function __construct($attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		
		if(count($attributes))
		$this->attributes=array_merge($this->attributes,$attributes);
		$this->privateName='menu_'.Util::makeRandomKey();
	}
	
	public function addMember($item)
	{
		if(isset($item['separator']))
		{
			$this->attributes['items'][]=$this->immExtjs->asVar("'-'");		
		}
		else {
			$this->attributes['items'][]=$item;		
		}
	}
	
	public function end()
	{			
		$this->immExtjs->private[$this->privateName]=$this->immExtjs->Menu($this->attributes);			
	}
}
?>