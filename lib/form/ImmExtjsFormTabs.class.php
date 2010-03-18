<?php
/**
 * extJs Form Tabs
 *
 */
class ImmExtjsFormTabs
{
	/**
	 * default attributes for the tabs
	 */
	public $attributes=array('xtype'=>'tabpanel',
							//'plain'=>true,
							'activeTab'=>0,
							//'height'=>235,
							'frame'=>true,
							'items'=>array(),'enableTabScroll'=>true);
	public $immExtjs=null;						
							
	public function __construct($attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		
		/**
		 * deferredRender & layoutOnTabChange, for creating the layout of each tab after the tab is clicked
		 */
		$this->attributes['deferredRender'] = false;
		
		//$this->attributes['forceLayout'] = true;				
		$ah = array('autoHeight'=>$attributes['tabHeight']?false:true);
		$this->attributes['defaults']=$this->immExtjs->asAnonymousClass(array_merge(array('iconCls'=>'icon-tab-default','bodyStyle'=>'padding:10px;','hideMode'=>'offsets'),$ah));
		
		$this->attributes['listeners']['tabchange']=$this->immExtjs->asMethod(array(
				          	      	'parameters'=>'tabPanel,tab',
				          	      	'source'=>"tabPanel.doLayout();"));
		/**
		 * Tabs cheats for Ext 3
		 */
		if(ImmExtjsLayout::getInstance()->getExtjsVersion() == 3){
			$this->attributes['deferredRender']['onTabChange'] = true;
			$this->attributes['listeners']['render']=$this->immExtjs->asMethod(array(
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
			$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'settings/ux_VerticalTabPanel.js')));
			$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'settings/Ext.ux.Settings.js')));
			$this->immExtjs->setAddons(array('css'=>array($this->immExtjs->getExamplesDir().'settings/ux_VerticalTabPanel.css')));
			$this->attributes['xtype'] = 'settings';
			$this->attributes['autoHeight'] = 'true';				
			$this->attributes['user'] = sfContext::getInstance()->getUser()->getGuardUser()->getUsername();
		}		
	}
	public function addMember($item)
	{		
		$this->attributes['items'][]=$this->immExtjs->asAnonymousClass($item);		
	}
	
	public function startTab($attributes=array())
	{
		$attributes['isSetting'] = (isset($this->attributes['xtype']) && $this->attributes['xtype'] == "settings");
		return new ImmExtjsFormTab($attributes);
	}
	
	public function endTab($tabObj)
	{
		$this->attributes['items'][]=$tabObj->end();
	}
	
	public function end()
	{
		return $this->immExtjs->asAnonymousClass($this->attributes);
	}	
}
?>