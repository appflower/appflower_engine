<?php
/**
 * extJs Wizard layout
 *
 */
class afExtjsWizardLayout extends afExtjsViewportLayout
{
    public function start($attributes=array())
	{
		$attributes['toolbar']=false;
		$attributes['north']=false;
		$attributes['west']=false;
				
		parent::start($attributes);
	}
	
	public function startColumn($attributes=array())
	{
		return new afExtjsPortalColumn($attributes);		
	}
	
	public function endColumn($columnObj)
	{
		$this->attributes['items'][]=$columnObj->end();
	}
	
	public function startGroup($attributes=array())
	{
		return new afExtjsGroupTabPanelGroup($attributes);		
	}
	
	public function endGroup($groupObj)
	{
		$this->attributes['items'][]=$groupObj->end();
	}
	
	public function beforeEnd()
	{
		$this->attributes['buttonAlign']='center';
		$this->attributes['autoScroll']=true;
		
		$attributes=$this->attributes;
		
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
				
		if(isset($this->attributes['centerType'])&&$this->attributes['centerType']=='group')
		{
			$this->afExtjs->setAddons(array ('css' => array('/appFlowerPlugin/css/my-extjs.css',$this->afExtjs->getPluginsDir().'grouptabs/GroupTab.css'), 'js' => array($this->afExtjs->getPluginsDir().'grouptabs/GroupTabPanel.js',$this->afExtjs->getPluginsDir().'grouptabs/GroupTab.js')));
			
			$attributes=$this->attributes;
			unset($attributes['viewport']);
			unset($attributes['centerType']);
			unset($attributes['toolbar']);
			unset($attributes['west']);
			unset($attributes['north']);
			
			$this->addGroupTabPanel('center',$attributes);
		}
		else {
			$this->afExtjs->setAddons(array ('css' => array('/appFlowerPlugin/css/my-extjs.css',$this->afExtjs->getPluginsDir().'portal/portal.css'), 'js' => array($this->afExtjs->getPluginsDir().'portal/Portal.js',$this->afExtjs->getPluginsDir().'portal/PortalColumn.js',$this->afExtjs->getPluginsDir().'portal/Portlet.js',$this->afExtjs->getPluginsDir().'portal/sample-grid.js')));
			
			$attributes=$this->attributes;
			unset($attributes['viewport']);
			unset($attributes['centerType']);
			unset($attributes['toolbar']);
			unset($attributes['west']);
			unset($attributes['north']);
			
			$this->addPortal('center',$attributes);
		}
		
		//parent::end();
	}
	
	public function end()
	{	
		if($this->showFullCenter())
		{
			$this->beforeEnd();
		}
		
		parent::end();
	}
}
?>