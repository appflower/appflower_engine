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
		$popup = new XmlParser();		
		$this->type = $popup->getType();
		$this->layout = $popup->getLayout();
		if(method_exists($this->layout,'beforeEnd'))
		{
			$this->layout->beforeEnd();
		}
	}
	public static function initialize($action){		
		
		//Check for the widget load request
		if($action->getRequestParameter("widget_load")){	
				
			if(!$action->isPageComponent){		
				sfConfig::set('app_parser_panels', array());
				sfConfig::set('app_parser_skip_toolbar', true);
				$w = new ImmExtjsAjaxLoadWidgets();				
				echo $w->getSourceForCenterLoad();
				exit;
			}
		}
		elseif($action->getRequestParameter("widget_popup_request")){	
				
			if(!$action->isPageComponent){		
				sfConfig::set('app_parser_panels', array());
				sfConfig::set('app_parser_skip_toolbar', true);
				$w = new ImmExtjsAjaxLoadWidgets();				
				echo $w->getSourceForPopupLoad();
				exit;
			}
		}
		return false;
		/*************************************************************/
	}
	public function getImmExtjsPrivateSource(){
		$sourcePrivate = '';
		if(isset($this->getLayout()->immExtjs->private['toolbar'])) unset($this->getLayout()->immExtjs->private['toolbar']);
		if(isset($this->getLayout()->immExtjs->private['north_panel'])) unset($this->getLayout()->immExtjs->private['north_panel']);
		if(isset($this->getLayout()->immExtjs->private['south_panel'])) unset($this->getLayout()->immExtjs->private['south_panel']);
		if(isset($this->getLayout()->immExtjs->private['center_panel'])) unset($this->getLayout()->immExtjs->private['center_panel']);
		if(isset($this->getLayout()->immExtjs->private['center_panel_first'])) unset($this->getLayout()->immExtjs->private['center_panel_first']);
		foreach ($this->getLayout()->immExtjs->private as $key => $value){			
			$sourcePrivate .= sprintf("%svar %s = %s;", ImmExtjs::LBR, $key, ImmExtjs::_quote($key, $value));
	    }
		return $sourcePrivate;		
	}
	public function getImmExtjsPublicSource(){
		$sourcePublic = '';
		if($this->getLayout()->immExtjs->public) {
			foreach ($this->getLayout()->immExtjs->public as $key => $value){
				$sourcePublic .= $value."\n";
	   		}
		}
	    return $sourcePublic;		
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
			return json_encode(array("center_panel"=>$this->getCenterPanelSource(),"source"=>$this->getImmExtjsPrivateSource(),"addons"=>$this->getAddons(),"public_source"=>$this->getImmExtjsPublicSource()));
		}
		if($this->type == XmlParser::PAGE){
			return json_encode(array("center_panel"=>$this->getPortalCenterPanelSource(),"source"=>$this->getImmExtjsPrivateSource(),"addons"=>$this->getAddons(),"public_source"=>$this->getImmExtjsPublicSource()));
		}
	}
	public function getSourceForCenterLoad(){
		if($this->type == XmlParser::PANEL){
			return json_encode(array("center_panel_first"=>$this->getCenterPanelFirstSource(),"source"=>$this->getImmExtjsPrivateSource(),"addons"=>$this->getAddons(),"public_source"=>$this->getImmExtjsPublicSource()));
		}
		if($this->type == XmlParser::PAGE){
			return json_encode(array("center_panel_first"=>$this->getCenterPanelFirstSource(),"source"=>$this->getImmExtjsPrivateSource(),"addons"=>$this->getAddons(),"public_source"=>$this->getImmExtjsPublicSource()));
		}
	}	

	public static function isWidgetRequest() {
		$request = sfContext::getInstance()->getRequest();
		return ($request->getParameter('widget_load') ||
			$request->getParameter('widget_popup_request'));
	}
}
