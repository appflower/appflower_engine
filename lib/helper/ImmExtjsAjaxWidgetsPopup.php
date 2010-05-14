<?php
class ImmExtjsAjaxWidgetsPopup{
	private $layout = null;	
	private $type = false;
	
	function __construct($layout=null,$type=false){		
		$this->type = $type;			
		if($layout != null){
			$this->layout = $layout;
						
		}else $this->init($type);		
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
	private function init($type){		
		$popup = new XmlParser($type);		
		$this->layout = $popup->getLayout();
	}
	public static function checkWidgetPopupRequest($action,$type=false,$layout=null){		
		
		//Check for the widget popup request
		if($action->getRequestParameter("widget_popup_request")){	
				
			if(!$action->isPageComponent){		
				sfConfig::set('app_parser_panels', array());
				$action->getUser()->getAttributeHolder()->add($action->getUser()->getAttributeHolder(),"SESSION_BACKUP");
				$w = new ImmExtjsAjaxWidgetsPopup($layout,$type);							
				echo $w->getSource();				
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
		foreach ($this->getLayout()->immExtjs->private as $key => $value){			
			$sourcePrivate .= sprintf("%svar %s = %s;", ImmExtjs::LBR, $key, ImmExtjs::_quote($key, $value));
	    }
	   // echo "<pre>";print_r($this->getLayout()->immExtjs->private);exit;	    
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
	public function getAddons(){		
		return $this->getLayout()->immExtjs->addons;
	}
	public function getSource(){
		if($this->type == XmlParser::PANEL){
			return json_encode(array("center_panel"=>$this->getCenterPanelSource(),"source"=>$this->getImmExtjsPrivateSource(),"addons"=>$this->getAddons(),"public_source"=>$this->getImmExtjsPublicSource()));
		}
		if($this->type == XmlParser::PAGE){
			return json_encode(array("center_panel"=>$this->getPortalCenterPanelSource(),"source"=>$this->getImmExtjsPrivateSource(),"addons"=>$this->getAddons(),"public_source"=>$this->getImmExtjsPublicSource()));
		}
	}	
}
