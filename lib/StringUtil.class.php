<?php

class StringUtil {
    public static function startsWith($str, $prefix) {
        return strpos($str, $prefix) === 0;
    }

    public static function endsWith($str, $end) {
        return strcmp(substr($str, strlen($str) - strlen($end)), $end) === 0;
    }

    /**
     * Returns true when the needle in inside haystack.
     */
    public static function isIn($needle, $haystack) {
        return false !== strstr($haystack, $needle);
    }
    
	public static function hasTags($str) {
		return preg_match("/<[\/]*[^>]+>/",$str);
    }
    
	public static function removeTags($str) {
		return preg_replace("/<[\/]*[^>]+>/","",$str);
    }
    
}

