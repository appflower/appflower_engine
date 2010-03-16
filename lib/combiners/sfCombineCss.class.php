<?php

class sfCombineCss extends sfCombiner
{
  /**
   * Processes the assets corresponding to a hash
   * 
   * @param string $key Key to a list of asset files in the `sf_combine` table
   *
   * @return string Processed CSS code
   */
  public function process($key)
  {
  	$files = $this->getContents($key);
  	
  	$config = sfConfig::get('app_sfCombinePlugin_css', array());
    
    foreach ($config['online'] as $file)
    {
    	if(isset($files[$file]))
    	unset($files[$file]);
    }
    
    foreach ($config['offline'] as $file)
    {
    	if(isset($files[$file]))
    	unset($files[$file]);
    }
    
    $finalContent = $this->merge($files);
                
    if (isset($config['minify']) && $config['minify'])
    {
      $finalContent = $this->minify($finalContent);
    }
    
    return $finalContent;
  }
  
  /**
   * Minify content
   *
   * @param string $content Content to be minified
   * 
   * @return string Minified content
   */
  protected  function minify($content)
  {
    return cssmin::minify($content);
  }
  
  protected function getAssetPath($file)
  {
    sfProjectConfiguration::getActive()->loadHelpers('Asset');
    
    return stylesheet_path($file);
  }
}

?>