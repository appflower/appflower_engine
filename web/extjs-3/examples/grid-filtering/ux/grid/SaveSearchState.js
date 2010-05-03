/**
* Ext.ux.SaveSearchState
* @author: Prakash Paudel
*
* Get the current search state of the grid filter
*/
Ext.ns("Ext.ux");
Ext.ux.SaveSearchState = function(grid){	
    var filtersObj = grid.filters;    
    if(!filtersObj) return;
    var p = new Ext.state.Provider();
    this.saveUrl = '/appFlower/saveFilter';
    this.listUrl = '/appFlower/listFilter';
    this.removeUrl = '/appFlower/removeFilter';
    this.save = function(){
    	var state = [];   
    	var list = this.list;
		filtersObj.filters.each(function(filter){
			if(filter.active){
				var obj = {					
					dataIndex: filter.dataIndex,
					value: p.encodeValue(filter.getValue())
				}
				state.push(obj);  
			}
		});
		if(state.length < 1){
			Ext.Msg.alert("Error","Filter criteria is empty.");
			return;
		}
		Ext.Msg.prompt("Name", "Please input filter name:", function(btn, text){
		    if (btn == "ok"){
		    	if(text == ""){
		    		Ext.Msg.alert("Error","Filter name is required.");
					return;
		    	}
		    	var json = Ext.util.JSON.encode(state);
		    	Ext.Ajax.request({
		    		method:"POST",
		    		url:this.saveUrl,
		    		success:function(r){
		    			var json = Ext.util.JSON.decode(r.responseText);
		    			Ext.Msg.show({
	    				   title:json.success?"Success":"Failed",
	    				   msg: json.message,
	    				   buttons: Ext.Msg.OK,    				   
	    				   icon: json.success?Ext.MessageBox.INFO:Ext.MessageBox.ERROR
	    				});
		    			if(list && list.getStore()){
		    				list.getStore().reload();
		    			}
		    		},
		    		failure:function(r){
		    			
		    		},
		    		params:{
		    			path:grid.path,
		    			name: text,
		    			state:json,
		    			title:grid.name?grid.name:grid.path
		    		}
		    	})
		    }
		},this)
		
		
    }
    this.viewSavedList = function(){
    	var path = grid.name?grid.name:grid.path;
    	var store = new Ext.data.JsonStore({
            fields: ['id','name','filter'],
            url:this.listUrl,
            root:'rows',
            autoLoad:true,
            baseParams:{
    			path: path
    		}
        });    	
    	var list = new Ext.grid.GridPanel({
    		store:store,
    		columns: [
    		    new Ext.grid.RowNumberer(),
	            {id:'name',header: 'Name', dataIndex: 'name',menuDisabled:true},
	            {id:'action',header:'Action',dataIndex:null, width:50,menuDisabled:true,
	            	renderer:function(){
	            		//return '<a href="#"><img class="restore-saved-filter-button" src="/images/famfamfam/connect.png" qtip="Apply this filter to grid"/></a>&nbsp;&nbsp;<a href="#"><img class="remove-saved-filter-button" src="/images/famfamfam/cross.png" qtip="Remove this filter"/></a>'
	            		return '<a href="#"><img class="remove-saved-filter-button" src="/images/famfamfam/cross.png" qtip="Remove this filter"/></a>'
	            	}
	            }
	        ],
	        tbar:[{	        	
	            text:'Save current filter',
	            icon:'/images/famfamfam/disk.png',
	            handler: function(){		        	
					var filters = grid.filters;
					if(!filters) return;							
					var saveFilter = Ext.ux.SaveSearchState(grid);
					saveFilter.save();
	        	}
	        },'-',{
	        	text:'Clear current filter',
	        	icon:'/images/famfamfam/drink_empty.png',
	        	handler: function(){
		        	var filters = grid.filters;
					if(!filters) return;
					filters.clearFilters();
	        	}
	        }],
	        autoExpandColumn: 'name',
	        loadMask:true
    	})
    	this.list = list;
    	list.on("cellclick",function(grid,rowIndex,columnIndex,e){
    		var target = e.getTarget();
    		if(target.className == "ux-grid-filter-apply"){
    			var record = grid.getStore().getAt(rowIndex);                
                var filterJson = record.get("filter");
                var keyword = record.get("name");
                this.restore(filterJson,keyword);                
    		}
    		if(target.className == "restore-saved-filter-button"){
    			var record = grid.getStore().getAt(rowIndex);                
                var filterJson = record.get("filter");
                var keyword = record.get("name");
                this.restore(filterJson,keyword);
    		}
    		if(target.className == "remove-saved-filter-button"){
    			var record = grid.getStore().getAt(rowIndex);                
                var id = record.get("id");
                Ext.Ajax.request({
		    		method:"POST",
		    		url:this.removeUrl,
		    		success:function(r){
		    			var json = Ext.util.JSON.decode(r.responseText);
		    			if(json.success){
		    				var row = grid.getView().getRow(rowIndex);                
		                    Ext.get(row).fadeOut({
		                        endOpacity: 0,
		                        easing: 'easeOut',
		                        duration: .5,
		                        remove: true,
		                        useDisplay: false,
		                        callback: function(){
			                    	grid.getStore().remove(record);
				                    grid.getView().refresh();
		                    	}
		                    });
		                    
		    			}
		    		},
		    		failure:function(r){
		    			
		    		},
		    		params:{
		    			id: id
		    		}
		    	})		    	
    		}
    	},this)
    	var win = new Ext.Window({    				
    		width:500,
    		height:300,
    		layout:'fit',
    		title:'Saved filters for '+path,
    		items: list,
    		closeAction:'close',
    		maximizable:true,
    		collapsible:true
    	})
    	
    	win.show();    
    }
    this.restore = function(json,keyword){
    	if(!grid.originalTitle){
    		grid.originalTitle = grid.title;
    	}
    	grid.setTitle(grid.originalTitle+": <font color=red>(Filtered by keyword: '"+keyword.replace(/<\S[^><]*>/g, "")+"'</font>)");    	
    	filtersObj.clearFilters();
    	filtersObj.filters.each(function(filter){	
    		var json_array = Ext.util.JSON.decode(json);    		
    		for(var i=0;i<json_array.length;i++){
    			if(json_array[i].dataIndex == filter.dataIndex){
    				var val = json_array[i].value;
    				val = p.decodeValue(val);
    				filter.setActive(false);
    				filter.setValue(val);
    				filter.setActive(true);
    			}
    		}
						
		});    	   	
    }   	
    return this;
}

