<?php
/**
 * extJs Menu Item
 */
class afExtjsMenuItem extends afExtjsToolbarComponent
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
		if(isset($attributes['url']))
		{	
			$param_name = isset($attributes['param'])?$attributes['param']:"param";		
			$url = $attributes['url']."&".$param_name."=";
			$param = $containerObject->privateName.'.stack["text"]';
			$cellDiv = $containerObject->privateName.'.stack["cellDiv"]';
			if(isset($attributes['ajax'])){
				sfProjectConfiguration::getActive()->loadHelpers(array('afExtjsContextMenu'));
				$source = ajax_source($url.'"+'.$param);				
			}else{
				$source = 'window.location.href="'.$url.'"+'.$param;
			}
			$this->attributes['handler']=$this->afExtjs->asMethod(array(
	  				'parameters'=>'',
	  				'source'=>$source
	  		));
			unset($attributes['url']);
			unset($attributes['ajax']);
			unset($attributes['param']);
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