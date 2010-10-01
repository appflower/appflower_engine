<?php

class afGenerator {
	
	public static function fetchData($peer,$orderby = null,$orderdir = "Ascending") {
		
		$c = new Criteria();
		if($orderby) {
			$method = "add".$orderdir."OrderByColumn"; 
			call_user_func(array($c,$method), constant($peer."::".strtoupper($orderby)));
		} else {
			call_user_func(array($c,"addAscendingOrderByColumn"), constant($peer."::ID"));
		}
		
		return $c;
		
	}
	
	public static function asCombo($peer,$name_columns = "name",$id_column = "id") {

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
	
	
	public static function asMulticombo($peer,$name_columns = "name",$id_column = "id") {
		return self::asCombo($peer,$name_columns,$id_column);
	}
	
	
	

	
}



?>