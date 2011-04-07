<?php
/**
 * extJs Toolbar Menu
 */
class afExtjsToolbarMenu
{
	public $attributes=array();
	
	public $afExtjs=null;	
	public $containerObject=null;		
							
	public function __construct($containerObject)
	{		
		$this->afExtjs=afExtjs::getInstance();
		
		$this->containerObject=$containerObject;
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
		if(!isset($this->attributes['url']) && !isset($this->attributes['handler']))
		$this->attributes['ignoreParentClicks'] = true;	
		$this->containerObject->addMember($this->attributes);
	}
}
?>