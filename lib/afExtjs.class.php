<?php
sfProjectConfiguration::getActive()->loadHelpers(array('sfExtjs2'));

/**
 * extends the sfExtjs2Plugin
 *
 */
class afExtjs extends sfExtjs2Plugin
{
  static public $instance = null;
  public $private,$public,$privateAttributes;
  
  public static function getInstance()
  {
    if (!isset(self::$instance))
    {
      self::$instance = new afExtjs();
    }

    return self::$instance;
  }
  
  public function __construct()
  {
  }
  
  /**
   * set js and/or css addons
   *
   */
  public function setAddons($addons)
  {
    foreach ($this->addons as $type => $v)
    {
      if (isset($addons[$type]))
      {
      	if(is_array($addons[$type]))
      	{
      		$this->addons[$type]=array_merge($this->addons[$type],$addons[$type]);
      		
      		$this->addons[$type]=array_unique($this->addons[$type]);
      	}
      }
    }
  }
  
  /**
   * returns the examples path
   */
  public function getPluginsDir()
  {
	return sfConfig::get('sf_extjs'.$this->getExtjsVersion().'_plugins_dir');
  }
  
  public function getCurrentTemplate()
  {
  	$pluginTemplateYml = sfYaml::load(sfConfig::get('sf_root_dir').'/plugins/appFlowerPlugin/config/template.yml');
  	if(file_exists(sfConfig::get('sf_root_dir').'/config/template.yml'))
  	{
  		$appTemplateYml = sfYaml::load(sfConfig::get('sf_root_dir').'/config/template.yml');
  		
  		if(is_array($appTemplateYml))
  		{
  			$templateYml = array_merge($pluginTemplateYml,$appTemplateYml);
  		}
  		else {
  			$templateYml = $pluginTemplateYml;
  		}
  	}
  	else {
  		$templateYml = $pluginTemplateYml;
  	}
  	
  	$template = in_array($templateYml['template']['current'],$pluginTemplateYml['template']['types'])?$templateYml['template']['current']:$pluginTemplateYml['template']['current'];
  	
  	return $template;
  }
  
  public function setCurrentTemplate($template)
  {
  	$appTemplateYml = sfYaml::load(sfConfig::get('sf_root_dir').'/config/template.yml');
  	
  	$appTemplateYml['template']['current'] = $template;
  	
  	file_put_contents(sfConfig::get('sf_root_dir').'/config/template.yml',sfYaml::dump($appTemplateYml,4));
  }
  
  public function addInitMethodSource($source)
  {
	 @$this->public['init'] .= $source;
  }
  
  public function init()
  {
  	$this->load();
  	
  	$this->begin(false);
  	
  	if(isset($this->public['init']))
  	$this->public['init'] = $this->asMethod(str_replace("\'","",$this->public['init']));
  	//echo "<pre>";echo $this->public['init'];
  	
	$this->beginApplication ( array ('name' => 'App', 'private' => $this->private, 'public' => $this->public ) );
	$this->endApplication ();
	
	$this->initApplication ( 'App' );
	$this->end ('',false);
	$favicon = sfConfig::get("app_favicon")?sfConfig::get("app_favicon"):"/favicon.ico";
	echo '<link rel="shortcut icon" href="'.$favicon.'" type="image/x-icon">';
	
	/**
	 * add html loading mask
	 */	
	echo "<style>.lm{position:absolute; top:40%; left:40%;}</style>";
	echo sprintf('<div id="loading-mask" class="lm"><div id="loading"><img src="%s" width="32" height="32" style="margin-right:8px;" align="absmiddle"/>Loading AppFlower v%s</div></div>%s', sfConfig::get('app_appFlower_loadingLogo'), sfConfig::get('app_appFlower_version'), self::LBR);
	
	echo "<script type='text/javascript'>\n";
	echo "// <![CDATA[\n";	
	echo $this->source;
	echo "// ]]>\n";
	echo "</script>\n";
	/**
	 * fields required for history management
	 */
	echo '<form id="history-form" class="x-hidden"><input type="hidden" id="x-history-field" /><iframe id="x-history-frame"></iframe></form>';
	
  }
}
?>
