<?php

class FileUtils {

	public static function scanDirs($dir,$ext,&$ret = null,$filenames = false) {
  	
		if(!file_exists($dir)) {
			return false;
		}
		
	  	$input = scandir($dir);
	  	
	  	foreach($input as $file) {
	  		
	  		$tmp = $dir."/".$file;
	  		
	  		if($file == "." || $file == ".." || $file == ".svn" || substr($file,0,1) == "." || is_link($tmp)) {
	  			continue;
	  		}
	  		
	  		if(is_dir($tmp)) {
	  			$ret[$tmp] = array();
	  			self::scanDirs($tmp,$ext,$ret,$filenames);
	  		}
	  		else if(strtolower(substr($tmp,strrpos($tmp,".")+1)) == $ext) {
	  			$ret[$dir][] = ($filenames) ? trim($file) : trim($tmp);  			
	  		}
	  		
	  	}
  	
  	}
  	
  	public static function getPluginDirs(Array &$ret,$root = null, $filter = null) {
  		
  		if($root === null) {
  			$root = sfConfig::get("sf_root_dir");	
  		} 
  		
		$tmp = scandir($root."/plugins");
		
		foreach($tmp as $file) {
			if($file == "." || $file == ".." || $file == ".svn" || substr($file,0,1) == ".") {
	  			continue;
	  		}
	  		if(is_dir($root."/plugins/".$file.$filter)) {
	  			$ret[] = $root."/plugins/".$file.$filter;	
	  		}
	  		
		}
		
		return $ret;
  		
  	}
  	
  	
 	public static function findFile($basename, Array $extensions) {

        foreach($extensions as $ext) {
                $filename = $basename.".".$ext;
                if(file_exists($filename)) {
                        return array($ext,$filename);
                }
        }

        return false;
  	}
  	
  	
  	public static function createFile($filename,$data = null,$type = "t") {
		
    		$fp = @fopen($filename,"w".$type);
    		
    		if(!$fp) {
    			throw new Exception("Couldn't save file: ".$filename);
    		}
    		
    		if(!is_null($data)) {
    			fwrite($fp, $data);	
    		}
    		
    		fclose($fp);
	}
	
  	
    
}
