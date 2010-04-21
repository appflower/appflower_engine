Ext.ns("Ext.ux");
Ext.ux.SynchronousTreeExpand = function(config){	
	var store,sm,ds,record;
	var counter = -1;
	var loading = false;
	var mask;
	var task = {
		run: function(){		
			if(!loading && ds){
				record = ds.getAt(counter);				
				if(!store.isLeafNode(record) && record.get("name").match(/<font color=red>&darr;<\/font>/)){			
					loading=true;
					store.expandNode(record);				
				}else{				
					loading = false;
					counter++;	
					if(counter >= ds.getCount()){
						Ext.TaskMgr.stop(task);
						mask.hide();
					}
				}
			}
		},
		interval:100
	}
	config.grid.store.on('load',function(){
		if(config.grid.remoteLoad){			
			store = config.grid.getStore();
			sm = config.grid.getSelectionModel();
			ds = config.grid.getView().ds;			
			counter++;	
			if(counter >= ds.getCount()){
				Ext.TaskMgr.stop(task);
				mask.hide();
			}
			loading = false;			
		}	
	});		
	
	if(config.grid.remoteLoad){
		Ext.TaskMgr.start(task);
		mask = new Ext.LoadMask(Ext.getBody(), {msg:"Retrieving data..."});
		mask.show();
	}
}