<?php
/**
 * extJs sfGuard layout
 *
 */
class afExtjsSfGuardLayout extends afExtjsViewportLayout
{
	public function start($attributes=array())
	{		
		$attributes['toolbar']=false;
		$attributes['north']=false;
		$attributes['west']=false;
		
		$this->afExtjs->setAddons(array ('css' => array($this->afExtjs->getPluginsDir().'layout-browser/Ext.ux.layout.CenterLayout.css'),'js'=>array($this->afExtjs->getPluginsDir().'layout-browser/Ext.ux.layout.CenterLayout.js')));		
		$this->layout='ux.center';
		
		parent::start($attributes);
	}
	
	public function addCenterComponent($tools,$attributes=array())
	{	
		$bgScript = '';
		if(sfConfig::get("app_avatar_login_screen_bg",false)){
			$bgScript = 'Ext.getBody().setStyle("background-image","url('.sfConfig::get("app_avatar_login_screen_bg").')");	Ext.getBody().setStyle("background-position","top center");';
		}	
		$attributes=array('id'=>'center_panel',
					      'title'=>isset($attributes['title'])?$attributes['title']:'Panel',
					      'autoScroll'=>true,
					      'width'=>410,
					      'frame'=>true,
					      'layout'=>'column',
					      'height'=>300,
						  'collapsible'=>false,
				          'style'=>'margin-top:150px;',
						  'listeners'=>'{"render":function(comp){'.$bgScript.'}}',
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
