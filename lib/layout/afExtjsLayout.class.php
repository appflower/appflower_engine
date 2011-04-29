<?php
/**
 * extJs layout
 *
 * base for all layout classes, generally used for now only in afExtjsViewportLayout and afExtjsDesktopLayout
 */
class afExtjsLayout 
{
	/**
	 * default attributes for the layout
	 */
	public $attributes=array();
	static public $instance = null;	
	public $afExtjs=null;
	public $sharpPrefix=null;
							
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
		$this->afExtjs->setAddons(array('js'=>array($this->afExtjs->getPluginsDir().'overrides/Override.Ext.data.SortTypes.js')));
		$this->afExtjs->setAddons(array('js'=>array($this->afExtjs->getPluginsDir().'overrides/Override.Ext.form.Field.js')));
		$this->afExtjs->setAddons(array('js'=>array($this->afExtjs->getPluginsDir().'overrides/Override.Fixes.js')));
		//if(sfContext::getInstance()->getUser()->isAuthenticated())
		$this->afExtjs->setAddons(array('js'=>array($this->afExtjs->getPluginsDir().'plugins/Ext.ux.Notification.js')));
		
		// Plugin to enable setting active item in accordion layout
		$this->afExtjs->setAddons(array('js'=>array($this->afExtjs->getPluginsDir().'layout/AccordionLayoutSetActiveItem.js')));
		
		// Plugin to maximize portlets
		$this->afExtjs->setAddons(array('js'=>array($this->afExtjs->getPluginsDir().'portal/Ext.ux.MaximizeTool.js')));
		
		$this->sharpPrefix = sfConfig::get('app_appflower_sharpPrefix','');

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
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
		
	public function addInitMethodSource($source)
	{
		@$this->afExtjs->public['init'] .= $source;
	}
	
	public function getPrivateSource()
	{
		$sourcePrivate = '';
		
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
		
	public function end()
	{		
        $this->afExtjs->public['init'] = ArrayUtil::get(
            $this->afExtjs->public, 'init', '');
		
        $this->afExtjs->public['init'] .= "
	    Ext.QuickTips.init();
	    Ext.apply(Ext.QuickTips.getQuickTip(), {
		    trackMouse: true
		});
		Ext.form.Field.prototype.msgTarget = 'side';
		Ext.History.init();
		afApp.urlPrefix = '".sfContext::getInstance()->getRequest()->getRelativeUrlRoot()."';
	    afApp.sharpPrefix = '".$this->sharpPrefix."';
		";
				
		$this->afExtjs->init();
	}
}
?>
