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
	
	  
	  
	private function removeTags(&$items) {
		
		foreach($items as $ik => $item) {
			foreach($item as $ck => $column) {
				$tmp = array();
				$m = preg_match_all("/(<[^>]+>)([^<]+)(<\/[^>]+>)/",$column,$matches);
				if($m) {
					foreach($matches[2] as $k => $v) {
						if(strstr($matches[1][$k],"hidden")) {
							$items[$ik][$ck] = str_replace($matches[1][$k].$matches[2][$k].$matches[3][$k],"",$items[$ik][$ck]);
						} else {
							if(!isset($tmp[$v])) {
								$tmp[$v] = $v;	
							}	
						}	
					}
					if(!empty($tmp)) {
						$items[$ik][$ck] = implode("",$tmp);
					}
				}
			}
		}
	}
	
	
/*
	 * Exports the list as CSV and forces the browser to open download dialogue.
	 * 
	 */
	public function executeCsvexport($request,$job = false) {
		
	
		if(!$job) {
			list($sort,$sort_dir,$start,$limit,$anode,$filters,$export,$uid) = $this->getParameters($this->getRequest());
			$parser = $this->getParserData($uid);	
			
			if(isset($parser[0])) {
				$this->redirect("parser/exportDenied");
			}
			
		} else {
			list($sort,$sort_dir,$start,$limit,$anode,$filters,$export,$data_file,$title,$xsort) = $job->getUnserializedParamsList();
			$uid = null;
			$parser = unserialize(file_get_contents($data_file));
		}
		
		// Create job and queue for export all..
		
		if($export == "all" && $uid) {
			
			$data_file = XmlParser::saveSessionData($parser);
			
			$params = array('sort' => $sort, 'sort_dir' => $sort_dir,'start' => $start,'limit' => $limit,
			'anode' => $anode,'filters' => $filters,'export' => 'all', 'data_file' => $data_file, 
			"title" => $parser["title"],"xsort" => $this->getRequestParameter("xsort",""));

		    $queue = sfJobQueuePeer::retrieveByQueueName('CSV_Exports');
		    
		    if (!$queue)
			{
				$queue = sfJobQueuePeer::createQueue("CSV_Exports","fifo");
			}
			
		    $queue->addJob('CsvExport', $params);
		    
		    $this->redirect("parser/exportJobList");
		    
		}
		
		if(!$job) {
			$items = $this->executeListjson(null,array($sort,$sort_dir,$start,$limit,$anode,$filters,$export,$uid));	
		} else {
			$items = $this->executeListjson(null,array($sort,$sort_dir,$start,$limit,$anode,$filters,$export,null,$data_file,$title,$job,$xsort));	
		}
		
		$this->removeTags($items);
		
		// Sort the results if necessary, since ExtJS can't do it this time..
		
		$page = $sorted_items = $tmp = array();
		
		
		if(!$parser["tree"] && !$job) {
					
			$post = $this->getRequest()->getParameterHolder()->getAll();
			
			if(isset($post["selections"])) {
				$seltmp = json_decode($post["selections"]);
				$items =  array();
				foreach($seltmp as $obj) {
					$arrtmp = array();
					foreach($obj as $propname => $prop) {
						if(substr($propname,0,1) != "_") {
							$arrtmp[$propname] = $prop;
						}
					}
					$items[] = $arrtmp;
				}
			
			}
			
			$this->removeTags($items);
			
		}
		
		if(!ArrayUtil::isTrue($parser, 'remoteSort') && $sort) {
			
			while(!empty($items)) {
				$tmp = array();
				$page = array_splice($items,0,$limit);	
				
				foreach($page as $k => $item) {
					$tmp[$item[$sort]][] = $item;
				}
				
				if($sort_dir == "ASC") {
					ksort($tmp);
				} else {
					krsort($tmp);
				}
				
				foreach($tmp as $t) {
					foreach($t as $i) {
						$sorted_items[] = $i;
					}
				}	
			}
			
			$items = $sorted_items;
		}
		
		$export_columns = array();
		foreach($parser["columns"] as $c) {
			$export_columns[] = (is_array($c)) ? $c["column"] : $c;
		}	
		
		$export_data = "";
		$export_config = sfConfig::get('app_parser_export');
			
		$j = 0;	

			
		// Generate CSV string from input array
			
		// Static pagination
		
		if($parser['static_real_pagination']){
			$ef = explode(",",$parser['exportFrom']);
			$ef['export'] = array('start'=>$start,'limit'=>$limit);
			$export_data = call_user_func(array($ef[0],$ef[1]),$ef);
		} else {
			
			if($parser["tree"]) {
	
				// Trees
				
				$response = $this->getResponse();
				$response->setHttpHeader('Cache-Control', 'no-cache');
				
				if(isset($parser['remoteLoad']) && $parser['remoteLoad']) {
					$ef = explode(",",$parser['exportFrom']);
					$export_data = call_user_func(array($ef[0],$ef[1]),$ef);
					
				} else {
					
					$post = $this->getRequest()->getParameterHolder()->getAll();
					$seltmp = json_decode($post["selections"]);
					
					$selection = Util::groupNodes($seltmp);
					
					foreach($selection as $entry) {
						$tmp = array();
						Util::getNodeAsText($entry,$tmp);
						foreach($tmp as $t) {
							$export_data .= implode($export_config["separator"],$t)."\r\n";	
						}
					}
					
				}
				
			} else {
				
				// Normal and grouped lists. 
				
				if($parser["group_field"]) {
					foreach($items as $item) {
						$output[$item[$parser["group_field"]]][] = $item;
					}
				} else {
					$output["ungrouped"] = $items;
				}
				
				// Date split
				
				$date_columns = array("user_timestamp","date_received","timestamp");
				
				foreach($output as $k => $group) {
					if($k != "ungrouped") {
						$export_data .= $k."\r\n";
					}
					foreach($group as $ik => $item) {
						foreach($item as $k => $cell) {
							$item[$k] = str_replace("\n","",trim(strip_tags($cell)));
							if(!in_array($k,$export_columns)) {
								unset($item[$k]);
							} 
							foreach($date_columns as $dc) {
								if($k == $dc) {
									$m = preg_match("/([0-9]{4}\-[0-9]{2}\-[0-9]{2}) ([0-9]{2}:[0-9]{2}:[0-9]{2})/",$cell,$matches);
									$tmp = array();
									foreach($item as $kx => $value) {
										if($kx != $k) {
											$tmp[$kx] = $value;
										} else {
											$tmp[$k."_date"] = $matches[1];
											$tmp[$k."_time"] = $matches[2];
										}
									}
									$item = $tmp;
								}	
							}
						}
						$export_data .= implode($export_config["separator"],$item)."\r\n";
					}	
				}				
			}			
		}
		
		// Send headers, force download or write tmp file for jobs..
		
		if(!$job) {
			
			HttpUtil::forceDownload($this,$export_data,"tmp.csv");
			
		} else {
			
			$config = sfConfig::get("app_parser_export");
			$root = $config["root"];
			
			$dirname = sfCsvExportJobHandler::generateDirName($title);
			
			$exported_file = FileUtil::saveTmpFile($export_data,$root."/".$dirname,"job_".$job->getId());
		
			$file = substr($exported_file,strrpos($exported_file,$dirname));
			
			$params = $job->getUnserializedParams();
			$params["csv_file"] = $file;
			$job->setParams(serialize($params));
			$job->save();
			
			
		}
		
		
		
	}
	
	public function executeExportDenied() {
		
		$this->html = "The file cannot be exported due to <b>expired data</b>. Please reload the screen you wish to export and try again!"; 
		return XmlParser::layoutExt($this);
	
	}
	
	
	/*
	 * This creates the grid object for lists and prints JSON result.

	 * The $args argument is always an empty array, unless a CSV export is performed, in which
	 * case this will be called as a simple function by other action and $args will provide
	 * the request params.
	 * 
	 */
	public function executeListjson($request,$args = array()) 
	{		
		// 1. Setting params		
		if(!empty($args)) {
			$params = $args;
		} else {
			$params = $this->getParameters($request);
		}
		
		$host = $this->getHost();
		
		// If uid is not null.
		if($params[7] !== null) {
			list($sort,$sort_dir,$start,$limit,$anode,$filters,$export,$uid) = $params;	
			$xsort = $data_file = $title = $job = null;
		} else {
			list($sort,$sort_dir,$start,$limit,$anode,$filters,$export,$uid,$data_file,$title,$job,$xsort) = $params;
		}
		
		// Getting parser session data..
		
		$parser = $this->getParserData($uid,$job);
		
		if(!ArrayUtil::isTrue($parser, 'remoteSort')) {

			if($export) {
				$sort = false;
				$sort_dir = "ASC";	
			}
			
		}
			
		if($export === "all") {
			$limit = null;
			$start = 0;
		}	
		
		
		// Invalid key in session, parser data cannot be found..
		
		
		if(empty($args)) {
			$this->forward404Unless(isset($parser["uid"]) && $parser["uid"] == $uid);	
		} else {
			if(isset($parser["uid"]) && $parser["uid"] != $uid)
				if($export !== "all") {
					return false;	
				}
		}
		
		if($parser["tree"] == 1) {
			$parser["limit"] = $limit = null;
		}

		
		// 2. Getting raw data fromf db / file source..
		
		if(!$parser["sql"]) {
			if(!$parser["sql"] && $parser["type"] == "orm") {
				Newsroom::waitIfRequested($parser['reload_topic']);
				$pager = $this->getRawDataOrm($parser,array($start,$limit,$sort,$sort_dir,$filters,$export,$xsort));		
			} else if(!$parser["sql"] && $parser["type"] == "file") { 
				Newsroom::waitIfRequested($parser['reload_topic']);
				$pager = $this->getRawDataFile($parser,array($start,$limit,$sort,$sort_dir,$filters,$export));
			}
			
		}

		// 3. Initializing grid object.
		
		if(empty($args)) {
			$grid_data = new ImmExtjsGridData();	
			
			if(!isset($parser['static_real_pagination']) || !$parser['static_real_pagination']) {
				if($parser["type"] == "file") {
			  		$grid_data->totalCount = $pager->getNumberOfResults();
			  	} else {			  		
			  		$grid_data->totalCount = ($parser["sql"] || $parser["type"] == "static") ? sizeof($parser["result"]) : $pager->getNbResults();	
			  	}	
			}
			
		} else {
			$grid_data = null;
		}
		
		// 4. Fetching grid data (rows as an array).
		
	  	$items = array();	
		
	  	// ORM / DB
	  	
		if(!$parser["sql"] && $parser["type"] == "orm") {
			$items = $this->getItemsOrm($pager,$parser);
		
		// Files
			
		} else if(!$parser["sql"] && $parser["type"] == "file") {			
			$items = $this->getItemsFile($pager,$parser);
			
		} else {
			
		// Direct SQL queries, static lists. 
			
			$items = $parser["result"];			
				
			if(isset($parser['remoteLoad']) && $parser['remoteLoad']) {
				$items = $this->getItemsRemoteLoad($parser,$anode,$sort,$sort_dir);
			}else {				
				self::sortColumn($items, $sort, $sort_dir);
				$items = array_slice($items,$start,$limit);
				if($this->hasRequestParameter('fresh')){
					$items = call_user_func(array($parser["datasource"]["class"],$parser["datasource"]["method"]["name"]));
				}			
			}		
			
			if(empty($args))
			{
				
				$filters[] = array(
					"data"=>array("type"=>"params","value"=>$start),
					"field"=>"start"
				);
				$filters[] = array(
					"data"=>array("type"=>"params","value"=>$limit),
					"field"=>"limit"
				);
				$argParam = $filters;
				
				if($parser['type'] == "static" && isset($parser['static_real_pagination']) && $parser['static_real_pagination']){
					$tmp = $this->getItemsStaticFiltered($parser,$start,$limit,$filters);
					$items = $tmp;					
					$c = call_user_func_array(array($parser["datasource"]["class"],$parser["datasource"]["method"]["name"]),array($argParam,true));						
					if($grid_data)					
		  				$grid_data->totalCount = (int)$c;
	  			}
	  			
	  			self::sortColumn($items, $sort, $sort_dir);
					
				
				$this->addRowActionSuffixes($parser,$items,$host);
			}
		
		}
		
		// 5. Send JSON or return results..
		
		if(empty($args)) {
			
			// Hide unwanted rowactions..
			
			if(isset($parser["conditions"])) {
				$this->hideActions($items,$parser);
			} 
			
			if(isset($parser['realtime']) and $parser['realtime'] == "yes")	{
				echo json_encode(array('success' => true, 'rows' => $items));
				exit();	
			}
			
			foreach ($items as $k=>$item) {
				
				if(!isset($item['_id']))
				{
					$item['_id']=$k;
				}
				
				$grid_data->addRowData($item);
		  	}	
		
			$response = $this->getResponse();
			$response->setHttpHeader('Cache-Control', 'max-age=1200');
		
			return $this->renderText($grid_data->end());
		
		} else {
			
			return $items;
			
		} 	
		
	}
	
	/***************************************************************************************************** 
	 * These functions get the raw data from the datasource, add a new one here for a different source   *
	 *****************************************************************************************************/
	
	
	private function getRowActionPk($action_id,$parser) {
		
		$j = 1;
		foreach($parser["rowactions"] as $k => $action) {
			if($j == $action_id) {
				return $action["attributes"]["pk"];
			}
			$j++;
		}
		
	}
	
	
	private function hideActions(&$items,$parser) {
		
		foreach($items as $k => &$item) {
			foreach($item as $kk => $value) {
				$args = array();
				$action = "row".$kk;
				
				if(isset($parser["conditions"][$action])) {
					$tmp = explode(",",$parser["conditions"][$action]);
					$class = $tmp[0];
					$method = $tmp[1];
					$pk = $this->getRowActionPk(preg_replace("/[^0-9]+/","",$action),$parser);
					
					/**
					 * if column name exists in $item
					 */
					if(isset($item[$pk]))
					{
						$args[] = $item[$pk];
					}
					
					unset($tmp[0],$tmp[1]);
					
					foreach($tmp as $t) {
						$args[] = (isset($item[$t])) ? $item[$t] : $t;
					}
					
					if(!call_user_func(array($class,$method),$args)) {
						unset($items[$k][$kk]);	
					}
							
				}
				
			}
			
		}
		
	}
	
	
	/*
	 * This one returns the raw data from  db, as a pager. This will be processed
	 * by the getItemsOrm function.
	 * 
	 */
	private function getRawDataOrm($parser,$args) {
		
		list($start,$limit,$sort,$sort_dir,$filters,$export) = $args;
		
		$pager = null;
		$page=($start==0)?1:(@ceil($start/$limit)+1);	
		
		$criteria=clone $parser['criteria'];
		$this->setFilters($criteria,$filters,$parser);
		$xsort = $this->getRequestParameter('xsort');
		if($xsort){$sort = constant($xsort);}
		
		if($sort)
		{
			$criteria->clearOrderByColumns();
			if($sort_dir=='ASC')			{			
						
				$criteria->addAscendingOrderByColumn($sort);					
			}
			elseif($sort_dir=='DESC')  {
				$criteria->addDescendingOrderByColumn($sort);
			}
		}		
		$pager = new sfPropelPager($parser["class"], ($export == "all") ? sfConfig::get("app_parser_max_items") : $parser["limit"]);
		$pager->setPage($page);
		$pager->setPeerMethod($parser["select_method"]);
		$pager->setCriteria($criteria);
		$pager->init();
		
		return $pager;
		
	}
	
	
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
	 * These functions will process the raw data and create input array for grids. Add a new one below   *
	 * ***************************************************************************************************/
	
	
	/*
	 * The function creates the output from pager data. Can be used when source is ORM.
	 * 
	 */
	private function getItemsOrm($pager,$parser) {

		$host = $this->getHost();
		$items = array();
		$i = $j = 0;
		
		foreach($pager->getResults() as $object) {
				
				foreach($parser["columns"] as $column) {
					$j = 0;
					$id = call_user_func(array($object,"getId"));
					$tmp = call_user_func(array($object,"get".$column["phpname"]));
										
					if(in_array($column["phpname"],$parser["foreign_keys"])) {
						if(isset($tmp)) {
							$tmp = call_user_func(array($tmp,"__toString"));
						}
					} else {
						if(isset($column["type"]) && $column["type"] == "TIMESTAMP") {
							 $tmp = Tz::formatTime(strtotime($tmp));
						}
					}
					if(!preg_match("/^Link/",$column['phpname']) && !preg_match("/^Html/",$column['phpname'])){
						$items[$i][$column["column"]] = htmlspecialchars($tmp);
					}else{ 
						$items[$i][$column["column"]] = $tmp;
					}
					/*
					 * Modified by Prakash
					 * overwrite by getHtmlMethods
					 */		
					if(isset($parser['remoteFilter']) && $parser['remoteFilter']){			
						if(method_exists($object,"getHtml".sfInflector::camelize($column['column']))){						
							$items[$i][$column["column"]] = call_user_func(array($object,"getHtml".sfInflector::camelize($column['column'])));
						}
					}
					//.................................................................................................
					if(isset($parser["rowactions"]))
					{					
						foreach($parser["rowactions"] as $k => $action) {
							
							$pk = $action["attributes"]["pk"];
							
							if(!strstr($host.$action["attributes"]["url"],"?")) {
								$host.$action["attributes"]["url"] .= "?";
							}
							$items[$i]["action".($j+1)] = $host.$action["attributes"]["url"].((substr($action["attributes"]["url"],-1,1) == "?") ? "" : "&").$pk."=".$id."&";
						$j++;
						}
					}			 	
				}
				$i++;			
			}
	
		return $items;
			
	}
	
	
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
	
	
	/*
	 * Retrieves db data for the selected node ($anode). It is used only in trees whose remoteLoad argument is set to true.
	 * 
	 */
	private function getItemsRemoteLoad($parser,$anode,$sort,$sort_dir) {
		
		$items = array();
		
		if($parser['type'] == "static"){
			$argParam = array('anode'=>$anode);
			$xmlParam = isset($parser["datasource"]["method"]["params"])?$parser["datasource"]["method"]["params"]:array();
			$argParam = array_merge($argParam,$xmlParam);								
			$items = call_user_func(array($parser["datasource"]["class"],$parser["datasource"]["method"]["name"]),$argParam);
			
		}else{
			$items = Util::getDataForAnode($anode,$items);
		}
		
		self::sortColumn($items, $sort, $sort_dir);
		
		return $items;
	}
	
	/*
	 * Adds filters, used only in "static_real_pagination"
	 * 
	 */
	private function getItemsStaticFiltered($parser,$start,$limit,$filters) {
		
		$items = null;		
		if($filters && isset($parser['static_real_pagination']) && $parser['static_real_pagination']){			
			
			$argParam = $filters;
			$items = call_user_func(array($parser["datasource"]["class"],$parser["datasource"]["method"]["name"]),$argParam);			
			
		}
		
		return $items;
	}
	

	

	/***************************************************************************************************** 
	 * Other private functions, used by the main actions and methods.                                    *
	 * ***************************************************************************************************/
	
	
	/*
	 * This fetches the session data, a specific parsing result.
	 * $uid is the key associated with the desired parser data.
	 * 
	 */
	private function getParserData($uid,$job = null) {
		
		if(!$job) {
			$parser = $this->getUser()->getAttributeHolder()->getAll('parser/grid');
		
			foreach($parser as $pk => $data) {
				if($data["uid"] == $uid) {
					$parser = $data;
					break;
				}
			}	
		} else {
			$params = $job->getUnserializedParams();
			$parser = unserialize(file_get_contents($params["data_file"]));
		}
		
		return $parser;
		
		
	}
	
	
	/*
	 * Gets parameters for export / list and returns them as an array.
	 * 
	 */
	private function getParameters($request = null,$args = array()) {
		
		
		$ret = array();
		
		$ret[] = $request->getParameterHolder()->has('sort') ? $request->getParameterHolder()->get('sort'):false;			
		$ret[] = $request->getParameterHolder()->has('dir') ? $request->getParameterHolder()->get('dir'):'ASC';
		$ret[] = $request->getParameterHolder()->has('start')?$request->getParameterHolder()->get('start'):0;
		$ret[] = $request->getParameterHolder()->has('limit')?$request->getParameterHolder()->get('limit'):20;
		$ret[] = $request->getParameterHolder()->has('anode')?$request->getParameterHolder()->get('anode'):null;
		$ret[] = $request->hasParameter('filter')?$request->getParameter('filter'):false;
		$ret[] = $request->getParameterHolder()->get("export",false);
		$ret[] = $request->getParameterHolder()->get('uid');	
		
		return $ret;
	
	}
	
	
	/*
	 * Gets the host (ip or domain) to be used in rowactions.
	 * 
	 */
	private function getHost() {
		
		$config_listjson=sfConfig::get("app_parser_listjson");
		
		if(!isset($_SERVER["SERVER_PORT"])) {
			$protocol = sfConfig::get("app_parser_host_type");
		} else {
			$protocol = ($_SERVER["SERVER_PORT"]=='443')?'https://':'http://';	
		}
		
		$host = $protocol.$this->getRequest()->getHost();
		
		return $host;
		
	}
	
	
	/*
	 * Adds suffixes to rowactions (urls).
	 * 
	 */
	private function addRowActionSuffixes($parser,&$items,$host) {
		
		$j = 1;
		
		if(isset($parser["rowactions"])&&count($parser["rowactions"])>0) {
			foreach ($parser["rowactions"] as $name=>$action)
			{
				if(isset($parser["rowactions"][$name]["attributes"]["params"]))
				{
					$params=explode(',',$parser["rowactions"][$name]["attributes"]["params"]);					
					
					$action_suffix=null;
					
					foreach ($items as $ki=>$item)
					{
						foreach ($params as $param)
						{
							if(isset($item[$param]))
							{
								$action_suffix[$ki][]=$param."=".$item[$param];
							}							
						}						
					}			
				
					foreach ($items as $ki=>$item)
					{								
						if(!strstr($host.$action["attributes"]["url"],"?")) {
							$host.$action["attributes"]["url"] .= "?";
						}
						
						if(isset($action_suffix[$ki])&&is_array($action_suffix[$ki]))
						{
							$items[$ki]["action".$j] = $host.$action["attributes"]["url"].implode('&',$action_suffix[$ki]);								}
					}					
				}
				else {
					
					$pk = $parser["rowactions"][$name]["attributes"]["pk"];
					
					foreach ($items as $ki=>$item)
					{								
						if(!strstr($host.$action["attributes"]["url"],"?")) {
							$host.$action["attributes"]["url"] .= "?";
						}
						
						$items[$ki]["action".$j] = $host.$action["attributes"]["url"].(isset($item[$pk])?($pk."=".$item[$pk]."&"):"");
					}
				
				}
									
				$j++;
			}	
		}
	}
	

	/**
	 * Sorts the items by the given column.
	 */
	private static function sortColumn(&$items, $sort, $sort_dir='ASC')
	{
		if($sort)
		{
			$cmp = new RowCmp($sort, $sort_dir);
			usort($items, array($cmp, 'cmp'));
		}
	}

	/**
	 * setting filters to parser criteria
	 * 
	 * @author radu
	 */
	public function setFilters(Criteria &$criteria,$filters,$parser)
	{		
		if($filters)
		{		
			for($i=0;$i<count($filters);$i++)
			{
				if($filters[$i]['field']!=false)
				{				
					switch($filters[$i]['data']['type'])
					{					
						case 'string' :					
							$critAnd[] = $criteria->getNewCriterion($filters[$i]['field'],"%".$filters[$i]['data']['value']."%",Criteria::LIKE); 
							break;
						case 'list' : 
						case 'combo':
							if (strstr($filters[$i]['data']['value'],',')){
								$fi = explode(',',$filters[$i]['data']['value']);							
								$filters[$i]['data']['value'] = $fi;
								$critAnd[] = $criteria->getNewCriterion($filters[$i]['field'],$filters[$i]['data']['value'],Criteria::IN); 
							}else{
								$critAnd[] = $criteria->getNewCriterion($filters[$i]['field'],$filters[$i]['data']['value'],Criteria::EQUAL);							 
							}
							Break;
						case 'boolean' : 
							$critAnd[] = $criteria->getNewCriterion($filters[$i]['field'],$filters[$i]['data']['value'],Criteria::EQUAL); 
							Break;
						case 'numeric' :					
							switch ($filters[$i]['data']['comparison']) {
								case 'eq' : 
									$critAnd[] = $criteria->getNewCriterion($filters[$i]['field'],$filters[$i]['data']['value'],Criteria::EQUAL); 
									break;
								case 'lt' : 
									$critNumeric[] = $criteria->getNewCriterion($filters[$i]['field'],$filters[$i]['data']['value'],Criteria::LESS_THAN); 
									break;
								case 'gt' : 
									$critNumeric[] = $criteria->getNewCriterion($filters[$i]['field'],$filters[$i]['data']['value'],Criteria::GREATER_THAN); 
									break;
							}
							break;
						case 'date' : 
							switch ($filters[$i]['data']['comparison']) {
								case 'eq' : 
									//$critAnd[] = $criteria->getNewCriterion($filters[$i]['field'],date('Y-m-d',strtotime($filters[$i]['data']['value'])),Criteria::EQUAL);
									$critDate[] = $criteria->getNewCriterion($filters[$i]['field'],date('Y-m-d',strtotime($filters[$i]['data']['value'])),Criteria::GREATER_THAN);
									$critDate[] = $criteria->getNewCriterion($filters[$i]['field'],date('Y-m-d',strtotime($filters[$i]['data']['value'])+(24*60*60)),Criteria::LESS_THAN);
									break;
								case 'lt' : 
									$critDate[] = $criteria->getNewCriterion($filters[$i]['field'],date('Y-m-d',strtotime($filters[$i]['data']['value'])),Criteria::LESS_THAN);
									break;
								case 'gt' : 
									$critDate[] = $criteria->getNewCriterion($filters[$i]['field'],date('Y-m-d',strtotime($filters[$i]['data']['value'])),Criteria::GREATER_THAN);
									break;
							}
						break;
					}
				}
			}
			
			
			//print_r($critNumeric);
			
			if(isset($critNumeric))
			{
				$critNumericCount=count($critNumeric);
							
				if($critNumericCount>0)
				{				
					for ($i=1;$i<$critNumericCount;$i++)
					{
						$critNumeric[0]->addAnd($critNumeric[$i]);
					}
															
					$criteria->add($critNumeric[0]);
				}
			}
			
			//print_r($criteria);
			
			if(isset($critDate))
			{
				$critDateCount=count($critDate);
				
				if($critDateCount>0)
				{				
					for ($i=1;$i<$critDateCount;$i++)
					{
						$critDate[0]->addAnd($critDate[$i]);
					}
					
					$criteria->add($critDate[0]);
				}
			}
									
			if(isset($critAnd))
			{
				$critAndCount=count($critAnd);
				
				if($critAndCount>0)
				{
					for ($i=1;$i<$critAndCount;$i++)
					{
						$critAnd[0]->addAnd($critAnd[$i]);
					}
					
					$criteria->add($critAnd[0]);
				}
			}
		}
	}
	
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
	
   /**
	* Renders all validation errors
	* in a JSON response.
	* The XmlParserValidationFilter uses it.
	*/
	public function executeErrors()
	{
	  if($this->missing){
		return JsonUtil::renderFailure($this,'Some form field(s) is missing');
	  }
	  $result = array('success' => false, 'message' => 'Validation error occured!');
	  foreach($this->errors as $error)
	  {
	      $result['errors'][$error[0]] = $error[1];
	  }
	  
	  return $this->renderText(json_encode($result));
	}
}
