<?php
/**
 * extJs Form Columns
 *
 */
class afExtjsFormColumns
{
	/**
	 * default attributes for the columns
	 */
	public $attributes=array('layout'=>'column',
							'xtype'=> 'panel',
							'autoWidth'=>true,
                    		'border'=> false,
                    		'anchor'=>'100%',
							'autoHeight'=>true,
	  						'items'=>array());
	
	public $afExtjs=null;						
							
	public function __construct($attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();

		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);	
	}
	
	public function addMember($item)
	{
		$this->attributes['items'][]=$this->afExtjs->asAnonymousClass($item);		
	}
	
	public function startColumn($attributes=array())
	{
		if(!isset($attributes['columnWidth']))
		$attributes['columnWidth']=.5;
		
		return new afExtjsFormColumn($attributes);
	}
	
	public function endColumn($columnObj)
	{
		$this->attributes['items'][]=$columnObj->end();
	}
	
	public function end()
	{
		return $this->afExtjs->asAnonymousClass($this->attributes);
	}	
}
?>