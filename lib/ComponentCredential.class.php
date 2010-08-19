<?php
/**
* Check credential for the url or module-action
* The url should be in the form module/action
* 
* @author: Prakash Paudel
*/
class ComponentCredential{
	
	/**
	* Components status for not having credential
	* either 'disabled' or 'hidden'
	*/
	private static $action = 'hidden';
	
	/**
	* The default hidden behavior can be changed by calling setAction with
	* 'hidden' or 'disabled' just before the refine() call
	*/
	public static function setAction($action){
		self::$action = $action;
	}
	/**
	* Make the component accessible or not accessible
	*/
	public static function filter($array,$module,$action=''){
		if($action == ""){
			if(!self::urlHasCredential($module)) $array[self::$action] = true;
		}else{
			if(!self::actionHasCredential($module,$action)) $array[self::$action] = true;
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
		$class = $module."Actions";
		$file = sfConfig::get("sf_root_dir")."/apps/frontend/modules/".$module."/actions/actions.class.php";
		if(!file_exists($file)){
			$file = sfConfig::get("sf_root_dir")."/plugins/appFlowerPlugin/modules/".$module."/actions/actions.class.php";
		}
		if(file_exists($file)){
			require_once($file);
			$obj = new $class(sfContext::getInstance(),$module,$action);	
			if(sfContext::getInstance()->getUser()->hasCredential($obj->getCredential())) return true;
		}
		return false;		
	}
	
	/**
	* Separator in menu for hidden mode. 
	* Because of hidden menu items, there may be multiple separators together.. just remove them
	*/
	private static function separatorFix($array){
		foreach($array as $key=>$item){
			if(
				isset($item['separator']) 
				&& 
				(
					(!isset($array[$key-1]) || (isset($array[$key-1]) && isset($array[$key-1]['separator'])))
					||
					(!isset($array[$key+1]) || (isset($array[$key+1]) && isset($array[$key+1]['separator'])))
				)
			)
			{
				unset($array[$key]);
			}
		}
		return $array;
	}
	
	/**
	* Recurse through menu items to hide or disable accordingly..
	*/	
	public static function refine($array){
		$toReturn = array();
		foreach($array as $key=>$item){		
			$tempItem = array();
			if(isset($item['url']) && !self::urlHasCredential($item['url'])){
				if(self::$action == 'hidden'){
					continue;
				}
				$item['disabled'] = true;
			}	
			$tempItem = $item;
			if(isset($item['items'])){
				$tempItem['items'] = self::refine($item['items']);	
				if(empty($tempItem['items'])) continue;
			}
			$toReturn[] = $tempItem;
		}
		return self::separatorFix($toReturn);
	}
}
?>