<?php
/**
 * extJs tree panel
 *
 */
class afExtjsTree 
{
	/**
	 * default attributes
	 */
	public $attributes=array('title'=>'TreePanel',
							'rootVisible'=>false,
							'lines'=>false,
							'autoScroll'=>true);
	
	public $afExtjs=null;	
	public $privateName=null;
							
	public function __construct($attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();
	
		$this->attributes['loader']=$this->afExtjs->asVar('new Ext.tree.TreeLoader()');
														
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
	
	public function startRoot($attributes=array())
	{
		return new afExtjsTreeNode($this,$attributes);
	}
	
	public function endRoot($root)
	{
		$this->attributes['root']=$this->afExtjs->asVar('new Ext.tree.AsyncTreeNode('.$root->end().')');
	}
			
	public function end()
	{
		$this->privateName='tree_'.Util::makeRandomKey();
					
		$this->afExtjs->private[$this->privateName]=$this->afExtjs->TreePanel($this->attributes);
	}
}
?>