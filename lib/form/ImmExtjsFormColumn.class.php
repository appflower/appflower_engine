<?php
/**
 * extJs Form Column
 *
 */
class ImmExtjsFormColumn
{
	/**
	 * default attributes for the column
	 */
	public $attributes=array('layout'=>'form','autoHeight'=>'true');
	
	public $immExtjs=null;						
							
	public function __construct($attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);	
	}
	
	public function addMember($item)
	{
		if(is_array($item))
		{
			$this->attributes['items'][]=$this->immExtjs->asAnonymousClass($item);
		}
		elseif(is_object($item)) {
			$this->attributes['items'][]=$this->immExtjs->asVar($item->privateName);
		}
		
		//$this->attributes['items'][]=$this->immExtjs->asAnonymousClass($item);		
	}
	
	public function startGroup($type,$attributes=array())
	{
		$class='ImmExtjsField'.ucfirst($type).'Group';
		return new $class($this,$attributes);
	}
	
	public function endGroup($groupObj)
	{
		$this->attributes['items'][]=$groupObj->end();
	}
	
	public function end()
	{
		return $this->immExtjs->asAnonymousClass($this->attributes);
	}	
}
?>