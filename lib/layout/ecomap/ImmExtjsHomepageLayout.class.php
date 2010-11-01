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
		
		$this->immExtjs->setOptions(array('theme'=>'blue'));
		
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
		$attributes['width']='470';
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
		
		$this->immExtjs->private['combo']=$this->immExtjs->ComboBox($attributes);
		
		$this->addInitMethodSource('Ext.get("center_combo").boxWrap().addClass("x-box-blue");Ext.get("center_combo").dom.parentNode.parentNode.parentNode.parentNode.parentNode.style.width="500px";Ext.get("center_combo").show();');	
	}
	
	public function addPanel()
	{
		$this->addCombo();
						
		$attributes['region']='center';	
		$attributes['id']='center_panel';	
		$attributes['style']='margin-top:2px;'; $attributes['border']=false;
		$attributes['width']='505';
		$attributes['items'][]=$this->immExtjs->asAnonymousClass(array('html'=>'<table width="600" align="center" cellspacing="0" cellpadding="0"border="0" style="background-color:#fff;color:#666;font-family:Verdana, Arial, sans-serif;font-size:9pt;line-height:1.5em;" ><tr valign="top"><td valign="top"> <div style="font-family:Verdana, Arial, sans-serif;font-size:9pt;line-height:1.5em;" ><div align="center"><img src="/images/ecomap.logo.png"></div></div></td></tr></table>','border'=>false));
		$attributes['items'][]=$this->immExtjs->asVar('combo');
		$attributes['items'][]=$this->immExtjs->asAnonymousClass(array('html'=>'<table width="600" align="center" cellspacing="0" cellpadding="0"border="0" style="background-color:#fff;color:#666;font-family:Verdana, Arial, sans-serif;font-size:9pt;line-height:1.5em;" ><tr valign="top"><td valign="top"> <div style="font-family:Verdana, Arial, sans-serif;font-size:9pt;line-height:1.5em;" ><small><i>alpha version, try to type copenhagen, <a href="'.UrlUtil::widgetUrl('/locations/editCity','l').'">create citymap</a> and <a href="'.UrlUtil::widgetUrl('/companies/listCompany','l').'">add place</a></i></small><br/>In a chaotic, manufactured world where we get further away from the source of life and polluting the planet we need to make a change together. To make a happy, clean and peaceful world we need not only to care for each other but also for the planet. What we create, eat and return back to the planet is what makes a difference. Please stop, take a deep breath and take a minute to feel how the earth is bleeding and how it needs our help. Think about your own reliance on daily consumption and then think about how the global need for consumption affects issues like Global Warming. With every single purchase, you can make a difference - what kind of future do you want for your kids...?  <br><br>Have you ever wanted to find a 100% sustainable product, but couldnt? Have you ever had trouble finding nearby organic stores? ECOMAP is here for you the person who wants to make this difference, in finding and buying products which are in balance with life from cradle to grave. A portal that will link the farmers, suppliers an d consumers who share the same goals about organic food and green products.  <br><br>Our goal is to emphasize the goodhearted production of naturally grown food and ecofriendly products under respectful circumstances for nature and human rights.  <br><br>- the green search engine <br><br><a href="http://www.facebook.com/group.php?gid=56339330761">Join our Facebook Group</a> | <a href="http://www.betterplace.org/organisations/ecomap">Donate on Betterplace.org</a> | <a href="/movie">Videos</a> | <a href="/about">About</a> <br/><a href="http://maastricht.ecomap.org">Looking for ecomap.org/maastricht?</a> | <a href="http://www.opengreenmap.org">OpenGreenmap</a> </div> </td></tr></table>','border'=>false));
		
		
		$this->immExtjs->private['center_panel']=$this->immExtjs->Panel($attributes);		
	}
	
	public function addInitMethodSource($source)
	{
		@$this->immExtjs->public['init'] .= $source;
	}
	
	public function end()
	{		
		$this->addPanel();
		
		$viewportItems=array();
		
		if(isset($this->immExtjs->private['center_panel']))
		$viewportItems[]=$this->immExtjs->asVar('center_panel');
			
		
		$this->immExtjs->private['viewport']=$this->immExtjs->Viewport(
		  	array
				(
				  'layout' => 'ux.center',  'items'  => $viewportItems
				)
		);
		
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
		
		$this->immExtjs->public['getViewport'] = "return false;";
		$this->immExtjs->public['getViewport'] = $this->immExtjs->asMethod($this->immExtjs->public['getViewport']);
		
		$this->immExtjs->public['getToolbar'] = "return false;";
		$this->immExtjs->public['getToolbar'] = $this->immExtjs->asMethod($this->immExtjs->public['getToolbar']);
		
		$this->immExtjs->init();
	}
}
?>
