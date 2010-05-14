<?php
class ImmExtjsAjaxLoadWidgets{
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
		if(method_exists($this->layout,'beforeEnd'))
		{
			$this->layout->beforeEnd();
		}
	}
	public static function initialize($action,$type=false,$layout=null){		
		
		//Check for the widget load request
		if($action->getRequestParameter("widget_load")){	
				
			if(!$action->isPageComponent){		
				sfConfig::set('app_parser_panels', array());
				sfConfig::set('app_parser_skip_toolbar', true);
				$w = new ImmExtjsAjaxLoadWidgets($layout,$type);				
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
	public function getCenterPanelFirstSource(){	
		return $this->getLayout()->immExtjs->private['center_panel_first'];
	}
	public function getAddons(){		
		return $this->getLayout()->immExtjs->addons;
	}
	public function getSource(){
		if($this->type == XmlParser::PANEL){
			return json_encode(array("center_panel_first"=>$this->getCenterPanelFirstSource(),"source"=>$this->getImmExtjsPrivateSource(),"addons"=>$this->getAddons(),"public_source"=>$this->getImmExtjsPublicSource()));
		}
		if($this->type == XmlParser::PAGE){
			return json_encode(array("center_panel_first"=>$this->getCenterPanelFirstSource(),"source"=>$this->getImmExtjsPrivateSource(),"addons"=>$this->getAddons(),"public_source"=>$this->getImmExtjsPublicSource()));
		}
	}	
}
