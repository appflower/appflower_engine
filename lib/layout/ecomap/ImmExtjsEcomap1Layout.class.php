<?php
/**
 * extJs Ecomap 1 layout
 *
 */
class ImmExtjsEcomap1Layout extends ImmExtjsLayout
{
	public function start($attributes=array())
	{
		$attributes['south']=false;
		$attributes['west']=false;
		
		$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'tabs/TabCloseMenu.js')));
		
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
				          'defaults'=>$this->immExtjs->asAnonymousClass(array('autoScroll'=>true)),
				          'tools'=>$tools->end(),
				          'plugins'=>$this->immExtjs->asVar('new Ext.ux.TabCloseMenu()'),
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
				          'defaults'=>$this->immExtjs->asAnonymousClass(array('autoScroll'=>true)),
				          'tools'=>$tools->end(),
				          'plugins'=>$this->immExtjs->asVar('new Ext.ux.TabCloseMenu()'),
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