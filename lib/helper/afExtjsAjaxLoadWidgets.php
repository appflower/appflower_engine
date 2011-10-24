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
	    
        if (sfContext::getInstance()->has('profiler')) {
            $timer = sfTimerManager::getTimer('afRead'); // this time will be stopeed inside XmlParser constructor
        }
		$parser = new XmlParser();		
		$this->type = $parser->getType();		
		$this->layout = $parser->getLayout();
		if(method_exists($this->layout,'beforeEnd'))
		{
			$this->layout->beforeEnd();
		}
        if (sfContext::getInstance()->has('profiler')) {
            $timer = sfTimerManager::getTimer('afRender');
            $timer->addTime();// this one closes afRender timer that was started inside XmlParser constructor
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
	
	private function getPopupPanelSource(){
	    
	    if($this->type == XmlParser::PANEL)
	    {
		  return afExtjs::getInstance()->getExtObject("Ext.Panel",$this->getLayout()->attributes['viewport']['center_panel']);
	    }
	    else if($this->type == XmlParser::PAGE)
	    {
	      return afExtjs::getInstance()->getExtObject((($this->getLayout()->attributes['layoutType']==afPortalStatePeer::TYPE_NORMAL)?"Ext.Container":"Ext.Panel"),array('items'=>array('center_panel_first_panel'),'height'=>500,'layout'=>'fit'));  
	    }
	}
	
	public function getCenterPanelSource(){	
	    
	    if($this->type == XmlParser::PANEL || $this->type == XmlParser::PAGE)
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
	
	private function getSourceForPopupLoad(){
	    
	    return array("center_panel"=>$this->getPopupPanelSource(),"source"=>$this->getLayout()->getPrivateSource(),"addons"=>$this->getAddons(),"public_source"=>$this->getLayout()->getPublicSource());
	}
	
	public function getSourceForCenterLoad(){
		
	    return array("center_panel_first"=>$this->getCenterPanelSource(),"source"=>$this->getLayout()->getPrivateSource(),"addons"=>$this->getAddons(),"public_source"=>$this->getLayout()->getPublicSource());
	}	

	public static function isWidgetRequest() {
	    
		$request = sfContext::getInstance()->getRequest();
		return ($request->getParameter('widget_load') ||
			$request->getParameter('widget_popup_request'));
	}
}
