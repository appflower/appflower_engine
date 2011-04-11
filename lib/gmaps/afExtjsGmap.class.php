<?php
/**
 * extJs Gmap Panel
 *
 */
class afExtjsGmap 
{
	/**
	 * default attributes
	 */
	public $attributes=array('zoomLevel'=>7,
							 'gmapType'=>'map');
	
	public $afExtjs=null;	
	public $privateName=null;
							
	public function __construct($attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
		
		//$this->privateName='gmap_'.Util::makeRandomKey();
		$this->privateName='gmap';
		
		$this->attributes['name']=$this->privateName;
		$this->attributes['id']=$this->privateName;
		
		$this->attributes['mapConfOpts']=array('enableScrollWheelZoom','enableDoubleClickZoom','enableDragging');
		$this->attributes['mapControls']=array('GSmallMapControl', 'GScaleControl','GMapTypeControl','NonExistantControl');
		
		$this->afExtjs->setAddons(array ('js' => array($this->afExtjs->getPluginsDir().'window/Ext.ux.GMapPanel.js') ));
				
		if(isset($attributes['tools']))
		{
			$this->attributes['tools']=$attributes['tools']->end();
			
			unset($attributes['tools']);
		}
		
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
	
	public function addMarker($attributes=array())
	{
		$attributes['listeners']['click']=$this->afExtjs->asMethod(array('parameters'=>'point','source'=>'this.getMap().setCenter(point, this.zoomLevel);'));
		$this->attributes['markers'][]=$attributes;
	}
	
	public function end()
	{				
		if(isset($this->attributes['markers'])&&$this->attributes['markers']>0)
		{
			$isCenterSet=false;
			
			foreach ($this->attributes['markers'] as $k=>$attributes)
			{
				if(isset($attributes['isCenter'])&&$attributes['isCenter'])
				{
					$centerK=$k;
					$isCenterSet=true;
					break;
				}
			}
			
			if(!$isCenterSet)
			{
				$centerK=0;
			}
			
			$centerMarker=$this->attributes['markers'][$centerK];
			unset($this->attributes['markers'][$centerK]);
			
			if(isset($centerMarker['marker']))
			$centerMarker['marker']=$this->afExtjs->asAnonymousClass($centerMarker['marker']);
			$this->attributes['setCenter']=$this->afExtjs->asAnonymousClass($centerMarker);
			
			/*$markers=$this->attributes['markers'];
			unset($this->attributes['markers']);
						
			foreach ($markers as $k=>$attributes)
			{
				if(isset($markers[$k]['marker']))
				$markers[$k]['marker']=$this->afExtjs->asAnonymousClass($markers[$k]['marker']);
				$this->attributes['markers'][$k]=$this->afExtjs->asAnonymousClass($markers[$k]);
			}*/
		}
		
		//print_r($this->attributes);
		
		$this->afExtjs->private[$this->privateName]=$this->afExtjs->GmapPanel($this->attributes);
	}
}
?>
