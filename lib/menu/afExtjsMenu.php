<?php
/**
 * extJs Menu
 */
class afExtjsMenu
{
	public $attributes=array();
	
	public $afExtjs=null;		
							
	public function __construct($attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
		
		if(count($attributes))
		$this->attributes=array_merge($this->attributes,$attributes);
		$this->privateName='menu_'.Util::makeRandomKey();
	}
	
	public function addMember($item)
	{
		if(isset($item['separator']))
		{
			$this->attributes['items'][]=$this->afExtjs->asVar("'-'");		
		}
		else {
			$this->attributes['items'][]=$item;		
		}
	}
	
	public function end()
	{			
		$this->afExtjs->private[$this->privateName]=$this->afExtjs->Menu($this->attributes);			
	}
}
?>