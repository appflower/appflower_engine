<?php
/**
 * extJs updater
 *
 */
class afExtjsUpdater
{
	/**
	 * default attributes
	 */
	public $attributes=array();
	
	public $afExtjs=null;	
	public $privateName=null;
							
	public function __construct($attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
		
		$this->afExtjs->setAddons(array ( 'js' => array($this->afExtjs->getPluginsDir().'comet/Ext.Comet.js',$this->afExtjs->getPluginsDir().'comet/Ext.ux.Updater.js') ));
		
		$this->privateName='updater_'.Util::makeRandomKey();
		
		$this->attributes['id']=$this->privateName;
						
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
		
		$this->end();
	}
		
	public function end()
	{			
		$this->afExtjs->private[$this->privateName]=$this->afExtjs->Updater($this->attributes);
	}
}
?>