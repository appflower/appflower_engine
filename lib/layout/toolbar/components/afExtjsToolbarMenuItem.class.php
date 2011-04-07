<?php
/**
 * extJs Toolbar Menu Item
 */
class afExtjsToolbarMenuItem extends afExtjsToolbarComponent
{
	public $attributes=array();
	
	public $afExtjs=null;	
	public $containerObject=null;		
							
	public function __construct($containerObject,$attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
		
		if(isset($attributes['label']))
		{
			$this->attributes['text']=$attributes['label'];
			
			unset($attributes['label']);
		}
		
		$attributes['load'] = isset($attributes['load'])?$attributes['load']:'center';
		
		if(isset($attributes['url']))
		{			
			$this->attributes['handler']=$this->afExtjs->asMethod(array(
  									'parameters'=>'b,e',
									'source'=>"if(!e.ctrlKey&&!e.metaKey){afApp.load(\"".$attributes['url']."\",\"".$attributes['load']."\");}else{afApp.widgetPopup(\"".$attributes['url']."\");}"
  								));
			unset($attributes['url']);
		}
		if(isset($attributes['source']))
		{			
			$this->attributes['handler']=$this->afExtjs->asMethod(array(
				'parameters'=>'',
				'source'=>$attributes["source"]
			));
		}
		
		parent::__construct($containerObject,$attributes);
	}
	
	public function addMember($item)
	{
		$this->attributes['menu']=$this->afExtjs->asAnonymousClass($item);		
	}
}
?>
