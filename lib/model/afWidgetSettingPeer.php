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

	public static function buildCriteria()
	{
		$criteria = new Criteria();
		$user = sfContext::getInstance()->getUser()->isAuthenticated()?sfContext::getInstance()->getUser()->getGuardUser()->getId():'';
		$criteria->add(self::USER,$user);
		return $criteria;
	}

	public static function getEventSetting()
	{
		$criteria = self::buildCriteria();
		$criteria->add(self::NAME,'/eventmanagement/showEventGraph');
		return self::doSelectOne($criteria);
	}

	public static function getSyslogSetting()
	{
		$criteria = self::buildCriteria();
		$criteria->add(self::NAME,'/eventmanagement/syslogGraph');
		return self::doSelectOne($criteria);
	}

	public static function getSyslogTotalSetting()
	{
		$criteria = self::buildCriteria();
		$criteria->add(self::NAME,'/eventmanagement/syslogGraphCountSize');
		return self::doSelectOne($criteria);
	}

	public static function getTopSuccessSetting()
	{
		$criteria = self::buildCriteria();
		$criteria->add(self::NAME,'/eventmanagement/topSuccessUser');
		return self::doSelectOne($criteria);
	}

	public static function getTopFailedSetting()
	{
		$criteria = self::buildCriteria();
		$criteria->add(self::NAME,'/eventmanagement/topFailedUser');
		return self::doSelectOne($criteria);
	}	
	public static function getFirewallSetting()
  	{
	    $criteria = self::buildCriteria();
	    $criteria->add(self::NAME,'/netflow/showFirewallGraph');
	    return self::doSelectOne($criteria);
  	}
	public static function getDefaultSettingByName($name){
		switch ($name){
			case "/eventmanagement/showEventGraph":
			case "/eventmanagement/syslogGraph":
				return array(
  					"basic" => array("graph_type_value" => "ImmAm3DColumn",	"text_color" => "#000000","text_font_value" => "Arial","text_size_value" => "11","bg_color" => "#FFFFFF", "height" => 380),	
			        "emergency" => array("show" => true, "text" => "Emergency", "color" => "#F8A281", "hover_color" => "#ff0000"),
					"alert" => array("show" => true, "text" => "Alert", "color" => "#FCC698", "hover_color" => "#ff0000"),	
					"critical" => array("show" => true, "text" => "Critical", "color" => "#FFE2A1", "hover_color" => "#ff0000"),	
			        "error" => array("show" => true, "text" => "Error", "color" => "#F6F3A7", "hover_color" => "#ff0000"),
		            "warning" => array("show" => true, "text" => "Warning", "color" => "#C5E0A4", "hover_color" => "#ff0000"),
					"notice" => array("show" => true, "text" => "Notice", "color" => "#A0C999", "hover_color" => "#ff0000"),
					"info" => array("show" => true, "text" => "Info", "color" => "#9DC4C6", "hover_color" => "#ff0000"),
					"debug" => array("show" => true, "text" => "Debug", "color" => "#A4BCE2", "hover_color" => "#ff0000"),
			        "reload" => array("started" => false, "interval" => 60));
				break;
			case "/eventmanagement/syslogGraphCountSize":
				return array(
					"basic" => array("graph_type_value" => "ImmAm3DColumn", "text_color" => "#000000","text_font_value" => "Arial", "text_size_value" => "11","bg_color" => "#FFFFFF", "height" => 380),
					"count" => array("show" => true, "text" => "Log Count", "color" => "#2C5D99", "hover_color" => "#ff0000"),
				 	"size" => array("show" => true, "text" => "Log Size", "color" => "#e0802b", "hover_color" => "#ff0000"),	
		         	"reload" => array("started" => false, "interval" => 60));
				break;
			case "/eventmanagement/topSuccessUser":
			case "/eventmanagement/topFailedUser":
				return array(
					"basic" => array("graph_type_value" => "ImmAmPie","text_color" => "#000000", "text_font_value" => "Arial","text_size_value" => "11", "bg_color" => "#FFFFFF", "height" => "380"),
	            	"reload" => array("started" => false, "interval" => 60));
			case "/netflow/showFirewallGraph":
				return array(
					"basic" => array("graph_type_value" => "ImmAm3DColumn", "text_color" => "#000000","text_font_value" => "Arial", "text_size_value" => "11","bg_color" => "#FFFFFF", "height" => 380),	
			        "granted" => array("show" => true, "text" => "Granted", "color" => "#C4DFA4", "hover_color" => "#ff0000"),
					"blocked" => array("show" => true, "text" => "Blocked", "color" => "#F5A07F", "hover_color" => "#ff0000"),	
			        "reload" => array("started" => false, "interval" => 60));	
		}
	}
	public static function renderUI($action){		
		$action->user_id = $action->getUser()->getGuardUser()->getId();
		$setting = self::getDefaultSettingByName($action->name);
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
		$posted = $action->getRequestParameter("edit[0]");
		$setting = self::getDefaultSettingByName($posted['name']);
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
		$setting = self::getDefaultSettingByName($name);		
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
