<?php
/**
 * extJs Toolbar Fill
 */
class afExtjsToolbarFill extends afExtjsToolbarComponent
{
	public $attributes=array();
	
	public $afExtjs=null;	
	public $containerObject=null;		
							
	public function __construct($containerObject)
	{		
		$this->afExtjs=afExtjs::getInstance();
		
		$this->attributes['xtype']='tbfill';
		
		parent::__construct($containerObject);
		
		$this->end();
	}
}
?>