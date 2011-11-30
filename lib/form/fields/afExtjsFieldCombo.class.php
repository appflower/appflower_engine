<?php
/**
 * extJs Form Field Combo
 */
class afExtjsFieldCombo extends afExtjsField
{
	public function __construct($containerObject,$attributes=array(), $parsedData = array())
	{
	    $this->attributes['triggerAction']='all';	
        if (@$attributes['minChars']) {
            $this->attributes['minChars']=$attributes['minChars'];
        }
		$this->afExtjs=afExtjs::getInstance();
		/**
		 * if isset button, then xtype will become combowbutton
		 */
		if(isset($attributes['button']))
		{
			$this->attributes['buttonConfig']=$attributes['button'];
			$this->attributes['xtype']='combowbutton';
			unset($attributes['button']);
			
			if(isset($attributes['window']))
			{
//				if(isset($attributes['window']['component'])&&is_object($attributes['window']['component']))
//				{
//					$this->attributes['windowConfig']['items'][]=$attributes['window']['component']->privateName;
//					
//					
//					
//					unset($attributes['window']['component']);
//				}
							
				$this->attributes['windowConfig']=array_merge(isset($this->attributes['windowConfig'])?$this->attributes['windowConfig']:array(),$attributes['window']);
				
				unset($attributes['window']);
			}
			
			$this->attributes['width']='250';
			if(empty($attributes['options'])){
				$this->attributes['store'] = '[[0,"No options available.."]]';
			}
		}
		else {
			$this->afExtjs->setAddons(array('js' => array($this->afExtjs->getPluginsDir().'form/Ext.ux.form.Combo.js') ));
			$this->attributes['xtype']='combowcolors';
			
			if(isset($attributes['proxy'])&&isset($attributes['template']))
			{
				/**
				 * autocomplete combo config
				 */
				$storePrivateName='store_'.Util::makeRandomKey();
				$readerPrivateName='reader_'.Util::makeRandomKey();
				$proxyPrivateName='proxy_'.Util::makeRandomKey();
				
				$templatePrivateName='tpl_'.Util::makeRandomKey();
				$xtemplatePrivateName='xtpl_'.Util::makeRandomKey();
				
				$attributes[$readerPrivateName]['totalProperty']='totalCount';
				$attributes[$readerPrivateName]['root']='rows';
				
				foreach ($attributes['proxy']['fields'] as $field)
				{
					$attributes[$readerPrivateName]['fields'][]=$this->afExtjs->asAnonymousClass(array('name'=>$field));
				}
				$attributes[$readerPrivateName]['fields'][]=$this->afExtjs->asAnonymousClass(array('name'=>'url'));
								
				$this->afExtjs->private[$readerPrivateName]=$this->afExtjs->JsonReader($attributes[$readerPrivateName]);
				unset($attributes[$readerPrivateName]);
				
				$attributes[$proxyPrivateName]['url']=$attributes['proxy']['url'];
				
				$this->afExtjs->private[$proxyPrivateName]=$this->afExtjs->HttpProxy($attributes[$proxyPrivateName]);
				unset($attributes[$proxyPrivateName]);
				
				$attributes[$storePrivateName]['reader']=$this->afExtjs->asVar($readerPrivateName);
				$attributes[$storePrivateName]['proxy']=$this->afExtjs->asVar($proxyPrivateName);
				
				$this->afExtjs->private[$storePrivateName]=$this->afExtjs->Store($attributes[$storePrivateName]);
				unset($attributes[$storePrivateName]);
				
				$this->afExtjs->private[$templatePrivateName]='<tpl for="."><div class="search-item">'.$attributes['template'].'</div></tpl>';
				$this->afExtjs->private[$xtemplatePrivateName]=$this->afExtjs->XTemplate(array($this->afExtjs->asVar($templatePrivateName)));
				
				$this->attributes['typeAhead']=false;
				$this->attributes['loadingText']='Searching...';
				$this->attributes['width']='250';
				$this->attributes['pageSize']=$attributes['proxy']['limit'];
				$this->attributes['hideTrigger']=true;
				$this->attributes['itemSelector']='div.search-item';
				$this->attributes['tpl']=$this->afExtjs->asVar($xtemplatePrivateName);
				$this->attributes['store']=$this->afExtjs->asVar($storePrivateName);
				$this->attributes['minChars']=$attributes['proxy']['minChars'];
				if(isset($attributes['proxy']['selectedIndex']))
				{
					$this->attributes['onSelect']=$this->afExtjs->asMethod(array(
						"parameters"=>"record",
						"source"=>"if(record.data.".$attributes['proxy']['selectedIndex']."){var selectedValue=record.data.".$attributes['proxy']['selectedIndex'].";this.setRawValue(record.data.".$attributes['proxy']['selectedIndex'].");this.setValue(record.data.".$attributes['proxy']['selectedIndex'].");this.collapse();}"
					));
				}
				
				unset($attributes['proxy']);
				unset($attributes['template']);				
			}
			else {
				/**
				 * normal combo config
				 */
				$this->attributes['forceSelection']=true;
				$this->attributes['disableKeyFilter']=true;
				$this->attributes['mode']='local';
				$this->attributes['triggerAction']='all';
				$this->attributes['width']='250';
			}
		}
		
		/**
		 * the options attribute, an assoc array for now
		 */
		if(isset($attributes['options'])&&count($attributes['options'])>0)
		{
			$options=array();
			foreach ($attributes['options'] as $key=>$value)
			{
				$tarray=null;
				$tarray[]=$key;
				if(is_array($value))
				{				
					foreach ($value as $k=>$v) {
						$tarray[]=$v;
					}					
				}
				else {
					$tarray[]=$value;
					$tarray[]='#FFFFFF';
				}
				
				$options[]=$tarray;
			}
			
			/**
			 * options will be an array of arrays like ($key,$text,$color)
			 */
									
			$this->attributes['store']=$this->afExtjs->asVar(json_encode($options));
			
			unset($attributes['options']);
		}
		
		
		
		/**
		 * selected attribute is the same as the value attribute, you can use either of them
		 */
		if(isset($attributes['selected']))
		{
			$this->attributes['value']=$attributes['selected'];
			
			unset($attributes['selected']);
		}
		
		if(isset($attributes['name']))
		{
			$this->attributes['hiddenName']=(substr($attributes['name'],-1)==']')?substr_replace($attributes['name'],'_value]',-1):$attributes['name'].'_value';
		}
		
		/*
		 * Set plugins and default attributes
		 */
		
		if(isset($attributes['plugin'])){
			if($attributes['plugin'] == 'extended_combo_box'){
				$this->afExtjs->setAddons(array('js' => array($this->afExtjs->getPluginsDir().'form/Ext.ux.plugins.ExtendedComboBox.js') ));
				$this->attributes['plugins'][]="Ext.ux.plugins.ExtendedComboBox";	
				$this->attributes['resizable'] = true;
				$this->attributes['triggerAction'] = 'all';
				$this->attributes['forceSelection'] = true;
				$this->attributes['selectOnFocus'] = true;
				$this->attributes['hideLabel'] = true;
				$this->attributes['editable'] = false;				
			}
			if($attributes['plugin'] == 'autocompleter'){
					$this->afExtjs->setAddons(array('js' => array($this->afExtjs->getPluginsDir().'form/Ext.ux.plugins.RemoteComboAutoSuggest.js') ));
					//$this->attributes['plugins'][]="Ext.ux.RemoteComboAutoSuggest";	
					$this->attributes['resizable'] = true;
					$this->attributes['triggerAction'] = 'all';
					$this->attributes['forceSelection'] = true;
					$this->attributes['selectOnFocus'] = true;
					$this->attributes['mode'] = 'remote';					
					$this->attributes['xtype'] = "remotecomboautosuggest";
					$this->attributes['enableKeyEvents'] = true;
					$this->attributes['disableKeyFilter'] = true;
			}
			if($attributes['plugin'] == 'fontfield'){
					$this->attributes['tpl'] = 'new Ext.XTemplate(
				       \'<tpl for="."><div class="x-combo-list-item" style="margin-bottom:10px;">{text}<br><span style="font-family:\\\'{text}\\\';font-size:15px;">A quick brown fox</span></div></tpl>\'
				    )';
			}
			if($attributes['plugin'] == 'checkcombo'){
					$this->afExtjs->setAddons(array('js' => array($this->afExtjs->getPluginsDir().'form/lovcombo-1.0/js/Ext.ux.form.LovCombo.js') ));					
					$this->afExtjs->setAddons(array('css' => array($this->afExtjs->getPluginsDir().'form/lovcombo-1.0/css/Ext.ux.form.LovCombo.css') ));					
					//$this->attributes['plugins'][]="Ext.ux.RemoteComboAutoSuggest";									
					$this->attributes['xtype'] = "lovcombo";
					$this->attributes['triggerAction'] = 'all';
					$this->attributes['editable'] = false;					
					$this->attributes['mode'] = 'local';		
					
			}
			
			unset($attributes['plugin']);
		}
		
		$this->attributes['listWidth']=$this->attributes['width'];
		parent::__construct($containerObject,$attributes);
	}
}
?>
