<?php
/**
 * extJs sfGuard layout
 *
 */
class ImmExtjsSfGuardLayout extends ImmExtjsLayout
{
	public function start($attributes=array())
	{
		$attributes['toolbar']=false;
		$attributes['north']=false;
		$attributes['west']=false;
		
		$this->immExtjs->setAddons(array ('css' => array($this->immExtjs->getExamplesDir().'layout-browser/Ext.ux.layout.CenterLayout.css'),'js'=>array($this->immExtjs->getExamplesDir().'layout-browser/Ext.ux.layout.CenterLayout.js')));		
		$this->layout='ux.center';
		
		parent::start($attributes);
	}
	
	public function addCenterComponent($tools,$attributes=array())
	{	
		//print sfConfig::get("app_avatar_login_screen_bg");		
		$attributes=array('id'=>'center_panel',
					      'title'=>isset($attributes['title'])?$attributes['title']:'Panel',
					      'autoScroll'=>true,
					      'width'=>'300px',
					      'frame'=>true,
						  'collapsible'=>false,
				          'style'=>'margin-top:150px;',
						  'listeners'=>'{"render":function(comp){						
							  		Ext.getBody().setStyle("background-image","url('.sfConfig::get("app_avatar_login_screen_bg").')");
							  		Ext.getBody().setStyle("background-position","top center");
						   }}',
				          'tools'=>$tools->end());
				
		if(isset($this->attributes['viewport']['center_panel'])&&count($this->attributes['viewport']['center_panel'])>0)
		$attributes=array_merge($attributes,$this->attributes['viewport']['center_panel']);
				          
		$this->addPanel('center',$attributes);
		
	}
	
	public function end()
	{
		parent::end();
	}
}
?>
