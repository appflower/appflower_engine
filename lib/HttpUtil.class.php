<?php
class HttpUtil {
	
	public static function forceDownload($actionInstance,$data,$filename) {
		
		$actionInstance->getResponse()->clearHttpHeaders();
		$actionInstance->getResponse()->setContentType('application/octet-stream');
		$actionInstance->getResponse()->setHTTPHeader('Content-disposition','attachment; filename = '.$filename, true);
		$actionInstance->getResponse()->sendHttpHeaders();
		
		echo $data;
		exit();
		
		
	}
	
}