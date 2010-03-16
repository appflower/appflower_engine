<?php

sfProjectConfiguration::getActive()->loadHelpers(array('Tag', 'Asset', 'Url'));

/**
 * Returns <script> tags with the url toward all javascripts configured in view.yml or added to the response object.
 *
 * You can use this helper to decide the location of javascripts in pages.
 * By default, if you don't call this helper, symfony will automatically include javascripts before </head>.
 * Calling this helper disables this behavior.
 *
 * @return string <script> tags
 */
function get_combined_javascripts()
{
  if (!sfConfig::get('app_sfCombinePlugin_enabled', false)) return get_javascripts();

  $response = sfContext::getInstance()->getResponse();
  sfConfig::set('symfony.asset.javascripts_included', true);    
  $configSfCombinePlugin['js'] = sfConfig::get('app_sfCombinePlugin_js', array());
      
  $html = '';
  $jsFiles = $include_tags = array();
  foreach (array('first', '', 'last') as $position)
  {
    foreach ($response->getJavascripts($position) as $files => $options)
    {
    	if(!in_array($files,$configSfCombinePlugin['js']['online'])&&!in_array($files,$configSfCombinePlugin['js']['offline']))
    	{
    	  if (!is_array($files))
	      {
	        $files = array($files);
	      }
	      $jsFiles = array_merge($jsFiles, $files);
    	}
    	else{
    		$include_tags[]=javascript_include_tag(url_for($files));
    	}
    }
  }
  
  $key = _get_key($jsFiles);
  
  $include_tags[]=str_replace('.js', '', javascript_include_tag(url_for('sfCombine/js?key='.$key)));
  
  return implode("",$include_tags);
}

function include_combined_javascripts()
{
  echo get_combine_javascripts();
}

/**
 * Returns <link> tags with the url toward all stylesheets configured in view.yml or added to the response object.
 *
 * You can use this helper to decide the location of stylesheets in pages.
 * By default, if you don't call this helper, symfony will automatically include stylesheets before </head>.
 * Calling this helper disables this behavior.
 *
 * @return string <link> tags
 */
function get_combined_stylesheets()
{
  if (!sfConfig::get('app_sfCombinePlugin_enabled', false)) return get_stylesheets();

  $response = sfContext::getInstance()->getResponse();
  sfConfig::set('symfony.asset.stylesheets_included', true);
  $configSfCombinePlugin['css'] = sfConfig::get('app_sfCombinePlugin_css', array());
    
  $html = '';
  $cssFiles = $include_tags = array();
  foreach (array('first', '', 'last') as $position)
  {
    foreach ($response->getStylesheets($position) as $files => $options)
    {
    	if(!in_array($files,$configSfCombinePlugin['css']['online'])&&!in_array($files,$configSfCombinePlugin['css']['offline']))
    	{
	      if (!is_array($files))
	      {
	        $files = array($files);
	      }
	      $cssFiles = array_merge($cssFiles, $files);
    	}
    	else{
    		$include_tags[]=stylesheet_tag(url_for($files));
    	}
    }
  }
  $key = _get_key($cssFiles);
  $include_tags[]=str_replace('.css', '', stylesheet_tag(url_for('sfCombine/css?key='.$key)));
  
  return implode("",$include_tags);
}

function include_combined_stylesheets()
{
  echo get_combined_stylesheets();
}

/**
 * Returns a key which combined all assets file
 *
 * @return string md5
 */
function _get_key($files)
{
	$content = base64_encode(serialize($files));
	$key = md5($content . sfConfig::get('app_sfCombine_asset_version', ''));
	
	if (!class_exists('DbFinder'))
	{
	throw new Exception('sfCombine expects DbFinder to call combined asset file');
	}
	
	$keyExists = DbFinder::from('sfCombine')->
	where('AssetsKey', $key)->
	count();  
	
	if (!$keyExists)
	{
		$combine = new sfCombine();
		$combine->setAssetsKey($key);
		$combine->setFiles($content);
		$combine->save();
	}
	
	// Checks if key exists
	if(!sfProcessCache::has($key))
	{
		sfProcessCache::set($key, true);
	}
	
	return $key;
}