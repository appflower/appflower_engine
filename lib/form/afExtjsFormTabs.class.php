<?php
/**
 * extJs Form Tabs
 *
 */
class afExtjsFormTabs
{
	/**
	 * default attributes for the tabs
	 */
	public $attributes=array('xtype'=>'tabpanel',
							//'plain'=>true,
							'activeTab'=>0,
							'height'=>300,
							'frame'=>false,
							'items'=>array(),'enableTabScroll'=>true);
	public $afExtjs=null;						
							
	public function __construct($attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
		
		/**
		 * deferredRender & layoutOnTabChange, for creating the layout of each tab after the tab is clicked
		 */
		$this->attributes['deferredRender'] = false;
		
		//$this->attributes['forceLayout'] = true;
		$ah = array('autoHeight'=>(isset($attributes['tabHeight'])&&$attributes['tabHeight'])?false:true);
		$this->attributes['defaults']=$this->afExtjs->asAnonymousClass(array_merge(array('iconCls'=>'','icon' => '', 'bodyStyle'=>'padding:10px;','hideMode'=>'offsets','autoWidth'=>true),$ah));
		
		$this->attributes['listeners']['tabchange']=$this->afExtjs->asMethod(array(
				          	      	'parameters'=>'tabPanel,tab',
				          	      	'source'=>"tabPanel.doLayout();"));
		/**
		 * Tabs cheats for Ext 3
		 */
		if(afExtjsLayout::getInstance()->getExtjsVersion() == 3){
			$this->attributes['deferredRender']['onTabChange'] = true;
			$this->attributes['listeners']['render']=$this->afExtjs->asMethod(array(
							                'parameters'=>'tabPanel',
							                'source'=>'	
							                	tabPanel.getEl().mask("Rendering UI").setStyle({backgroundColor:"#dfe8f6"}).setOpacity(1);
								                tabPanel.setActiveTab(0);
								                while(true){								                		          	      		
								                	var tp = tabPanel.getActiveTab().nextSibling();								                	
								                	if(!tp) break;
								                	tabPanel.activate(tp);
								                }
								                tabPanel.setActiveTab(0);
								                tabPanel.getEl().unmask();
								               
							         		'));
		}
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
		$this->checkIfSetting($this->attributes);		
	}
	public function checkIfSetting($attr){		
		if(isset($attr['isSetting']) && $attr['isSetting'] !== "false"){			
			$this->afExtjs->setAddons(array('js'=>array($this->afExtjs->getPluginsDir().'settings/ux_VerticalTabPanel.js')));
			$this->afExtjs->setAddons(array('js'=>array($this->afExtjs->getPluginsDir().'settings/Ext.ux.Settings.js')));
			$this->afExtjs->setAddons(array('css'=>array($this->afExtjs->getPluginsDir().'settings/ux_VerticalTabPanel.css')));
			$this->attributes['xtype'] = 'settings';
			$this->attributes['enableTabScroll'] = 'false';	
			$this->attributes['user'] = sfContext::getInstance()->getUser()->getAppFlowerUser()->getUsername();
		}		
	}
	public function addMember($item)
	{		
		$this->attributes['items'][]=$this->afExtjs->asAnonymousClass($item);		
	}
	
	public function startTab($attributes=array())
	{
		$attributes['isSetting'] = (isset($this->attributes['xtype']) && $this->attributes['xtype'] == "settings");
		return new afExtjsFormTab($attributes);
	}
	
	public function endTab($tabObj)
	{
		$this->attributes['items'][]=$tabObj->end();
	}
	
	public function end()
	{
		return $this->afExtjs->asAnonymousClass($this->attributes);
	}	
}
?>