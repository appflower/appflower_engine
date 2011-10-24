<?php
/**
* Check credential for the url or module-action
* The url should be in the form module/action
* 
* @author: Prakash Paudel
*/
class ComponentCredential{
	
	/**
	* Make the component accessible or not accessible
	*/
	public static function filter($array,$url){
        if(!self::urlHasCredential($url)) {
            $array['hidden'] = true;
        }
		return $array;
	}
	/*
	* Check if user has credential for url
	* url should be in module/action format
	*/
	public static function urlHasCredential($url){
		$array = explode("/",$url);
		$array = array_diff($array,array("/",null,""));
		$array = array_values($array);
		$module = isset($array[0])?$array[0]:null;
		$action = isset($array[1])?$array[1]:null;	
		if(strpos($action,"?") !== false){
			$temp = explode("?",$action);
			$action = $temp[0];
		}				
		if(!$module || !$action) return true;		
		return self::actionHasCredential($module,$action);
	}
	
	/**		
	* Direct checking whether user has credential for module,action 
	*/
	public static function actionHasCredential($module,$action){
        $actionInstance = afConfigUtils::getActionInstance($module, $action);
        $user = sfContext::getInstance()->getUser();
        
        if (!$actionInstance->isSecure()) {
            return true;
        } else if (!$user->isAuthenticated()) {
            return false;
        }
        
        $credentials = $actionInstance->getCredential();
        /**
         * the action might not have any credentials set, so allow access
         */
        if($credentials==null)
        {
            return true;
        }
        else if($user->hasCredential($credentials))
        {
            return true;
        }
	}
}
?>