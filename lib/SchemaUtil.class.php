<?php

class SchemaUtil {

	public static function readSchema($combined = true) {
	  	
		
		$schema = array();
		$dirs = array(sfConfig::get("sf_root_dir"));
		
		if($combined) {
			FileUtils::getPluginDirs($dirs);	
		}
	  	
	  	foreach($dirs as $dir) {
	  		if(file_exists($dir."/config/schema.yml")) {
	  			$content = file_get_contents($dir."/config/schema.yml");
	  			$tmp = sfYaml::load($content);	
	  			unset($tmp["propel"]["_attributes"]);
	  			$schema = array_merge($schema,$tmp["propel"]);	
	  		}
	  		
	  	} 
	  	
	  	return array("propel" => $schema); 	
		
	}
	
	public static function getSchemaDataForModel($model,$schema) {
  	
	  	foreach($schema["propel"] as $name => $table) {
	  		if($table["_attributes"]["phpName"] == $model) {
	  			return $table;
	  		}
	  	}
	  	
	  	return array();
  	
  	}
  	
  	
  	public static function getPrimaryKey(Array $table) {
  		
  		if(array_key_exists("id", $table) && !is_array($table["id"])) {
  			return "id";
  		}
  		
  		foreach($table as $col => $data) {
  			if(is_array($data) && array_key_exists("primaryKey", $data)) {
  				return $col;
  			}
  		}
  		
  		return false;
  		
  	}
  	
	
	

}
