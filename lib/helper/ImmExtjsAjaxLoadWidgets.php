<?php
class ImmExtjsAjaxLoadWidgets{
	private $layout = null;	
	private $type = false;
	
	function __construct(){		
		$this->init();
	}
	public function setLayout($layout){
		$this->layout = $layout;
		return $this;
	}
	public function getLayout(){
		return $this->layout;
	}
	public function getType(){
		return $this->type;
	}
	private function init(){		
		$parser = new XmlParser();		
		$this->type = $parser->getType();		
		$this->layout = $parser->getLayout();
		if(method_exists($this->layout,'beforeEnd'))
		{
			$this->layout->beforeEnd();
		}
	}
	public static function initialize($action){		
		
		$widgetLoadJson=$action->hasRequestParameter("widget_load_json")?$action->getRequestParameter("widget_load_json"):"true";
		if(!in_array($widgetLoadJson,array("true","false")))
		{
			$widgetLoadJson="true";
		}
		
		//Check for the widget load request
		if($action->getRequestParameter("widget_load") && $action->getRequestParameter("widget_load") != "false"){	
				
			if(!$action->isPageComponent){		
				sfConfig::set('app_parser_panels', array());
				sfConfig::set('app_parser_skip_toolbar', true);
				$w = new ImmExtjsAjaxLoadWidgets();				
				echo $widgetLoadJson=='true'?json_encode($w->getSourceForCenterLoad()):print_r($w->getSourceForCenterLoad());
				exit;
			}
		}
		elseif($action->getRequestParameter("widget_popup_request")){	
				
			if(!$action->isPageComponent){		
				sfConfig::set('app_parser_panels', array());
				sfConfig::set('app_parser_skip_toolbar', true);
				$w = new ImmExtjsAjaxLoadWidgets();				
				echo $widgetLoadJson=='true'?json_encode($w->getSourceForPopupLoad()):print_r($w->getSourceForPopupLoad());
				exit;
			}
		}
		return false;
		/*************************************************************/
	}	
	public function getCenterPanelSource(){	
		if(isset($this->getLayout()->attributes['viewport']['center_panel']['tbar'])){
			//if(count($this->getLayout()->attributes['viewport']['center_panel']['tbar']) > 1)
			unset($this->getLayout()->attributes['viewport']['center_panel']['tbar']);
		}
		
		return ImmExtjs::getInstance()->getExtObject("Ext.Panel",$this->getLayout()->attributes['viewport']['center_panel']);
	}
	public function getPortalCenterPanelSource(){
		$config = array();
		if(isset($this->getLayout()->attributes['viewport']['center_panel']['tbar'])){
			//if(count($this->getLayout()->attributes['viewport']['center_panel']['tbar']) > 1)
			unset($this->getLayout()->attributes['viewport']['center_panel']['tbar']);
		}
		if(isset($this->getLayout()->attributes['viewport']['center_panel']['title']))
		unset($this->getLayout()->attributes['viewport']['center_panel']['title']);
		return ImmExtjs::getInstance()->getExtObject("Ext.Panel",$this->getLayout()->attributes['viewport']['center_panel']);
	}
	public function getCenterPanelFirstSource(){	
		return $this->getLayout()->immExtjs->private['center_panel_first'];
	}
	public function getAddons(){		
		return $this->getLayout()->immExtjs->addons;
	}
	public function getSourceForPopupLoad(){
		if($this->type == XmlParser::PANEL){
			return array("center_panel"=>$this->getCenterPanelSource(),"source"=>$this->getLayout()->getPrivateSource(),"addons"=>$this->getAddons(),"public_source"=>$this->getLayout()->getPublicSource());
		}
		if($this->type == XmlParser::PAGE){
			return array("center_panel"=>$this->getPortalCenterPanelSource(),"source"=>$this->getLayout()->getPrivateSource(),"addons"=>$this->getAddons(),"public_source"=>$this->getLayout()->getPublicSource());
		}
	}
	public function getSourceForCenterLoad(){
		if($this->type == XmlParser::PANEL){
			return array("center_panel_first"=>$this->getCenterPanelFirstSource(),"source"=>$this->getLayout()->getPrivateSource(),"addons"=>$this->getAddons(),"public_source"=>$this->getLayout()->getPublicSource());
		}
		if($this->type == XmlParser::PAGE){
			return array("center_panel_first"=>$this->getCenterPanelFirstSource(),"source"=>$this->getLayout()->getPrivateSource(),"addons"=>$this->getAddons(),"public_source"=>$this->getLayout()->getPublicSource());
		}		
	}	

	public static function isWidgetRequest() {
		$request = sfContext::getInstance()->getRequest();
		return ($request->getParameter('widget_load') ||
			$request->getParameter('widget_popup_request'));
	}
}
