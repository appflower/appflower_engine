<?php

class ArrayUtil {
    /**
     * Returns the found value or the given default value.
     * Usage: get($array, $path1, $path2, ..., $defaultValue)
     */
    public static function get() {
        $args = func_get_args();
        $array = $args[0];
        $defaultValue = $args[count($args) - 1];
        for ($i = 1; $i < count($args) - 1; $i++) {
            $path = $args[$i];
            if(!isset($array[$path])) {
                return $defaultValue;
            }
            $array = $array[$path];
        }
        return $array;
    }

    public static function isTrue($array, $key) {
        if (!isset($array[$key])) {
            return false;
        }
        $value = $array[$key];
        return $value === true || $value === 'true';
    }
    
    
    public static function arrayToQueryString(Array $input,Array $exclusions = array()) {
    	
    	$str = "";
    	
    	foreach($input as $name => $data) {
    		if(!in_array($name,$exclusions)) {
    			$str .= $name."=".$data."&";	
    		}
    	}
    	
    	return trim($str,"&");
    	
    }
    
    
	public static function queryStringToArray($input) {
    	
		$tmp = explode("&",$input);
		
		$ret = array();
		
		foreach($tmp as $var) {
			$ret[strtok($var,"=")] = strtok("=");
		}
		
    	return $ret;
    	
    }
    
    
}
