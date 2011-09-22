<?php
/**
 * extJs Desktop Start Menu, Configuration setup
 */
class afExtjsStartMenu
{
	public $attributes=array(), 
	       $containerObject=null;
	
	public $afExtjs=null;		
							
	public function __construct($attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
		
		if(!is_object($attributes))
		{
			$this->attributes=array_merge($this->attributes,$attributes);	
		}
		else {
			$this->containerObject = $attributes;
		}
		
		$this->privateName='startMenuConfig';
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
		if($this->containerObject)
		{
			$this->containerObject->addMember($this->attributes);
		}
		else {
			$this->afExtjs->private[$this->privateName]=$this->afExtjs->asAnonymousClass($this->attributes);	
		}
	}
}
?>