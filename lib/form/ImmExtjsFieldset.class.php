<?php
/**
 * extJs Form Fieldset
 *
 */
class ImmExtjsFieldset
{
	/**
	 * default attributes for the fieldset
	 */
	public $attributes=array('collapsible'=>'true',
	  						'autoHeight'=>'true',
							'layout'=>'fit', //this creates an issue on login screen!							
	  						'width'=>'98%');
	
	public $immExtjs=null;						
							
	public function __construct($attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();

		if(count($this->attributes)>0)
		$attributes=array_merge($this->attributes,$attributes);
		
		if(isset($attributes['legend']))
		{		
			$this->attributes['title']=$attributes['legend'];
			unset($attributes['legend']);
		}

		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);	
	}
	
	public function startColumns()
	{
		return new ImmExtjsFormColumns();		
	}
	
	public function endColumns($columnsObj)
	{
		$this->attributes['items'][]=$columnsObj->end();
	}
	
	public function addMember($item)
	{
		$this->attributes['items'][]=$this->immExtjs->asAnonymousClass($item);		
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
		if(count($this->attributes['items'])>0)
		$this->attributes['items']=array_diff($this->attributes['items'],array(null));
		
		return $this->immExtjs->FieldSet($this->attributes);
	}	
}
?>