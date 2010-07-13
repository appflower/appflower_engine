<?php

function uri_for($file) {
	
	if(strstr($file,"/plugins")) {
		return str_replace("web/","",substr($file,strrpos($file,"/plugins")+8));
	} else {
		return str_replace(sfConfig::get("sf_root_dir")."/web","",$file);
	}
	
}

?>