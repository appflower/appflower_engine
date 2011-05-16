<?php
class afExtjsAjaxLoadWidgets{
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
                                $context = sfContext::getInstance();
				$profiler = $context->has('profiler') ? $context->get('profiler') : null;
				if ($profiler) {
    				$profiler->sendHeaders();
				}
				sfConfig::set('app_parser_panels', array());
				sfConfig::set('app_parser_skip_toolbar', true);
				$w = new afExtjsAjaxLoadWidgets();				
				echo $widgetLoadJson=='true'?json_encode($w->getSourceForCenterLoad()):print_r($w->getSourceForCenterLoad());
				if ($profiler) {
    				$profiler->collectFromContext();
				}
				exit;
			}
		}
		elseif($action->getRequestParameter("widget_popup_request")){	
				
			if(!$action->isPageComponent){		
                                $context = sfContext::getInstance();
				$profiler = $context->has('profiler') ? $context->get('profiler') : null;
				if ($profiler) {
    				$profiler->sendHeaders();
				}
				sfConfig::set('app_parser_panels', array());
				sfConfig::set('app_parser_skip_toolbar', true);
				$w = new afExtjsAjaxLoadWidgets();				
				echo $widgetLoadJson=='true'?json_encode($w->getSourceForPopupLoad()):print_r($w->getSourceForPopupLoad());
				if ($profiler) {
    				$profiler->collectFromContext();
				}
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
		
		return afExtjs::getInstance()->getExtObject("Ext.Panel",$this->getLayout()->attributes['viewport']['center_panel']);
	}
	public function getPortalCenterPanelSource(){
		$config = array();
		if(isset($this->getLayout()->attributes['viewport']['center_panel']['tbar'])){
			//if(count($this->getLayout()->attributes['viewport']['center_panel']['tbar']) > 1)
			unset($this->getLayout()->attributes['viewport']['center_panel']['tbar']);
		}
		if(isset($this->getLayout()->attributes['viewport']['center_panel']['title']))
		unset($this->getLayout()->attributes['viewport']['center_panel']['title']);
		
		return afExtjs::getInstance()->getExtObject((($this->getLayout()->attributes['layoutType']==afPortalStatePeer::TYPE_NORMAL)?"Ext.Container":"Ext.Panel"),array('items'=>array('center_panel_first_panel'),'height'=>500,'layout'=>'fit'/*,'autoScroll'=>true*/));
	}
	public function getCenterPanelFirstSource(){	
		return $this->getLayout()->afExtjs->private['center_panel_first'];
	}
	public function getAddons(){		
		if(sfConfig::get('af_debug'))
		{
			return $this->getLayout()->afExtjs->addons;
		}
		else {
			return array();
		}			
	}
	public function getSourceForPopupLoad(){
		if($this->type == XmlParser::PANEL){
			return array("center_panel"=>$this->getCenterPanelSource(),"winConfig"=>array('title'=>$this->getLayout()->afExtjs->privateAttributes['center_panel_first_panel']['title']),"source"=>$this->getLayout()->getPrivateSource(),"addons"=>$this->getAddons(),"public_source"=>$this->getLayout()->getPublicSource());
		}
		if($this->type == XmlParser::PAGE){
			return array("center_panel"=>$this->getPortalCenterPanelSource(),"winConfig"=>array('title'=>$this->getLayout()->afExtjs->privateAttributes['container']['title']),"source"=>$this->getLayout()->getPrivateSource(),"addons"=>$this->getAddons(),"public_source"=>$this->getLayout()->getPublicSource());
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
