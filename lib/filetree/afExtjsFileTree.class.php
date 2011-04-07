<?php
/**
 * extJs file tree panel
 *
 */
class afExtjsFileTree 
{
	/**
	 * default attributes
	 */
	public $attributes=array('title'=>'FileTreePanel');
	
	public $afExtjs=null;	
	public $privateName=null;
							
	public function __construct($attributes=array())
	{		
		$this->afExtjs=afExtjs::getInstance();

		if(count($this->attributes)>0)
		$attributes=array_merge($this->attributes,$attributes);
		
		$this->attributes['url']='/afExtjs/filetree';
		
		$this->afExtjs->setAddons(array ('css' => array($this->afExtjs->getExamplesDir().'filetree/css/filetype.css',$this->afExtjs->getExamplesDir().'filetree/css/filetree.css',$this->afExtjs->getExamplesDir().'filetree/css/icons.css'), 'js' => array($this->afExtjs->getExamplesDir().'filetree/js/Ext.ux.FileTreePanel.js',$this->afExtjs->getExamplesDir().'filetree/js/Ext.ux.FileTreeMenu.js',$this->afExtjs->getExamplesDir().'filetree/js/Ext.ux.form.BrowseButton.js',$this->afExtjs->getExamplesDir().'filetree/js/Ext.ux.FileUploader.js',$this->afExtjs->getExamplesDir().'filetree/js/Ext.ux.UploadPanel.js')));
												
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
		
		$this->end();
	}
			
	public function end()
	{
		$this->privateName='filetree_'.Util::makeRandomKey();
					
		$this->afExtjs->private[$this->privateName]=$this->afExtjs->FileTreePanel($this->attributes);
	}
}
?>