<?php

/**
 * @plugin           sfExtjs2Plugin
 * @description      sfExtjs2Plugin is a symfony plugin that provides an easy to use wrapper for the Ext javascript library
 * @author           Benjamin Runnels<benjamin.r.runnels [at] citi [dot] com>, Leon van der Ree<Leon [at] fun4me [dot] demon [dot] nl>, Wolfgang Kubens<wolfgang.kubens [at] gmx [dot] net>, Jerome Macias
 * @version          0.60
 * @last modified
 *                   11.11.2009 Ivo Danihelka
 *                    - Added iframe prevention.
 *                   07.03.2009 Ivo Danihelka
 *                    - Used json_encode() on the quoted values.
 *                   08.04.2008 Eric
 *                    - Fixed the default value for isAssoc() with XTemplate
 *                   07.22.2008
 *                    - Several by LvanderRee see svn log
 *                   02.18.2008 swagner
 *                    - Fixed harmless bug (a simple empty array resulted in "['']" insted of"[]")
 *                   02.12.2008 swagner
 *                    - added two help-functions (isAssoc() and isSimpleArray())
 *                    - Fixed _quote() and added check for simple Array
 *                   02.04.2008 swagner
 *                    - Fixed handling of arrays in getExtObjectComponent
 *                   12.28.2007 Jerome
 *                    - Fixed method getExtObject
 *                    - Added constants
 *                    - Fixed method _build_datas (return empty string if array is empty)
 *                   12.27.2007 Jerome
 *                    - Added handling of null values
 *                   12.26.2007 Jerome
 *                    - Fixed method getExtObject when we want to assign a name attribute and we don't specify "attributes" key in $attributes param
 *                    - Fixed order for display private attributes in method beginApplication
 *                    - Added datas parameter for method getExtObjectComponent
 *                    - Added method _build_datas
 *                    - Replaced all call_user_func and sfExtjs2Plugin:: by self::
 *                    - Added possibility to load js or/and css addons/plugins
 *                    - Added possibility to set name for a function in method asMethod
 *                    - Replaced some ',' by self::LBR_CM
 *                    - Added method initApplication
 *                   12.20.2007 Wolfgang
 *                     - Added method asListener
 *                     - Renamed method customClass into asCustomClass
 *                     - Renamed method anonymousClass into asAnonymousClass
 *                    12.19.2007 Wolfgang
 *                    - Added method asVar
 *                    - Added logic for anonymousClass
 *                    - Changed parameters handling
 *                   12.18.2007 Wolfgang
 *                    - Added sf_extjs2_comment
 *                   12.17.2007 Leon:
 *                    - handling of inner (recursive) arrays (see items => array(array(...), array(...))
 *                   12.17.2007 Kubens:
 *                    - Added handling of boolean values
 *                    - Fixed quoting logic for beginClass
 *                    - Fixed quoting logic for beginApplication
 *                   12.15.2007 Kubens:
 *                     - Overworked quoting logic
 *                   11.22.2007 Kubens:
 *                     - Added features to create application
 *                     - Added parameters support for Ext.object constructors
 *                    11.17.2007 Kubens:
 *                     - Added features to create custom classes and custom methods
 *                    11.12.2007  Kubens:
 *                     - Fixed loading order of adapters. If adapters are used then it is important to load
 *                       adapters and coresponding files before ext-all.js
 *                     - Overworked: load method. Adapters and themes are setuped in config.php
 *                     - Overworked: constructor. If no adapter or theme is passed then default
 *                       settings from config.php will used
 *                    11.07.2007 Benjamin:
 *                     - Fixed the adapter includes to load all required files in the correct order
 *                       moved ext-base into adapters, pass ext as adapter for standalone
 *                       changed all javascript to load first so they will come before files specified in view.yml
 *                   07.15.2007 Kubens:
 *                     - created
 */
class sfExtjs2Plugin {

  const
    LBR      = "\n",
    LBR_CM   = ",\n",
    LBR_SM   = ";\n",
    LBR_CB_L = "{\n",
    LBR_CB_R = "\n}",
    LBR_SB_L = "[\n",
    LBR_SB_R = "\n]";

  public
    $namespace = '', // current namespace
    $options   = array('theme' => '', // current theme
                       'adapter' => ''), // current adapter
    $addons    = array('css' => array(), // current css plugins
                       'js' => array()), // current js addons
    $source= '',
    $extjsVersion=3;

  static public function isAssoc($class, $arr){

    // constructors which accept arrays only
    if (in_array($class, array('Ext.XTemplate'))) return false;

    foreach ( $arr as $key => $skip )
    {
      if ( !is_integer( $key ) )
      {
        return true;
      }
    }
    return false;
  }

  /**
   * Creates an instance of sfExtjs2Plugin.
   *
   * Usage:
   *
   *   $sfExtjs2Plugin = new sfExtjs2Plugin(
   *                           array
   *                           (
   *                             'adapter' => 'jquery', // config.sf_extjs2_adapters
   *                             'theme'   => 'gray'    // config.sf_extjs2_themes
   *                           ),
   *                           array
   *                           (
   *                             'js' => '/js/myExtjsPlugin.js',
   *                             'css' => '/css/symfony-extjs.css'
   *                           )
   *                         );
   *
   * @param array options
   */
  public function __construct($options = array(), $addons = array())
  {
    if ($options)
    {
      $this->setOptions($options);
    }

    if ($addons)
    {
      $this->setAddons($addons);
    }
  }

  public function setExtjsVersion($version)
  {
	$this->extjsVersion=$version;
  }
	
  public function getExtjsVersion()
  {
	return $this->extjsVersion;
  }
  
  /**
   * set theme and adapter
   *
   */
  public function setOptions($options)
  {
    foreach ($this->options as $optName => $v)
    {
      $this->options[$optName] = isset($options[$optName]) && array_key_exists($options[$optName], sfConfig::get('sf_extjs'.$this->getExtjsVersion().'_'.$optName.'s', array())) ? $options[$optName] : sfConfig::get('sf_extjs'.$this->getExtjsVersion().'_default_'.$optName);
    }
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
        $this->addons[$type] = is_array($addons[$type]) ? $addons[$type] : array($addons[$type]);
      }
    }
  }

  /**
   * If method does not exists and method is listed in
   * config.sf_extjs2_classes then Extjs2.class.constructor will rendered.
   *
   * Usage:
   *
   *   $sfExtjs2Plugin = new sfExtjs2Plugin(
   *                           array
   *                           (
   *                             'adapter' => 'jquery',
   *                             'theme'   => 'gray'
   *                           ),
   *                           array
   *                           (
   *                             'js' => '/js/myExtjsPlugin.js',
   *                             'css' => '/css/symfony-extjs.css'
   *                           )
   *                         );
   *   $sfExtjs2Plugin->Window(
   *                      array
   *                      (
   *                        'title'  => 'Window Title',
   *                        'border' => false,
   *                        'width'  => 600,
   *                        'height' => 500
   *                      )
   *                    );
   *
   * @param string class
   * @param array attributes
   * @return string Javascript source of Extjs2.class
   */
  public /*static*/ function __call ($class, $attributes)
  {
    $classes = sfConfig::get('sf_extjs2_classes');
    if (is_array($classes) && isset($classes[$class]))
    {
      $object = sfConfig::get($classes[$class]);
      if(isset($attributes[0]))
      {
      	return self::getExtObject($object['class'], $attributes[0]);
      }
      else {
      	return self::getExtObject($object['class']);
      }
    }
  }

  /**
   * Creates Javascript source for Extjs2.class
   *
   * Usage:
   *
   *   Syntax A = short form without any options
   *   $sfExtjs2Plugin->Object(array
   *   (
   *     'id'       => 'id',
   *     'renderTo' => $sfExtjs2Plugin->asVar('document.body'),
   *     'items'    => array
   *     (
   *       $sfExtjs2Plugin->Object(array('title'=>'Object A')),
   *       $sfExtjs2Plugin->Object(array('title'=>'Object B'))
   *     )
   *   ));
   *
   *   => new Object({id: 'id', renderTo: document.body, items: [new Object(title: 'Object A'), new Object(title: 'Object B')]})
   *
   *
   *   Syntax B = long form with additional options
   *   $sfExtjs2Plugin->Object(array
   *   (
   *     'name'       => 'string',      // option to render Javascript variable
   *     'parameters' => array
   *      (
   *        'parameter1',
   *        'parameter2'
   *      ),
   *     'attributes' => array          // attributes for Ext constructor
   *     (
   *       'id'       => 'id',
   *       'renderTo' => 'document.body',
   *       'items'    => array
   *       (
   *         $sfExtjs2Plugin->Object(array('title' => 'Object A')),
   *         $sfExtjs2Plugin->Object(array('title' => 'Object B'))
   *       )
   *     )
   *   ));
   *
   *   => new Object(parameter1, parameter2, {id: 'id', renderTo: document.body, items: [new Object(title: 'Object A'), new Object(title: 'Object B')]})
   *
   * @param string class
   * @param array attributes
   * @return string source
   */
  public static function getExtObject($class, $params = array())
  {
    $name = $lbr = null;
    $attributes = $parameters = $datas = array();

    # syntax A is a shortform of syntax B
    # if syntax A is used then convert syntax A to syntax B
    if (is_array($params) && !isset($params['attributes']) && !isset($params['parameters']) && !isset($params['datas']))
    {
      $attributes = $params;
    }
    else
    {
      $attributes = isset($params['attributes']) ? $params['attributes'] : array();

      // name for var
      if (is_array($params) && isset($params['name']))
      {
        $name = $params['name'];
        unset($params['name']);
      }

      // 'lbr' for a line break
      if (is_array($params) && isset($params['lbr']))
      {
        $lbr = $params['lbr'];
        unset($params['name']);
      }

      // parameters for constructor
      if (is_array($params) && isset($params['parameters']))
      {
        $parameters = $params['parameters'];
        unset($params['parameters']);
      }

      // datas for constructor
      if (is_array($params) && isset($params['datas']))
      {
        $datas = $params['datas'];
        unset($params['datas']);
      }
    }

    # list attributes must defined as an Javascript array
    # therefore all list attributes must be rendered as [attributeA, attributeB, attributeC]
    foreach (sfConfig::get('sf_extjs2_list_attributes') as $attribute)
    {
      if (isset($attributes[$attribute]) && !$attributes[$attribute] instanceof sfExtjs2Var)
      {
        $attributes[$attribute] = sprintf
        (
          self::LBR_SB_L.'%s'.self::LBR_SB_R,
          self::_build_attributes($attributes[$attribute])
        );
      }
    }

    // get source of component
    $source = self::getExtObjectComponent($attributes, sfConfig::get($class), $parameters, $datas);

    // if 'name' is assigned then we must render
    // either a Javascript variable or an attribute of this
    if ($name)
    {
      $source = sprintf
      (
        '%s%s = %s',
        strpos($name, 'this.') === false ? 'var ' : '',
        $name,
        $source
      );
    }

    // if 'lbr' assigned then we must render a line break
    if ($lbr)
    {
      $source .= $lbr;
    }

    return $source;
  }

  /**
   * Creates Javascript source for Extjs2.class
   *
   * @param array attributes
   * @param array config
   * @param array parameters
   * @return string source
   */
  public static function getExtObjectComponent($attributes = array(), $config = array(), $parameters = array(), $datas = array())
  {
    $isAssoc = self::isAssoc($config['class'], $attributes);
    $LBR_B_L = $isAssoc ? self::LBR_CB_L : self::LBR_SB_L;
    $LBR_B_R = $isAssoc ? self::LBR_CB_R : self::LBR_SB_R;

    $attributes = self::_build_attributes($attributes, $config['attributes']);
    $attributes = sprintf('%s', $attributes != '' ? $LBR_B_L.$attributes.$LBR_B_R : '');

    $parameters = implode(self::LBR_CM, $parameters);
    $datas = $config['class'] == 'anonymousClass' ? self::_build_datas($datas) : (!empty($datas) ? "'".implode("'".self::LBR_CM."'", $datas)."'" : '');

    switch ($config['class'])
    {
      case 'anonymousClass':
        $source = sprintf(
          '%s%s%s%s%s',
          $parameters,
          $parameters != '' && $datas != '' ? self::LBR_CM : '',
          $datas,
          $datas != '' && $attributes != '' ? self::LBR_CM : '',
          $attributes
        );
        return $source;

      case 'customClass':
        $source = sprintf(
          '{ %s }',
          $attributes
        );
        return $source;

      default:
        $source = sprintf(
          'new %s (%s%s%s%s%s)',
          $config['class'],
          $parameters != '' ? self::LBR_SB_L.$parameters.self::LBR_SB_R : '',
          $parameters != '' && $datas != '' ? self::LBR_CM : '',
          $datas,
          $datas != '' && $attributes != '' ? self::LBR_CM : '',
          $attributes
        );
        return $source;
    }

  }

  /**
   * add sources for css and js to html head
   *
   * default extjsVersion loaded is 3
   */
  public function load()
  {
  	$extjsVersion=$this->getExtjsVersion();
    $response = sfContext::getInstance()->getResponse();
    $configSfCombinePlugin['enabled'] = sfConfig::get('app_sfCombinePlugin_enabled', false);
	$configSfCombinePlugin['js'] = sfConfig::get('app_sfCombinePlugin_js', array());
	$configSfCombinePlugin['css'] = sfConfig::get('app_sfCombinePlugin_css', array());
	
	if($configSfCombinePlugin['enabled'])
	{
		$sfCombineServerObj=sfCombineServerPeer::getDefault();

		if($sfCombineServerObj)
		{
			$serverStatus=($sfCombineServerObj->getOnline()?'online':'offline');
		}
		else {
			$serverStatus='offline';
		}		
	}
	
	if(!$configSfCombinePlugin['enabled'])
	{
	    // add javascript sources for adapter
	    $adapters = sfConfig::get('sf_extjs'.$extjsVersion.'_adapters', array());
		
	    if ($this->options['adapter']) {
	        foreach ($adapters[$this->options['adapter']] as $file)
	        {
	            $response->addJavascript(sfConfig::get('sf_extjs'.$extjsVersion.'_js_dir').$file, 'first');
	            $this->jsVar[] = sfConfig::get('sf_extjs'.$extjsVersion.'_js_dir').$file;	           
	        }
	    }

	    // add javascript sources for ext all
	    $debug = (sfConfig::get('sf_web_debug', false)) ? '-debug' : ''; // if in web_debug mode, also use debug-extjs source
	    $response->addJavascript(sfConfig::get('sf_extjs'.$extjsVersion.'_js_dir').'ext-all'.$debug.'.js', 'first');
	    $this->jsVar[] = sfConfig::get('sf_extjs'.$extjsVersion.'_js_dir').'ext-all'.$debug.'.js';
	}
	
	if($configSfCombinePlugin['enabled'])
	{
	    foreach ($configSfCombinePlugin['js'][$serverStatus] as $path)
	    {
	    	$response->addJavascript($path, 'first');
	    	$this->jsVar[] = $path;	    	
	    }
	}

    if (isset($this->addons['js']))
    {
      foreach ($this->addons['js'] as $jsAddon)
      {      
        $response->addJavascript($jsAddon, 'first');
        $this->jsVar[] = $jsAddon;
      }
    }

    //if(!$configSfCombinePlugin['enabled'])
    {
    	// add css sources for ext all
    	$response->addStylesheet(sfConfig::get('sf_extjs'.$extjsVersion.'_css_dir').'ext-all.css', 'first');
    	$this->cssVar[] = sfConfig::get('sf_extjs'.$extjsVersion.'_css_dir').'ext-all.css';	    
    }
    
    if($configSfCombinePlugin['enabled'])
    {
	    foreach ($configSfCombinePlugin['css'][$serverStatus] as $path)
	    {
	    	$response->addStylesheet($path, 'first');
	    	$this->cssVar[] = $path;
	    }
    }

    if($extjsVersion==2)
    {
    	// add css sources for ext fixes
    	$response->addStylesheet(sfConfig::get('sf_extjs'.$extjsVersion.'_plugin_dir').'patches/fixes.css', 'first');
    	$response->addJavascript(sfConfig::get('sf_extjs'.$extjsVersion.'_js_dir').'build/widgets/form/Label-min.js');
    }

    // add css sources for theme
    $themes = sfConfig::get('sf_extjs'.$extjsVersion.'_themes', array());
	
    if ($this->options['theme']) {
        foreach ($themes[$this->options['theme']] as $file)
        {        	
            $response->addStylesheet(sfConfig::get('sf_extjs'.$extjsVersion.'_css_dir').$file, 'first');            
        }
    }    
	
    if (isset($this->addons['css']))
    {
      $this->addons['css'] = array_unique($this->addons['css']);
      foreach ($this->addons['css'] as $cssAddon)
      {
        $response->addStylesheet($cssAddon, 'first');
        $this->cssVar[] = $cssAddon;        
      }
    }    
  }

  /**
   * writes opening tag for javascript
   *
   * @param  boolean scripttag
   * @return string source
   */
  public function begin($script = true)
  {
    $source = self::LBR;
    if($script) $source .= sprintf("<script type='text/javascript'>%s", self::LBR);
    $source .= self::_comment(sprintf("%s// appFlower: v%s%s", self::LBR, sfConfig::get('app_appFlower_version'), self::LBR));
    $source .= sprintf("Ext.BLANK_IMAGE_URL = '%s'%s", sfConfig::get('sf_extjs'.$this->getExtjsVersion().'_spacer'), self::LBR_SM);
    
    $source .= "Ext.state.Manager.setProvider(new Ext.state.CookieProvider());";
	$source .= "\nvar GLOBAL_JS_VAR = ".json_encode($this->jsVar).";";
	$source .= "\nvar GLOBAL_CSS_VAR = ".json_encode($this->cssVar).";";
    $this->source.=$source;
    $this->preventFrames();
  }

  /**
   * Running inside a iframe is not allowed.
   * Clickjacking makes it dangerous.
   */
  private function preventFrames()
  {
      $this->source .= (
"
if (window.top !== window.self) {
    window.top.location.replace(window.self.location.href);
    alert('For security reasons, frames are not allowed.');
    setInterval(function(){document.body.innerHTML='';},1);
}
");
  }

  /**
   * writes closing tag for javascript
   *   *
   * @param  string source
   * @param  boolean scripttag
   * @return Javascript source
   */
  public function end($source = '', $script = true)
  {
    $source  = sprintf("%s%s%s", self::LBR, $source, $source != '' ? self::LBR : '');
    if($script) $source .= sprintf("</script>%s", self::LBR);

    $this->source.=$source;
  }

  /**
   * writes opening class tag
   *
   * @param string namespace
   * @param string classname
   * @param string extend
   * @param array attributes
   * @return string source
   */
  public function beginClass($namespace = null, $classname = null, $extend = null, $attributes = array())
  {
    $source = '';

    // write namespace directive
    // prevent double output of namespace directive
    if ($this->namespace !== $namespace)
    {
      $this->namespace = $namespace;
      $source .= self::_comment(sprintf("%s// namespace: %s%s", self::LBR, $namespace, self::LBR));
      $source .= sprintf("Ext.namespace('%s')%s", $namespace, self::LBR_SM);
    }

    // write class tag
    $source .= self::_comment(sprintf("%s// class: %s.%s%s", self::LBR, $namespace, $classname, self::LBR));
    $source .= sprintf("%s.%s = Ext.extend(%s, { %s", $namespace, $classname, $extend, self::LBR);

    // write attributes
    $source .= self::_build_attributes($attributes);

    $this->source.=$source;
  }

  /**
   * writes closing class tag
   *
   * @return Javascript source
   */
  public function endClass()
  {
    $source  = '';
    $source .= sprintf("})%s%s", self::LBR_SM, self::LBR_SM);

    $this->source.=$source;
  }

  /**
   * writes begining application tag
   *
   * @param attributes['name']
   * @param attributes['private']
   * @param attributes['public']
   * @return string source
   */
   public function beginApplication($attributes = array())
   {
     // private attributes
     $sourcePrivate = '';
     if (isset($attributes['private']))
     {
       foreach ($attributes['private'] as $key => $value)
       {
         $sourcePrivate .= sprintf("%svar %s = %s;", self::LBR, $key, self::_quote($key, $value));
       }
     }

     // public attributes
     $sourcePublic = '';
     if (isset($attributes['public']))
     {
       // write attributes
       $sourcePublic .= self::_build_attributes($attributes['public']);
     }

     // write application syntax
     $source  = '';
     $source .= self::_comment(sprintf("%s// application: %s%s", self::LBR, $attributes['name'], self::LBR));
     $source .= sprintf(
       'var %s = function () { %s%sreturn {%s %s %s',
       $attributes['name'],
       $sourcePrivate,
       $sourcePrivate != '' ? self::LBR : '',
       self::LBR,
       $sourcePublic,
       $sourcePublic != '' ? self::LBR : ''
     );

     $this->source.=$source;
   }

  /**
   * writes closing application tag
   *
   * @return source
   */
  public function endApplication()
  {
    $source  = '';
    $source .= sprintf("%s}}()%s", self::LBR, self::LBR_SM);

    $this->source.=$source;
  }

  /**
   * writes init application tag
   *
   * Usage:
   *
   *    $sfExtjs2Plugin->initApplication('App');
   *
   *    => Ext.onReady(App.init, App);
   *
   *    $sfExtjs2Plugin->initApplication('App', true, 'myInit');
   *
   *    => Ext.onReady(App.myInit, App, true);
   *
   * @param string scope
   * @param boolean override
   * @param string fn
   * @return string source
   */
  public function initApplication($scope, $override = false, $fn = 'init')
  {
    $source  = '';
    $source .= sprintf("%sExt.onReady(%s.%s, %s%s)%s", self::LBR, $scope, $fn, $scope, $override ? ', true' : '', self::LBR_SM);

    $this->source.=$source;
  }

  /**
   * returns source of custom class
   *
   * Usage:
   *
   *     $sfExtjs2Plugin->customClass('Ext.app.symfony.ModuleA', array('title' => 'Module A', 'closable' => false));
   *
   *     => new Ext.app.symfony.ModuleA ({title:'Module A',closable:false})
   *
   * @param string classname
   * @param array attributes
   * @return string source
   */
  public function asCustomClass($classname, $attributes = array())
  {
    $source  = '';
    $source .= $this->getExtObjectComponent($attributes, array('attributes'=>array(), 'class'=>$classname));

    return new sfExtjs2Var($source);
  }

  /**
   * returns source of anonymous class
   *
   * Usage:
   *
   *     $sfExtjs2Plugin->asAnonymousClass(array('name'=>'id','mapping'=>'id','type'=>'int'));
   *
   *     => {name: 'id', mapping: 'id', type: 'int'}
   *
   * @param string classname
   * @param array attributes
   * @return string source
   */
  public function asAnonymousClass($attributes = array())
  {
    $source  = '';
    $source .= $this->getExtObject('anonymousClass', $attributes);

    return new sfExtjs2Var($source);
  }

  /**
   * returns source of anonymous listener
   *
   * Usage:
   *
   *     $sfExtjs2Plugin->asListener(array
   *     (
   *      'rowcontextmenu' => $sfExtjs2Plugin->asMethod(array
   *      (
   *        'parameters' => 'grid, rowIndex, e',
   *        'source'     => "
   *
   *           // ensure that row could reselect
   *          // if onLoad event of data store occurs
   *          grid.selectedRowIndex = rowIndex;
   *          grid.getSelectionModel().selectRow(rowIndex);
   *
   *          // prevent browser default context menu
   *          e.stopEvent();
   *
   *          // show context menu
   *          var coords = e.getXY();
   *          grid.cmenu.showAt([coords[0], coords[1]]);
   *        "
   *      ))
   *    ))
   *
   * @param string classname
   * @package array attributes
   * @return string source
   */
  public function asListener($attributes = array())
  {
    $source = '';
    foreach ($attributes as $key => $value)
    {
      $source .= sprintf
      (
        '%s"%s":%s',
        $source != '' ? self::LBR_CM : '',
        $key,
        $value
      );
    }
    $source = sprintf('{ %s }', $source);

    return new sfExtjs2Var($source);
  }

  /**
   * returns string the passed string without additional quoting
   *
   * @param string var
   * @return sfExtjs2Var var
   */
  public static function asVar($var)
  {
    return new sfExtjs2Var($var);
  }

  /**
   * returns source for method including output of evaled php code
   *
   * Usage:
   *
   *    Syntax A = short form without any options
   *    $sfExtjs2Plugin->asMethod('alert("foo");');
   *
   *     => function() { alert("foo"); }
   *
   *    Syntax B = short form with parameters
   *    $sfExtjs2Plugin->asMethod(array('parameters' => 'msg', 'source' => 'alert(msg)'));
   *
   *     => function(msg) { alert(msg); }
   *
   * @param array attributes
   * @return string source
   */
  public static function asMethod($attributes = array())
  {
    $name = is_array($attributes) && isset($attributes['name']) ? $attributes['name'] : '';
    $parameters = is_array($attributes) && isset($attributes['parameters']) ? $attributes['parameters'] : '';

    $source = is_array($attributes) && isset($attributes['source']) ? $attributes['source'] : $attributes;
    $source = preg_replace_callback(
      '/(\<\?php)(.*?)(\?>)/si',
      array('self', '_methodEvalPHP'),
      $source
    );
    $source = sprintf("function %s(%s) { %s }", $name, $parameters, $source);

    return new sfExtjs2Var($source);
  }
  
  /**
   * returns output of evaled php code
   *
   * @param array matches
   * @return string source
   */
  private static function _methodEvalPHP ($matches)
  {
    $source = str_replace( array('<?php', '<?', '?>'), '', $matches[0]);
    ob_start();
    eval($source);
    $source = ob_get_contents();
    ob_end_clean();

    return $source;
  }

  /**
   * Build attributes based on custom attributes and default attributes.
   * Custom attributes and default attributes will merged.
   * Custom attributes overwrites default attributes.
   *
   * Usage:
   *
   *         _build_attributes(
   *             array('foo' => 'custombar', 'foo1' => 'bar1', 'foo2' => 'bar2'),    // custom attributes
   *             array('foo' => 'defbar')                                            // default attributes
   *        )
   *
   *         returns 'foo: custombar, foo1: bar1, foo2: bar2'
   *
   * @param array custom attributes
   * @param array default attributes
   * @return string merged attributes
   */
  private static function _build_attributes ($custom_attributes = array(), $default_attributes = array(), $always_quote_numeric = false)
  {
    $attributes = '';

    $merged_attributes = $default_attributes;
    if (is_array($custom_attributes) && is_array($default_attributes))
    {
      $merged_attributes = array_merge($default_attributes, $custom_attributes);
    }

    foreach ($merged_attributes as $key => $value)
    {
      if (!is_numeric($key))
      {
        $attributes .= sprintf('%s%s: %s', ($attributes == '' ? '' : self::LBR_CM), $key, self::_quote($key, $value));
      }
      else
      {
      	$attributes .= sprintf('%s%s', ($attributes == '' ? '' : self::LBR_CM), self::_quote($key, $value, $always_quote_numeric));
      }
    }

    return $attributes;
  }

  private static function _build_datas ($custom_datas = array(), $isArray = false)
  {
    if (!is_array($custom_datas)) return $custom_datas;
    if (empty($custom_datas)) return '';

    $datas = $isArray ? self::LBR_SB_L : self::LBR_CB_L;
    $first = true;

    foreach ($custom_datas as $key => $value)
    {
      $final_value = '\''.$value.'\'';
      if (is_array($value))
      {
        $final_value = self::_build_datas($value, $isArray ? false : true);
      }

      if (!is_numeric($key))
      {
        $datas .= sprintf('%s%s: %s', ($first ? '' : self::LBR_CM), $key, $final_value);
      }
      else
      {
        $datas .= sprintf('%s%s', ($first ? '' : self::LBR_CM), $final_value);
      }

      $first = false;
    }

    $datas .= $isArray ? self::LBR_SB_R : self::LBR_CB_R;

    return $datas;
  }

  /**
   * checks if $arr is a numeric Array
   * @param Array
   */
  private static function isNumericArray($arr){
    foreach ( $arr as $key => $value ) {
      if ( !is_numeric( $key ) ) {
        return false;
      }
    }
    return true;
  }

  /**
   * checks if $arr is a simple Array (contains
   * a list of komma-separated values)
   *
   * @param Array
   */
  private static function isSimpleArray($arr){
    foreach ( $arr as $key => $value ) {
      if ( !is_numeric( $key ) ) {
        return false;
      }
      if (is_array($value)){
        return false;
      }
    }
    return true;
  }

  /**
   * quotes everything except:
   *   values that are arrays
   *   values that are sfExtjs2Var
   *   values and keys that are listed in sf_extjs2_quote_except
   *
   * @param string key
   * @param string value
   * @return string attribute
   */
  public static function _quote($key, $value, $always_quote = false)
  {
    if (is_array($value))
    {
      $numeric = self::isNumericArray($value);
      if ($numeric && self::isSimpleArray($value))
      {
          return json_encode($value);
      }

      // test if key is one of the special list-attributes
      if (!$numeric && in_array($key, sfConfig::get('sf_extjs2_list_attributes'), true))
      {
          $numeric = true;
      }

      $attribute = '';
      foreach ($value as $k => $v)
      {
        if (!$numeric)
        {
          $attribute .= sprintf('%s%s: %s', ($attribute == '' ? '' : self::LBR_CM), $k, self::_quote($k, $v));
        }
        else
        {
          $attribute .= sprintf('%s%s', ($attribute == '' ? '' : self::LBR_CM), self::_quote($k, $v));
        }
      }

      if ($numeric)
      {
        $attribute = sprintf('[ %s ]', $attribute);
      }
      else
      {
        $attribute = sprintf('{ %s }', $attribute);
      }
      return $attribute;
    }

    if (is_bool($value ))
    {
      $attribute = $value ? 'true' : 'false';
      return $attribute;
    }

    if (is_null($value ))
    {
      return 'null';
    }

    //always quote has of course an exception for numerics and sfExtjs2Vars
    if (!is_numeric($value) && (($always_quote && !$value instanceof sfExtjs2Var) || (!$value instanceof sfExtjs2Var && self::_quote_except($key, $value))))
    {
      $attribute = json_encode($value);
      return $attribute;
    }

    $attribute = $value;
    return $attribute;
  }

  /**
   * @param string key
   * @param string value
   * @return boolean quote
   */
  private static function _quote_except($key, $value)
  {
    $quoteExcept = sfConfig::get('sf_extjs2_quote_except');

    // Values with numeric keys are not quoted,
    // because the _build_attributes() don't want them so.
    if (is_int($key) || is_int($value))
    {
      return false;
    }

    $listAttributes = sfConfig::get('sf_extjs2_list_attributes');
    if (in_array($key, $listAttributes))
    {
      return false;
    }

    foreach ($quoteExcept['key'] as $except)
    {
      if ($key == $except)
      {
        return false;
      }
    }

    foreach ($quoteExcept['value'] as $except)
    {
      if (substr($value, 0, strlen($except)) == $except)
      {
        return false;
      }
    }

    return true;
  }

  /**
   * @param string comment
   * @return string comment
   */
  private static function _comment($comment)
  {
    if (sfConfig::get('sf_extjs2_comment'))
    {
      return $comment;
    }
    else
    {
      return '';
    }
  }

}

/**
 * @class            sfExtjs2Var
 * @description      sfExtjs2Var is used by quoting logic which ignores variables of this class
 * @author           Leon van der Ree
 * @version          0.0.01
 * @last modified    12.13.2007 Leon:
 *                     - created
 */
class sfExtjs2Var {

  private $var = '';

  public function __construct($var)
  {
    $this->var = $var;
  }

  public function __toString()
  {
    return $this->var;
  }

}



?>
