/**
* Ext.ux.RePositionFilters
* @author: Prakash Paudel
*
* Re-position the filters from the hmenu to the just below column header..
*/
Ext.ns("Ext.ux");
Ext.ux.RePositionFilters = function(grid){
	grid.addEvents('spresize');
	//Find column model
	var cm = grid.getColumnModel();	
	this.grid = grid;
	//Apply Template
	this.applyTemplate = function() {		
	    var colTpl = "";
	    this.eachColumn(function(col) {
	      var filterDivId = this.getFilterDivId(col.id);	      
	      var style = col.hidden ? " style='display:none'" : "";
	      colTpl += '<td' + style + '><div class="x-small-editor" id="' + filterDivId + '"></div></td>';
	    });
	    
	    var headerTpl = new Ext.Template(
	      '<table border="0" cellspacing="0" cellpadding="0" style="{tstyle}">',
	      '<thead><tr class="x-grid3-hd-row">{cells}</tr></thead>',
	      '<tbody><tr class="filter-row-header">',
	      colTpl,
	      '</tr></tbody>',
	      "</table>"
	    );	    
	   return headerTpl;
	}
	this.getFilterFieldDom = function(field) {
	    return field.wrap ? field.wrap.dom : field.el.dom;
	}
	this.eachColumn = function(func) {
		Ext.each(this.grid.getColumnModel().config, func, this);
	}
	// Returns HTML ID of element containing filter div
    this.getFilterDivId = function(columnId) {
		return this.grid.id + '-filter-' + columnId;
    }
    this.resizeSp = function(column, newColumnWidth) {
		//var col = cm.getColumnById(cm.getColumnId(colIndex));
    	if(column.sp)
		column.sp.setWidth(newColumnWidth);		
	}
    this.resizeAllSp = function() {
	    var cm = this.grid.getColumnModel();	    
	    this.eachColumn(function(col, i) {
	      this.resizeSp(col, cm.getColumnWidth(i));
	    });
	}
    this.renderFields = function() {    	
        this.eachColumn(function(col) {
          var filterDiv = Ext.get(this.getFilterDivId(col.id));          
          var filterMenu = this.getFilterField(col);
          this.grid.filters.menu = filterMenu;
          if(filterMenu){
              if (filterMenu.rendered) {
                filterDiv.appendChild(this.getFilterFieldDom(filterMenu));
              }
              else {               
                filterMenu.render(filterDiv);
              }
          }          
        });
    }
    // returns filter field of a column
    this.getFilterField = function(column) {
    	if(column.sp) return column.sp;
    	if(!column.dataIndex) return;
    	//return new Ext.form.TextField();
    	var filter = this.getFilterForColumn(column);
    	if(!filter) return column.sp = new Ext.Button({width:column.width,disabled:true});    	
    	var filterMenu = this.getFilterMenuForColumn(column);    	
    	var sp = new Ext.SplitButton({
			text: this.grid.filters.menuFilterText,
			menu: filterMenu,
			width:column.width,
			allowDepress:true,
			layout:'fit',
			arrowTooltip:'Click for filter options',
			enableToggle:true,
			tooltip:'Click to enable/disable this filter',
			disabled:filter?false:true
		});    	
    	
    	sp.on('toggle',function(btn,pressed){			
			if(filter.getValue() && filter.isActivatable()){
				filter.setActive(pressed);
			}else{
				btn.toggle(false);
			}		
    	},this);
    	sp.on("render",function(){
    		if(filter.active){
        		sp.toggle(true);
    		}
    	},this);
    	filter.on("activate",function(){
    		sp.toggle(true);
    	},this);
    	filter.on("deactivate",function(){
    		sp.toggle(false);
    	},this);
    	column.sp = sp;
    	return sp;
    }
    this.getFilterMenuForColumn = function(column){    	
    	var filter = this.grid.filters.filters.get(column.dataIndex);    	
    	if(filter){
    		return filter.menu;
    	}
    	return null;    	
    }
    this.getFilterForColumn = function(column){    	
    	var filter = this.grid.filters.filters.get(column.dataIndex);    	
    	return filter;
    }
	var view = this.grid.getView();	
    Ext.applyIf(view, { templates: {} });
    view.templates.header = this.applyTemplate();
    view.refresh(true);	   
	this.renderFields();
	
	cm.on("widthchange", function(cm, colIndex, newWidth){
		this.resizeAllSp();		
	}, this);
	cm.on("hiddenchange",function(cm, colIndex, hidden) {
	    var filterDiv = Ext.get(this.getFilterDivId(cm.getColumnId(colIndex)));
	    if (filterDiv) {
	      filterDiv.parent().dom.style.display = hidden ? 'none' : '';
	    }
	    this.resizeAllSp();
	},this);
	grid.on("columnresize",function(colIndex, newSize) {	    
	    this.resizeAllSp();
	},this);
	grid.on("resize",function() {	    
	    this.resizeAllSp();	   
	},this);	
	
	cm.on("columnmoved", function(cm,oldIndex,newIndex){		
		var view = this.grid.getView();
		Ext.applyIf(view, { templates: {} });
	    view.templates.header = this.applyTemplate();
	    view.refresh(true);		   
		this.renderFields();
	}, this);		
}