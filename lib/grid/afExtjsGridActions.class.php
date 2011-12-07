<?php
/**
 * extJs grid actions
 *
 */
class afExtjsGridActions
{
	/**
	 * default attributes
	 */
	public $attributes=array();
	
	public $afExtjs=null;	
	public $privateName=null;	
	public $actions = array();						
	public function __construct($attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();

		if(count($this->attributes)>0)
		$attributes=array_merge($this->attributes,$attributes);
		
		$this->afExtjs->setAddons(array ('css' => array($this->afExtjs->getPluginsDir().'rowactionsImm/css/Ext.ux.GridRowActions.css',$this->afExtjs->getPluginsDir().'rowactionsImm/css/icons.css'), 'js' => array($this->afExtjs->getPluginsDir().'rowactionsImm/js/Ext.ux.GridRowActions.js')));
		
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
	
	public function addAction($attributes=array())
	{
		
		if(!isset($this->attributes['actions']))
		$this->attributes['actions']=array();
		/** Check Credential ***/
		if(isset($attributes['url'])&&!ComponentCredential::urlHasCredential($attributes['url'])) return;
		$count_actions=count($this->attributes['actions']);
		$attributes['urlIndex']='action'.($count_actions+1);
		$attributes['hideIndex']='hide'.($count_actions+1);
		if(isset($attributes['script'])){
			$attributes['script']=$attributes['script'];
		}
		if(isset($attributes['popup'])){
			$attributes['popup']=$attributes['popup'];
		}
		if(isset($attributes['popupSettings'])){
			$attributes['popupSettings']=$attributes['popupSettings'];
		}
		else{
			$attributes['popupSettings']='';
		}

		if(isset($attributes['loadas'])) {
			$attributes['load'] = $attributes['loadas'];
			unset($attributes['loadas']);
		}
		array_push($this->actions,$attributes);
		array_push($this->attributes['actions'],$this->afExtjs->asAnonymousClass($attributes));
	}
	public function getActions(){
		return $this->actions;
	}
	public function removeAction($name){
		$toRemove = null;
		foreach($this->actions as $key=>$action){
			if($action['name'] == $name){
				$toRemove = $key;
			}
		}
		if($toRemove !== null){
			unset($this->actions[$toRemove]);
		}
		$this->attributes['actions'] = $this->afExtjs->asAnonymousClass($this->actions);
		return $this;
	}
	public function changeProperty($name,$propKey,$propVal){	
			
		$toChange = null;
		foreach($this->actions as $key=>$action){
			if($action['name'] == $name){
				$toChange = $key;
			}
		}		
		if($toChange !== null){
			if(isset($this->actions[$toChange][$propKey])){
				$this->actions[$toChange][$propKey] = $propVal;
			}else{
				$this->actions[$toChange][$propKey] = $propVal;
			}
		}		
		$this->attributes['actions'][$toChange] = $this->afExtjs->asAnonymousClass($this->actions[$toChange]);
		return $this;
	}
	public function end()
	{
		$this->privateName='grid_actions_'.Util::makeRandomKey();
		
		$this->afExtjs->private[$this->privateName]=$this->afExtjs->GridRowActions($this->attributes);
		
	}
}
?>
