<?php

class afWidgetHelpSettingsPeer extends BaseafWidgetHelpSettingsPeer
{
	public static function retrieveCurrent()
	{
		$userId=sfContext::getInstance()->getUser()->getGuardUser()->getId();
		
		$c=new Criteria();
		$c->add(self::USER_ID,$userId);
		$obj=self::doSelectOne($c);
		if($obj!=null)
		{
			return $obj;
		}
		else {
			$obj=new afWidgetHelpSettings();
			$obj->setUserId($userId);
			$obj->save();
			
			return $obj;
		}
	}
}
