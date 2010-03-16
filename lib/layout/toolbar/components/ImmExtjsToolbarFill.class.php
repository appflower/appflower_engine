<?php
/**
 * extJs Toolbar Fill
 */
class ImmExtjsToolbarFill extends ImmExtjsToolbarComponent
{
	public $attributes=array();
	
	public $immExtjs=null;	
	public $containerObject=null;		
							
	public function __construct($containerObject)
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		
		$this->attributes['xtype']='tbfill';
		
		parent::__construct($containerObject);
		
		$this->end();
	}
}
?>