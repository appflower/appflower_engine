<?php
/**
 * extJs Tree Node
 */
class afExtjsTreeNode
{
	public $attributes=array();
	
	public $afExtjs=null;	
	public $containerObject=null;
	public $children=null;
		
	public function __construct($containerObject,$attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
		$this->containerObject=$containerObject;
				
		if(isset($attributes['href']))
		{
			$this->attributes['leaf']=true;
			$this->attributes['icon']='/images/famfamfam/link.png';
		}
		
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
	
	public function addChild($attributes=array())
	{
		$child=new afExtjsTreeNode($this,$attributes);
		$this->children[]=$child;
		
		return $child;
	}
	
	public function end()
	{
		if(get_class($this->containerObject)=='afExtjsTreeNode')
		{
			$this->containerObject->attributes['children'][]=$this->afExtjs->asAnonymousClass($this->attributes);
		}
		else {
			return $this->afExtjs->asAnonymousClass($this->attributes);		
		}
	}
}
?>