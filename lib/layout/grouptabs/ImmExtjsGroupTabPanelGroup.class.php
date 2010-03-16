<?php
/**
 * extJs Group Tab Panel Group
 *
 */
class ImmExtjsGroupTabPanelGroup
{
	/**
	 * default attributes for the group
	 */
	public $attributes=array('mainItem'=>0, 'style'=>'padding:10px;');
	
	public $immExtjs=null;	
							
	public function __construct($attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
		
	public function addItem($container, $attributes=array())
	{
		if(!isset($this->attributes['items']))
		$this->attributes['items']=array();
		
		if (is_object($container))
		{
			array_push($this->attributes['items'],$this->immExtjs->asAnonymousClass(array('title'=> $attributes['title'], 'layout'=> 'fit','iconCls'=> $attributes['iconCls'],'tabTip'=> $attributes['tabTip'],'items'=>array($this->immExtjs->asVar($container->privateName)))));
		}
	}
	
	public function end()
	{
		return $this->immExtjs->asAnonymousClass($this->attributes);
	}
}
?>