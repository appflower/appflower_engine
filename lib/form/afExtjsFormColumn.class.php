<?php
/**
 * extJs Form Column
 *
 */
class afExtjsFormColumn
{
	/**
	 * default attributes for the column
	 */
	public $attributes=array('layout'=>'form','autoHeight'=>'true');
	
	public $afExtjs=null;						
							
	public function __construct($attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
		
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);	
	}
	
	public function addMember($item)
	{
		if(is_array($item))
		{
			$this->attributes['items'][]=$this->afExtjs->asAnonymousClass($item);
		}
		elseif(is_object($item)) {
			$this->attributes['items'][]=$this->afExtjs->asVar($item->privateName);
		}
		
		//$this->attributes['items'][]=$this->afExtjs->asAnonymousClass($item);		
	}
	
	public function startGroup($type,$attributes=array())
	{
		$class='afExtjsField'.ucfirst($type).'Group';
		return new $class($this,$attributes);
	}
	
	public function endGroup($groupObj)
	{
		$this->attributes['items'][]=$groupObj->end();
	}
	
	public function end()
	{
		return $this->afExtjs->asAnonymousClass($this->attributes);
	}	
}
?>