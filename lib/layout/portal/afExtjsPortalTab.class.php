<?php
/**
 * extJs Portal Tab
 *
 */
class afExtjsPortalTab
{
	/**
	 * default attributes for the column
	 */
	public $attributes=array('enableTabScroll'=>true);
	public $portalPrivateName=null;
	public $afExtjs=null;	
							
	public function __construct($attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();

		$this->portalPrivateName='portal_'.Util::makeRandomKey();
						
		if(isset($attributes['portalLayoutType']))
		{
			$this->attributes['portal']['portalLayoutType']=$attributes['portalLayoutType'];
			unset($attributes['portalLayoutType']);
		}
		
		if(isset($attributes['portalWidgets']))
		{
			$this->attributes['portal']['portalWidgets']=$attributes['portalWidgets'];
			unset($attributes['portalWidgets']);
		}
		
		$this->attributes['portal']['bodyBorder']=false;
		$this->attributes['portal']['autoWidth']=true;
		@$this->attributes['portal']['style'].='padding-right:5px;';
		@$this->attributes['portal']['bodyStyle'].='overflow-x:hidden;overflow-y:hidden;padding-right:5px;';
		
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
		
	public function startColumn($attributes=array())
	{
		return new afExtjsPortalColumn($attributes);		
	}
	
	public function endColumn($columnObj)
	{
		
		$this->attributes['portal']['items'][]=$columnObj->end();
	}
	
	public function end()
	{			
		$this->afExtjs->private[$this->portalPrivateName]=$this->afExtjs->Portal($this->attributes['portal']);
		
		$this->attributes['items'][]=$this->afExtjs->asVar($this->portalPrivateName);
		
		unset($this->attributes['portal']);
		
		return $this->afExtjs->asAnonymousClass($this->attributes);
	}
}
?>