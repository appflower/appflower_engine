<?php
/**
 * extJs file tree panel
 *
 */
class ImmExtjsFileTree 
{
	/**
	 * default attributes
	 */
	public $attributes=array('title'=>'FileTreePanel');
	
	public $immExtjs=null;	
	public $privateName=null;
							
	public function __construct($attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();

		if(count($this->attributes)>0)
		$attributes=array_merge($this->attributes,$attributes);
		
		$this->attributes['url']='/immExtjs/filetree';
		
		$this->immExtjs->setAddons(array ('css' => array($this->immExtjs->getExamplesDir().'filetree/css/filetype.css',$this->immExtjs->getExamplesDir().'filetree/css/filetree.css',$this->immExtjs->getExamplesDir().'filetree/css/icons.css'), 'js' => array($this->immExtjs->getExamplesDir().'filetree/js/Ext.ux.FileTreePanel.js',$this->immExtjs->getExamplesDir().'filetree/js/Ext.ux.FileTreeMenu.js',$this->immExtjs->getExamplesDir().'filetree/js/Ext.ux.form.BrowseButton.js',$this->immExtjs->getExamplesDir().'filetree/js/Ext.ux.FileUploader.js',$this->immExtjs->getExamplesDir().'filetree/js/Ext.ux.UploadPanel.js')));
												
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
		
		$this->end();
	}
			
	public function end()
	{
		$this->privateName='filetree_'.Util::makeRandomKey();
					
		$this->immExtjs->private[$this->privateName]=$this->immExtjs->FileTreePanel($this->attributes);
	}
}
?>