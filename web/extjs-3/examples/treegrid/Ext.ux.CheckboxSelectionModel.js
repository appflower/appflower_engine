/**
 * @class Ext.ux.CheckboxSelectionModel
 * @extends Ext.grid.CheckboxSelectionModel
 * A custom selection model that renders a column of checkboxes that can be toggled to select or deselect rows.
 * @constructor
 * @param {Object} config The configuration options
 */
Ext.ux.CheckboxSelectionModel = Ext.extend(Ext.grid.CheckboxSelectionModel, {
    /**
     * @cfg {String} header Any valid text or HTML fragment to display in the header cell for the checkbox column
     * (defaults to '&lt;div class="x-grid3-hd-checker">&#160;&lt;/div>').  The default CSS class of 'x-grid3-hd-checker'
     * displays a checkbox in the header and provides support for automatic check all/none behavior on header click.
     * This string can be replaced by any valid HTML fragment, including a simple text string (e.g., 'Select Rows'), but
     * the automatic check all/none behavior will only work if the 'x-grid3-hd-checker' class is supplied.
     */
    header: '<div class="x-grid3-hd-checker" id="hd-checker">&#160;</div>',
    /**
     * @cfg {Number} width The default width in pixels of the checkbox column (defaults to 20).
     */
    width: 20,
    /**
     * @cfg {Boolean} sortable True if the checkbox column is sortable (defaults to false).
     */
    sortable: false,

    // private
    menuDisabled:true,
    fixed:true,
    dataIndex: '',
    id: 'checker',

    // private
    initEvents : function(){
        Ext.grid.CheckboxSelectionModel.superclass.initEvents.call(this);
        this.grid.on('render', function(){
            var view = this.grid.getView();
            view.mainBody.on('mousedown', this.onMouseDown, this);
            Ext.fly(view.innerHd).on('mousedown', this.onHdMouseDown, this);   
            
           //Ext.util.Observable.capture(view, function(e){console.info('VIEW:'+e);});		
           //Ext.util.Observable.capture(this.grid.store, function(e){console.info('STORE:'+e);});
		   //Ext.util.Observable.capture(this, function(e){console.log('SM:'+e);});
            
        }, this);
    },

    // private
    onMouseDown : function(e, t){
    	
    	if(e.button === 0 && t.className == 'x-grid3-row-checker'){ // Only fire if left-click
            e.stopEvent();            
            var target = e.getTarget('.x-grid3-row'),
				view = this.grid.getView(),
				index = target.rowIndex,
				store = this.grid.getStore(),
				record,parentRecord={};
            
				record = store.getAt(index);
				

			//console.info('Cliked on: ');							 			
			//console.info(record);
				
			if(target){            	
            	if(this.isSelected(index)){
                    this.myDeselectRow(record);
                    if(this.grid.tree && !this.grid.remoteLoad){
                    	
                    	/*
                    	 * Disabled the tree nodes selection for remoteLoad
                    	 */
	                    this.clearSelectionsFrom(record);
	                    this.clearSelectionsUpFrom(record);
                    }
                    
                }else{
                    this.selectRow(index, true);
                    if(this.grid.tree && !this.grid.remoteLoad){
                    	/*
                    	 * Disabled the tree nodes selection for remoteLoad
                    	 */
		                this.selectAllFrom(record);
		                this.selectAllUpFrom(record);
                    }
                }
            }
        }
    },

    // private
    onHdMouseDown : function(e, t){
        if(t.className == 'x-grid3-hd-checker'){
            e.stopEvent();
            var hd = Ext.fly(t.parentNode);            
            var isChecked = hd.hasClass('x-grid3-hd-checker-on');
            if(isChecked){
                hd.removeClass('x-grid3-hd-checker-on');
                this.clearSelections();
            }else{
                hd.addClass('x-grid3-hd-checker-on');
                this.selectAll();
            }
        }
    },
    getSelectionsJSON : function(fields)
    {
    	var selections=this.getSelections(),json_selections=new Array();
    	
    	for (i = 0, len = selections.length; i < len; i++) {
    		
    		if(fields)
    		{
    			var array=new Array();
    			
    			for (j=0;j<fields.length;j++)
    			{
    				array.push(selections[i].json[fields[j]]);
    			}    		
    			
    			json_selections.push(array);
    		}
    		else
    		{
    			json_selections.push(selections[i].json);
    		}
    	}
    	
    	return Ext.encode(json_selections);
    }
    ,
    /**
     * Selects all rows from parent record
     */
    selectAllFrom : function(record){
    	var view = this.grid.getView(),
			store = this.grid.getStore(),
			children;
			
		if(store.isLeafNode(record)) return;	
			
		//node is already expanded
    	if (store.isExpandedNode(record)) {
    		
    		//add selection on children
			if (store.isVisibleNode(record)) {
				children = store.getNodeChildren(record);
				
				if(children.length>0)
				{
					for (i = 0, len = children.length; i < len; i++) {
						child_index = store.indexOf(children[i]);
						this.selectRow(child_index, true);
						this.selectAllFrom(children[i]);
					}
				}
			}
		}
		//node is not expanded, expand it
    	else if(!this.grid.remoteLoad) {
			
			store.expandNode(record);
			children = store.getNodeChildren(record);

			if(children.length>0)
			{						
				for (i = 0, len = children.length; i < len; i++) {
					child_index = store.indexOf(children[i]);
					this.selectRow(child_index, true);
					this.selectAllFrom(children[i]);
				}
			}
			
			store.on('expandnode2', this.onStoreExpandNode, this);
		}
    },
    /**
    * override the function from TreeGrid.js, and then delete & re-register the default listener from TreeGrid.js
    */
    onStoreExpandNode : function(ds,rc){
    	var view = this.grid.getView(),
			store = this.grid.getStore();    	
    	if (ds.isVisibleNode(rc)) {
			children = ds.getNodeChildren(rc);

			if(children.length>0)
			{
				for (i = 0, len = children.length; i < len; i++) {
					child_index = ds.indexOf(children[i]);
					this.selectRow(child_index, true);
					this.selectAllFrom(children[i]);
				}
			}
		}
		
		store.un('expandnode2', this.onStoreExpandNode, this);
    },
    /**
     * Clears selection from parent record
     */
    clearSelectionsUpFrom : function(record){
    	var store = this.grid.getStore();
    	//if there is no selection in parent node
        var parentRecord=store.getNodeParent(record);
        
		if(parentRecord!=null&&!this.parentHasSelections(parentRecord))
		{
			this.myDeselectRow(parentRecord);
			this.clearSelectionsUpFrom(parentRecord);
		}
    },
    /**
     * Select all from parent record
     */
    selectAllUpFrom : function(record){    	
    	var store = this.grid.getStore();
    	//if there are all selected in parent node
        var parentRecord=store.getNodeParent(record);
        
		if(parentRecord!=null&&this.parentHasSelections(parentRecord))
		{
			this.selectRow(store.indexOf(parentRecord), true);
			this.selectAllUpFrom(parentRecord);
		}
    },
	 /**
     * Clears all selections from parent record
     */
    clearSelectionsFrom : function(record){
    	var view = this.grid.getView(),
			store = this.grid.getStore(),
			children;
			
		if(store.isLeafNode(record)) return;	
		
		if (store.isVisibleNode(record)) {
			children = store.getNodeChildren(record);
			
			if(children.length>0)
			{
				for (i = 0, len = children.length; i < len; i++) {
					this.myDeselectRow(children[i]);
					this.clearSelectionsFrom(children[i]);
				}
			}
		}
		
		//if there is no selection
		if (this.selections.length==0)
		{
			var t=Ext.get('hd-checker');
			var hd = Ext.fly(t.dom.parentNode);
			hd.removeClass('x-grid3-hd-checker-on');
		}
		
    },
    /**
    * find if parent node has any selected children
    */
    parentHasSelections : function(record,all)
    {
    	var view = this.grid.getView(),
			store = this.grid.getStore(),
			children,hasSelection;
			
		children = store.getNodeChildren(record);
		
		if(all)
		{
			hasSelection=true;
			if(children.length>0)
			{						
				for (i = 0, len = children.length; i < len; i++) {
					child_index = store.indexOf(children[i]);
					if(!this.isSelected(child_index))
					{
						hasSelection=false;
						break;
					}
				}
			}
		}
		else
		{
			hasSelection=false;
			if(children.length>0)
			{						
				for (i = 0, len = children.length; i < len; i++) {
					child_index = store.indexOf(children[i]);
					if(this.isSelected(child_index))
					{
						hasSelection=true;
						break;
					}
				}
			}		
		}	
		
		return hasSelection;
    },
    /**
     * Selects a row.
     * @param {Number} row The index of the row to select
     * @param {Boolean} keepExisting (optional) True to keep existing selections
     */
    selectRow : function(index, keepExisting, preventViewNotify){    	
    	if(this.locked || (index < 0 || index >= this.grid.store.getCount()) || this.isSelected(index)) return;
        var r = this.grid.store.getAt(index);
        
        if(r && this.fireEvent("beforerowselect", this, index, keepExisting, r) !== false){
           /* if(!keepExisting || this.singleSelect){
                this.clearSelections();
            }*/
            this.selections.add(r);
            
            this.last = this.lastActive = index;
            if(!preventViewNotify){
                this.grid.getView().onRowSelect(index);
            }
            
            /**
            * if rows has _selected attribute, then select them after html render
            */
            if(this.grid.select&&r.data[this.grid.store.selected_field_name])
            {
	            this.grid.getView().on('rowsinserted', function(){
	            	            	
		        	//this.grid.getView().onRowSelect(index);
	            	
	            }, this);
            }
                        
            this.fireEvent("rowselect", this, index, r);
            this.fireEvent("selectionchange", this);
        }
    },
    myDeselectRow : function(record)
    {
    	var view = this.grid.getView(),
			store = this.grid.getStore();
    	
		var index = store.indexOf(record);	
			
    	this.selections.remove(record);
    	record.data[this.grid.store.selected_field_name]=false;
    	view.removeRowClass(index, "x-grid3-row-selected");	
    	
    	this.fireEvent("rowdeselect", this, index, record);
        this.fireEvent("selectionchange", this);
    },
    // private
    renderer : function(v, p, record){
    	return '<div class="x-grid3-row-checker">&#160;</div>';
    }
});