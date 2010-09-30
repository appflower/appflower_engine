<?php

class afWidgetSettingPeer extends BaseafWidgetSettingPeer
{
	public static function getGraphTypes()
	{
		return array("ImmAmBar"=>"2DBar Graph", "ImmAm3DBar"=>"3DBar Graph","ImmAmLine"=>"Line Graph", "ImmAmColumn"=>"2DColumn Graph", "ImmAm3DColumn"=>"3DColumn Graph", "ImmAm3DStackedColumn"=>"3DStackedColumn Graph");
	}

	public static function getPieGraphTypes()
	{
		return array("ImmAmPie"=>"2DPie Graph", "ImmAm3DPie"=>"3DPie Graph","ImmAmDonut"=>"2DDoughnut Graph", "ImmAm3Donut"=>"3DDoughnut Graph");
	}

	public static function getTextSize()
	{
		$options = array();

		for($i=8;$i<=42;$i++)
		{
			$options[$i] = $i;
		}
		return $options;
	}

	public static function getTextFont()
	{
		$fonts = array("Arial" => "Arial", "Helvetica" => "Helvetica", "sans-serif" => "sans-serif","Arial Black" => "Arial Black", "Comic Sans MS" => "Comic Sans MS","Courier New" => "Courier New","Georgia" => "Georgia","Serif" => "Serif","Times New Roman" => "Times New Roman", "Verdana" => "Verdana", "Webdings" => "Webdings");
		asort($fonts);
		return $fonts;
	}
	public static function renderUI($action){		
		$action->user_id = $action->getUser()->getGuardUser()->getId();
		if(class_exists("GraphUtil"))
		$setting = GraphUtil::getDefaultSettingByName($action->name);
		$action->id = '';
		$c = new Criteria();
		$c->add(afWidgetSettingPeer::USER,$action->user_id);
		$c->add(afWidgetSettingPeer::NAME,$action->name);
		$obj = afWidgetSettingPeer::doSelectOne($c);
		if($obj){
			$action->id = $obj->getId();
			$setting = json_decode($obj->getSetting(),true);
		}
		$varHolder = array();
		if(is_array($setting)){
			foreach($setting as $k=>$v){
				if(is_array($v)){
					foreach($v as $kk=>$vv){
						$varHolder[$k."_".$kk] = $vv;
					}
				}else{
					$varHolder[$k] = $v;
				}
			}
		}
		$action->getVarHolder()->add($varHolder);
		return XmlParser::layoutExt($action);
	}
	public static function updateSettings($action){
		$setting = array();
        $formData = $action->getRequestParameter("edit");
		$posted = $formData[0];
		if(class_exists("GraphUtil"))
		$setting = GraphUtil::getDefaultSettingByName($posted['name']);
		if(!isset($posted['default'])){
			$userSetting = array();
			foreach($setting as $key=>$value){
				if(is_array($value)){
					foreach($value as $k=>$v){
						$userSetting[$key][$k] = isset($posted[$key."_".$k])?$posted[$key."_".$k]:false;
					}
				}
			}
			$setting = $userSetting;			
		}		
		$obj = afWidgetSettingPeer::retrieveByPK($posted['id']);
		if(!$obj){
			$obj = new afWidgetSetting();
			$obj->setUser($action->getUser()->getGuardUser()->getId());
			$obj->setName($posted['name']);
		}
		$obj->setSetting(json_encode($setting));
		$obj->save();
		$message='Settings successfully saved';
		return $action->renderText(json_encode(array('success' => true, 'message' =>$message)));
	}
	public static function getSettingByName($name){
		$user_id = sfContext::getInstance()->getUser()->getGuardUser()->getId();
		$setting = GraphUtil::getDefaultSettingByName($name);		
		$c = new Criteria();
		$c->add(afWidgetSettingPeer::USER,$user_id);
		$c->add(afWidgetSettingPeer::NAME,$name);
		$obj = afWidgetSettingPeer::doSelectOne($c);
		if($obj){			
			$setting = json_decode($obj->getSetting(),true);
		}
		return $setting;
	}
	public static function getOriginalGraphTitle($name,$title){
		$settings = self::getSettingByName($name);
		foreach($settings as $key=>$setting){
			if(isset($setting['text']) && $setting['text'] == $title){
				return $key;
			}
		}
		return $title;
	}
}
