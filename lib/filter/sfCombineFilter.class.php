<?php
/**
 * sfCombineFilter
 *
 * @package    sfCombinePlugin
 * @subpackage filter
 * @author     Alexandre Mogère
 */
class sfCombineFilter extends sfFilter
{
  public function execute($filterChain)
  {
    // execute next filter
    $filterChain->execute();
    
    $response = $this->context->getResponse();
    $content = $response->getContent();
    
    // include javascripts and stylesheets
    if (false !== ($pos = strpos($content, '</head>')))
    {
      sfProjectConfiguration::getActive()->loadHelpers(array('sfCombine'));
      $html = '';
      
      if (!sfConfig::get('symfony.asset.javascripts_included', false))
      {
        $html .= get_combined_javascripts();
      }
      
      if (!sfConfig::get('symfony.asset.stylesheets_included', false))
      {
        $html .= get_combined_stylesheets();
      }
      
      if ($html)
      {
        $response->setContent(substr($content, 0, $pos) . $html . substr($content, $pos));
      }
    }
    
    sfConfig::set('symfony.asset.javascripts_included', false);
    sfConfig::set('symfony.asset.stylesheets_included', false);
  }
  
}