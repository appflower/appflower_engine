<?php

class afValidatorCachePeer extends BaseafValidatorCachePeer
{
	
	public static function clearCache() {
		
		$con = Propel::getConnection();
    	$stmt = $con->prepare('TRUNCATE TABLE af_validator_cache');
    	$stmt->execute();
		
	}
	
	
	public static function inCache($path) {
		
		$c = new Criteria();
		$c->add(self::PATH,$path);
		$obj=self::doSelectOne($c);
		
		if($obj!=null)
		{
			return $obj;
		}
		else {
			return false;
		}
	}
	
	
	public static function putCache($hash,$path,$obj = null) {

		if($obj) {
			$cache = $obj;	
		} else {
			$cache = new afValidatorCache();
		}
		
		$cache->setPath($path);
		$cache->setSignature($hash);
		$cache->save();	
	}
	
}
