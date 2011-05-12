<?php

class sfCombineServerPeer extends BasesfCombineServerPeer
{
	public static function setDefault($status)
	{
		$object=self::doSelectOne(new Criteria());
		if($object!=null)
		{
			$object->setOnline($status);
			$object->save();
		}
		else {
			$objectn=new sfCombineServer();
			$objectn->setOnline($status);
			$objectn->save();
		}
	}
	
	public static function getDefault()
	{
		$object=self::doSelectOne(new Criteria());
		if($object!=null)
		{
			return $object;
		}
		else 
		{
			self::setDefault(false);
			
			return self::getDefault();
		}
	}
}
