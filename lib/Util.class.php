<?php

/**
 * Utility class wich can be used in several places.
 *
 * It contains general purpose methods, all these shoould be public static.
 *
 * @author gtamas
 * @package sfdemo
 */
class Util {


	public static
	$filters = null;
	
	
	public static function getNode($tree,$id) {
		
		foreach($tree as $node) {
			if($node->_id == $id) {
				return $node;
			}
		}
		
		return null;
		
	}
	
	public static function getNodeAsText($nodes,&$res) {
		
		foreach($nodes as $obj) {
			
			if(is_object($obj)) {
				
				foreach($obj as $k => $value) {
					if(substr($k,0,1) != "_") {
						$tmp[] = str_replace("\n","",trim(strip_tags($value)));;
					} 	
				}
				
				$res[] = $tmp;
	
			} else {
				self::getNodeAsText($obj,$res);
			}	
		}	
		
	}
	
	public static function groupNodes($tree) {

		$ret = array();
		
		foreach($tree as $obj) {
			
			if(!$obj->_parent) {
					$ret[$obj->_id][] = $obj;	
			} else {
				
				$parent = $obj->_parent;
				$key = "[\"".$obj->_id."\"]";
				
				while(($node = self::getNode($tree,$parent)) !== null) {
					$key = "[\"".$node->_id."\"]".$key;
					$parent = $node->_parent;
				}

				eval("\$ret".$key."[] = \$obj;");
			}
		}
		
		foreach($ret as $k => &$r) {
			$tmp = $r[0];
			unset($r[0]);
			array_unshift($r,$tmp);
		}
				
		return $ret;
		
	}
	
	public static function arrayToString($arr) {
		
		$ret = "";
		
		foreach($arr as $k => $v) {
			$ret .= ucfirst($k).": ".$v."\n";
		}
		
		return trim($ret);
		
	}
	
	public static function arraySum($arr) {
		
		$ret = 0;
		
		foreach($arr as $n) {
			$ret += $n;
		}
		
		return $ret;
		
	}

	
	public static function listActionsRemove($class,$post,$redirect){
			if(isset($post['all'])){				
				/****** Delete all **********/
				call_user_func(array($class,"doDeleteAll"));				
				/****** Delete all **********/
				$msg = "All data removed successfully";	
				myAuditLogger::logMessage("All record has deleted from ".str_replace("Peer","",$class));			
			}else{							
				$items = json_decode($post["selections"],true);
				
				if(!count($items)){
					$result = array('success' => true,'message'=>'No items selected..');
					return $result = json_encode($result);
				}
			
				foreach ($items as $item){
					/******* Delete individual ***************/
					preg_match("/id=([0-9]+)/",$item['action1'],$matches);
					$id = preg_replace("/id=([0-9]+)/","$1",$matches[0]);	
					$c = new Criteria();					
					$c->add(constant($class."::ID"),$id);
					call_user_func($class."::doDelete",$c);	
					/******* Delete individual ***************/
				}
				$msg = "Selected data removed successfully";
				myAuditLogger::logMessage(count($items)." record has deleted from ".str_replace("Peer","",$class));	
			}

			$result = array('success' => true, 'message' => $msg, 'redirect' => $redirect);
			return $result = json_encode($result);		
	}
	static public function createJson($eventInfoId = null)
	{
		if(!is_null($eventInfoId))
		{
			$c = new Criteria();
			$c->addJoin(EventInfoPeer::ID,NadeSignaturePeer::EVENT_INFO_ID);
			$c->add(EventInfoPeer::ID,$eventInfoId);
			$eventinfo = EventInfoPeer::doSelectOne($c);
			$signature = $eventinfo->getNadeSignatures();
				
			if($signature)
			{
				$nadeSignature = $signature[0];
				$c->clear();
				$c->add(NadeResponderPeer::NADE_SIGNATURE_ID,$nadeSignature->getId());
				$nadeResponders = NadeResponderPeer::doSelect($c);
			}
				
			if($eventinfo && $nadeSignature && $nadeResponders)
			{
				$json = array('id' => $eventinfo->getId(), 'rule' => $eventinfo->getName(), 'connection' => array('source_ip' => $nadeSignature->getSourceIp(),
					  'destination_ip' => $nadeSignature->getDestinationIp(), 'port' => $nadeSignature->getDestinationPort(),
					  'protocol' => $nadeSignature->getIpProtocol()),'analyzer' => array('time_span' => $nadeSignature->getTimeSpan()));

				foreach($nadeResponders as $nadeResponder)
				{
					$json['responders'][$nadeResponder->getResponderName()] = array('count' => $nadeResponder->getCount(), 'threshold' => $nadeResponder->getThreshold(),
																				'message' => $nadeResponder->getThresholdMessage());
				}

				return $json;
			}
		}

		return null;
	}
	public static function validateIpAddress($ip_addr)
	{
		//first of all the format of the ip address is matched
		if(preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/",$ip_addr))
		{
			//now all the intger values are separated
			$parts=explode(".",$ip_addr);
			//now we need to check each part can range from 0-255
			foreach($parts as $ip_parts)
			{
				if(intval($ip_parts)>255 || intval($ip_parts)<0)
				return false; //if number is not within range of 0-255
			}
			return true;
		}
		else
		return false; //if format of ip address doesn't matches
	}


	public static function executeCommand($method,$start,$end,$sid='',$resolution = 0)
	{
		$serverSelection = '';
		if($sid != '') {
			if ($method === 'syslogd') {
				if ($sid !== 'all') {
					$serverSelection = '-i '.escapeshellarg($sid);
				}
			} else {
				$serverSelection = '-n '.escapeshellarg($sid);
			}
		}

		$res = '';
		if($method != 'eventhandler') $res = ' -r '.$resolution;
		$cmd = '/usr/bin/sudo /installed/system/syscontrol.sh '.$method.' total '.$serverSelection.' -s '.$start.' -e '.$end.$res;
		return shell_exec($cmd);
	}


	public static function getGraphData($sid = "all", $do = "event", $report = false,$date_range = null) {
		
		$date = new myDate($report);
		$viewdates = $date->getViewDates();
		
		Tz::$zone = $date->getZone();
		
		if($report) {
			$type = $report->getType();
		} else {
			$type = $viewdates['type'];	
		}
		
		$start = Tz::pickTime(($date_range["start_date"]) ? strtotime($date_range["start_date"]) : $viewdates[$type.'_start']);
		$end = Tz::pickTime(($date_range["end_date"]) ? strtotime($date_range["end_date"]) : $viewdates[$type.'_end']);
		
		$data = array();
		$resultSet = array();

		if($do == "event") {
			switch($type)
			{
				case 'day':
					$resolution = 3600;
					for($time = $start; $time <=$end; $time+=$resolution)
					{
						$data[Tz::date('H',$time)] = self::executeCommand('eventhandler',$time,$time+$resolution,$sid);
					}
					break;
				case 'week':
					$resolution = 86400;
					for($time = $start; $time <=$end; $time+=$resolution)
					{
						$data[Tz::date('Y-m-d',$time)] = self::executeCommand('eventhandler',$time,$time+$resolution,$sid);
					}
					break;
				case 'month':
					$resolution = 86400;
					for($time = $start; $time <=$end; $time+=$resolution)
					{
						$data[Tz::date('Y-m-d',$time)] = self::executeCommand('eventhandler',$time,$time+$resolution,$sid);
					}
					break;
				case 'year':
					while(true)
					{
						$mend = strtotime('+1 month',$start) -1;
						$data[Tz::date('F',$start)] = self::executeCommand('eventhandler',$start,$mend,$sid);
						$start = $mend +1;
						if($start >= $end)
						{
							break;
						}
					}
					break;
				default:
					list($start,$end) = $date->findWeek(time());
					$start = Tz::pickTime($start);
					$end = Tz::pickTime($end);
					$resolution = 86400;
					for($time = $start; $time <=$end; $time+=$resolution)
					{
						$data[Tz::date('Y-m-d',$time)] = self::executeCommand('eventhandler',$time,$time+$resolution);
					}
					break;
			}
		} else if($do == "syslog") {
			switch($type)
			{
				case 'day':
					$resolution = 3600;
					$strData = self::executeCommand('syslogd',$start,$end,$sid,$resolution);
					$arrData = json_decode($strData);
					foreach($arrData as $key => $indData){
						array_shift($indData);
						array_shift($indData);
						$data[Tz::date('H',$start+$resolution*$key)] = $indData;
					}
					break;
				case 'week':
					$resolution = 86400;
					$strData = self::executeCommand('syslogd',$start,$end,$sid,$resolution);
					$arrData = json_decode($strData);
						
					foreach($arrData as $key => $indData){
						array_shift($indData);
						array_shift($indData);
						$data[Tz::date('Y-m-d',$start+$resolution*$key)] = $indData;
					}
					break;
				case 'month':
					$resolution = 86400;
					$strData = self::executeCommand('syslogd',$start,$end,$sid,$resolution);
					$arrData = json_decode($strData);
					
					foreach($arrData as $key => $indData){
						array_shift($indData);
						array_shift($indData);
						$data[Tz::date('Y-m-d',$start+$resolution*$key)] = $indData;
					}

					break;
				case 'year':
					while(true)
					{
						$mend = strtotime('+1 month',$start) -1;
						$strData = self::executeCommand('syslogd',$start,$mend,$sid,$mend-$start);
						$arrData = json_decode($strData);

						array_shift($arrData[0]);
						array_shift($arrData[0]);
						$data[Tz::date('F',$start)] = $arrData[0];
							
						$start = $mend +1;
						if($start >= $end)
						{
							break;
						}
							
					}
					//echo "<pre>";print_r($data);exit;
					break;
				default:
					$resolution = 86400;
					$strData = self::executeCommand('syslogd',$start,$end,$sid,$resolution);
					$arrData = json_decode($strData);
						
					foreach($arrData as $key => $indData){
						array_shift($indData);
						array_shift($indData);
						$data[Tz::date('Y-m-d',$start+$resolution*$key)] = $indData;
					}
					break;
			}
		} else if($do == "syslogcnt") {
			switch($type)
			{
				case 'day':
					$resolution = 3600;
					$strData = self::executeCommand('syslogd',$start,$end,$sid,$resolution);
					$arrData = json_decode($strData);
					foreach($arrData as $key => $indData){
						$data[Tz::date('H',$start+$resolution*$key)] = $indData;
					}
					break;
				case 'week':
					$resolution = 86400;
					$strData = self::executeCommand('syslogd',$start,$end,$sid,$resolution);
					$arrData = json_decode($strData);
					foreach($arrData as $key => $indData){
						$data[Tz::date('Y-m-d',$start+$resolution*$key)] = $indData;
					}
					break;
				case 'month':
					$resolution = 86400;
					$strData = self::executeCommand('syslogd',$start,$end,$sid,$resolution);
					$arrData = json_decode($strData);
					foreach($arrData as $key => $indData){
						$data[Tz::date('Y-m-d',$start+$resolution*$key)] = $indData;
					}

					break;
				case 'year':
					while(true)
					{
						$mend = strtotime('+1 month',$start) -1;
						$strData = self::executeCommand('syslogd',$start,$mend,$sid,$mend-$start);
						$arrData = json_decode($strData);
						$data[Tz::date('F',$start)] = $arrData[0];
							
						$start = $mend +1;
						if($start >= $end)
						{
							break;
						}
							
					}
					//echo "<pre>";print_r($data);exit;
					break;
				default:
					list($start,$end) = $date->findWeek(time());
					$start = Tz::pickTime($start);
					$end = Tz::pickTime($end);
					$resolution = 86400;
					for($time = $start; $time <=$end; $time+=$resolution)
					{
						$data[Tz::date('Y-m-d',$time)] = self::executeCommand('syslogd',$time,$time+$resolution,$sid);
					}
					break;
			}
		}
		
		
		if($report) {
		
			if($report->getType() == "day") {
				$str = trim(substr($date_range["start_date"],0,strrpos($date_range["start_date"]," ")));
			}
			
			if($do == "event" || $do == "syslog") {
				$tmp = array();
					
				$i = 0;
				foreach($data as $key => $item) {
					$key = trim($key);
					
					if($report->getType() == "day") {
						$key = $str." ".$key.":00:00";	
						
					}
					
					$tmp[$i] = ($do == "syslog") ? $item : explode(",",trim($item));
					$tmp[$i][] = $key;
					$i++;
				}
				
				return $tmp;
			}
				
		}
	
		return $data;

	}

	public static function getFilters($type = "event_query",$rid = "new") {

		$data = array
		(
			"event_query" => array("eventmanagementActions", "sf_admin/event/filters"),
			"netflow_query" => array("netflowActions", "sf_admin/log_firewall/filters"),
			"log_query" => array("loganalysisActions", "sf_admin/log_search/filters")
		);

		$isReporting = is_numeric($rid);
		$specialActions = false;
		if (!$isReporting) {
			// The report id is not provided, let's work online.
			$context = sfContext::getInstance();
			$filters = $context->getUser()->getAttributeHolder()->getAll($data[$type][1]);
			if(strstr($context->getActionName(),"pdf") || strstr($context->getActionName(),"run") || $context->getActionName() == "getCustomLog") {
				$specialActions = true;
			}
		} else {
			if(!self::$filters) {
				$report = PdfReportsPeer::retrieveByPk($rid);
				$query = $report->getByName($type,BasePeer::TYPE_FIELDNAME);
				$tmp = explode("&",$query);

				foreach($tmp as $pair) {
					$key = str_replace("_value","",strtok($pair,"="));
					self::$filters[$key] = strtok("=");
				}
			}
		}
			
		if($isReporting || $specialActions) {
			$filters = self::$filters;
				
			foreach($filters as $key => $item) {
				if((is_string($item) && !trim($item)) || $item === false) {
					$filters[$key] = "";
				}
			}
		}


		return $filters;

	}


	public static function in_array_recursive($item,$array,$keys = false) {

		$ret = false;

		foreach($array as $key => $it) {

			if($keys && $key === $item) {
				$ret = true;
				break;
			}
			if(!is_array($it)) {
				if($it === $item) {
					$ret = true;
					break;
				}
			} else {
				if(!$ret) {
					$ret = self::in_array_recursive($item,$it,$keys);
				} else {
					break;
				}

			}
		}

		return $ret;

	}

	public static function readEula() {

		return self::getHtmlForJs(file_get_contents("eula"));
	}

	public static function getRequest() {
		return sfContext::getInstance()->getRequest();
	}

	public static function getFromRequest($param) {
		return self::getRequest()->getParameter($param);
	}


	public static function isValidTimeRange($param) {

		if((self::getFromRequest("w_week") && self::getFromRequest("w_year")) ||
		(self::getFromRequest("m_months") && self::getFromRequest("m_years")) ||
		self::getFromRequest("y_years")) {
			return true;
		} else {
			return false;
		}

	}

	public static function isValidDateTo($param) {

		$other = sfContext::getInstance()->getRequest()->getParameter("extraction_from");

		if(!$param || !$other) {
			return true;
		}

		$dateTo = strtotime($param);
		$dateFrom = strtotime($other);

		if($dateFrom > $dateTo) {
			return false;
		} else {
			return true;
		}

	}

	public static function isPastDate($param) {

		if(!$param) {
			return true;
		}

		return !(strtotime($param) < strtotime(date("Y-m-d H:i:s")));

	}

	public static function isValidDays($param) {

		return checkdate(self::getFromRequest("d_months"),self::getFromRequest("d_days"),
		self::getFromRequest("d_years"));

	}

	public static function isValidDate($param) {

		if(!$param) {
			return ture;
		}

		$matches = preg_split("/[\-: ]{1}/",$param);

		if(sizeof($matches) != 5) {
			return false;
		} else if(!checkdate($matches[1],$matches[2],$matches[0])) {
			return false;
		} else if(!is_numeric($matches[3]) || $matches[3] > 23 || $matches[3] < 0 ||
		!is_numeric($matches[4]) || $matches[4] > 59 || $matches[4] < 0) {
			return false;
		}

		return true;

	}


	public static function isOccuringDateSelected($param) {

		$request = sfContext::getInstance()->getRequest();

		if(!$request->getParameter("schedule_hour") &&
		!$request->getParameter("schedule_month") &&
		!$request->getParameter("schedule_dom") &&
		!$request->getParameter("schedule_dow") &&
		!$request->getParameter("extraction_period")) {
			return false;
		} else {
			return true;
		}
	}


	public static function sfOutputEscaperArrayDecoratorToArray($inp) {

		$ret = array();

		$inp->rewind();

		while($inp->key()) {
			$ret[$inp->key()] = $inp->getRaw($inp->key());
			$inp->next();
		}

		return $ret;
	}


	public static function isValidIntro($param) {

		if(!preg_match("/^[<>\/a-zA-Z0-9[:punct:][:space:]]+$/",$param)) {
			return false;
		}

		$allowed_tags = array
		(
	  	"<immune:b>",
	  	"</immune:b>",
	  	"<immune:i>",
	  	"</immune:i>",
	  	"<immune:u>",
	  	"</immune:u>"
	  	);

	  	$matches = preg_match_all("/<[^>]+>/",$param,$regs);

	  	foreach($regs[0] as $match) {
	  		if(!in_array($match,$allowed_tags)) {
	  			return false;
	  		}
	  	}

	  	return true;

	}

	public static function leadZero($val) {

		return ($val < 10) ? "0".$val : $val;

	}

	public static function addMonth($timestamp) {
		$month = date('m', $timestamp);
		$month += 1;
		if($month > 12) {
			$month = $month - 12;
			$timestamp = self::addYear($timestamp);
		}
		return strtotime(date('Y-'.$month.'-d H:i:s', $timestamp));
	}

	/**
	 * Adds a year but preserves the month, day and hour.
	 */
	private static function addYear($timestamp) {
		$year = date('Y', $timestamp);
		$year += 1;
		return strtotime(date($year.'-m-d H:i:s', $timestamp));
	}

	public static function parseCronValue($hour = 0,$dom = 0,$month = 0,$dow = -1, $basedate = false) {
		if(!$basedate) {
			$curTimestamp = strtotime(date('Y-m-d H:00:00'));
		} else {
			$curTimestamp = strtotime($basedate);
		}

		// Set hour
		$timestamp = strtotime(date('Y-m-d '.Util::leadZero($hour).':00:00', $curTimestamp));
		if($timestamp <= $curTimestamp) {
			$timestamp += 3600*24;
		}

		// Set the day of month
		if($dom) {
			$timestamp = strtotime(date('Y-m-'.Util::leadZero($dom).' H:00:00', $timestamp));
			if($timestamp <= $curTimestamp) {
				$timestamp = self::addMonth($timestamp);
			}
		}

		// Set month
		if($month) {
			$timestamp = strtotime(date('Y-'.Util::leadZero($month).'-d H:00:00', $timestamp));
			if($timestamp <= $curTimestamp) {
				$timestamp = self::addYear($timestamp);
			}
		}

		// Set the day of week
		if($dow !== -1 && $dow !== false) {
			$day = date("w", $timestamp);
			$diff = $dow - $day;
			if($diff < 0) {
				$diff = 7 + $diff;
			}

			$timestamp += $diff*3600*24;
		}

		return date('Y-m-d H:00:00', $timestamp);
	}

	public static function getDays($undefined = true) {

		$ret = array
		(
		"" => "Undefined",
		"0" => "Sunday",
		"1" => "Monday",
		"2" => "Tuesday",
		"3" => "Thusday",
		"4" => "Wednesday",
		"5" => "Friday",
		"6" => "Saturday"
		);

		if($undefined) {
			if($undefined == 1) {
				$ret[""] = "Choose!";
			}
		} else {
			unset($ret[""]);
		}

		return $ret;

	}


	public static function getMonths($undefined = true) {

		$ret = array
		(
		"" => "Undefined",
		"1" => "January",
		"2" => "February",
		"3" => "March",
		"4" => "April",
		"5" => "May",
		"6" => "June",
		"7" => "July",
		"8" => "August",
		"9" => "September",
		"10" => "October",
		"11" => "November",
		"12" => "December",
		);

		if($undefined) {
			if($undefined == 1) {
				$ret[""] = "Choose!";
			}
		} else {
			unset($ret[""]);
		}

		return $ret;


	}

	public static function getCounter($init,$max,$undefined = true) {

		if($undefined) {
			if($undefined == 1) {
				$ret = array("" => "Choose!");
			} else {
				$ret = array("" => "Undefined");
			}
		}

		if($init < $max) {
			for($i = $init; $i <= $max; $i++) {
				$ret[$i] = $i;
			}
		} else {
			for($i = $init; $i >= $max; $i--) {
				$ret[$i] = $i;
			}
		}

		return $ret;
	}


	/**
	 * This returns all items from table specified by $peer as array of id => name pairs.
	 *
	 * It can be used as input to options_for_select() helper.
	 * Will return false if there are no records to fetch.
	 *
	 * @param string $peer the name of the peer class
	 * @param string $name_column The column whose value will be used as text in the select box
	 * @param string $id_column The column whose value will be used as value in the select box
	 *
	 * @author gtamas
	 * @access public
	 * @return array the result
	 *
	 */
	public static function getAllAsOptions($peer,$name_columns = "name",$id_column = "id") {

		$names = array_unique(explode(",",$name_columns));

		$c = new Criteria();
		$c->addAscendingOrderByColumn(constant($peer."::".strtoupper(trim($names[0]))));
		$res = call_user_func(array($peer,"doSelect"),$c);

		$ret = array();

		if($res) {
			foreach($res as $item) {

				$name_value = null;
				foreach($names as $name) {
					if($name_value) {
						$name_value .= ' ';
					}
					$name_value .= call_user_func(array($item,"get".sfInflector::camelize(trim($name))));
				}

				$ret[call_user_func(array($item,"get".sfInflector::camelize($id_column)))] = $name_value;
			}
		}

		return $ret;
	}


	/**
	 * Returns all relevant objects from the resolver table
	 *
	 * Accepts either with array of arrays or array of objects as input.
	 *
	 * @param array $input Array of elements whose properties need to be resolved
	 * @param string $fetchMethod the getter method that returns the the value to be resolved
	 * @param string $class The resolver class
	 *
	 * @author gtom
	 * @access public
	 * @return array The resolver objects
	 *
	 */
	public static function fetchResolverObjects($input, $fetchMethod, $class = "NetworkAddressLookup") {

		try {
			if(!is_array($input)) {
				throw new Exception("Invalid input!");
			} else if($class != "NetworkAddressLookup" && $class != "NetworkServiceLookup" && $class != "UserObjectLookup") {
				throw new Exception("Invalid class!");
			} else if(!$fetchMethod && $fetchMethod != "0") {
				throw new Exception("Invalid fetch method!");
			}

			$instance = new $class;

			$fields = array("NetworkAddressLookup" => "ip", "NetworkServiceLookup" => "port", "UserObjectLookup" => "token");
			$storage = array();

			$fetch = explode(",",$fetchMethod);

			foreach($fetch as $key => $function) {
				$fetch[$key] = trim($function);
			}

			foreach($input as $entry) {

				foreach($fetch as $function) {
					if(!is_numeric($function)) {
						if(!method_exists($entry,$function)) {
							throw new Exception("Invalid fetch method!");
						}

						$tmp = call_user_func(array($entry,$function));
							
					} else {
						if(!isset($entry[$function])) {
							throw new Exception("Invalid fetch index!");
						}
							
						$tmp = $entry[$function];
					}

					if(!in_array($tmp,$storage)) {
						$storage[] = $tmp;
					}
				}

			}

			$c = new Criteria();
			$c->add(constant($class."Peer::".strtoupper($fields[$class])),$storage,Criteria::IN);
			return call_user_func(array($class."Peer","doSelect"),$c);

		}
		catch(Exception $e) {
			throw $e;
		}

	}


	public static function MysqlResultSetToArray($result) {

		try {
			if(get_class($result) != "MySQLResultSet") {
				throw new Exception("Invalid input!");
			}
		}
		catch(Exception $e) {
			throw $e;
		}

		$resolver_input = array();
			
		foreach($result as $item) {
			$resolver_input[] = $item;
		}

		return $resolver_input;
	}


	public static function iteratorToArray($result) {

		$resolver_input = array();
			
		foreach($result as $item) {
			$resolver_input[] = $item;
		}

		return $resolver_input;
	}

	public static function addToSearch($criteria,$value,$targetclass,$targetfield,$getmethod = "getIp",$baseclass = "NetworkAddressLookup",
	$groupclass = "NetworkAddressHasGroups")
	{		
		if(substr($value,0,2) == "g-")
		{
			$gid = str_replace("g-","",$value);
			if($gid && $gid != 0)
			{
				$result = array();
				$items = call_user_func(array(sfInflector::camelize($groupclass)."Peer","getAllByGroup"),$gid);
					
				foreach($items as $key => $item)
				{
					$result[] =  call_user_func(array(call_user_func(array($item,"get".sfInflector::camelize($baseclass))),$getmethod));
				}
			}
			else
			{
				$result = call_user_func(array(sfInflector::camelize($baseclass)."Peer","getAllGrouped"),0);
			}

			$criteria->add(constant(sfInflector::camelize($targetclass)."::".strtoupper($targetfield)),$result,Criteria::IN);
				
		}

		elseif(substr($value,0,2) == "s-")
		{
			$sid = preg_replace("/s-[0-9]+-/","",$value);
			if($sid && $sid != '')
			{
				if($targetfield != "service")
				{
					$items = call_user_func(array(sfInflector::camelize($baseclass)."Peer","getAllAssociated"));
				}
				else
				{
					$items = call_user_func(array(sfInflector::camelize($baseclass)."Peer","getAllAssociatedServices"));
				}
				if(isset($items[$sid]))
				{
					$value=$items[$sid];
				}
				else
				{
					$value=false;
				}
			}
				
			if($value)
			{
				$criteria->add(constant(sfInflector::camelize($targetclass)."::".strtoupper($targetfield)), $value);
			}
		}
		return $criteria;

	}

	public static function formatData($data,$type,$name) {
			
		switch($type) {
			case 1:
				echo "<end />".$data;
				echo str_pad('',4096)."\n";
				break;

			case 2:
				header("Content-type: application/x-dom-event-stream");

				print "Event: $name\n";
				print "data: $data\n\n";

				break;

			case 3:
				print "<script>parent._cometObject.event.push(\"".$data."\")</script>";
				break;
		}

	}

	/**
	 * prints to response an json string
	 *
	 * @param array $array
	 * @param int $timeout in microseconds || false
	 * @initial author: tamas
	 * @current author: radu
	 */

	public static function serverPush($array=false,$timeout = false) {

		if($array)
		print json_encode($array)."\n";

		self::forceFlush();

		if(!$timeout) {
			sleep(1);
		} else {
			usleep($timeout);
		}
	}

	public static function forceFlush() {
		ob_start();
		ob_end_clean();
		flush();
		set_error_handler("dummyErrorHandler");
		ob_end_flush();
		restore_error_handler();
	}

	public static function dateToTimeStamp($date)
	{
		$hour = 0;
		$minute = 0;
		$seconds = 0;
		$day = 0;
		$month = 0;
		$year = 0;
		if(preg_match('/\s/',trim($date)))
		{
			$date = explode(' ',$date);
			$firstPart = explode('-',$date[0]);
			$secondPart = explode(':',$date[1]);
			$year = $firstPart[0];
			$month = $firstPart[1];
			$day = $firstPart[2];
			$hour = $secondPart[0];
			$minute = $secondPart[1];
			$seconds = $secondPart[2];
		}
		else
		{
			$firstPart = explode('-', $date);
			$year = $firstPart[0];
			$month = $firstPart[1];
			$day = $firstPart[2];
		}

		return mktime($hour, $minute, $seconds, $month, $day, $year);
	}

	public static function makeRandomKey($length=16)
	{
		$C = "HimmiHerrGottSakramentZeFixHallelujaMiLeckstAmArschScheissGlumpFarrektsWennsdDesLesnKostBistFeiSaubaZnaDro";
		$totalC = strlen($C)-1;

		$password = '';

		mt_srand((double) microtime() * 1000000);

		while (strlen($password) < $length)
		{
			$password .= substr($C, mt_rand(0, $totalC), 1)
			.  mt_rand(0, 148)
			.  substr($C, mt_rand(0, $totalC), 1);
		}

		$key=substr($password, 0, $length);

		return $key;
	}

	public static function stripText($text)
	{
		$text = strtolower($text);

		// strip all non word chars
		$text = preg_replace('/\W/', ' ', $text);

		// replace all white space sections with a dash
		$text = preg_replace('/\ +/', '-', $text);

		// trim dashes
		$text = preg_replace('/\-$/', '', $text);
		$text = preg_replace('/^\-/', '', $text);

		return $text;
	}

	public static function removeResource( $resource )
	{
		exec( "rm -rf $resource" );
		return true;
	}

	public static function renameResource( $old_resource, $new_resource )
	{
		exec( "mv $old_resource $new_resource" );
		return true;
	}

	public static function makeDirectory( $resource )
	{
		exec( "mkdir -pv $resource" );
		return true;
	}

	public static function makeFile( $resource )
	{
		if (!$handle = @fopen($resource, 'a'))
		{
			return false;
		}

		// Write $somecontent to our opened file.
		if (fwrite($handle, ' ') === FALSE)
		{
			return false;
		}

		fclose($handle);
			
		chmod($resource,0775);
			
		return true;
	}

	public static function getPropelObjectAsArray($object)
	{
		if($object!=null)
		{
			$object_peer=$object->getPeer();
			$field_names=$object_peer->getFieldNames(BasePeer::TYPE_FIELDNAME);

			foreach ($field_names as $field_name)
			{
				$value=$object->getByName($field_name,BasePeer::TYPE_FIELDNAME);
				if($field_name=='id'&&$value=='')
				{
					$value=0;
				}
				$array[$field_name]=($value!='')?$value:'';
			}

			return $array;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Replaces \n with <br />.
	 * The other escaping is left on sfExtjs2Plugin::_quote().
	 */
	public static function getHtmlForJs($html)
	{
		return nl2br($html);
	}

	public static function getDbTables()
	{
		$con = Propel::getConnection();
		$sql = "SHOW TABLES";
		$stmt = $con->prepare($sql);

		$stmt->execute();
		$res = array();
		while($rs = $stmt->fetch()) {
			$tables[]=$rs[0];
		}

		return $tables;
	}

	public static function getDbName()
	{
		$con = Propel::getConnection();
		$sql = "SELECT DATABASE()";
		$stmt = $con->prepare($sql);

		$stmt->execute();
		$res = array();
		while($rs = $stmt->fetch()) {
			$db=$rs[0];
		}

		return $db;
	}


	public static function getGrouped($objects)
	{
		$result = array('' => 'Any');

		foreach($objects as $key => $value)
		{
			$result['g-'.$key] = '<b>'.$value['name'].'</b>';
			if(array_key_exists('members',$value))
			{
				foreach($value['members'] as $sid => $server)
				{
					$result['s-'.$key.'-'.$sid] = $server;
				}
			}
		}
		//echo "<pre>";print_r($result); echo "<br/>";
		return $result;
	}

	public static function getPatternArray($string)
	{
		$result = array();

		preg_match_all('/-["'."'".']([^"'."'".']+)["'."'".']/',$string,$matches);

		if(array_key_exists(0,$matches))
		{
			foreach($matches[0] as $value)
			{
				$string = preg_replace('/'.$value.'/','',$string);
			}
		}

		if(array_key_exists(1,$matches))
		{
			foreach($matches[1] as $value)
			{
				$result['excluded'][] = $value;
			}
		}

		preg_match_all('/\S?["'."'".']([^"'."'".']+)["'."'".']/',trim($string),$matches);

		if(array_key_exists(0,$matches))
		{
			foreach($matches[0] as $value)
			{
				$string = preg_replace('/'.$value.'/','',$string);
			}
		}

		if(array_key_exists(1,$matches))
		{
			foreach($matches[1] as $value)
			{
				$result['included'][] = $value;
			}
		}

		preg_match_all('/-(\S+)/',$string,$matches);

		if(array_key_exists(0,$matches))
		{
			foreach($matches[0] as $value)
			{
				$string = preg_replace('/'.$value.'/','',$string);
			}
		}

		if(array_key_exists(1,$matches))
		{
			foreach($matches[1] as $value)
			{
				$result['excluded'][] = $value;
			}
		}

		if($string && trim($string != ''))
		{
			if(preg_match('/\s/',$string))
			{
				$explode = explode(' ',trim($string));
				foreach($explode as $exp)
				{
					if(trim($exp) != '')
					{
						$result['included'][] = $exp;
					}
				}
			}
			else
			{
				$result['included'][] = $string;
			}
		}

		return $result;
	}

	/**
	 * Appends values to the end of the given array.
	 */
	public static function append_values(&$target, $values)
	{
		array_splice($target, count($target), 0, $values);
	}

	/**
	 * Returns a new instance when no id is given.
	 * Returns an existing instance for the id.
	 * Throws 404 when the id is invalid.
	 * @param className class to use (e.g. 'ServerSnmpConnection')
	 * @param idname param name to use (e.g. 'edit[0][id]')
	 */
	public static function getNewByIdOr404($className, $idname="id")
	{
		$peer = $className . 'Peer';
		$id = sfContext::getInstance()->getRequest()->getParameter($idname);
		if(!empty($id))
		{
			$obj = call_user_func(array($peer, 'retrieveByPk'), $id);
			$actionInstance = sfContext::getInstance()->getActionStack()
			->getLastEntry()->getActionInstance();
			$actionInstance->forward404Unless($obj);
			return $obj;
		}
		else
		{
			return new $className();
		}
	}

	/**
	 * Returns all names of entities of the given peer
	 * sorted by name.
	 * The array keys are the entity ids,
	 * so the array is usable for <select> options.
	 */
	public static function getAllNames($peer)
	{
		$c = new Criteria();
		$c->addAscendingOrderByColumn(eval('return '.$peer.'::NAME;'));
		$collection = new Collection(
		call_user_func(array($peer, 'doSelect'), $c), 'getName');
		return $collection->getArray();
	}

	public static function run($cmd)
	{
		posix_setpgid(0, 0);
		exec($cmd, $output, $returnCode);
		return $returnCode === 0;
	}
	
	public static function runTask($class_name, $arguments = array(), $options = array())
    {
	    $dispatcher = sfContext::getInstance()->getEventDispatcher();
	    $formatter = new sfFormatter();
	    $task = new $class_name($dispatcher, $formatter);
	    chdir(sfConfig::get('sf_root_dir'));
	    $task->run($arguments, $options);
    } 
    
    public static function getColor($status = NULL){
    	$color = '#FFFFFF';
    	if($status == 'success'){
			$color = '#C4DFA4';
		} else if($status == 'failure'){
			$color = '#F5A07F';
		}		
		return $color;    	
    }
    
    public static function getAppFlowerFiltersForWidgetTitle()
    {
    	$filters=sfContext::getInstance()->getUser()->getAttribute('filters',null,'/appflower');

    	$titleSuffix='';
    	
		if($filters!=null)
		{
			foreach ($filters as $name=>$value)
			{
				if($value==null)
				{
					unset($filters[$name]);
				}
			}
			
			if(count($filters)>0)
			{
				foreach ($filters as $name=>$value)
				{
					$titleSuffix[]=$name.': "'.$value.'"';
				}
				
				$titleSuffix=' <span style="color:red;">(filtered by '.implode(' & ',$titleSuffix).')</span>';
			}
		}
		
		return $titleSuffix;
    }
}


function dummyErrorHandler ($errno, $errstr, $errfile, $errline) {}
?>
