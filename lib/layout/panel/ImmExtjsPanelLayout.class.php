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
				          'collapsible'=>true,
				          'style'=>'padding-right:0px;',
				          //'tools'=>$tools->end()
		);
		
		
		if(isset($this->attributes['viewport']['center_panel'])&&count($this->attributes['viewport']['center_panel'])>0)
		$attributes=array_merge($attributes,$this->attributes['viewport']['center_panel']);
				          
		$this->immExtjs->private['center_panel_first_panel']=$this->immExtjs->Panel($attributes);
				
		$attributesPanel['items'][]=$this->immExtjs->asVar('center_panel_first_panel');
		$attributesPanel['border']=false;
		$attributesPanel['bodyBorder']=false;
		$attributesPanel['layout']='fit';
						
		$attributesPanel['id']='center_panel_first';
		$this->immExtjs->private['center_panel_first']=$this->immExtjs->Panel($attributesPanel);
		
		$attributesPanelContainer['items'][]=$this->immExtjs->asVar('center_panel_first');
		$attributesPanelContainer['style']='padding-right:5px;';
		$attributesPanelContainer['border']=false;
		$attributesPanelContainer['bodyBorder']=false;
		$attributesPanelContainer['layout']='fit';
		$attributesPanelContainer['id']='center_panel';         	      	
		
		$this->addPanel('center',$attributesPanelContainer);
	}
	
	public function end()
	{
		$this->addSouthComponent();
		
		sfProjectConfiguration::getActive()->loadHelpers(array('ImmExtjsWest'));
		
		parent::end();
	}
}
?>