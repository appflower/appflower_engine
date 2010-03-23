<?php
sfProjectConfiguration::getActive()->loadHelpers(array('sfExtjs2'));

/**
 * extends the sfExtjs2Plugin
 *
 */
class ImmExtjs extends sfExtjs2Plugin
{
  static public $instance = null;
  public $private,$public;
  
  public static function getInstance()
  {
    if (!isset(self::$instance))
    {
      self::$instance = new ImmExtjs();
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
      	}
      }
    }
  }
  
  /**
   * returns the examples path
   */
  public function getExamplesDir()
  {
	return sfConfig::get('sf_extjs'.$this->getExtjsVersion().'_examples_dir');
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
	
	echo "<script type='text/javascript'>\n";
	echo "// <![CDATA[\n";	
	echo JSMin::minify($this->source);
	echo "// ]]>\n";
	echo "</script>\n";
  }
}
?>
