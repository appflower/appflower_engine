<?php
/**
 * extJs Ecomap 1 layout
 *
 */
class afExtjsEcomap1Layout extends afExtjsViewportLayout
{
	public function start($attributes=array())
	{
		$attributes['south']=false;
		$attributes['west']=false;
		
		$this->afExtjs->setAddons(array('js'=>array($this->afExtjs->getPluginsDir().'tabs/TabCloseMenu.js')));
		
		parent::start($attributes);
	}
	
	public function addEastComponent($tools,$attributes=array())
	{	
		$attributes=array('id'=>'east_panel',
					      'enableTabScroll'=>true,
					      'width'=>'200',
				          'resizeTabs'=>true,
				          'minTabWidth'=>35,
				          'tabWidth'=>35,
					      'frame'=>true,
				          'collapsible'=>false,
				          'style'=>'',
				          'defaults'=>$this->afExtjs->asAnonymousClass(array('autoScroll'=>true)),
				          'tools'=>$tools->end(),
				          'plugins'=>$this->afExtjs->asVar('new Ext.ux.TabCloseMenu()'),
				          'listeners'=>array(
				          	
				          ));
				
		if(isset($this->attributes['viewport']['east_panel'])&&count($this->attributes['viewport']['east_panel'])>0)
		$attributes=array_merge($attributes,$this->attributes['viewport']['east_panel']);
				          
		$this->addTabPanel('east',$attributes);
		
	}
	
	public function addCenterComponent($tools,$attributes=array())
	{	
		$attributes=array('id'=>'center_panel',
					      'enableTabScroll'=>true,
					      'resizeTabs'=>true,
				          'minTabWidth'=>115,
				          'tabWidth'=>135,
					      'frame'=>true,
				          'collapsible'=>false,
				          'style'=>'',
				          'defaults'=>$this->afExtjs->asAnonymousClass(array('autoScroll'=>true)),
				          'tools'=>$tools->end(),
				          'plugins'=>$this->afExtjs->asVar('new Ext.ux.TabCloseMenu()'),
				          'listeners'=>array(
				          	
				          ));
				
		if(isset($this->attributes['viewport']['center_panel'])&&count($this->attributes['viewport']['center_panel'])>0)
		$attributes=array_merge($attributes,$this->attributes['viewport']['center_panel']);
				          
		$this->addTabPanel('center',$attributes);
		
	}
	
	public function end()
	{
		parent::end();
	}
}
?>