<?php

class afSaveFilterPeer extends BaseafSaveFilterPeer
{
	public static function getAllFilters()
	{
		$c = new Criteria();
		$user = sfContext::getInstance()->getUser()->getGuardUser()->getId();
		$c->add(self::USER,$user);	
		$c->addAscendingOrderByColumn(afSaveFilterPeer::TITLE);	
		return $c;
	}
	public static function hasFilters(){
		$c = new Criteria();
		$user = sfContext::getInstance()->getUser()->getGuardUser()->getId();
		$c->add(self::USER,$user);
		$filter = afSaveFilterPeer::doSelectOne($c);
		if($filter) return true;
		return false;
	}
}
