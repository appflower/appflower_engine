<?php
/**
 * extJs tree panel
 *
 */
class ImmExtjsTree 
{
	/**
	 * default attributes
	 */
	public $attributes=array('title'=>'TreePanel',
							'rootVisible'=>false,
							'lines'=>false,
							'autoScroll'=>true);
	
	public $immExtjs=null;	
	public $privateName=null;
							
	public function __construct($attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
	
		$this->attributes['loader']=$this->immExtjs->asVar('new Ext.tree.TreeLoader()');
														
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
	
	public function startRoot($attributes=array())
	{
		return new ImmExtjsTreeNode($this,$attributes);
	}
	
	public function endRoot($root)
	{
		$this->attributes['root']=$this->immExtjs->asVar('new Ext.tree.AsyncTreeNode('.$root->end().')');
	}
			
	public function end()
	{
		$this->privateName='tree_'.Util::makeRandomKey();
					
		$this->immExtjs->private[$this->privateName]=$this->immExtjs->TreePanel($this->attributes);
	}
}
?>