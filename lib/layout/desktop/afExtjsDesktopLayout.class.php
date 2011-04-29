<?php
/**
 * extJs desktop layout
 */
class afExtjsDesktopLayout extends afExtjsLayout
{	
	public function start($attributes=array())
	{
		$this->afExtjs->setAddons(array ('css' => array($this->afExtjs->getPluginsDir().'desktop/css/desktop.css'), 'js' => array($this->afExtjs->getPluginsDir().'desktop/js/StartMenu.js',$this->afExtjs->getPluginsDir().'desktop/js/TaskBar.js',$this->afExtjs->getPluginsDir().'desktop/js/Desktop.js')));
		
		sfProjectConfiguration::getActive()->loadHelpers(array('afExtjsDesktopStartMenu'));
		
		$this->addInitMethodSource("this.startConfig = startMenuConfig;this.desktop = new Ext.Desktop(this);");
		
		$this->sharpPrefix = 'interface/desktop';
	}
	
	public function end()
	{		
		$this->addInitMethodSource("
		setTimeout(function(){			
			Ext.get('loading').remove();
	        Ext.get('loading-mask').fadeOut({remove:true});
	        
	        afApp.loadFirst(true);
	    }, 250);
		");		
		
		parent::end();
	}
}
?>
