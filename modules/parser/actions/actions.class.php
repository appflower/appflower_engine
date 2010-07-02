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
	
	/***************************************************************************************************** 
	 * These functions get the raw data from the datasource
	 *****************************************************************************************************/
	
	/*
	 * This one returns the raw data from a file, as a pager. This will be processed
	 * by the getItemsFile function.
	 * 
	 */
	private function getRawDataFile($parser,$args) {
		
		list($start,$limit,$sort,$sort_dir,$filters,$export) = $args;
		
		$pager = null;
		$page=($start==0)?1:(@ceil($start/$limit)+1);		
		
		if($this->getUser()->getProfile()) {
			if($this->getUser()->getProfile()->getWidgetHelpIsEnabled()) {
				$chunk = sfConfig::get("app_parser_filepager_help");
			} else {
				$chunk = sfConfig::get("app_parser_filepager_nohelp");
			}	
			
		}
	
		if($export == "all") {
			$chunk = sfConfig::get("app_parser_filepager_all");
		}
		
		$pager = new immFilePager($parser["datafile"],$chunk,($export == "all") ? sfConfig::get("app_parser_max_chunk_size"): 1);
		$pager->setPage($page);
		$pager->init();
		
		//print_r($pager->getResults());
		
		return $pager;
		
	}
	
	
	/***************************************************************************************************** 
	 * These functions will process the raw data and create input array for grids.
	 * ***************************************************************************************************/
	
	/*
	 * This creates the output from pager data. Can be used when the source is a file.
	 * 
	 */
	private function getItemsFile($pager,$parser) {
		
		$items = array();
		
		if(isset($parser['realtime']) and $parser['realtime'] == "yes")	{				
			$items = call_user_func(array($parser["datasource"]["class"],$parser["datasource"]["method"]["name"]),$parser["datafile"]);			
		} else { 			
			foreach($pager->getResults() as $s) {
				if($parser["datasource"]["lister"] !== "false") {
					$item = call_user_func(array($parser["datasource"]["class"],$parser["datasource"]["method"]["name"]),$s);
					if($item !== false) {
						$items[] = $item;
					}
				} else {
					$items[] = array_combine($parser["columns"],$s);	
				}	
			}
		}
		
		return $items;
	}
	

	/***************************************************************************************************** 
	 * Other private functions, used by the main actions and methods.                                    *
	 * ***************************************************************************************************/
	
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
