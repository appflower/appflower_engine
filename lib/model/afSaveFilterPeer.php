<?php

class afSaveFilterPeer extends BaseafSaveFilterPeer
{
	public static function getAllFilters()
	{
		$c = new Criteria();
		$user = sfContext::getInstance()->getUser()->getAppFlowerUser()->getId();
		$c->add(self::USER,$user);	
		$c->addAscendingOrderByColumn(afSaveFilterPeer::TITLE);	
		return $c;
	}
	public static function hasFilters(){
		$c = new Criteria();
		$user = sfContext::getInstance()->getUser()->getAppFlowerUser()->getId();
		$c->add(self::USER,$user);
		$filter = afSaveFilterPeer::doSelectOne($c);
		if($filter) return true;
		return false;
	}
	public static function getFiltersByName($name){		
		$data = array();		
		$user = sfContext::getInstance()->getUser()->getAppFlowerUser()->getId();
		$title = $name;
		$c = new Criteria();
		$c->add(afSaveFilterPeer::USER,$user);
		$c->add(afSaveFilterPeer::TITLE,$title);
		$c->addDescendingOrderByColumn(afSaveFilterPeer::ID);
		$objs = afSaveFilterPeer::doSelect($c);
		return $objs;
	}
}
