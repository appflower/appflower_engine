<?php
/**
 * extJs Panel layout
 *
 */
class afExtjsPanelLayout extends afExtjsViewportLayout
{	
	public function addSouthComponent($tools=false,$attributes=array())
	{	
		$attributes=array('id'=>'south_panel',
					      'title'=>isset($attributes['title'])?$attributes['title']:' ',
					      'height'=>'150',
					      'minHeight'=>'0',
					      'split'=>'true',
					      'collapsible'=>'true',
				          'tools'=>($tools?$tools->end():''),
						  'idxml'=>isset($attributes['idxml'])?$attributes['idxml']:false,
		);
		
		
		if(isset($this->attributes['viewport']['south_panel'])&&count($this->attributes['viewport']['south_panel'])>0)
		$attributes=array_merge($attributes,$this->attributes['viewport']['south_panel']);
				          
		if(isset($attributes['items'])&&count($attributes['items'])>0)
		$this->addPanel('south',$attributes);		
	}
	
	public function addCenterComponent($tools,$attributes=array())
	{	    
		if($this->showFullCenter())
		{		    		    
			if(!afExtjs::getInstance()->isDesktop())
		    {
		        $attributes['title'] = isset($attributes['title'])?$attributes['title']:'Panel';
		        $attributes['tools']=$tools->end();
		    }
		    else {
		        $attributes['winTitle'] = isset($attributes['title'])?$attributes['title']:'Panel';
		        unset($attributes['tools']);
		    }
		    
		    $attributes=array_merge($attributes,array('id'=>'center_panel_first_panel',
						      'autoScroll'=>true,
						      'width'=>'auto',
					          'frame'=>true,
					          'collapsible'=>true,
					          'style'=>'padding-right:0px;',					          
							  'idxml'=>isset($attributes['idxml'])?$attributes['idxml']:false,
			));
			
			$attributes['plugins'][] = 'new Ext.ux.MaximizeTool()';
			if(isset($this->attributes['viewport']['center_panel'])&&count($this->attributes['viewport']['center_panel'])>0)
			$attributes=array_merge($attributes,$this->attributes['viewport']['center_panel']);

			$this->afExtjs->privateAttributes['center_panel_first_panel']=$attributes;
			$this->afExtjs->private['center_panel_first_panel']=$this->afExtjs->Panel($attributes);
					
			$attributesPanel['items'][]=$this->afExtjs->asVar('center_panel_first_panel');
			$attributesPanel['border']=false;
			$attributesPanel['bodyBorder']=false;
			$attributesPanel['layout']='fit';
							
			$attributesPanel['id']='center_panel_first';
			$this->afExtjs->private['center_panel_first']=$this->afExtjs->Panel($attributesPanel);
			
			$attributesPanelContainer['items'][]=$this->afExtjs->asVar('center_panel_first');
			$attributesPanelContainer['style']='padding-right:5px;';
			$attributesPanelContainer['border']=false;
			$attributesPanelContainer['bodyBorder']=false;
			$attributesPanelContainer['layout']='fit';
			$attributesPanelContainer['id']='center_panel';         	      	
			
			$this->addPanel('center',$attributesPanelContainer);
		}
	}
	
	public function end()
	{
		$this->addSouthComponent();
		
        if(!sfConfig::get('app_parser_skip_west')){
    		sfProjectConfiguration::getActive()->loadHelpers(array('afExtjsWest'));
        }
		
		parent::end();
	}
}
?>