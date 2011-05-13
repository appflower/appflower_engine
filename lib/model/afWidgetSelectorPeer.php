<?php

class afWidgetSelectorPeer extends BaseafWidgetSelectorPeer
{

	public static function addNewItem($url,$cid,$params = null,$permission=null) {
		
		$class = new afWidgetSelector();
		
		$class->setUrl($url);
		$class->setCategoryId($cid);
		$class->setParams($params);
		$class->setPermission($permission);
		$class->save();
		
		return $class->getId();
		
	}
	
	public static function getWidgetByUrl($url) {
		
		$c = new Criteria();
		$c->add(self::URL,$url);
		return self::doSelectOne($c);
		
	}
	
	public static function removeWidgetByUrl($url) {
		
		$c = new Criteria();
		$c->add(self::URL,$url);
		$c->setLimit(1);
		return self::doDelete($c);
		
	}
	
	
	public static function clear() {
		
		$con = Propel::getConnection();
    	$stmt = $con->prepare('TRUNCATE TABLE af_widget_selector');
    	$stmt->execute();
		
	}
	
	public static function getAllWidgets($propel = true) {
		
		return self::getWidgetsByCategory(array(),$propel);
	}
	
	
	public static function getWidgetsByCategory(Array $ids,$propel = true) {
		$user = sfContext::getInstance()->getUser();
		$c = new Criteria();
		if(!empty($ids)) {
			$c->add(self::CATEGORY_ID,$ids,Criteria::IN);	
		}
		
		$c->addAscendingOrderByColumn(afWidgetCategoryPeer::NAME);
		
		$res = self::doSelectJoinafWidgetCategory($c);
		
		if($propel) {
			return $res;
		} else {
			$ret = array();
			foreach($res as $k => $item) {
				$permission = $item->getPermission();
				if(json_decode($permission,true) != NULL){
					$permission = json_decode($permission);
				}
				if($user->hasCredential($permission)){
					$cat = $item->getafWidgetCategory();
					if(!isset($ret[$cat->getId()])) {
						$ret[$cat->getId()] = array("title" => $item->getafWidgetCategory()->getName(), "widgets" => array("/".$item->getUrl()));	
					} else {
						$ret[$cat->getId()]["widgets"][] = "/".$item->getUrl();
					}
				}
			}
			sort($ret);
			return $ret;
		}
		
	}
	
	
	
	
}
