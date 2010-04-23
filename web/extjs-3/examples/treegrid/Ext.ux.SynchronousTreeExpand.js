Ext.ns("Ext.ux");
Ext.ux.SynchronousTreeExpand = function(config){	
	var store,sm,ds,record;
	var counter = 0;
	var loading = false;
	var expandedNodes = [];
	var mask;
	var moveFurther = function(){
		while (true) {
			if(counter >= ds.getCount()) return null;		
			var record = store.getAt(counter);
			counter++;
			if(!store.isLeafNode(record) && record.get("name").match(/<font color=red>&darr;<\/font>/)){				
				return record;
			}
		}
	}
	config.grid.store.on('load',function(){		
		if(config.grid.remoteLoad && config.grid.select){	
			mask = new Ext.LoadMask(Ext.getBody(), {msg:"Retrieving data..."});
			mask.show();
			
			store = config.grid.getStore();
			sm = config.grid.getSelectionModel();
			ds = config.grid.getView().ds;			
			
			if(ds.getCount()){
				var record = moveFurther();
				if(record){
					store.expandNode(record);	
				}else{
					mask.hide();
				}
			}
		}	
	});	
}
