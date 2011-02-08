<?php
/**
 * extJs homepage layout
 *
 */
class ImmExtjsHomepageLayout 
{
	/**
	 * default attributes for the layout
	 */
	public $attributes=array();
	public $proxy=array(),$template='';	
	public $immExtjs=null;
						
	public function __construct($attributes=array())
	{
		$this->immExtjs=ImmExtjs::getInstance();
		
		$this->setExtjsVersion(3);
		
		$this->immExtjs->setOptions(array('theme'=>'green'));
		
		$this->immExtjs->setAddons(array('js'=>array('/appFlowerPlugin/js/custom/widgetJS.js')));
		
		$this->immExtjs->setAddons(array ('css' => array('/css/my-extjs.css',$this->immExtjs->getExamplesDir().'layout-browser/Ext.ux.layout.CenterLayout.css'),'js'=>array($this->immExtjs->getExamplesDir().'layout-browser/Ext.ux.layout.CenterLayout.js',$this->immExtjs->getExamplesDir().'form/TriggerField.js')));
	}
	
	public function setExtjsVersion($version)
	{
		$this->immExtjs->setExtjsVersion($version);
	}
	
	public function getExtjsVersion()
	{
		return $this->immExtjs->getExtjsVersion();
	}
	
	public function setProxy($attributes=array())
	{
		$this->proxy=$attributes;
	}
	
	public function setTemplate($t)
	{
		$this->template=$t;
	}
			
	public function addCombo($attributes=array())
	{
		$storePrivateName='store_'.Util::makeRandomKey();
		$readerPrivateName='reader_'.Util::makeRandomKey();
		$proxyPrivateName='proxy_'.Util::makeRandomKey();
		
		$templatePrivateName='tpl_'.Util::makeRandomKey();
		$xtemplatePrivateName='xtpl_'.Util::makeRandomKey();
		
		$attributes[$readerPrivateName]['totalProperty']='totalCount';
		$attributes[$readerPrivateName]['root']='rows';
		
		foreach ($this->proxy['fields'] as $field)
		{
			$attributes[$readerPrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>$field));
		}
		$attributes[$readerPrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'url'));
						
		$this->immExtjs->private[$readerPrivateName]=$this->immExtjs->JsonReader($attributes[$readerPrivateName]);
		unset($attributes[$readerPrivateName]);
		
		$attributes[$proxyPrivateName]['url']=$this->proxy['url'];
		
		$this->immExtjs->private[$proxyPrivateName]=$this->immExtjs->HttpProxy($attributes[$proxyPrivateName]);
		unset($attributes[$proxyPrivateName]);
		
		$attributes[$storePrivateName]['reader']=$this->immExtjs->asVar($readerPrivateName);
		$attributes[$storePrivateName]['proxy']=$this->immExtjs->asVar($proxyPrivateName);
		
		$this->immExtjs->private[$storePrivateName]=$this->immExtjs->Store($attributes[$storePrivateName]);
		unset($attributes[$storePrivateName]);
		
		$this->immExtjs->private[$templatePrivateName]='<tpl for="."><div class="search-item">'.$this->template.'</div></tpl>';
		$this->immExtjs->private[$xtemplatePrivateName]=$this->immExtjs->XTemplate(array($templatePrivateName));
		
		$attributes['typeAhead']=false;
		$attributes['loadingText']='Searching...';
		$attributes['width']='520';
		$attributes['pageSize']=$this->proxy['limit'];
		$attributes['hideTrigger']=true;
		$attributes['itemSelector']='div.search-item';
		$attributes['tpl']=$this->immExtjs->asVar($xtemplatePrivateName);
		$attributes['store']=$this->immExtjs->asVar($storePrivateName);
		$attributes['id']='center_combo';
		$attributes['minChars']=$this->proxy['minChars'];
		$attributes['onSelect']=$this->immExtjs->asMethod(array(
			"parameters"=>"record",
			"source"=>"window.location.href=record.data.url;"
		));
		$attributes['applyTo']='search_input';
		
		$this->immExtjs->private['combo']=$this->immExtjs->ComboBox($attributes);
		
//$this->addInitMethodSource('Ext.get("center_combo").boxWrap().addClass("x-box-blue");Ext.get("center_combo").dom.parentNode.parentNode.parentNode.parentNode.parentNode.style.width="500px";Ext.get("center_combo").show();');	
	}
	
	public function addInitMethodSource($source)
	{
		@$this->immExtjs->public['init'] .= $source;
	}
	
	public function end()
	{		
		$this->addCombo();
		
		@$this->immExtjs->public['init'] .= "
	    Ext.QuickTips.init();
	    Ext.apply(Ext.QuickTips.getQuickTip(), {
		    trackMouse: true
		});
		Ext.form.Field.prototype.msgTarget = 'side';
		Ext.History.init();
		";
		
		@$this->immExtjs->public['init'] .="
		setTimeout(function(){
			Ext.get('loading').remove();
	        Ext.get('loading-mask').fadeOut({remove:true});
	    }, 250);
	    afApp.urlPrefix = '".sfContext::getInstance()->getRequest()->getRelativeUrlRoot()."';
	    ";
		
		$this->immExtjs->init();
	}
}
?>
