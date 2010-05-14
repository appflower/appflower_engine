<?php
/**
 * extJs layout
 *
 */
class ImmExtjsLayout 
{
	/**
	 * default attributes for the layout
	 */
	public $attributes=array(), $layout='border';
	static public $instance = null;	
	public $immExtjs=null;
	
							
	public function __construct($attributes=array())
	{
		$this->immExtjs=ImmExtjs::getInstance();
		
		$this->setExtjsVersion(3);
		
		$this->immExtjs->setOptions(array('theme'=>'blue'));
		
		/**
		 * /appFlowerPlugin/extjs/build/widgets/form/Label-min.js is necessary because cachefly version doesn't contain label field in the build
		 */
		
		$this->immExtjs->setAddons(array ('css' => array('/appFlowerPlugin/css/my-extjs.css')));
		/*
		 * js for ajax widget popup
		 */
		$this->immExtjs->setAddons(array('js'=>array('/appFlowerPlugin/js/custom/widgetJS.js')));
		/**
		 * add custom title to south collapsed
		 */
		$this->immExtjs->setAddons(array('js'=>array('/appFlowerPlugin/js/custom/BorderLayoutOverride.js')));
		
		$this->immExtjs->setAddons(array('js'=>array('/appFlowerPlugin/js/custom/gridUtil.js')));
		
		/******************************************************************************************************************/
		/*
		 * Add overrides
		 */
		$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'overrides/Override.Ext.data.SortTypes.js')));
		$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'overrides/Override.Ext.form.Field.js')));
		$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'overrides/Override.Fixes.js')));
		if(sfContext::getInstance()->getUser()->isAuthenticated())
		$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'plugins/Ext.ux.Notification.js')));
		
		// Plugin to enable setting active item in accordion layout
		$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'layout/AccordionLayoutSetActiveItem.js')));
		
		// Plugin to maximize portlets
		$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'portal/Ext.ux.MaximizeTool.js')));
		
		$this->start($attributes);
		
		self::setInstance($this);
	}
	
	public static function getInstance()
	{
		return self::$instance;
	}
	
	public static function setInstance($object)
	{
		self::$instance = $object;
	}
	
	public function setTitle($title)
	{
		$this->attributes['viewport']['center_panel']['title']=$title;
	}
	
	public function setSouthTitleAndStyle($title,$style)
	{
		$this->attributes['viewport']['south_panel']['collapsedTitle']=$title;
		$this->attributes['viewport']['south_panel']['collapsedTitleStyle']='padding:0 0 0 5px;'.$style;
	}
	
	public function setExtjsVersion($version)
	{
		$this->immExtjs->setExtjsVersion($version);
	}
	
	public function getExtjsVersion()
	{
		return $this->immExtjs->getExtjsVersion();
	}
	
	public function start($attributes=array())
	{		
		/**
		 * TOOLBAR
		 */
		if(isset($attributes['toolbar'])&&is_object($attributes['toolbar'])&&get_class($attributes['toolbar'])=='ImmExtjsToolbar')
		{
			$attributes['toolbar']->end();
		}
		/**
		 * default toolbar constructed in helper /plugins/immExtjsPlugin/helper/ImmExtjsToolbar.php
		 */
		elseif(!isset($attributes['toolbar'])||(isset($attributes['toolbar'])&&$attributes['toolbar']!=false)) {
			if(!sfConfig::get('app_parser_skip_toolbar')){
				sfProjectConfiguration::getActive()->loadHelpers(array('ImmExtjsToolbar'));
			}
		}
		
		/**
		 * NORTH PANEL
		 */
		if(isset($attributes['north'])&&is_array($attributes['north']))
		{
			$this->addPanel('north',$attributes['north']);
		}
		/**
		 * default north panel
		 */
		elseif(!isset($attributes['north'])||(isset($attributes['north'])&&$attributes['north']!=false)){
			
			$this->addPanel('north',array('id'=>'north_panel',
									      'region'=>'north',
									      'height'=>'32',
									      'border'=>false,
									      'bodyStyle'=>'background-color:#dfe8f6;'
									    ));
		}
				
		/**
		 * WEST PANEL
		 */
		if(isset($attributes['west'])&&is_array($attributes['west']))
		{
			$this->addPanel('west',$attributes['west']);			
		}
		/**
		 * default west panel
		 */
		elseif(!isset($attributes['west'])||(isset($attributes['west'])&&$attributes['west']!=false)){
			
			$attributes_temp=array('id'=>'west_panel',
						      'region'=>'west',
						      'title'=>'Navigation',
						      'width'=>'220',
							  'minWidth'=>'220',
							  'autoHeight'=>'false',
							  'autoScroll'=>'true',								  
						      'split'=>'true',							
							  'layoutConfig'=>array('animate'=>'true'),
						      'collapsible'=>'true',
						      'layout'=>'accordion',
							  'listeners'=>'{"beforerender": function(){this.activeItem = this.findById("profile")}}'							   
							  
								
			);
						      
			if(!isset($this->attributes['viewport']['west_panel']))
			{			
				$this->attributes['viewport']['west_panel']=$attributes_temp;		
			}
			else {
				$this->attributes['viewport']['west_panel']=array_merge($attributes_temp,$this->attributes['viewport']['west_panel']);		
			}
			unset($attributes_temp);
		}
		elseif (isset($attributes['west'])&&$attributes['west']==false)
		{
			$this->attributes['viewport']['west_panel']=false;
		}
		
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
		
	public function addPanel($region,$attributes)
	{
		$attributes['region']=$region;
		
		$this->immExtjs->private[$region.'_panel']=$this->immExtjs->Panel($attributes);		
	}
	
	public function addTabPanel($region,$attributes)
	{
		$attributes['region']=$region;
		
		$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'tabsImm/TabMenu.js')));
		
		$this->immExtjs->private[$region.'_panel']=$this->immExtjs->TabPanel($attributes);		
	}
	
	public function addPortal($region,$attributes)
	{
		$attributes['region']=$region;
		
		$this->immExtjs->private[$region.'_panel']=$this->immExtjs->Portal($attributes);
	}
	
	public function addGroupTabPanel($region,$attributes)
	{
		$attributes['region']=$region;
		
		$this->immExtjs->private[$region.'_panel']=$this->immExtjs->GroupTabPanel($attributes);
	}
	
	public function addItem($region,$attributes=array())
	{
		if(is_array($attributes))
		{
			$this->attributes['viewport'][$region.'_panel']['items'][]=$this->immExtjs->asAnonymousClass($attributes);
		}
		elseif(is_object($attributes)) {
			$this->attributes['viewport'][$region.'_panel']['items'][]=$this->immExtjs->asVar($attributes->privateName);
		}
	}
	
	public function addButton($button)
	{
		if(!isset($this->attributes['buttons']))
		$this->attributes['buttons']=array();
		
		array_push($this->attributes['buttons'],$this->immExtjs->asVar($button->end()));
	}
	
	public function addHelp($html)
	{
		$panel=new ImmExtjsPanel(array('html'=>'<div style="white-space:normal;">'.$html.'</div>','listeners'=>array('render'=>$this->immExtjs->asMethod(array("parameters"=>"panel","source"=>"if(panel.body){panel.body.dom.style.border='0px';panel.body.dom.style.background='transparent';}")))));
		
		$this->attributes['viewport']['center_panel']['tbar'][]=$this->immExtjs->asVar($panel->privateName);
		$this->attributes['viewport']['center_panel']['listeners']=array('render'=>$this->immExtjs->asMethod('if(this.getTopToolbar()&&this.getTopToolbar().container){this.getTopToolbar().container.addClass(\'tbarBottomBorderFix\');}'));
	}
	
	public function addInitMethodSource($source)
	{
		@$this->immExtjs->public['init'] .= $source;
	}
	
	public function end()
	{
		/**
		 * default west panel items
		 */
		if($this->attributes['viewport']['west_panel']!=false&&!isset($this->attributes['viewport']['west_panel']['items']))
		{
			$this->addItem('west',array('title'=>'Navigation',
								      		'autoScroll'=>'true',
								      		'border'=>'false',
								      		'iconCls'=>'nav',
								      		'html'=>'test'	      	
								      	));
								      	
			$this->addItem('west',array('title'=>'Settings',
								      		'autoScroll'=>'true',
								      		'border'=>'false',
								      		'iconCls'=>'settings',
								      		'html'=>'test2'      	
								      	));
		}
		
		if(isset($this->attributes['viewport']['north_panel'])&&!isset($this->immExtjs->private['north_panel']))
		$this->addPanel('north',$this->attributes['viewport']['north_panel']);
		
		if(isset($this->attributes['viewport']['west_panel'])&&$this->attributes['viewport']['west_panel']!=false&&!isset($this->immExtjs->private['west_panel'])){
			
			$this->addPanel('west',$this->attributes['viewport']['west_panel']);
		}
			
		if(isset($this->attributes['viewport']['south_panel'])&&!isset($this->immExtjs->private['south_panel']))
		$this->addPanel('south',$this->attributes['viewport']['south_panel']);
		
		if(isset($this->attributes['viewport']['east_panel'])&&!isset($this->immExtjs->private['east_panel']))
		$this->addPanel('east',$this->attributes['viewport']['east_panel']);
		
		$viewportItems=array();
		
		if(isset($this->immExtjs->private['north_panel']))
		$viewportItems[]=$this->immExtjs->asVar('north_panel');
		
		if(isset($this->immExtjs->private['west_panel']))
		$viewportItems[]=$this->immExtjs->asVar('west_panel');
		
		if(isset($this->immExtjs->private['center_panel']))
		$viewportItems[]=$this->immExtjs->asVar('center_panel');
		
		if(isset($this->immExtjs->private['south_panel']))
		$viewportItems[]=$this->immExtjs->asVar('south_panel');
		
		if(isset($this->immExtjs->private['east_panel']))
		$viewportItems[]=$this->immExtjs->asVar('east_panel');
		
		$this->immExtjs->private['viewport']=$this->immExtjs->Viewport(
		  	array
				(
				  'id'	=> 'viewport',
				  'layout' => $this->layout,  'items'  => $viewportItems
				)
		);
		
		@$this->immExtjs->public['init'] .= "
	    Ext.QuickTips.init();
	    Ext.apply(Ext.QuickTips.getQuickTip(), {
		    trackMouse: true
		});
		Ext.form.Field.prototype.msgTarget = 'side';
		";
		
		if(isset($this->immExtjs->private['toolbar']))
		@$this->immExtjs->public['init'] .= "toolbar.render(document.body);";
		
		$this->immExtjs->public['getViewport'] = "return viewport;";
		$this->immExtjs->public['getViewport'] = $this->immExtjs->asMethod($this->immExtjs->public['getViewport']);
		
		$this->immExtjs->init();
	}
}
?>
