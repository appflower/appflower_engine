<?php
/**
 * extJs grid actions
 *
 */
class ImmExtjsGridActions
{
	/**
	 * default attributes
	 */
	public $attributes=array();
	
	public $immExtjs=null;	
	public $privateName=null;
							
	public function __construct($attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();

		if(count($this->attributes)>0)
		$attributes=array_merge($this->attributes,$attributes);
		
		$this->immExtjs->setAddons(array ('css' => array($this->immExtjs->getExamplesDir().'rowactionsImm/css/Ext.ux.GridRowActions.css',$this->immExtjs->getExamplesDir().'rowactionsImm/css/icons.css'), 'js' => array($this->immExtjs->getExamplesDir().'rowactionsImm/js/Ext.ux.GridRowActions.js')));
		
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
	
	public function addAction($attributes=array())
	{
		
		if(!isset($this->attributes['actions']))
		$this->attributes['actions']=array();
		
		$count_actions=count($this->attributes['actions']);
		$attributes['urlIndex']='action'.($count_actions+1);
		$attributes['hideIndex']='hide'.($count_actions+1);
		if(isset($attributes['script'])){
			$attributes['script']=$attributes['script'];
		}
		if(isset($attributes['popup'])){
			$attributes['popup']=$attributes['popup'];
		}
		
		array_push($this->attributes['actions'],$this->immExtjs->asAnonymousClass($attributes));
	}
	
	public function end()
	{
		$this->privateName='grid_actions_'.Util::makeRandomKey();
		
		$this->immExtjs->private[$this->privateName]=$this->immExtjs->GridRowActions($this->attributes);
		
	}
}
?>