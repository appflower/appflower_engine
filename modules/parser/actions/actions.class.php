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
	
	public function executeExportJobList() {
		
		 $this->status = $this->getRequestParameter("type",sfJobPeer::SUCCESS);
		 return XmlParser::layoutExt($this);
		
	}
	
	public function executeSendfile() {
		
		$config = sfConfig::get("app_parser_export");
		$root = $config["root"];
		
		$file = $this->getRequestParameter("file");
		$dir = $this->getRequestParameter("dir");
		$this->forward404Unless(($file && $dir));
		$data = file_get_contents($root."/".$dir."/".$file);
		
		HttpUtil::forceDownload($this,$data,"tmp.csv");
		
		
	}
	
	public function executeDeleteExportJob($request,$job = false) {
		
		 $config = sfConfig::get("app_parser_export");
		 $root = $config["root"];
		 $redirect = (!($job));
		
		 if(!$job) {
		 	
		 	$jid = $this->getRequestParameter("id");
		 	$this->forward404Unless($jid);
		 
		 	$job = sfJobPeer::retrieveByPK($jid);
		 	$this->forward404Unless($job);
		 
		 } 
		 
		 if(!$job->isRunning()) {
		 	
		 	$params = $job->getUnserializedParams();
		 	
			if(isset($params["csv_file"]) && file_exists($root."/".$params["csv_file"]) && !@unlink($root."/".$params["csv_file"])) {
			 	//unlink($root."/".$params["csv_file"]);
			 	//exit();
				//throw new Exception("Unable to delete file: ".$params["csv_file"]);
			}
			 
			$job->delete();	
			
		 }
		 
		 if($redirect) {
		 	$result = array('success' => true, 'message' => "The job has been successfuly removed", 'redirect' => "/parser/exportJobList");
			$result = json_encode($result);
			return $this->renderText($result);
		 }
		
	}
	
	public function executeDeleteJobs() {
		
		$queue_name = $this->getRequestParameter("qname");
		$queue = sfJobQueuePeer::retrieveByQueueName($queue_name);
		
		if(!$queue) {
			$result = array('success' => false, 'message' => "Queue doesn't exist yet! Please create some jobs first..", 'redirect' => "/parser/exportJobList");
			$result = json_encode($result);
			return $this->renderText($result);
		} else {
			$qid = $queue->getId(); 
		}
		
		if($this->getRequest()->getMethod() == sfRequest::POST)
		{
			$post = $this->getRequest()->getParameterHolder()->getAll();
			if(isset($post['all'])){
				//Additional action to perfom: delete ...............................
				$c = new Criteria();
				$c->add(sfJobPeer::SF_JOB_QUEUE_ID,$qid);
				$jobs = sfJobPeer::doSelect($c);
				foreach($jobs as $job){
					if($job != null){
						if(!$job->isRunning()){
							$this->executeDeleteExportJob(null,$job);
						}
					}					
				}					 
				$msg = "All data removed successfully";
			}else{							
				$items = json_decode($post["selections"],true);
				if(!count($items)){
					$result = array('success' => true,'message'=>'No items selected..');
					$result = json_encode($result);
					return $this->renderText($result);
				}
				foreach ($items as $item){
					// Delete individual 
					preg_match("/id=([0-9]+)/",$item['action1'],$matches);
					$id = preg_replace("/id=([0-9]+)/","$1",$matches[0]);	
					$job = sfJobPeer::retrieveByPk($id);
					if($job != null){
						if(!$job->isRunning()) {
							$this->executeDeleteExportJob(null,$job);
						}
					}				    				
				}
				$msg = "Selected data removed successfully";
			}

			$result = array('success' => true, 'message' => $msg, 'redirect' => "/parser/exportJobList");
			$result = json_encode($result);
			return $this->renderText($result);
				
		}
	}
	
	
	
	public static function getExportJobs($status = self::SUCCESS) {
		
		$config = sfConfig::get("app_parser_export");
		$root = $config["root"];
		
      	sfLoader::loadHelpers(array('Url','Tag'));
      
	   	$results = JobUtil::findQueueJobs('CSV_Exports');
	   	
	   	foreach($results as &$row) {
	   		$output = '';
	          if ($row['statusCode'] === sfJobPeer::SUCCESS) {
	          
	          	 if(file_exists($root."/".$row['csv_file'])) {  
		          	
	          	 	$dirname = substr($row['csv_file'],0,strrpos($row['csv_file'],"/"));
		          	$file = str_replace($dirname."/","",$row['csv_file']);
	          	 	$row['csv_link'] = ' '.link_to("fetch file",'parser/sendfile?file='.$file.'&dir='.$dirname);
	              } else {
	              	$row['csv_link'] = "No generated files yet..";
	              }    
	          }
	          
	   	}
	   	
		return $results;
		
	  }
	
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
