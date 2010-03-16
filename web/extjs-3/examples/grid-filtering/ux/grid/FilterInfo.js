/**
* Ext.ux.FilterInfo
* @author: Prakash Paudel
*
* Information about the filtered criteria
*/
Ext.ns("Ext.ux");
Ext.ux.FilterInfo = function(grid){
	var filterInfo = this;	
	//Get column model
	var cm = grid.getColumnModel();
	//Get filters object of grid
    var filtersObj = grid.filters;
    
    //return if no filters obj
    if(!filtersObj) return;
    
	//Get the grid element
	var gridEl = grid.getGridEl();
	
	//Get info div if already exists
	var infoDivExists = Ext.DomQuery.selectNode(".ux-grid-filter-info",gridEl.dom);
	
	//Use info div if it already exists or create new
	var infoDiv = infoDivExists?infoDivExists:Ext.DomHelper.insertFirst(gridEl,{tag:'div',html:'',cls:'ux-grid-filter-info'});
	
	//Create a template for info-box
	var tpl = Ext.DomHelper.createTemplate({tag: 'div', cls: 'ux-grid-filter-info-box', html: '{html}&nbsp;&nbsp;<a title="Remove this filter" id="{id}" href="#" onclick="Ext.ux.FilterInfo.remove(this)"></a>'});
	
	infoDiv.innerHTML = '';
	var actions = [];
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
				var id = Ext.id(null,"filter-");
				
				actions.push(new Ext.Button(new Ext.Action({				    
				    handler: function(){
						filter.setActive(false);
				    },				    
				    itemId: id+"-action",
				    hidden:true
				})));
				
				tpl.append(infoDiv,{
		    		html:'<b>'+header+':</b> '+val,
		    		id:id
		    	});
				
			}
    	}
    });
	var panel = new Ext.Panel({
		renderTo:infoDiv,
		items: actions,
		id:'filter-info-action-panel'
	})
	
	
}
Ext.ns("Ext.ux.FilterInfo");
Ext.ux.FilterInfo.remove = function(anchor){
	var panel = Ext.getCmp("filter-info-action-panel");
	var action = panel.getComponent(anchor.id+"-action");
	action.baseAction.execute();

}

