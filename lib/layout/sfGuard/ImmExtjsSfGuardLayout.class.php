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
		$attributes=array('id'=>'center_panel',
					      'title'=>isset($attributes['title'])?$attributes['title']:'Panel',
					      'autoScroll'=>true,
					      'width'=>'300px',
					      'frame'=>true,
						  'collapsible'=>false,
				          'style'=>'margin-top:150px;',
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
