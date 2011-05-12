<?php

class Tz {
    
	public static 
		$zone;
		
	public static function formatTime($unix_timestamp = null) {
        if($unix_timestamp === null) {
            $unix_timestamp = time();
        } else if((int) $unix_timestamp === 0) {
            return $unix_timestamp;
        }

        return self::date('Y-m-d H:i:s', $unix_timestamp);
    }

    /**
     * Returns timestamp that correspond to the time
     * that the user really wanted.
     * For example, (00:00 UTC == 02:00 CEST) -> 00:00 CEST.
     */
    public static function pickTime($unix_timestamp) {
        
    	if(self::$zone) {
        	$time_zone_offset = self::$zone->getOffset();	
        } else {
        	$time_zone_offset = self::getUserTimeZones()->getOffset();
        }
    	
        return $unix_timestamp - $time_zone_offset;
    }

    /**
     * Returns formatted time.
     * It considers the user's timezone.
     */
    public static function date($format, $unix_timestamp) {
        
    	if(self::$zone) {
        	$time_zone_offset = self::$zone->getOffset();	
        } else {
        	$time_zone_offset = self::getUserTimeZones()->getOffset();
        }
    	
        return date($format, $unix_timestamp + $time_zone_offset);
    }

    /**
     * Formats string timestamp obtained from the db.
     */
    public static function reformat($formatted) {
        return self::formatTime(strtotime($formatted));
    }

    public static function getUserTimeZones() {
        $timeZones = null;
    	$user = sfContext::getInstance()->getUser();
        $profile = $user->getProfile();
        if ($profile !== null) {
            $timeZones = $profile->getTimeZones();
        }
        if ($timeZones === null) {
            $timeZones = TimeZonesPeer::retrieveByPk(1);
        }
        return $timeZones;
    }
}
