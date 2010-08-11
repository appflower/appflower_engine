<?php
/**
 * extJs Wizard layout
 *
 */
class ImmExtjsWizardLayout extends ImmExtjsLayout
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
		return new ImmExtjsPortalColumn($attributes);		
	}
	
	public function endColumn($columnObj)
	{
		$this->attributes['items'][]=$columnObj->end();
	}
	
	public function startGroup($attributes=array())
	{
		return new ImmExtjsGroupTabPanelGroup($attributes);		
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
			$this->immExtjs->setAddons(array ('css' => array('/appFlowerPlugin/css/my-extjs.css',$this->immExtjs->getExamplesDir().'grouptabs/GroupTab.css'), 'js' => array($this->immExtjs->getExamplesDir().'grouptabs/GroupTabPanel.js',$this->immExtjs->getExamplesDir().'grouptabs/GroupTab.js')));
			
			$attributes=$this->attributes;
			unset($attributes['viewport']);
			unset($attributes['centerType']);
			unset($attributes['toolbar']);
			unset($attributes['west']);
			unset($attributes['north']);
			
			$this->addGroupTabPanel('center',$attributes);
		}
		else {
			$this->immExtjs->setAddons(array ('css' => array('/appFlowerPlugin/css/my-extjs.css',$this->immExtjs->getExamplesDir().'portal/portal.css'), 'js' => array($this->immExtjs->getExamplesDir().'portal/Portal.js',$this->immExtjs->getExamplesDir().'portal/PortalColumn.js',$this->immExtjs->getExamplesDir().'portal/Portlet.js',$this->immExtjs->getExamplesDir().'portal/sample-grid.js')));
			
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