<?php

class afWidgetCategoryPeer extends BaseafWidgetCategoryPeer
{
	
	public static function addNewItem($module,$name) {
		
		$class = new afWidgetCategory();
		
		$class->setModule($module);
		$class->setName($name);
		$class->save();
		
		return $class->getId();
		
	}
	
	
	public static function getCategoryByModule($module) {
		
		$c = new Criteria();
		$c->add(self::MODULE,$module);
		return self::doSelectOne($c);
		
	}
	
	public static function clear() {
		
		$con = Propel::getConnection();
    	$stmt = $con->prepare('TRUNCATE TABLE af_widget_category');
    	$stmt->execute();
		
	}
	
}
