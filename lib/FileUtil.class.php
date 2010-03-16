<?php
class FileUtil {
	
	public static function saveTmpFile($data,$dir = null,$prefix = null, $permission = 0666) {
		
		if(!file_exists($dir)) {
			if(!@mkdir($dir,0700,true)) {
				throw new Exception("Unable to create directory: ".$dir);
			}
		}
	
		if(($file = tempnam($dir,$prefix)) === false) {
			throw new Exception("Unable to save tmp file in dir ".$dir);
		}
		
		$fp = fopen($file,"wt");
		
		if(!$fp) {
			throw new Exception("Unable to write tmp file: ".$file);
		}
		
		fwrite($fp,$data,strlen($data));
		fclose($fp);
		
		return $file;
		
		
	}
	
}