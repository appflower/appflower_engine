/**
* Ext.ux.FilterInfo
* @author: Prakash Paudel
*
* Information about the filtered criteria
*/
Ext.ns("Ext.ux");
Ext.ux.FilterInfo = function(grid,mode){

	var filterInfo = this;	
	//Get column model
	var cm = grid.getColumnModel();
	//Get filters object of grid
    var filtersObj = grid.filters;
    
    //return if no filters obj
    if(!filtersObj || !filtersObj.filters) return;
    
	//Get the grid element
	var gridEl = grid.getGridEl();
	
	//Get the clear div between info and grid
	var clearDivExists = Ext.DomQuery.selectNode(".ux-grid-filter-info-clear",gridEl.dom);
	
	//Use clear div if it already exists or create new
	if(mode == 'panel')var clearDiv = clearDivExists?clearDivExists:Ext.DomHelper.insertFirst(gridEl,{tag:'span',html:'&nbsp;',cls:'ux-grid-filter-info-clear'});
	
	//Get info div if already exists
	var infoDivExists = Ext.DomQuery.selectNode(".ux-grid-filter-info",gridEl.dom);
	
	//Use info div if it already exists or create new
	if(mode == 'panel') var infoDiv = infoDivExists?infoDivExists:Ext.DomHelper.insertFirst(gridEl,{tag:'div',html:'',cls:'ux-grid-filter-info'});
	//infoDiv.style.width=gridEl.getWidth()+"px";
	//Create a template for info-box
	var tpl = Ext.DomHelper.createTemplate({tag: 'div', cls: 'ux-grid-filter-info-box', html: '{html}&nbsp;&nbsp;<a title="Remove this filter" id="{id}" href="javascript:void(0)" onclick="Ext.ux.FilterInfo.remove(this)">&nbsp;</a>'});
	
	if(infoDiv) infoDiv.innerHTML = '';
	var actions = [];
	var plainText = '';
	var originalTitle = null;
	filtersObj.filters.each(function(filter){
		
    	var dataIndex = filter.dataIndex;
    	var header = cm.getColumnHeader(cm.findColumnIndex(dataIndex));    	
    	if(filter.active){
    		var val = '';
			if(filter.getDisplayValue){
				val = filter.getDisplayValue();
			}else{
				val = filter.getValue();				
			}			
			if(val){
				plainText += "'"+val+"', ";
				var id = Ext.id(null,"filter-");
				
				actions.push(new Ext.Button(new Ext.Action({				    
				    handler: function(){
						filter.setActive(false);
				    },				    
				    itemId: id+"-action",
				    hidden:true
				})));
				if(mode == 'panel'){
					tpl.append(infoDiv,{
						html:'<b>'+header+':</b> '+val,
						id:id
					});	
				}				
			}
    	}
    });	
	if(mode == 'panel'){
		var panel = new Ext.Panel({
			renderTo:infoDiv,
			items: actions,
			id:'filter-info-action-panel'
		});
		plainText = '';
	}
	if(mode == "title"){		
		if(plainText){
			//grid.setTitle(grid.originalTitle+" <font color='red'>Filtered by keyword: "+plainText+"</font>");
		}
		else{
			grid.setTitle(grid.originalTitle);
		}
	}

	
}
Ext.ns("Ext.ux.FilterInfo");
Ext.ux.FilterInfo.remove = function(anchor){
	var panel = Ext.getCmp("filter-info-action-panel");
	var action = panel.getComponent(anchor.id+"-action");
	action.baseAction.execute();
	try{
		var grid = Ext.getCmp(Ext.get(anchor).findParent('.x-grid-panel').id);
		grid.setTitle(grid.originalTitle);
	}catch(e){
		
	}
}

