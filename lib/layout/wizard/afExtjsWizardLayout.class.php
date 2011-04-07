<?php
/**
 * extJs Wizard layout
 *
 */
class afExtjsWizardLayout extends afExtjsLayout
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
	
	public function end()
	{
		$this->attributes['buttonAlign']='center';
		$this->attributes['autoScroll']=true;
				
		if(isset($this->attributes['centerType'])&&$this->attributes['centerType']=='group')
		{
			$this->afExtjs->setAddons(array ('css' => array('/appFlowerPlugin/css/my-extjs.css',$this->afExtjs->getExamplesDir().'grouptabs/GroupTab.css'), 'js' => array($this->afExtjs->getExamplesDir().'grouptabs/GroupTabPanel.js',$this->afExtjs->getExamplesDir().'grouptabs/GroupTab.js')));
			
			$attributes=$this->attributes;
			unset($attributes['viewport']);
			unset($attributes['centerType']);
			unset($attributes['toolbar']);
			unset($attributes['west']);
			unset($attributes['north']);
			
			$this->addGroupTabPanel('center',$attributes);
		}
		else {
			$this->afExtjs->setAddons(array ('css' => array('/appFlowerPlugin/css/my-extjs.css',$this->afExtjs->getExamplesDir().'portal/portal.css'), 'js' => array($this->afExtjs->getExamplesDir().'portal/Portal.js',$this->afExtjs->getExamplesDir().'portal/PortalColumn.js',$this->afExtjs->getExamplesDir().'portal/Portlet.js',$this->afExtjs->getExamplesDir().'portal/sample-grid.js')));
			
			$attributes=$this->attributes;
			unset($attributes['viewport']);
			unset($attributes['centerType']);
			unset($attributes['toolbar']);
			unset($attributes['west']);
			unset($attributes['north']);
			
			$this->addPortal('center',$attributes);
		}
		
		parent::end();
	}
}
?>