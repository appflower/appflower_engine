<?php
/**
 * extJs grid
 *
 */
class ImmExtjsGrid 
{
	/**
	 * default attributes for the grid
	 */
	public $attributes=array('loadMask'=>true,'frame'=>true,'bodyStyle'=>'border: 1px solid #8db2e3;','idxml'=>false);
	public $immExtjs=null;	
	public $privateName=null;
	public $contextMenu = array();
	public $actionsObject=null,$columns=null,$filters=array(),$proxy=null;
	public $gridType = null, $menuactions_items=array();
	public $filter_types = array("boolean","numeric","list","string","combo","date");
	public $moveEditRowAction = false;		
	public function __construct($attributes=array())
	{		
		$this->immExtjs=ImmExtjs::getInstance();
		//for test
		$this->attributes['tbar']=array();			
		sfLoader::loadHelpers(array('ImmExtjsContextMenu'));
		if(isset($attributes['datasource']))
		{
			$this->gridType = $attributes['datasource']['type'];
			unset($attributes['datasource']);
		}
		$this->privateName='grid_'.Util::makeRandomKey();
		
		if(isset($attributes['idxml'])&&$attributes['idxml'])
		{
			//$this->attributes['id']=$attributes['idxml'];
			$this->proxy['stateId']=$attributes['idxml'];
		}

		if(isset($attributes['plugins'])){
			$this->attributes['plugins'] = $attributes['plugins'];
			unset($attributes['plugins']);
		}
			
		$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'grid/Ext.ux.GridColorView.js',$this->immExtjs->getExamplesDir().'grid/Ext.ux.GroupingColorView.js')));
		$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'grid/Ext.ux.Grid.GroupingStoreOverride.js')));
		
				
		if(isset($attributes['action'])&&$attributes['action'] !='n/a'){
			$attributes['url'] = $attributes['action'];			
		}
		//echo "<pre>";print_r($attributes);
		/*
		 * Check for expand button
		 */
		if(isset($attributes['expandButton']) && $attributes['expandButton']){
			$this->immExtjs->setAddons(array('js' => array($this->immExtjs->getExamplesDir().'grid/Ext.ux.plugins.AddExpandListButton.js') ));
			$this->attributes['plugins'][]="new Ext.ux.plugins.AddExpandListButton";
			if(!isset($this->attributes['tbar']))$this->attributes['tbar']=array();
			unset($attributes['expandButton']);
		}
		
		/**
		 * Plugins for the grid
		 */
		
		if(isset($attributes['plugin']) && $attributes['plugin']){
			if($attributes['plugin'] == "index_search"){
				$this->immExtjs->setAddons(array('js' => array($this->immExtjs->getExamplesDir().'grid/Ext.ux.plugins.IndexSearch.js') ));
				$this->attributes['plugins'][]="new Ext.ux.plugins.IndexSearch";
			}
			if($attributes['plugin'] == "row_order"){
				$this->immExtjs->setAddons(array('js' => array($this->immExtjs->getExamplesDir().'plugins/grid-row-order/Ext.ux.plugins.GridRowOrder.js') ));
				$this->immExtjs->setAddons(array('css' => array($this->immExtjs->getExamplesDir().'plugins/grid-row-order/row-up-down.css') ));
				$this->attributes['plugins'][]='new Ext.ux.plugins.GridRowOrder()';
			}
			if(preg_match('/custom:/',$attributes['plugin'])){
				if(isset($attributes['plugin']) && $attributes['plugin']){
					/*if($attributes['plugin'] == "index_search"){
						$this->immExtjs->setAddons(array('js' => array($this->immExtjs->getExamplesDir().'grid/Ext.ux.plugins.IndexSearch.js') ));
						$this->attributes['plugins'][]="new Ext.ux.plugins.IndexSearch";
					}*/
					if($attributes['plugin'] == "row_order"){
						$this->immExtjs->setAddons(array('js' => array($this->immExtjs->getExamplesDir().'plugins/grid-row-order/Ext.ux.plugins.GridRowOrder.js') ));
						$this->immExtjs->setAddons(array('css' => array($this->immExtjs->getExamplesDir().'plugins/grid-row-order/row-up-down.css') ));
						$this->attributes['plugins'][]='new Ext.ux.plugins.GridRowOrder()';
					}
					if(preg_match('/^custom:(.*)$/', $attributes['plugin'], $match)){
						$plugin = $match[1];
						if(file_exists(sfConfig::get('sf_root_dir')."/web/js/custom/".$plugin.".js")){
							$this->immExtjs->setAddons(array('js' => array("/js/custom/".$plugin.".js") ));			
						}else if(file_exists(sfConfig::get('sf_root_dir')."/plugins/appFlowerPlugin/web/js/custom/".$plugin.".js")){
							$this->immExtjs->setAddons(array('js' => array("/appFlowerPlugin/js/custom/".$plugin.".js") ));			
						}
						
						if(file_exists(sfConfig::get('sf_root_dir')."/web/css/".$plugin.".css")){
							$this->immExtjs->setAddons(array('css' => array("/css/".$plugin.".css") ));			
						}else if(file_exists(sfConfig::get('sf_root_dir')."/plugins/appFlowerPlugin/web/css/".$plugin.".css")){
							$this->immExtjs->setAddons(array('css' => array("/appFlowerPlugin/css/".$plugin.".css") ));			
						}			
						$this->attributes['plugins'][]='new '.$plugin.'()';
					}
				}
				/*$plugin = str_replace("custom:","",$attributes['plugin']);				
				$this->immExtjs->setAddons(array('js' => array("/appFlowerPlugin/js/custom/".$plugin.".js") ));			
				$this->immExtjs->setAddons(array('css' => array("/appFlowerPlugin/css/".$plugin.".css") ));								
				$this->attributes['plugins'][]='new '.$plugin.'()';*/
			}
		}
		/*
		 * Grid Remote Search Test
		 */	
		/*$this->immExtjs->setAddons(array('js' => array($this->immExtjs->getExamplesDir().'search/js/Ext.ux.grid.Search.js') ));
		$this->immExtjs->setAddons(array('js' => array($this->immExtjs->getExamplesDir().'search/js/Ext.ux.IconMenu.js') ));
		$this->attributes['plugins'][]="new Ext.ux.grid.Search({
				iconCls:'icon-zoom'
				,readonlyIndexes:['note']
				,disableIndexes:['pctChange']
				,minChars:3
				,autoFocus:true
				,position:'bottom'
				,menuStyle:'radio'
			})";
		if(!isset($this->attributes['tbar']))$this->attributes['tbar']=array();*/
		//unset($attributes['expandButton']);
		
		/******** TEST ENDS HERE ****************************************************/
		
		$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'grid/RowExpander.js')));
		
		$attributes['tree']=(!isset($attributes['tree'])?false:$attributes['tree']);
		$attributes['select']=(!isset($attributes['select'])?false:$attributes['select']);
		
		$attributes['pager']=(!isset($attributes['pager'])?true:$attributes['pager']);
		
		$attributes['forceFit']=(!isset($attributes['forceFit'])?true:$attributes['forceFit']);
		$attributes['remoteSort']=(!isset($attributes['remoteSort'])?false:$attributes['remoteSort']);
		
		if(isset($attributes['portal'])&&$attributes['portal']==true)
		{
			$this->attributes=array_merge($this->attributes,array('anchor'=> '100%',
															'frame'=>true,
															'draggable'=>true,
															'cls'=>'x-portlet'));
			$this->attributes['plugins'][] = 'new Ext.ux.MaximizeTool()';												
			unset($attributes['portal']);
		}
			
		if(isset($attributes['tools']))
		{
			$this->attributes['tools']=$attributes['tools']->end();
			
			unset($attributes['tools']);
		}
						
		$this->attributes['getWidgetConfig']=$this->immExtjs->asMethod(
			'var o={};
			 o.idxml=this.idxml || false;
			 return o;'
		);
		if(count($attributes)>0)
		$this->attributes=array_merge($this->attributes,$attributes);
	}
	
	public function startRowActions($attributes=array())
	{
		return new ImmExtjsGridActions($attributes);		
	}
	
	public function endRowActions($actionsObject)
	{
		
		$actionsObject->end();
		
		$this->actionsObject=$actionsObject;
	}
	
	public function addColumn($attributes=array())
	{
		$this->columns[]=$attributes;
	}
	
	public function addFilter($attributes=array())
	{
		$this->filters[]=$attributes;
	}
	
	public function addButton($button)
	{
		if(!isset($this->attributes['tbar']))
		$this->attributes['tbar']=array();
		
		if(is_object($button))
		{
			array_push($this->attributes['tbar'],$this->immExtjs->asVar($button->end()));
		}
		else {
			array_push($this->attributes['tbar'],$this->immExtjs->asAnonymousClass($button));
		}
	}
	
	public function addHelp($html)
	{
		if(!isset($this->attributes['tbar']))
		{
			$this->attributes['tbar']=array();
		}
		
		$panel=new ImmExtjsPanel(array('html'=>'<div style="white-space:normal;">'.$html.'</div>','listeners'=>array('render'=>$this->immExtjs->asMethod(array("parameters"=>"panel","source"=>"if(panel.body){panel.body.dom.style.border='0px';panel.body.dom.style.background='transparent';}")))));
		@$this->attributes['listeners']['render']['source'].="var tb;if(this.getTopToolbar()&&this.getTopToolbar().items.items.length==0){tb = this.getTopToolbar();tb.addItem(".$panel->privateName.");}else{ tb = new Ext.Toolbar({renderTo: this.tbar,items: [".$panel->privateName."]});}if(tb&&tb.container){tb.container.addClass('tbarBottomBorderFix');}";
		
	}
	
	/**
	 * add a menu actions item
	 * ticket 1140
	 */
	public function addMenuActionsItem($attributes)
	{			
		$this->menuactions_items[]=$attributes;			
	}
	
	/**
	 * constructing menuactions
	 * ticket 1140
	 */
	public function addMenuActions()
	{
		
		if(count($this->menuactions_items)>0)
		{		
			/**
			 * Fill to move menuactions button to the right
			 */
			new ImmExtjsToolbarFill($this);
			
			$menuactions_button=new ImmExtjsToolbarButton($this,array('label'=>'More Actions'));
			$menuactions_menu=new ImmExtjsToolbarMenu($menuactions_button);		
			
			foreach ($this->menuactions_items as $attributes)
			{
				$item=new ImmExtjsToolbarMenuItem($menuactions_menu,$attributes);$item->end();
			}		
			
			$menuactions_menu->end();
			$menuactions_button->end();
		}
	}
	
	public function setProxy($attributes=array())
	{	
		if(is_array($this->proxy))
		{
			$this->proxy=array_merge($this->proxy,$attributes);
		}
		else {
			$this->proxy=$attributes;
		}
	}
		
	public function end()
	{	
		/**
		 * Stateful grid
		 */
		$this->attributes['stateful'] = true;
		$this->attributes['stateId'] = isset($this->attributes['name'])?$this->attributes['name']:$this->attributes['path'];
		$this->attributes['stateId'] = str_replace(" ","_",$this->attributes['stateId']);
		$this->attributes['stateEvents'] = array('columnresize', 'columnmove', 'show', 'hide','sortchange'); 
		
		
		if(!$this->attributes['tree'])
		{
			$this->attributes['view']=$this->immExtjs->GroupingColorView(array('forceFit'=>$this->attributes['forceFit'],'groupTextTpl'=>' {text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})'));
		
		
			if(isset($this->attributes['clearGrouping'])&&$this->attributes['clearGrouping'])
			{
				@$this->attributes['listeners']['render']["source"].="this.store.clearGrouping();";
			}
		}
		else 
		{
			$this->immExtjs->setAddons(array('css'=>array($this->immExtjs->getExamplesDir().'treegrid/css/TreeGrid.css'),'js'=>array($this->immExtjs->getExamplesDir().'treegrid/TreeGrid.js',$this->immExtjs->getExamplesDir().'treegrid/Ext.ux.SynchronousTreeExpand.js')));
						
			$this->attributes['viewConfig']=$this->immExtjs->asAnonymousClass(array('forceFit'=>$this->attributes['forceFit']));
		}
		
		if(isset($this->proxy['url'])&&count($this->columns)>0)
		{			
			$filtersPrivateName='filters_'.Util::makeRandomKey();
			$storePrivateName='store_'.Util::makeRandomKey();
			$readerPrivateName='reader_'.Util::makeRandomKey();
			if($this->attributes['pager'])
			{
				$pagingToolbarPrivateName='pt_'.Util::makeRandomKey();
			}
			
			$wasSort=false;
			$firstColumnName=false;
			
			foreach ($this->columns as $column)
			{				
				
				$temp_column=null;
				$temp_field=null;
				$temp_name='Header '.Util::makeRandomKey();
				
				$temp_column['dataIndex']=isset($column['name'])?$column['name']:Util::stripText($temp_name);
				
				$temp_field['name']=isset($column['name'])?$column['name']:Util::stripText($temp_name);
				//$temp_field['type']=isset($column['type'])?$column['type']:'auto';
				$temp_field['sortType']=isset($column['sortType'])?$column['sortType']:'asText';
				$temp_column['sortType']=isset($column['sortType'])?$column['sortType']:'asText';
				
				$temp_column['header']=isset($column['label'])?$column['label']:$temp_name;				
				$temp_column['sortable']=isset($column['sortable'])?$column['sortable']:true;
				if(isset($column['width'])&&$column['width']!='auto')
				{
					$temp_column['width']=$column['width'];
				}
				$temp_column['hidden']=isset($column['hidden'])?$column['hidden']:false;
				$temp_column['hideable']=isset($column['hideable'])?$column['hideable']:true;				
				$temp_column = $this->formatNumberColumn($temp_column);
				//$temp_column['align']=isset($column['align'])?$column['align']:'left';
				
				/**
				 * Edit link at defined column
				 * Please comment this block if the edit should be under the Actions column.
				 * This section looks the edit="true" in the xml columns. If found, and if 
				 * there is a row actions matching the name or label with edit, this will
				 * be transformed to the edit="true" column
				 */				
				if((isset($column['edit']) && $column['edit'])){				
					//print_r($this->actionsObject);					
					if($this->actionsObject){
						$actions = $this->actionsObject->getActions();									
						if(is_array($actions))
						foreach($actions as $key=>$action){
							if(preg_match("/_edit$/",$action['name']) || preg_match("/edit$/i",$action['label']) || preg_match("/_modify$/",$action['name']) || preg_match("/modify$/i",$action['label']) || preg_match("/_update$/",$action['name']) || preg_match("/update$/i",$action['label'])){
								$urlIndex = $action['urlIndex'];															
								$credential = ComponentCredential::urlHasCredential($action['url']);								
								$temp_column['renderer']=$this->immExtjs->asMethod(array(
									"parameters"=>"value, metadata, record",
									"source"=>"if(!".intval($credential).") return value;var action = record.get('".$urlIndex."'); if(!action) return value; var m = action.toString().match(/.*?\?(.*)/);return '<a href=\"".$action['url']."?'+m[1]+'\" qtip=\"Click to edit\">'+ value + '</a>';"
								));							
								$this->actionsObject = $this->actionsObject->changeProperty($action['name'],'hidden',true);
								if(isset(ImmExtjs::getInstance()->private[$this->actionsObject->privateName]))
								unset(ImmExtjs::getInstance()->private[$this->actionsObject->privateName]);
								$this->actionsObject->end();
								$this->moveEditRowAction = true;							
							}						
						}
					}
				}
				/*
				 * check for context menu
				 */
				$style = '';
				$arrowSpan = '';
				if(isset($column['contextMenu']) && $column['contextMenu']){	
					$style = "";
					$arrowSpan = '<span class="interactive-arrow"><a class="interactive-arrow-a"  href="#">&nbsp;</a></span>';				
					$contextMenu = context_menu($column['contextMenu'])->privateName;
					$this->contextMenu[$temp_field['name']] = $contextMenu;
					$temp_column['renderer']=$this->immExtjs->asMethod(array(
							"parameters"=>"value, metadata, record",
							"source"=>"return '<span $style>$arrowSpan' + value + '</span>';"
					));
				}/*else{
					$contextMenu = context_menu('',array('grid'))->privateName;
					$this->contextMenu[$temp_field['name']] = $contextMenu;
				}*/
				/**********************************************************************************/
				if(isset($column['qtip'])&&$column['qtip'])
				{
					$temp_column['renderer']=$this->immExtjs->asMethod(array(
							"parameters"=>"value, metadata, record",
							"source"=>"var qtip = value;  return '<span qtip=\"' + qtip + '\" $style>$arrowSpan' + value + '</span>';"
					));
				}
				
				// Add filter here
				ImmExtjsGridFilter::add($this,$column,$temp_column,$temp_field);
				//Remote filter
				if(isset($column['sortIndex'])){
					$temp_column['sortIndex'] = $column['sortIndex'];
				}
				if(!isset($temp_column['id']))
				{
					$temp_column['id']=$temp_column['dataIndex'];
				}
				
				if(!$this->attributes['tree'])
				{
					if(isset($column['id'])&&$column['id'])
					{
						$temp_column['id']=$temp_column['dataIndex'];
						if(!isset($this->attributes[$readerPrivateName]['id']))
						{
							$this->attributes[$readerPrivateName]['id']=$temp_column['dataIndex'];
						}
					}
					
					if(isset($column['groupField'])&&$column['groupField'])
					{
						$this->attributes[$storePrivateName]['groupField']=$temp_column['dataIndex'];
					}
				}
				
				if(!$wasSort&&isset($column['sort'])&&in_array($column['sort'],array('ASC','DESC')))
				{					
					$wasSort=true;
					$this->defineSortInfo($storePrivateName, $temp_column['dataIndex'], $column['sort']);
				}

				if(!$firstColumnName)
				{
					$firstColumnName=$temp_column['dataIndex'];
				}
								
				$this->attributes['columns'][]=$this->immExtjs->asAnonymousClass($temp_column);
				
				if($this->attributes['tree']&&!isset($this->attributes['master_column_id']))
				{					
					$this->attributes['master_column_id']=$temp_column['dataIndex'];
				}
				
				$this->attributes[$readerPrivateName]['fields'][]=$this->immExtjs->asAnonymousClass($temp_field);				
			}
			
			/*
			 * Add listeners for context menu
			 */
			$this->_addListenersForContextMenu($attributes);
			/**********************************************************/
			
			if (!$wasSort&&$firstColumnName)
			{
				$this->defineSortInfo($storePrivateName, $firstColumnName, 'ASC');
			}
			
			$count_actions=(is_object($this->actionsObject)?count($this->actionsObject->attributes['actions']):0);
			
			if($count_actions>0)
			{
				for ($i=1;$i<=$count_actions;$i++)
				{
					$this->attributes[$readerPrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'action'.$i,'type'=>'string'));
					$this->attributes[$readerPrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'hide'.$i,'type'=>'boolean'));
				}
			}
			
			$this->attributes[$readerPrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'message'));
			$this->attributes[$readerPrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'redirect'));
			
			
			
			if($this->attributes['tree'])
			{								
				$this->attributes[$readerPrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'_id','type'=>'int'));
				$this->attributes[$readerPrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'_parent','type'=>'auto'));
				$this->attributes[$readerPrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'_is_leaf','type'=>'bool'));
				$this->attributes[$readerPrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'_color','type'=>'auto'));
				$this->attributes[$readerPrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'_cell_color','type'=>'auto'));
				$this->attributes[$readerPrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'_buttonOnColumn','type'=>'auto'));
				$this->attributes[$readerPrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'_buttonText','type'=>'auto'));
				$this->attributes[$readerPrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'_buttonDescription','type'=>'auto'));
				
				if($this->attributes['select'])
				{
					$this->attributes[$readerPrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'_selected','type'=>'auto'));
				}
				
				$this->attributes[$readerPrivateName]['id']='_id';
			}
			else {
				$this->attributes[$readerPrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'_color','type'=>'auto'));
				$this->attributes[$readerPrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'_cell_color','type'=>'auto'));
				//Select for normal grid too.....
				if($this->attributes['select'])
				{
					$this->attributes[$readerPrivateName]['fields'][]=$this->immExtjs->asAnonymousClass(array('name'=>'_selected','type'=>'auto'));
				}
				//..................................
				if(!isset($this->attributes[$readerPrivateName]['id']))
				{
					$this->attributes[$readerPrivateName]['id']='_id';
				}
			}
			
			$this->attributes[$readerPrivateName]['totalProperty']='totalCount';
			$this->attributes[$readerPrivateName]['root']='rows';
			$this->attributes[$readerPrivateName]['properties']='properties';
			
			$this->immExtjs->private[$readerPrivateName]=$this->immExtjs->JsonReader($this->attributes[$readerPrivateName]);
			unset($this->attributes[$readerPrivateName]);
			
			$this->attributes[$storePrivateName]['reader']=$this->immExtjs->asVar($readerPrivateName);
			if(isset($this->attributes['remoteSort']))
			{
				$this->attributes[$storePrivateName]['remoteSort']=$this->attributes['remoteSort'];
				unset($this->attributes['remoteSort']);
			}
			$this->attributes[$storePrivateName]['proxy']=$this->immExtjs->HttpProxy(array('url'=>$this->proxy['url'],'method'=>'GET','disableCaching'=>false));
			
			$beforeloadListener = "
				if(!Ext.isIE&&!".$this->privateName.".disableLoadMask){".$this->privateName.".getEl().mask('Loading, please Wait...', 'x-mask-loading');}
				var grid = ".$this->privateName.";
				var cm = grid.getColumnModel();				
				var id = object.getSortState().field;
				if(!id) return;
				var col = cm.getColumnById(id);
				if(col.sortIndex){
					Ext.apply(grid.getStore().lastOptions.params,{
						xsort:col.sortIndex
					})						
				}else{
					grid.getStore().lastOptions.params.xsort = null
				}
			";
			
			if(isset($this->proxy['stateId']))
			{
				$this->attributes[$storePrivateName]['pt_state_loaded']=false;
				$this->attributes[$storePrivateName]['pt_state']="Ext.state.Manager.get('".$this->proxy['stateId']."')";
				$this->attributes[$storePrivateName]['listeners']['beforeload']=$this->immExtjs->asMethod(array(
																			"parameters"=>"object,options",
																			"source"=>
																			"if(!this.pt_state_loaded&&this.pt_state){options.params=this.pt_state;this.pt_state_loaded=true;}".$beforeloadListener
																	));
			}
			else {
				$this->attributes[$storePrivateName]['listeners']['beforeload']=$this->immExtjs->asMethod(array(
																			"parameters"=>"object,options",
																			"source"=>$beforeloadListener
																	));
			}
			$this->attributes[$storePrivateName]['listeners']['load']=$this->immExtjs->asMethod(array(
																			"parameters"=>"object,records,options",
																			"source"=>
																			'if(records.length>0&&records[0].json.redirect&&records[0].json.message){var rec=records[0].json;Ext.Msg.alert("Failure", rec.message, function(){'.$this->privateName.'.getEl().unmask();window.location.href=rec.redirect;});}else{if(!Ext.isIE){'.$this->privateName.'.getEl().unmask();}}'
																	));
																	
			$this->attributes[$storePrivateName]['listeners']['loadexception']=$this->immExtjs->asMethod(array(
																			"parameters"=>"",
																			"source"=>
																			'if(!Ext.isIE){'.$this->privateName.'.getEl().unmask();}'
																	));
					
			if(!$this->attributes['tree'])
			{
				$this->immExtjs->private[$storePrivateName]=$this->immExtjs->GroupingStore($this->attributes[$storePrivateName]);
			}
			else{
				$this->immExtjs->private[$storePrivateName]=$this->immExtjs->AdjacencyListStore($this->attributes[$storePrivateName]);
			}
			unset($this->attributes[$storePrivateName]);
			
			if($this->attributes['pager'])
			{
				$this->attributes[$pagingToolbarPrivateName]['store']=$this->immExtjs->asVar($storePrivateName);
				$this->attributes[$pagingToolbarPrivateName]['displayInfo']=true;
				if($this->gridType == "file"){
										
					$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'grid/Ext.ux.plugins.FilePagingInfo.js')));
					$this->attributes[$pagingToolbarPrivateName]['plugins'][]="Ext.ux.plugins.FilePagingInfo";
				}
				$this->attributes[$pagingToolbarPrivateName]['pageSize']=isset($this->proxy['limit'])?$this->proxy['limit']:20;
							
				if(isset($this->proxy['stateId']))
				{
					$this->attributes[$pagingToolbarPrivateName]['stateId']=$this->proxy['stateId'];
					$this->attributes[$pagingToolbarPrivateName]['stateEvents']=array('change');
					$this->attributes[$pagingToolbarPrivateName]['stateful']=true;
					$this->attributes[$pagingToolbarPrivateName]['getState']=$this->immExtjs->asMethod(array(
																				"parameters"=>"",
																				"source"=>"return { start: ".(isset($this->proxy['start'])?$this->proxy['start']:"this.cursor").",
																									limit: this.pageSize };"
																		));			
				}
			
				if(count($this->filters)>0)
				{
					//$this->attributes[$pagingToolbarPrivateName]['plugins'] = $this->immExtjs->asVar($filtersPrivateName);
				}				
			
				if(!$this->attributes['tree'])
				{
					$this->immExtjs->private[$pagingToolbarPrivateName]=$this->immExtjs->PagingToolbar($this->attributes[$pagingToolbarPrivateName]);
				}
				else {
					$this->immExtjs->private[$pagingToolbarPrivateName]=$this->immExtjs->GridTreePagingToolbar($this->attributes[$pagingToolbarPrivateName]);
				}
				unset($this->attributes[$pagingToolbarPrivateName]);	
			}
			
		}
				
		if(count($this->filters)>0)
		{
			$this->attributes[$filtersPrivateName]['filters']=$this->filters;
			$this->attributes[$filtersPrivateName]['local']=(isset($this->attributes['remoteFilter']) && $this->attributes['remoteFilter'])?false:true;			
			//$this->attributes[$filtersPrivateName]['filterby']=sfContext::getInstance()->getActionStack()->getLastEntry()->getActionInstance()->getRequestParameter("filterby",false);
			$this->attributes[$filtersPrivateName]['filterby']=sfContext::getInstance()->getUser()->getAttribute('filterby',false);
			$this->attributes[$filtersPrivateName]['filterbyKeyword']=sfContext::getInstance()->getUser()->getAttribute('filterbyKeyword',false);
			sfContext::getInstance()->getUser()->setAttribute('filterby',false);
			sfContext::getInstance()->getUser()->setAttribute('filterbyKeyword',false);
			//$this->attributes['title'] = $this->attributes['title'].": <font color=red>(Filtered by keyword: '".$this->attributes[$filtersPrivateName]['filterbyKeyword']."'</font>)";
			$this->immExtjs->private[$filtersPrivateName]=$this->immExtjs->GridFilters($this->attributes[$filtersPrivateName]);
			
			$this->attributes['plugins'][]=$this->immExtjs->asVar($filtersPrivateName);
			
			unset($this->attributes[$filtersPrivateName]);	
		}
		
		if($count_actions>0)
		{
			if($this->moveEditRowAction){
				if(($count_actions - 1)>0)
				$this->attributes['columns'][]=$this->immExtjs->asVar($this->actionsObject->privateName);
			}else{
				$this->attributes['columns'][]=$this->immExtjs->asVar($this->actionsObject->privateName);
			}
			$this->attributes['plugins'][]=$this->immExtjs->asVar($this->actionsObject->privateName);
		}
		$this->attributes['store']=$this->immExtjs->asVar($storePrivateName);
		if($this->attributes['pager'])
		{
			$this->attributes['bbar']=$this->immExtjs->asVar($pagingToolbarPrivateName);
		}
		//changed to have select on normal grid too..
		//if($this->attributes['tree'] && $this->attributes['select'])
		if($this->attributes['select'])
		{
			$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'treegrid/Ext.ux.CheckboxSelectionModel.js')));
			
			$selectionModelPrivateName='sm_'.Util::makeRandomKey();
			$this->immExtjs->private[$selectionModelPrivateName]=$this->immExtjs->UxCheckboxSelectionModel(array());
			$this->attributes['sm']=$this->immExtjs->asVar($selectionModelPrivateName);
			//if($this->attributes['tree'])			
			$this->attributes['columns'][]=$this->immExtjs->asVar($selectionModelPrivateName);
			//array_unshift($this->attributes['columns'],$this->immExtjs->asVar($selectionModelPrivateName));
			
			/*
			 * Since the insertion of checkbox selection model at the beginning of the grid, the tree structrue get lost, though it was 
			 * fine for non-tree grid. To overcome this first the grid is rendered as it is with the checkbox selection model at the end
			 * and when the grid is rendered the checkbox selection model is now moved to the initial column position of the grid.
			 */
			$jsSource = "
				var gcm = ".$this->privateName.".getColumnModel();
				if(gcm.getColumnHeader(gcm.getColumnCount()-1) == '<div class=\"x-grid3-hd-checker\" id=\"hd-checker\">&#160;</div>') 
				gcm.moveColumn(gcm.getColumnCount()-1,0);
				";
		}
		else {
			$jsSource = '';
		}
		
		@$this->attributes['listeners']['render']["source"].="
			this.store.load({
				params:{
					start:".(isset($this->proxy['start'])?$this->proxy['start']:0).", 
					limit:".(isset($this->proxy['limit'])?$this->proxy['limit']:20)."
				}
			});";
		
		$attributes['listeners']['render']["source"]=$this->attributes['listeners']['render']["source"];
		$attributes['listeners']['render']["source"] .= $jsSource;
		unset($this->attributes['listeners']['render']["source"]);
		
		$this->attributes['listeners']['render']=$this->immExtjs->asMethod(array(
				"parameters"=>"",
				"source"=>$attributes['listeners']['render']["source"]
		));		
		
		unset($attributes['listeners']['render']["source"]);
						
		if(count($this->filters)>0)
		{		
			$this->immExtjs->setAddons(array('js'=>array($this->immExtjs->getExamplesDir().'grid-filtering/ux/menu/EditableItem.js',$this->immExtjs->getExamplesDir().'grid-filtering/ux/menu/ComboMenu.js',$this->immExtjs->getExamplesDir().'grid-filtering/ux/menu/RangeMenu.js',$this->immExtjs->getExamplesDir().'grid-filtering/ux/grid/GridFilters.js',$this->immExtjs->getExamplesDir().'grid-filtering/ux/grid/DrillFilter.js',$this->immExtjs->getExamplesDir().'grid-filtering/ux/grid/RePositionFilters.js',$this->immExtjs->getExamplesDir().'grid-filtering/ux/grid/SaveSearchState.js',$this->immExtjs->getExamplesDir().'grid-filtering/ux/grid/FilterInfo.js',$this->immExtjs->getExamplesDir().'grid-filtering/ux/grid/filter/Filter.js',$this->immExtjs->getExamplesDir().'grid-filtering/ux/grid/filter/BooleanFilter.js',$this->immExtjs->getExamplesDir().'grid-filtering/ux/grid/filter/ComboFilter.js',$this->immExtjs->getExamplesDir().'grid-filtering/ux/grid/filter/DateFilter.js',$this->immExtjs->getExamplesDir().'grid-filtering/ux/grid/filter/ListFilter.js',$this->immExtjs->getExamplesDir().'grid-filtering/ux/grid/filter/NumericFilter.js',$this->immExtjs->getExamplesDir().'grid-filtering/ux/grid/filter/StringFilter.js'),'css'=>array($this->immExtjs->getExamplesDir().'grid-filtering/resources/style.css')));
			$savedFilters = afSaveFilterPeer::getFiltersByName(isset($this->attributes['name'])?$this->attributes['name']:$this->attributes['path']);
			$fc = 0;
			$str = '';
			foreach($savedFilters as $f){
				if($str == "") $str.=',"-"';
				if($fc > 4) break;			
				$str .= ',{
							text:"'.++$fc.'. '.((strlen($f->getName())>25)?(substr($f->getName(),0,25).'...'):$f->getName()).'",
							handler:function(){
								var grid = '.$this->privateName.';
								var filters = grid.filters;
								if(!filters) return;							
								var saveFilter = Ext.ux.SaveSearchState(grid);							
								saveFilter.restore(\''.$f->getFilter().'\',"'.$f->getName().'");
							}
						}';
			}
			// Add reset filters on menu action if there is filter in grid
			$this->addMenuActionsItem(array('label'=>'Filters','iconCls'=>'icon-filter','menu'=>$this->immExtjs->asVar('[
				{
					iconCls:"icon-folder",
					text:"Saved filters detail",
					handler:function(){
						var grid = '.$this->privateName.';
						var filters = grid.filters;
						if(!filters) return;							
						var saveFilter = Ext.ux.SaveSearchState(grid);
						saveFilter.viewSavedList();
					}
				},{
					text:"Save current filter",
					iconCls:"icon-save",
					handler:function(){
						var grid = '.$this->privateName.';
						var filters = grid.filters;
						if(!filters) return;							
						var saveFilter = Ext.ux.SaveSearchState(grid);
						saveFilter.save();
					}
				}'.$str.'
			]')));			
			
			/*$this->addMenuActionsItem(array('label'=>'Filters','icon'=>'/images/famfamfam/drink.png','listeners'=>array('click'=>array('parameters'=>'','source'=>'var grid = '.$this->privateName.';
							var filters = grid.filters;
							if(!filters) return;							
							var saveFilter = Ext.ux.SaveSearchState(grid);
							saveFilter.viewSavedList();'))));		*/		
				
			
		}
		$this->addMenuActions();
		
		if(!$this->attributes['tree'])
		{
			$this->immExtjs->private[$this->privateName]=$this->immExtjs->GridPanel($this->attributes);
		}
		else {
			$this->immExtjs->private[$this->privateName]=$this->immExtjs->GridTreePanel($this->attributes);
		}
		//print_r($this);
	}

	private function defineSortInfo($storePrivateName, $field, $direction)
	{
		$this->attributes[$storePrivateName]['sortInfo']=$this->immExtjs->asAnonymousClass(array('field'=>$field,'direction'=>$direction));
	}
	private function _addListenersForContextMenu(&$attributes){
		/*
		 * Listener for the context menu
		 *
		 */
		$initialize = '';
				
		foreach($this->contextMenu as $key=>$value){				
			$initialize .= "contextMenus['".$key."'] = ".$value.";";	
		}
		$this->attributes['listeners']['click'] = "function(e){		
			var t = e.getTarget();										
			if(t.className != 'x-grid3-header'){
	            var r = e.getRelatedTarget();
	            var v = this.view;
	            var ci = v.findCellIndex(t.parentNode);
	            var ri = v.findRowIndex(t);	
	            
	            var grid = this;
	            //alert(ci); alert(ri);            
	            if(ci === false || ri === false) return ;
	            var cell = this.getView().getCell(ri,ci);
	          
	            if(t.className == 'interactive-arrow-a'){
	           
	            	ci = v.findCellIndex(t.parentNode.parentNode);
	            	var contextMenus = Array();
					".$initialize."	
					var fieldName = grid.getColumnModel().getDataIndex(ci);
					var data = null;
					if(grid.getSelectionModel){					
						grid.getSelectionModel().clearSelections();
						grid.getSelectionModel().selectRow(ri);
						var record = grid.getSelectionModel().getSelected();
						data = record.get(fieldName);					
						grid.getSelectionModel().clearSelections();
					}
					var xy = e.getXY();
					
					if(data == null || data == ''){
						var valueNode = Ext.DomQuery.selectNode('.interactive-arrow-a',grid.getView().getCell(ri,ci));
						data = valueNode.innerHTML;						
					}
					var pattern = /(\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b)/;
					var ip = data.match(pattern);
					if(!ip){
						ip = data.match(/[0-9]+/);
						if(!ip){
							Ext.Msg.alert('Notice','No valid data found');
							return;
						}
					}					
					data = ip[0];
					
					var contextMenu = contextMenus[fieldName];
					if(contextMenu && data != ''){
						contextMenu.stack['text'] = data;
						contextMenu.stack['grid'] = grid;
						contextMenu.stack['ri'] = ri;
						contextMenu.stack['ci'] = ci;
						contextMenu.stack['cell'] = grid.getView().getCell(ri,ci);
						contextMenu.stack['cellDiv'] = grid.getView().getCell(ri,ci).getElementsByTagName('div')[0];			
						contextMenu.stack['rowDivs'] = grid.getView().getRow(ri).getElementsByTagName('div');
						contextMenu.showAt(xy);
					}		
	            }
	            if(t.className == 'grid-util-action'){
	            	gridUtil(this,t.rel);
	            }
	            if(Ext.ux.DrillFilter)	            	
	            new Ext.ux.DrillFilter(grid,e);
	        }          
            
		}";
		$this->attributes['listeners']['mouseout'] = "function(e){
			var t = e.getTarget();
			if(t.className != 'x-grid3-header'){
	            var r = e.getRelatedTarget();
	            var v = this.view;
	            var ci = v.findCellIndex(t);
	            var ri = v.findRowIndex(t);	            
	            if(ci === false || ri === false) return ;
	            var cell = this.getView().getCell(ri,ci);
	            if(cell){		            
	            	
	            	//Cross browser implementation
	            	var className = 'interactive-arrow-active';
	            	var tagName = 'span', _tags = cell.getElementsByTagName(tagName), _nodeList = [];
				    for (var i = 0, _tag; _tag = _tags[i++];) {
				        if (_tag.className.match(new RegExp('(\\s|^)'+className+'(\\s|$)'))) {
				            _nodeList.push(_tag);
				        }
				    }
				    //.............................................................................						            	
	            
		            var arrowDiv = _nodeList[0];
		            if(arrowDiv){
		            	arrowDiv.className = 'interactive-arrow';			            
		            }
	            }
            }	            
		}";
		$this->attributes['listeners']['mouseover'] = "function(e){			
			var t = e.getTarget();				
			if(t.className != 'x-grid3-header'){
	            var r = e.getRelatedTarget();
	            var v = this.view;
	            var ci = v.findCellIndex(t);
	            var ri = v.findRowIndex(t);
	           
	            if(ci === false || ri === false) return ;
	            var cell = this.getView().getCell(ri,ci);		           		           
	            if(cell){		            
	            	
	            	//Cross browser implementation
	            	var className = 'interactive-arrow';
	            	var tagName = 'span', _tags = cell.getElementsByTagName(tagName), _nodeList = [];
				    for (var i = 0, _tag; _tag = _tags[i++];) {
				        if (_tag.className.match(new RegExp('(\\s|^)'+className+'(\\s|$)'))) {
				            _nodeList.push(_tag);
				        }
				    }
				    //.............................................................................
						            	
	            
		            var arrowDiv = _nodeList[0];
		            if(arrowDiv){
		            	arrowDiv.className = 'interactive-arrow-active';			            	
		            }
	            }
            }
            
		}";
	}
	
	public function postAsTraditional($params=array()){
		/**
		 * Submitting the data through traditional form
		 * @var unknown_type
		 */	
		$ts = '';
		$randId = "traditional_form_".rand(1,999999999); 
		if(isset($params['preSource'])){
			$ts.=$params['preSource']."\r\n";
		}
		$method = isset($params['method'])?$params['method']:"POST";		
		$ts .= '
			var randId = "traditional_form_"+Math.floor(Math.random()*11);
			var div = document.createElement("div");
			var form = document.createElement("form");
			form.name=randId;
			form.action = "'.$params['action'].'";
			form.method = "'.$method.'";			
		';
		if(isset($params['params']) && is_array($params['params'])){
			foreach($params['params'] as $key=>$value){
														
				$ts .= '					
					var field = document.createElement("input");
					field.name = "'.$key.'";					
					form.appendChild(field);
				';
				if($value[1] == "string"){
					$ts.='field.value = "'.$value[0].'"';
				}else if($value[1] == "js"){
					$ts.='field.value = '.$value[0];
				}
			}
		}
		$ts.='	
			div.appendChild(form)		
			div.style.display="none"
			document.body.appendChild(div)
			form.submit();			
		';
		if(isset($params['postSource'])){
			$ts.=$params['postSource']."\r\n";
		}
		return $ts;
		/******************************************************************************/	
	}
	public function getListenerParams(&$action,$type,$iteration='',$select="false"){		
		$grid =$this;
		
		if($type == "moreactions"){
			if(!isset($action['attributes']['confirmMsg']))$action['attributes']['confirmMsg'] = "";
			if(!isset($action['attributes']['forceSelection']))$action['attributes']['forceSelection'] = "true";
			if(!isset($action['attributes']['confirm']))$action['attributes']['confirm'] = "true";
			if(!isset($action['attributes']['post']))$action['attributes']['post'] = "true";
		}else{
			if(!isset($action['attributes']['confirmMsg']))$action['attributes']['confirmMsg'] = "";
			if(!isset($action['attributes']['forceSelection']))$action['attributes']['forceSelection'] = "false";
			if(!isset($action['attributes']['confirm']))$action['attributes']['confirm'] = "false";
			if(!isset($action['attributes']['post']))$action['attributes']['post'] = "false";
		}
		if(!isset($action['attributes']['icon']))$action['attributes']['icon'] = ""; 
		if(!isset($action['attributes']['iconCls']))$action['attributes']['iconCls'] = "";
		if(!isset($action['attributes']['url']))$action['attributes']['url'] = "#";		
		if(!isset($action["attributes"]["label"]))
		$action["attributes"]["label"] = ucfirst($action["attributes"]["name"]);						
		$action["attributes"]["name"] = $iteration."_".$action["attributes"]["name"];
		
		$confirmMsg = $action["attributes"]["confirmMsg"] == ""?"Are you sure to perform this operation?":$action["attributes"]["confirmMsg"];
		$noItemsSelectedFunction='';
		$noDataInGridFunction = '';
		$requestParams = '"';
		$params = '';
		$functionForUpdater = '';		
		if($action["attributes"]["updater"] === "true") {
			$action["attributes"]["post"] = "true";			
			$updater = new ImmExtjsUpdater(array('url'=>$action["attributes"]["url"],'width' => 500));
			$functionForUpdater = $updater->privateName.'.start();
			'.$updater->privateName.'.on("finish",function(comet,response){
				//var response = response.responseText;
				var grid = '.$grid->privateName.';
				var store = grid.getStore();
				if(store.proxy.conn.disableCaching === false) {
					store.proxy.conn.disableCaching = true;
				}
				store.reload();
				if((grid.tree && response.redirect) || (response.redirect && response.forceRedirect)){
					window.location.href = response.redirect;
				}
			});
			';								
		}
		if($action["attributes"]["forceSelection"] != "false"){
			$noDataInGridFunction = '
				if(!'.$grid->privateName.'.getStore().getCount()){
					Ext.Msg.alert("No Data In Grid","There is no data on grid.");
					return;
				}
			';
			$noItemsSelectedFunction = '								
				if(!'.$grid->privateName.'.getSelectionModel().getCount()){
					Ext.Msg.alert("No items selected","Please select at least one item");
					return;
				}
			';
			if($select == "true"){
				$requestParams = '/selections/"+'.$grid->privateName.'.getSelectionModel().getSelectionsJSON()';
				$params = 'params:{"selections":'.$grid->privateName.'.getSelectionModel().getSelectionsJSON()}, ';
			} 
		}
		$successFunction='';
		if($action["attributes"]["updater"] != "true"){
			if($action["attributes"]["post"] != "false"){
				$successFunction = '
					Ext.getBody().mask("Action in progress. Please wait...");
					Ext.Ajax.request({ 
						url: "'.$action["attributes"]["url"].'",
						method:"post", 
						'.$params.'
						success:function(response, options){
							if(Ext.getBody().isMasked()) Ext.getBody().unmask();
							response=Ext.decode(response.responseText);
							if(response.message){								
								new Ext.ux.InstantNotification({title:"Success",message:response.message});	
								var grid = '.$grid->privateName.';
								if(grid){
									sm = grid.getSelectionModel();
									if(sm){
										sm.clearSelections();
									}
									var store = grid.getStore();
									if(store.proxy.conn.disableCaching === false) {
										store.proxy.conn.disableCaching = true;
									}
									store.reload();
								}
								if((grid.tree && response.redirect) || (response.redirect && response.forceRedirect)){
									window.location.href = response.redirect;
								}																			
							}
						},
						failure: function(response,options) {
							if(Ext.getBody().isMasked()) Ext.getBody().unmask();
							if(response.message){
								Ext.Msg.alert("Failure",response.message);
							}
						}
					});
				';
			}else{
				$successFunction = 'window.location.href="'.$action["attributes"]["url"].$requestParams;
			}
		}
		//popup = true will overwrite the successFunction
        
		if(!isset($action['attributes']['popupSettings']))
		{
			$action['attributes']['popupSettings']="";
		}
		
		if(isset($action['attributes']['popup']) && $action['attributes']['popup'] && $action['attributes']['popup'] !=="false") $successFunction = 'ajax_widget_popup("'.$action["attributes"]["url"].'","","","'.$action['attributes']['popupSettings'].'");';
		if($action["attributes"]["confirm"] != "false"){
			$confirmFunction = '
				Ext.Msg.show({
				   title:"Confirmation Required",
				   msg: "'.$confirmMsg.'",
				   buttons: Ext.Msg.YESNO,
				   fn: function(buttonId){if(buttonId == "yes"){'.$functionForUpdater.$successFunction.'}},
				   icon: Ext.MessageBox.QUESTION								   
				});
			';
		}else{
			$confirmFunction = $functionForUpdater.$successFunction;
		}							
		$sourceForButton = $noDataInGridFunction.$noItemsSelectedFunction.$confirmFunction;		
		$handlersForMoreActions = array(
				'click'=>array(
					'parameters'=>'field,event',
					'source'=>(isset($action['attributes']['script'])?$action['attributes']['script']:'').";".$sourceForButton
				)
			);
		if(isset($action["handlers"])){
			sfLoader::loadHelpers(array('ImmExtjsExecuteCustomJS'));
			setHandler($action);
			$handlersForMoreActions = array_merge(array(
					'click'=>array(
						'parameters'=>'field,event',
						'source'=>(isset($action['attributes']['script'])?$action['attributes']['script']:'').";".$sourceForButton
					)
				),$action["attributes"]["handlers"]
			);
		}						
		//echo "<pre>";print_r($action);
		$parameterForButton = array(
		
			'label'=>$action["attributes"]["label"],
			'icon' => $action["attributes"]["icon"],			
			'iconCls' => $action["attributes"]["iconCls"],
			'listeners'=>$handlersForMoreActions
		);		
		//echo print_r($parameterForButton);
		return ComponentCredential::filter($parameterForButton,$action['attributes']['url']);
	}
	private function formatNumberColumn(&$column){
		if(in_array($column['sortType'],array("asSize","htmlAsSize","asInt","htmlAsInt","asFloat","htmlAsFloat"))){
			$column['align'] = "right";
		}
		return $column;
	}
}
?>