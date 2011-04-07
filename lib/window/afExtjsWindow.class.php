<?php
/**
 * extJs window
 *
 */
class afExtjsWindow
{
	/**
	 * default attributes for the window
	 */
	public $attributes=array('layout'  => 'fit',
							'width'    => 'auto',
							'height'   => '500',
							'closeAction'=>'hide',
							'plain'=>true,
							'modal'=>true);
	
	public $afExtjs=null;	
	public $privateName=null;
							
	public function __construct($attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
		
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
			$this->attributes['items'][]=$this->afExtjs->asVar($item->privateName);
		}
		else {
			$this->attributes['items'][]=$this->afExtjs->asAnonymousClass($item);		
		}
	}
	
	public function end()
	{			
		$this->afExtjs->private[$this->privateName]=$this->afExtjs->Window($this->attributes);
	}
}
?>