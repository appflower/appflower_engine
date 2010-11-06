<?php

class afWidgetHelpSettingsPeer extends BaseafWidgetHelpSettingsPeer
{
	public static function retrieveCurrent()
	{
        $afUser = sfContext::getInstance()->getUser()->getAppFlowerUser();
		if(!$afUser->isAnonymous()) {
            return new afWidgetHelpSettings();
        }

		$userId=$afUser->getId();
		
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
