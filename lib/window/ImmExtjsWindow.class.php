<?php
/**
 * extJs window
 *
 */
class ImmExtjsWindow
{
	/**
	 * default attributes for the window
	 */
	public $attributes=array('layout'  => 'fit',
							'width'    => 'auto',
							'height'   => '500',
							'closeAction'=>'hide',
							'shadow'=>false,
							'plain'=>true,
							'modal'=>true);
	
	public $immExtjs=null;	
	public $privateName=null;
							
	public function __construct($attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		
		$this->privateName='window_'.Util::makeRandomKey();
		
		$this->attributes['id']=$this->privateName;
						
		if(isset($attributes['tools']))
		{
			$this->attributes['tools']=$attributes['tools']->end();
			
			unset($attributes['tools']);
		}
				
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
		
	public function addItem($item)
	{
		if(is_object($item))
		{
			$this->attributes['items'][]=$this->immExtjs->asVar($item->privateName);
		}
		else {
			$this->attributes['items'][]=$this->immExtjs->asAnonymousClass($item);		
		}
	}
	
	public function end()
	{			
		$this->immExtjs->private[$this->privateName]=$this->immExtjs->Window($this->attributes);
	}
}
?>