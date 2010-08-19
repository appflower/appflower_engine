<?php
/**
 * extJs Toolbar Menu Item
 */
class ImmExtjsToolbarMenuItem extends ImmExtjsToolbarComponent
{
	public $attributes=array();
	
	public $immExtjs=null;	
	public $containerObject=null;		
							
	public function __construct($containerObject,$attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		
		if(isset($attributes['label']))
		{
			$this->attributes['text']=$attributes['label'];
			
			unset($attributes['label']);
		}
		
		$attributes['load'] = isset($attributes['load'])?$attributes['load']:'center';
		
		if(isset($attributes['url']))
		{			
			$this->attributes['handler']=$this->immExtjs->asMethod(array(
  									'parameters'=>'b,e',
									'source'=>"if(!e.ctrlKey&&!e.metaKey){afApp.load(\"".$attributes['url']."\",\"".$attributes['load']."\");}else{afApp.widgetPopup(\"".$attributes['url']."\");}"
  								));
			unset($attributes['url']);
		}
		if(isset($attributes['source']))
		{			
			$this->attributes['handler']=$this->immExtjs->asMethod(array(
				'parameters'=>'',
				'source'=>$attributes["source"]
			));
		}
		
		parent::__construct($containerObject,$attributes);
	}
	
	public function addMember($item)
	{
		$this->attributes['menu']=$this->immExtjs->asAnonymousClass($item);		
	}
}
?>
