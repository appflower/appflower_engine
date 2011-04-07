<?php
/**
 * extJs layout
 *
 */
class afExtjsLayout 
{
	/**
	 * default attributes for the layout
	 */
	public $attributes=array(), $layout='border';
	static public $instance = null;	
	public $afExtjs=null;
							
	public function __construct($attributes=array())
	{
		$this->afExtjs=afExtjs::getInstance();
		
		$this->setExtjsVersion(3);
		
		$this->afExtjs->setOptions(array('theme'=>'blue'));
		
		/**
		 * /appFlowerPlugin/extjs/build/widgets/form/Label-min.js is necessary because cachefly version doesn't contain label field in the build
		 */
		
		$this->afExtjs->setAddons(array ('css' => array('/appFlowerPlugin/css/my-extjs.css')));
		/*
		 * js for ajax widget popup
		 */
		$this->afExtjs->setAddons(array('js'=>array('/appFlowerPlugin/js/custom/widgetJS.js')));
		/**
		 * add custom title to south collapsed
		 */
		//$this->afExtjs->setAddons(array('js'=>array('/appFlowerPlugin/js/custom/BorderLayoutOverride.js')));
		
		$this->afExtjs->setAddons(array('js'=>array('/appFlowerPlugin/js/custom/gridUtil.js')));
		
		/**
		 * general document key maps
		 */
		$this->afExtjs->setAddons(array('js'=>array('/appFlowerPlugin/js/custom/keyMaps.js')));
		
		/* development under progress: commented for now */
		//$this->afExtjs->setAddons(array('js'=>array('/js/custom/Ext.ux.NeedHelp.js')));
		
		/*
		 * Add overrides
		 */
		$this->afExtjs->setAddons(array('js'=>array($this->afExtjs->getExamplesDir().'overrides/Override.Ext.data.SortTypes.js')));
		$this->afExtjs->setAddons(array('js'=>array($this->afExtjs->getExamplesDir().'overrides/Override.Ext.form.Field.js')));
		$this->afExtjs->setAddons(array('js'=>array($this->afExtjs->getExamplesDir().'overrides/Override.Fixes.js')));
		//if(sfContext::getInstance()->getUser()->isAuthenticated())
		$this->afExtjs->setAddons(array('js'=>array($this->afExtjs->getExamplesDir().'plugins/Ext.ux.Notification.js')));
		
		// Plugin to enable setting active item in accordion layout
		$this->afExtjs->setAddons(array('js'=>array($this->afExtjs->getExamplesDir().'layout/AccordionLayoutSetActiveItem.js')));
		
		// Plugin to maximize portlets
		$this->afExtjs->setAddons(array('js'=>array($this->afExtjs->getExamplesDir().'portal/Ext.ux.MaximizeTool.js')));
		
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
	
	public function showFullCenter()
	{
		// The first page is optimized to show less than full center.
		$request = sfContext::getInstance()->getRequest();
		return $request->getAttribute('af_first_page_request') !== true;
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
		$this->afExtjs->setExtjsVersion($version);
	}
	
	public function getExtjsVersion()
	{
		return $this->afExtjs->getExtjsVersion();
	}
	
	public function start($attributes=array())
	{		
		if(sfConfig::get('app_parser_skip_toolbar')){
			$attributes['toolbar'] = false;
			$attributes['north'] = false;
			$attributes['west'] = false;
		}

		/**
		 * TOOLBAR
		 */
		if(isset($attributes['toolbar'])&&is_object($attributes['toolbar'])&&get_class($attributes['toolbar'])=='afExtjsToolbar')
		{
			$attributes['toolbar']->end();
		}
		/**
		 * default toolbar constructed in helper /plugins/afExtjsPlugin/helper/afExtjsToolbar.php
		 */
		elseif(!isset($attributes['toolbar'])||(isset($attributes['toolbar'])&&$attributes['toolbar']!=false)) {
			sfProjectConfiguration::getActive()->loadHelpers(array('afExtjsToolbar'));
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
			
			$logoScript = "";
                        if (class_exists('ConfigPeer')) {
                            $avatarLogo = ConfigPeer::get("avatar_logo",false);
                        } else {
                            $avatarLogo = sfConfig::get('app_avatar_logo',false);
                        }
			if($avatarLogo && file_exists(sfConfig::get('sf_web_dir').$avatarLogo)){
				$imagesize = getimagesize(sfConfig::get('sf_web_dir').$avatarLogo);
				$clickAction = 'onClick="var aboutWin = null;if(aboutWin = Ext.getCmp(\'about-window\')){aboutWin.show();aboutWin.center();}" style="cursor:pointer"';				
				$logo = '<div style="background-color:#d9e7f8;border-right:1px solid #99bbe8;border-left:1px solid #99bbe8;border-bottom:1px solid #99bbe8; padding:2px 0px 0px 0px; margin:0px;text-align:center;"><img id="avatar_image" '.$clickAction.' src="'.$avatarLogo.'"/></div>';
				$logoScript = 'var logoDiv = Ext.DomHelper.insertBefore(comp.bwrap,{tag:"div",html:"'.addslashes($logo).'"});			
				var resize = function(comp){
					var body = comp.body, bodyHeight = body.getHeight();
					body.setHeight(bodyHeight-'.$imagesize[1].'-3);					
				}; 
				resize(comp); 
				comp.on("bodyresize",function(comp,w,h){resize(comp);});';
			}
			$attributes_temp=array('id'=>'west_panel',
							  'stateful'=>true,
							  'stateEvents'=>array('afterlayout'),
							  'getState'=>$this->afExtjs->asMethod(array("parameters"=>"","source"=>"return { activeItemIndex: this.items.findIndex('id',this.layout.activeItem.id) };")),
							  'stateId'=>'west_panel',
						      'region'=>'west',
						      'title'=>'Navigation',
						      'width'=>'255',
							  'minWidth'=>'255',
							  /*'autoHeight'=>'false',
							  'autoScroll'=>'true',*/								  
						      'split'=>'true',							
							  'layoutConfig'=>array('animate'=>'true'),
						      'collapsible'=>'true',
						      'layout'=>'accordion',							  
							  'listeners'=>'{"beforerender": function(){var state=Ext.state.Manager.get("west_panel");if(!state){this.activeItem = this.findById("profile");}else{this.activeItem = state.activeItemIndex;}},"render":function(comp){'.$logoScript.'}}');
						      
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
		
		$this->afExtjs->private[$region.'_panel']=$this->afExtjs->Panel($attributes);		
	}
	
	public function addTabPanel($region,$attributes)
	{
		$attributes['region']=$region;
		
		$this->afExtjs->setAddons(array('js'=>array($this->afExtjs->getExamplesDir().'tabsImm/TabMenu.js')));
		
		$this->afExtjs->private[$region.'_panel']=$this->afExtjs->TabPanel($attributes);		
	}
	
	public function addPortal($region,$attributes)
	{
		$attributes['region']=$region;
		
		$this->afExtjs->private[$region.'_panel']=$this->afExtjs->Portal($attributes);
	}
	
	public function addGroupTabPanel($region,$attributes)
	{
		$attributes['region']=$region;
		
		$this->afExtjs->private[$region.'_panel']=$this->afExtjs->GroupTabPanel($attributes);
	}
	
	public function addItem($region,$attributes=array())
	{
		if(is_array($attributes))
		{
			$this->attributes['viewport'][$region.'_panel']['items'][]=$this->afExtjs->asAnonymousClass($attributes);
		}
		elseif(is_object($attributes)) {
			$this->attributes['viewport'][$region.'_panel']['items'][]=$this->afExtjs->asVar($attributes->privateName);
		}
	}
	
	public function addButton($button)
	{
		if(!isset($this->attributes['buttons']))
		$this->attributes['buttons']=array();
		
		array_push($this->attributes['buttons'],$this->afExtjs->asVar($button->end()));
	}
	
	public function addHelp($html)
	{
		$panel=new afExtjsPanel(array('html'=>'<div style="white-space:normal;">'.$html.'</div>','listeners'=>array('render'=>$this->afExtjs->asMethod(array("parameters"=>"panel","source"=>"if(panel.body){panel.body.dom.style.border='0px';panel.body.dom.style.background='transparent';}")))));
		
		$this->attributes['viewport']['center_panel']['tbar'][]=$this->afExtjs->asVar($panel->privateName);
		$this->attributes['viewport']['center_panel']['listeners']=array('render'=>$this->afExtjs->asMethod('if(this.getTopToolbar()&&this.getTopToolbar().container){this.getTopToolbar().container.addClass(\'tbarBottomBorderFix\');}'));
	}
	
	public function addInitMethodSource($source)
	{
		@$this->afExtjs->public['init'] .= $source;
	}
	
	public function getPrivateSource()
	{
		$sourcePrivate = '';
		if(isset($this->afExtjs->private['toolbar'])) unset($this->afExtjs->private['toolbar']);
		if(isset($this->afExtjs->private['north_panel'])) unset($this->afExtjs->private['north_panel']);
		if(isset($this->afExtjs->private['south_panel'])) unset($this->afExtjs->private['south_panel']);
		if(isset($this->afExtjs->private['center_panel'])) unset($this->afExtjs->private['center_panel']);
		if(isset($this->afExtjs->private['center_panel_first'])) unset($this->afExtjs->private['center_panel_first']);
		foreach ($this->afExtjs->private as $key => $value){			
			$sourcePrivate .= sprintf("%svar %s = %s;", afExtjs::LBR, $key, afExtjs::_quote($key, $value));
	    }
		return $sourcePrivate;		
	}
	
	public function getPublicSource(){
		$sourcePublic = '';
		if($this->afExtjs->public) {
			foreach ($this->afExtjs->public as $key => $value){
				$sourcePublic .= $value."\n";
	   		}
		}
	    return $sourcePublic;		
	}
	
	public function setCenterWidget($widget)
	{
		$attributesPanel['items'][]=$this->afExtjs->asVar($widget->privateName);
		$attributesPanel['border']=false;
		$attributesPanel['bodyBorder']=false;
		$attributesPanel['layout']='fit';						
		$attributesPanel['id']='center_panel_first';
		$center_panel_first=$this->afExtjs->Panel($attributesPanel);		
		
		echo json_encode(array("center_panel_first"=>$center_panel_first,"source"=>$this->getPrivateSource(),"addons"=>$this->afExtjs->addons,"public_source"=>$this->getPublicSource()));
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
		
		if(isset($this->attributes['viewport']['north_panel'])&&!isset($this->afExtjs->private['north_panel']))
		$this->addPanel('north',$this->attributes['viewport']['north_panel']);
		
		if(isset($this->attributes['viewport']['west_panel'])&&$this->attributes['viewport']['west_panel']!=false&&!isset($this->afExtjs->private['west_panel'])){
			
			$this->addPanel('west',$this->attributes['viewport']['west_panel']);
		}
			
		if(isset($this->attributes['viewport']['south_panel'])&&!isset($this->afExtjs->private['south_panel']))
		$this->addPanel('south',$this->attributes['viewport']['south_panel']);
		
		if(isset($this->attributes['viewport']['east_panel'])&&!isset($this->afExtjs->private['east_panel']))
		$this->addPanel('east',$this->attributes['viewport']['east_panel']);
		
		$viewportItems=array();
		
		if(isset($this->afExtjs->private['north_panel']))
		$viewportItems[]=$this->afExtjs->asVar('north_panel');
		
		if(isset($this->afExtjs->private['west_panel']))
		$viewportItems[]=$this->afExtjs->asVar('west_panel');
		
		/**
		 * if users sends a url directly in browser, then the request is not ajax, so create an empty center_panel in which to load the contents of the given url
		 */
		if(!$this->showFullCenter())
		{
			$attributesPanelContainer['style']='padding-right:5px;';
			$attributesPanelContainer['border']=false;
			$attributesPanelContainer['bodyBorder']=false;
			$attributesPanelContainer['layout']='fit';
			$attributesPanelContainer['id']='center_panel';         	      	
			
			$this->addPanel('center',$attributesPanelContainer);
		}
		if(isset($this->afExtjs->private['center_panel']))
		$viewportItems[]=$this->afExtjs->asVar('center_panel');
		
		if(isset($this->afExtjs->private['south_panel']))
		$viewportItems[]=$this->afExtjs->asVar('south_panel');
		
		if(isset($this->afExtjs->private['east_panel']))
		$viewportItems[]=$this->afExtjs->asVar('east_panel');
		
		$this->afExtjs->private['viewport']=$this->afExtjs->Viewport(
		  	array
				(
				  'id'	=> 'viewport',
				  'layout' => $this->layout,  'items'  => $viewportItems
				)
		);
		
        $this->afExtjs->public['init'] = ArrayUtil::get(
            $this->afExtjs->public, 'init', '');
		$this->afExtjs->public['init'] .= "
	    Ext.QuickTips.init();
	    Ext.apply(Ext.QuickTips.getQuickTip(), {
		    trackMouse: true
		});
		Ext.form.Field.prototype.msgTarget = 'side';
		Ext.History.init();
		";
				
		@$this->afExtjs->public['init'] .="
		viewport.doLayout();";
		
		@$this->afExtjs->public['init'] .="
		setTimeout(function(){			
			Ext.get('loading').remove();
	        Ext.get('loading-mask').fadeOut({remove:true});
	        	        
	    ".(!$this->showFullCenter()?"afApp.loadFirst();":"")."        
	    }, 250);
	    afApp.urlPrefix = '".sfContext::getInstance()->getRequest()->getRelativeUrlRoot()."';
	    afApp.sharpPrefix = '".sfConfig::get('app_appflower_sharpPrefix')."';
	    ";

		$this->afExtjs->public['getNorth'] = "return north_panel;";
		$this->afExtjs->public['getNorth'] = $this->afExtjs->asMethod($this->afExtjs->public['getNorth']);
		
		$this->afExtjs->public['getViewport'] = "return viewport;";
		$this->afExtjs->public['getViewport'] = $this->afExtjs->asMethod($this->afExtjs->public['getViewport']);
		
		$this->afExtjs->public['getToolbar'] = "return toolbar;";
		$this->afExtjs->public['getToolbar'] = $this->afExtjs->asMethod($this->afExtjs->public['getToolbar']);
		
		$this->afExtjs->init();
	}
}
?>
