<?php
/**
 * extJs Group Tab Panel Group
 *
 */
class afExtjsGroupTabPanelGroup
{
	/**
	 * default attributes for the group
	 */
	public $attributes=array('mainItem'=>0, 'style'=>'padding:10px;');
	
	public $afExtjs=null;	
							
	public function __construct($attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
		
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
		
	public function addItem($container, $attributes=array())
	{
		if(!isset($this->attributes['items']))
		$this->attributes['items']=array();
		
		if (is_object($container))
		{
			array_push($this->attributes['items'],$this->afExtjs->asAnonymousClass(array('title'=> $attributes['title'], 'layout'=> 'fit','iconCls'=> $attributes['iconCls'],'tabTip'=> $attributes['tabTip'],'items'=>array($this->afExtjs->asVar($container->privateName)))));
		}
	}
	
	public function end()
	{
		return $this->afExtjs->asAnonymousClass($this->attributes);
	}
}
?>