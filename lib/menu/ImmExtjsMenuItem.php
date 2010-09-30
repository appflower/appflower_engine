<?php
/**
 * extJs Menu Item
 */
class ImmExtjsMenuItem extends ImmExtjsToolbarComponent
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
		if(isset($attributes['url']))
		{	
			$param_name = isset($attributes['param'])?$attributes['param']:"param";		
			$url = $attributes['url']."&".$param_name."=";
			$param = $containerObject->privateName.'.stack["text"]';
			$cellDiv = $containerObject->privateName.'.stack["cellDiv"]';
			if(isset($attributes['ajax'])){
				sfProjectConfiguration::getActive()->loadHelpers(array('ImmExtjsContextMenu'));
				$source = ajax_source($url.'"+'.$param);				
			}else{
				$source = 'window.location.href="'.$url.'"+'.$param;
			}
			$this->attributes['handler']=$this->immExtjs->asMethod(array(
	  				'parameters'=>'',
	  				'source'=>$source
	  		));
			unset($attributes['url']);
			unset($attributes['ajax']);
			unset($attributes['param']);
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