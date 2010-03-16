<?php

class sfCombineJs extends sfCombiner
{
  /**
   * Processes the assets corresponding to a hash
   * 
   * @param string $key Key to a list of asset files in the `sf_combine` table
   *
   * @return string Processed Javascript code
   */
  public function process($key)
  {
    $files = $this->getContents($key);
    
    $config = sfConfig::get('app_sfCombinePlugin_js', array());
    
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
    
    if (isset($config['minify']) && $config['minify'])
    {
      // minification
      $skipMinify = isset($config['minify_skip']) ? $config['minify_skip'] : array();
      
      foreach ($files as $filePath => $content)
      {
        if (!in_array($filePath, $skipMinify))
        {
          $files[$filePath] = $this->minify($content);
        }
      }
    }
    
    // packing
    if (isset($config['pack']) && $config['pack'])
    {
      $skipPack = isset($config['pack_skip']) ? $config['pack_skip'] : array();
      if(!$skipPack)
      {
        // simple: pack everything together
        $finalContent = $this->pack($this->merge($files));
      }
      else
      {
        // less simple: pack groups of files, avoiding the ones that should not be packed
        $finalContent = '';
        $toProcess = '';
        foreach ($files as $filePath => $content)
        {
          if (!in_array($filePath, $skipPack))
          {
            $toProcess .= $content;
          }
          else
          {
            $finalContent .= $this->pack($toProcess);
            $finalContent .= $content;
            $toProcess = '';
          }
        }
        if($toProcess)
        {
          $packer = new JavaScriptPacker($toProcess, 'Normal', true, false);
          $finalContent .= $packer->pack();
        }
      }
    }
    else
    {
      // no packing at all, simply merge
      $includeComment = isset($config['minify']) ? !$config['minify'] : true;
      $finalContent = $this->merge($files, $includeComment);
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
  public function minify($content)
  {
    $packer = new JavaScriptPacker($content, 'None', false, false);
    
    return $packer->pack();
  }

  /**
   * Pack content
   *
   * @param string $content Content to be packed
   * 
   * @return string Packed content
   */
  public function pack($content)
  {
    $packer = new JavaScriptPacker($content, 'Normal', false, false);
    
    return $packer->pack();
  }
  
  protected function getAssetPath($file)
  {
    sfProjectConfiguration::getActive()->loadHelpers('Asset');

    return javascript_path($file);
  }
}