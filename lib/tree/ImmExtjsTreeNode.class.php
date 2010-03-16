<?php
/**
 * extJs Tree Node
 */
class ImmExtjsTreeNode
{
	public $attributes=array();
	
	public $immExtjs=null;	
	public $containerObject=null;
	public $children=null;
		
	public function __construct($containerObject,$attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
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
		$child=new ImmExtjsTreeNode($this,$attributes);
		$this->children[]=$child;
		
		return $child;
	}
	
	public function end()
	{
		if(get_class($this->containerObject)=='ImmExtjsTreeNode')
		{
			$this->containerObject->attributes['children'][]=$this->immExtjs->asAnonymousClass($this->attributes);
		}
		else {
			return $this->immExtjs->asAnonymousClass($this->attributes);		
		}
	}
}
?>