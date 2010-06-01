<?php
/**
 * extJs Panel layout
 *
 */
class ImmExtjsPanelLayout extends ImmExtjsLayout
{	
	public function addSouthComponent($tools=false,$attributes=array())
	{	
		$attributes=array('id'=>'south_panel',
					      'title'=>isset($attributes['title'])?$attributes['title']:' ',
					      'height'=>'150',
					      'minHeight'=>'0',
					      'split'=>'true',
					      'collapsible'=>'true',
				          'tools'=>($tools?$tools->end():''));
		
		
		if(isset($this->attributes['viewport']['south_panel'])&&count($this->attributes['viewport']['south_panel'])>0)
		$attributes=array_merge($attributes,$this->attributes['viewport']['south_panel']);
				          
		$this->addPanel('south',$attributes);		
	}
	
	public function addCenterComponent($tools,$attributes=array())
	{	
		$attributes=array('id'=>'center_panel',
					      'title'=>isset($attributes['title'])?$attributes['title']:'Panel',
					      'autoScroll'=>true,
					      'width'=>'auto',
				          'frame'=>true,
				         // 'collapsible'=>true,
				          'style'=>'padding-right:5px;',
				          'tools'=>$tools->end(),
				          'idxml'=>isset($attributes['idxml'])?$attributes['idxml']:false,
		);
		
		
		if(isset($this->attributes['viewport']['center_panel'])&&count($this->attributes['viewport']['center_panel'])>0)
		$attributes=array_merge($attributes,$this->attributes['viewport']['center_panel']);
				          
		$this->addPanel('center',$attributes);
		
	}
	
	public function end()
	{
		$this->addSouthComponent();
		
		sfProjectConfiguration::getActive()->loadHelpers(array('ImmExtjsWest'));
		
		parent::end();
	}
}
?>