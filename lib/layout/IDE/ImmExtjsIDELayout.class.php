<?php
/**
 * extJs IDE layout
 *
 */
class ImmExtjsIDELayout extends ImmExtjsLayout
{
	public function start($attributes=array())
	{
		$attributes['toolbar']=false;
		$attributes['north']=false;
		$attributes['south']=false;
		$attributes['west']=false;
		
		$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'codepress/Ext.ux.CodePress.js')));
				
		parent::start($attributes);
	}
	
	public function addEastComponent($attributes=array())
	{	
		$attributes=array('id'=>'east_panel',
					      'title'=>isset($attributes['title'])?$attributes['title']:'Navigation',
					      'width'=>'200',
					      'split'=>'true',
					      'collapsible'=>'true',
					      'layout'=>'accordion');
		
		
		if(isset($this->attributes['viewport']['east_panel'])&&count($this->attributes['viewport']['east_panel'])>0)
		$attributes=array_merge($attributes,$this->attributes['viewport']['east_panel']);
				          
		$this->addPanel('east',$attributes);
		
	}
	
	public function addCenterComponent($tools,$attributes=array())
	{	
		$attributes=array('id'=>'center_panel',
					      'enableTabScroll'=>true,
					      'width'=>'100%',
				          'resizeTabs'=>true,
				          'minTabWidth'=>115,
				          'tabWidth'=>135,
					      'frame'=>false,
				          'collapsible'=>false,
				          'style'=>'',
				          'defaults'=>$this->immExtjs->asAnonymousClass(array('autoScroll'=>true)),
				          'tools'=>$tools->end(),
				          'plugins'=>$this->immExtjs->asVar('new Ext.ux.TabMenu()'),
				          'listeners'=>array(
				          	'remove'=>$this->immExtjs->asMethod(array(
				          	      	'parameters'=>'tabPanel,tab',
				          	      	'source'=>"if(tabPanel.items.items.length==0){tabPanel.add({'title':'No file','closable':true,'path':'nofile'}).show();}")),
				          	 'beforetabchange'=>$this->immExtjs->asMethod(array(
				          	      	'parameters'=>'tabPanel,newTab,oldTab',
				          	      	'source'=>"if(oldTab&&oldTab.iframe){oldTab.toggleIframe();}if(newTab&&newTab.iframe){newTab.toggleIframe();}"))
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