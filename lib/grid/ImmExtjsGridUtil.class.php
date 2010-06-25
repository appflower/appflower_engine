<?php
/**
 * 
 * @author Prakash
 * Grid util: adding a link in grid, which on clicked, perform the action, reload grid, redirect etc
 * 
 * #### SAMPLE USAGE
 * 		$gu = new ImmExtjsGridUtil('/loganalysis/deleteLogSignature');
		$gu->onSuccess("reload");//$gu->onSuccess("redirect");
		$gu->setConfirmMsg("Are u sure?");
		$gu->setParams(array("id"=>$this->getId()));
		return $gu->getHandler($this->getName());
 */
class ImmExtjsGridUtil{	
	private $is_ajax = true;	
	private $url = '#';
	private $params = array();	
	private $onSuccess = '';
	private $confirmMsg = "";
	private $redirect = '';	
	
	public function __construct($url='#',$params=array(),$is_ajax=true){		
		$this->setUrl($url);
		$this->setParams($params);
		$this->isAjax($is_ajax);		
	}
	public function setConfirmMsg($msg){
		$this->confirmMsg = $msg;
	}
	public function setUrl($url){
		$this->url = $url;
		return $this;
	}
	public function setParams($params=array()){
		$this->params = $params;
	}
	public function addParam($param){
		if(is_array($param))
		$this->params[$param[0]] = $param[1];
		return $this;
	}
	
	// state methods
	public function onSuccess($param,$redirect=''){
		$this->onSuccess = strtoupper($param);
		$this->redirect = $redirect;
	}
	public function isAjax($bool){
		$this->is_ajax = $bool;
		return $this;
	}
	public function getHandler($text){		
		$config = array(			
			'url'=>$this->url,
			'params'=>$this->params,
			'is_ajax'=>$this->is_ajax,
			'onsuccess'=>$this->onSuccess,
			'confirmMsg'=>$this->confirmMsg,
			'redirect'=>$this->redirect
		);		
		return "<a href='#' rel='".json_encode($config)."' class='grid-util-action'>".$text."</a>";		
	}	
	public static function grid($url){
		$id = "inline-grid-id-".rand(1,10000)."-".rand(1,10000)."-".rand(1,10000)."-".rand(1,10000);		 
		$html = '<div id="'.$id.'"></div>';
		$script = "afApp.widgetPopup('".$url."','',null,\"'width':'100%','autoHeight':true,'frame':false,'plain':true,'applyTo':'".$id."'\");";
		$return = array("success"=>true,"script"=>$script,"html"=>$html);
		return json_encode($return);		
	}
}