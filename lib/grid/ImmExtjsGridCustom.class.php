<?php
/**
 * extJs grid custom
 *
 */
class ImmExtjsGridCustom 
{
	/**
	 * default attributes for the grid
	 */
	public $attributes=array('loadMask'=>true,'frame'=>true);
	
	public $immExtjs=null;	
	public $privateName=null;
	public $proxy=null;
							
	public function __construct($attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		
		$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'view/data-view-plugins.js')));
				
		if(isset($attributes['portal'])&&$attributes['portal']==true)
		{
			$this->attributes=array_merge($this->attributes,array('anchor'=> '100%',
															'frame'=>true,
															'collapsible'=>true,
															'draggable'=>true,
															'cls'=>'x-portlet'));
															
			unset($attributes['portal']);
		}
				
		if(isset($attributes['tools']))
		{
			$this->attributes['tools']=$attributes['tools']->end();
			
			unset($attributes['tools']);
		}
		
		$this->attributes['cls']='custom-dataview';
												
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
		
	public function addItem($item)
	{
		$this->attributes['items'][]=$this->immExtjs->asVar($item);
	}
	
	public function addButton($button)
	{
		if(!isset($this->attributes['tbar']))
		$this->attributes['tbar']=array();
		
		array_push($this->attributes['tbar'],$this->immExtjs->asVar($button->end()));
	}
	
	public function setProxy($attributes=array())
	{
		$this->proxy=$attributes;
	}
		
	public function addHelp($html)
	{
		if(!isset($this->attributes['tbar']))
		{
			$this->attributes['tbar']=array();
		}
		
		$panel=new ImmExtjsPanel(array('html'=>'<div style="white-space:normal;">'.$html.'</div>'));
		@$this->attributes['listeners']['render']['source'].="var tb;if(this.getTopToolbar()&&this.getTopToolbar().items.items.length==0){tb = this.getTopToolbar();tb.addItem(".$panel->privateName.");}else{ tb = new Ext.Toolbar({renderTo: this.tbar,items: [".$panel->privateName."]});}if(tb&&tb.container){tb.container.addClass('tbarBottomBorderFix');}if(".$panel->privateName.".body){".$panel->privateName.".body.dom.style.border='0px';}if(".$panel->privateName.".bwrap){".$panel->privateName.".bwrap.dom.firstChild.style.background='transparent';}";
	}
	
	public function end()
	{
		$this->privateName='customgrid_'.Util::makeRandomKey();
		
		$storePrivateName='store_'.Util::makeRandomKey();
		$templatePrivateName='tpl_'.Util::makeRandomKey();
		$xtemplatePrivateName='xtpl_'.Util::makeRandomKey();
		$dataviewPrivateName='dv_'.Util::makeRandomKey();
		
		$this->attributes[$storePrivateName]['url']=$this->proxy['url'];
		
		$this->attributes[$storePrivateName]['root']='rows';
		
		$this->attributes[$storePrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'message'));
		$this->attributes[$storePrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'redirect'));
		$this->attributes[$storePrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'html'));
		
		$this->attributes[$storePrivateName]['listeners']['load']=$this->immExtjs->asMethod(array(
																		"parameters"=>"object,records,options",
																		"source"=>
																		'if(records.length>0&&records[0].json.redirect&&records[0].json.message){var rec=records[0].json;Ext.Msg.alert("Failure", rec.message, function(){afApp.loadCenterWidget(rec.redirect);});}'
																));
				
		$this->immExtjs->private[$storePrivateName]=$this->immExtjs->JsonStore($this->attributes[$storePrivateName]);
		unset($this->attributes[$storePrivateName]);
		
		$this->immExtjs->private[$templatePrivateName]='<tpl for="."><div class="item-wrap"><div class="item">{html}</div></div></tpl><div class="x-clear"></div>';
		
		$this->immExtjs->private[$xtemplatePrivateName]=$this->immExtjs->XTemplate(array($templatePrivateName));
		
		$this->attributes[$dataviewPrivateName]['store']=$this->immExtjs->asVar($storePrivateName);
		$this->attributes[$dataviewPrivateName]['tpl']=$this->immExtjs->asVar($xtemplatePrivateName);
		
		$this->attributes[$dataviewPrivateName]['autoHeight']=true;
		$this->attributes[$dataviewPrivateName]['multiSelect']=true;
		$this->attributes[$dataviewPrivateName]['overClass']='x-view-over';
		$this->attributes[$dataviewPrivateName]['itemSelector']='div.item-wrap';
		$this->attributes[$dataviewPrivateName]['emptyText']='No data to display !';
		
		$this->attributes[$dataviewPrivateName]['plugins'][]="new Ext.DataView.DragSelector()";
		
		$this->immExtjs->private[$dataviewPrivateName]=$this->immExtjs->DataView($this->attributes[$dataviewPrivateName]);
		unset($this->attributes[$dataviewPrivateName]);
		
		$this->addItem($dataviewPrivateName);			
					
		@$this->attributes['listeners']['render']["source"].=$storePrivateName.".load();";
				
		$attributes['listeners']['render']["source"]=$this->attributes['listeners']['render']["source"];
		unset($this->attributes['listeners']['render']["source"]);
		
		$this->attributes['listeners']['render']=$this->immExtjs->asMethod(array(
				"parameters"=>"",
				"source"=>$attributes['listeners']['render']["source"]
		));		
		
		unset($attributes['listeners']['render']["source"]);
			
		$this->immExtjs->private[$this->privateName]=$this->immExtjs->Panel($this->attributes);
	}
}
?>
