<?php
/**
 * extJs Gmap Panel
 *
 */
class ImmExtjsGmap 
{
	/**
	 * default attributes
	 */
	public $attributes=array('zoomLevel'=>7,
							 'gmapType'=>'map');
	
	public $immExtjs=null;	
	public $privateName=null;
							
	public function __construct($attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		
		//$this->privateName='gmap_'.Util::makeRandomKey();
		$this->privateName='gmap';
		
		$this->attributes['name']=$this->privateName;
		$this->attributes['id']=$this->privateName;
		
		$this->attributes['mapConfOpts']=array('enableScrollWheelZoom','enableDoubleClickZoom','enableDragging');
		$this->attributes['mapControls']=array('GSmallMapControl', 'GScaleControl','GMapTypeControl','NonExistantControl');
		
		$this->immExtjs->setAddons(array ('js' => array($this->immExtjs->getExamplesDir().'window/Ext.ux.GMapPanel.js','http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key='.sfConfig::get('app_gmaps_key')) ));
				
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
		$attributes['listeners']['click']=$this->immExtjs->asMethod(array('parameters'=>'point','source'=>'this.getMap().setCenter(point, this.zoomLevel);'));
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
			$centerMarker['marker']=$this->immExtjs->asAnonymousClass($centerMarker['marker']);
			$this->attributes['setCenter']=$this->immExtjs->asAnonymousClass($centerMarker);
			
			/*$markers=$this->attributes['markers'];
			unset($this->attributes['markers']);
						
			foreach ($markers as $k=>$attributes)
			{
				if(isset($markers[$k]['marker']))
				$markers[$k]['marker']=$this->immExtjs->asAnonymousClass($markers[$k]['marker']);
				$this->attributes['markers'][$k]=$this->immExtjs->asAnonymousClass($markers[$k]);
			}*/
		}
		
		$this->immExtjs->private[$this->privateName]=$this->immExtjs->GmapPanel($this->attributes);
	}
}
?>
