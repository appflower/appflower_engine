<?php
/**
 * extJs Ecomap WestCenterEast Portal Panels layout
 *
 */
class afExtjsWCEPortalLayout extends afExtjsPortalLayout
{
	public function start($attributes=array())
	{
		$attributes['toolbar']=false;
		$attributes['north']=false;
		$attributes['south']=false;
		$attributes['west']=false;
						
		parent::start($attributes);
	}
	
	public function addEastComponent($attributes=array())
	{	
		$attributes=array('id'=>'east_panel',
					      'title'=>isset($attributes['title'])?$attributes['title']:'Navigation East',
					      'width'=>'200',
					      'split'=>'true',
					      'collapsible'=>'true',
					      'layout'=>'accordion');
		
		
		if(isset($this->attributes['viewport']['east_panel'])&&count($this->attributes['viewport']['east_panel'])>0)
		$attributes=array_merge($attributes,$this->attributes['viewport']['east_panel']);
				          
		$this->addPanel('east',$attributes);
		
	}
	
	public function addWestComponent($attributes=array())
	{	
		$attributes=array('id'=>'west_panel',
					      'title'=>isset($attributes['title'])?$attributes['title']:'Navigation West',
					      'width'=>'150',
					      'split'=>'true',
					      'collapsible'=>'true',
					      'layout'=>'accordion');
		
		
		if(isset($this->attributes['viewport']['west_panel'])&&count($this->attributes['viewport']['west_panel'])>0)
		$attributes=array_merge($attributes,$this->attributes['viewport']['west_panel']);
				          
		$this->addPanel('west',$attributes);
		
	}
}
?>