<?php
class HttpUtil {
	
	public static function forceDownload($actionInstance,$data,$filename) {
		self::sendDownloadHeaders($filename, null, strlen($data));
		echo $data;
		exit();
	}

	public static function sendDownloadHeaders($basename, $contentType=null, $size=null, $mtime=null) {
		if($contentType === null) {
			$contentType = 'application/octet-stream';
		}

		$response = sfContext::getInstance()->getResponse();
		$response->clearHttpHeaders();
		$response->setHttpHeader('Content-Type', $contentType);
		$response->setHttpHeader('Content-Disposition',
			'attachment; filename='.$basename);

		if($size) {
			$response->setHttpHeader('Content-Length', $size);
		}
		if($mtime) {
			$response->setHttpHeader('Last-Modified',
				gmdate('D, d M Y H:i:s', $mtime).' GMT');
		}
		$response->sendHttpHeaders();
	}
}
