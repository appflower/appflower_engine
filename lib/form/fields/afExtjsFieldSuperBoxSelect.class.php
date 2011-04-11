<?php
/**
 * extJs Form Field Combo
 */
class afExtjsFieldSuperBoxSelect extends afExtjsField
{
	public function __construct($containerObject,$attributes=array())
	{
		$this->attributes['triggerAction']='all';
		$this->afExtjs=afExtjs::getInstance();
		{
			$this->afExtjs->setAddons(array(
				'js' => array($this->afExtjs->getPluginsDir().'superboxselect/SuperBoxSelect.js'),
				'css'=>array($this->afExtjs->getPluginsDir().'superboxselect/superboxselect.css')
			));
				
			$this->attributes['xtype']='superboxselect';						
			$this->attributes['resizable']=true;
			$this->attributes['anchor']='95%';
			$this->attributes['allowAddNewData']=true;
			$this->attributes['mode']='remote';
			$this->attributes['displayField']= 'name';
			$this->attributes['minChars']= 1;
			$this->attributes['valueField']='id';			
			$template = '<tpl for="."><div class="x-combo-list-item">{name} <span style="color:#888;margin-left:5px;font-size:11px">{description}</span></div></tpl>';
			$this->attributes['tpl'] = 	$this->afExtjs->asVar("new Ext.XTemplate('".$template."')");


			if(isset($attributes['url']))
			{
				/**
				 * autocomplete combo config
				 */
				$storePrivateName='store_'.Util::makeRandomKey();
				$readerPrivateName='reader_'.Util::makeRandomKey();
				$proxyPrivateName='proxy_'.Util::makeRandomKey();

				$attributes[$readerPrivateName]['totalProperty']='totalCount';
				$attributes[$readerPrivateName]['root']='rows';
				$attributes[$readerPrivateName]['fields'][]=$this->afExtjs->asAnonymousClass(array('name'=>"id"));
				$attributes[$readerPrivateName]['fields'][]=$this->afExtjs->asAnonymousClass(array('name'=>"name"));
				$attributes[$readerPrivateName]['fields'][]=$this->afExtjs->asAnonymousClass(array('name'=>"description"));
				$attributes[$readerPrivateName]['fields'][]=$this->afExtjs->asAnonymousClass(array('name'=>'url'));


				$this->afExtjs->private[$readerPrivateName]=$this->afExtjs->JsonReader($attributes[$readerPrivateName]);
				unset($attributes[$readerPrivateName]);

				$attributes[$proxyPrivateName]['url']=$attributes['url'];

				$this->afExtjs->private[$proxyPrivateName]=$this->afExtjs->HttpProxy($attributes[$proxyPrivateName]);
				unset($attributes[$proxyPrivateName]);

				$attributes[$storePrivateName]['reader']=$this->afExtjs->asVar($readerPrivateName);
				$attributes[$storePrivateName]['proxy']=$this->afExtjs->asVar($proxyPrivateName);

				$this->afExtjs->private[$storePrivateName]=$this->afExtjs->Store($attributes[$storePrivateName]);
				unset($attributes[$storePrivateName]);

				$this->attributes['loadingText']='Searching...';
				$this->attributes['store']=$this->afExtjs->asVar($storePrivateName);

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



		parent::__construct($containerObject,$attributes);
	}
	private function addCommentInterface(){
		return 'var newObj = null,tip=null;
			var textfield = new Ext.form.TextField({
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							v = v.slice(0,1).toUpperCase() + v.slice(1).toLowerCase();
							var newObj = {
								id: v,
								name: v,
								description:field.getValue()
							};
							bs.addItem(newObj);
							if(tip) tip.hide();
							bs.getEl().focus();
						}
					}
				}
			})
			var panel = new Ext.Panel({
				title:"Comment: "+v,
				frame:true,
				width:200,
				layout:"fit",							
				items:[textfield]
			});
			tip = new Ext.ToolTip({
				autoShow:true,
				anchor:"top",							
				autoHide:false
			});	
			tip.add(panel);
			tip.showAt([bs.getEl().getX(),bs.getEl().getY()+bs.wrapEl.getHeight()+10]);
			textfield.focus();';
	}
	public function end(){
		$this->attributes['listeners']['newitem']['parameters']='bs,v';
		$this->attributes['listeners']['newitem']['source']='			
			v = v.slice(0,1).toUpperCase() + v.slice(1).toLowerCase();
			var newObj = {
				id: v,
				name: v
			};
			bs.addItem(newObj);			
			'
        ;
        
        $this->attributes['listeners']['render'] = array("parameters"=>"ct","source"=>'
        	return;
			new Ext.ToolTip({
			    target : ct.wrapEl,
			    delegate : "li.x-superboxselect-item",
			    //trackMouse : true,
			    anchor:"top",
			    animCollapse:true,
			    hideDelay:1000,
			    renderTo : document.body,
			    listeners : {
			        "beforeshow" : {
			           fn : function(tip) {
			                var rec = this.findSelectedRecord(tip.triggerElement);
			                if(rec && rec.get("description"))
			                tip.body.dom.innerHTML = rec.get("description");
			                else
			                //tip.body.dom.innerHTML = "<div style=\"font-style:italic\">Not Available</div>";
			                tip.setVisible(false);
			           },
			           scope : this
			       }
			    }
			});
		');
        parent::end();
	}
}
?>
