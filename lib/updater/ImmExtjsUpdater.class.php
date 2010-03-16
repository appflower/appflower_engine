<?php
/**
 * extJs updater
 *
 */
class ImmExtjsUpdater
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
		
		$this->immExtjs->setAddons(array ( 'js' => array($this->immExtjs->getExamplesDir().'comet/Ext.Comet.js',$this->immExtjs->getExamplesDir().'comet/Ext.ux.Updater.js') ));
		
		$this->privateName='updater_'.Util::makeRandomKey();
		
		$this->attributes['id']=$this->privateName;
						
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
		
		$this->end();
	}
		
	public function end()
	{			
		$this->immExtjs->private[$this->privateName]=$this->immExtjs->Updater($this->attributes);
	}
}
?>