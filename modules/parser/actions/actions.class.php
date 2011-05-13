<?php

/**
 * parser actions.
 *
 * @package    manager
 * @subpackage parser
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class parserActions extends sfActions
{
    /**
	 * retrieving description and images for some widgets
	 * @author radu
	 */
	public function executeRetrieveWidgetsInfo()
	{
		$portalWidgets=json_decode($this->getRequestParameter('portalWidgets'));
				
		foreach ($portalWidgets as $pwi=>$portalWidgetsFielset)
		{
			foreach ($portalWidgetsFielset->widgets as $pfwi=>$widget)
			{
				$ma=explode('/',$widget);
				$path = sfConfig::get("sf_root_dir").'/apps/'.$this->context->getConfiguration()->getApplication().'/modules/'.$ma[1].'/config/'.$ma[2].'.xml';
				$dom = new DOMDocument();
				if($dom->load($path))
				{
					foreach ($dom->documentElement->childNodes as $oChildNode) {
						if($oChildNode->nodeName=='i:title')
						{
							$title=trim($oChildNode->nodeValue);
						}
						if($oChildNode->nodeName=='i:description')
						{
							$description=trim($oChildNode->nodeValue);	
							$image=$oChildNode->getAttribute('image');					
						}
					}
					
					$portalWidgets[$pwi]->widgetsInfo[$pfwi]['title']=$title;
					$portalWidgets[$pwi]->widgetsInfo[$pfwi]['description']=$description;
					$portalWidgets[$pwi]->widgetsInfo[$pfwi]['image']=$image;
				}
			}
		}
		
		$info=json_encode($portalWidgets);
		return $this->renderText($info);
	}
}
