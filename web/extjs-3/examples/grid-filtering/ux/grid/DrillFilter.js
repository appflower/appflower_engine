/**
* Ext.ux.DrillFilter
* @author: Prakash Paudel
*
* Drill filtering to the grid filter, by clicking on the text or selecting the text
*/
Ext.ns("Ext.ux");
Ext.ux.DrillFilter = function(grid,e){
	
	//Find clicked target
	var t = e.getTarget();
	
	//Find column index
	var colIndex = grid.getView().findCellIndex(t.parentNode);
	
	//Find row index
    var rowIndex = grid.getView().findRowIndex(t);
    
    //Get column model
    var cm = grid.getColumnModel();
    
    //Find data index of the column
	var dataIndex = cm.getDataIndex(colIndex);
	
	//Get filters object of grid
    var filtersObj = grid.filters;
    
    //return if no filters obj
    if(!filtersObj) return;
    if(!filtersObj.filters) return;
    //Get filter on current column
	var filter = filtersObj.filters.get(dataIndex);
	if(!filter) return;
	
	//If no valid target and not selectable filter
	if(t.className != 'ux-grid-filter'){	            		
		if(!filter.selectable){
			return;
		}
    }
	
	//Get the data in cell
	var data = grid.getView().getCell(rowIndex,colIndex).innerHTML;
	
	var valueNode = Ext.DomQuery.selectNode(".ux-grid-filter-hidden-value",grid.getView().getCell(rowIndex,colIndex)); 
	
	var text = new Ext.Imm.SelectedText();
	text = text.toString();
    
    //Return if not value node and not text
    if(!text && !valueNode) return;
    
	var finalValue = text?text:valueNode.innerHTML; 
	//Re-format data
	if(filter.type == "numeric"){
		finalValue = {
			'eq':finalValue
		}
	}
	if(filter.type == "list"){
		finalValue =[finalValue];
	}
	
	//Start filter	
	filter.setValue(finalValue);	
	filter.setActive(true);
	
}
Ext.ns("Ext.Imm");
Ext.Imm.SelectedText = function(){	
	var txt = null;
	if(window.getSelection){
		txt = window.getSelection();
	}else if(document.getSelection){
		txt = document.getSelection();
	}else if(document.selection){
		txt = document.selection.createRange().text;
	}else{
		return;
	}
	return txt;	
}
