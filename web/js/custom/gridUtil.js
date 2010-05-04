var gridUtil = function(grid,config){
	config = Ext.decode(config);
	var proceed = function(){
		if(config.is_ajax){
			if(config.url == "#") return;
			Ext.Ajax.request({
				url: config.url,
				params:config.params,
				success: function(response){
					if(config.onsuccess == "RELOAD"){
						var store = grid.getStore();
						if(store.proxy.conn.disableCaching === false) {
							store.proxy.conn.disableCaching = true;
						}
						grid.getStore().reload();
						if(grid.getSelectionModel){
							var sm = grid.getSelectionModel();
							sm.clearSelections();
						}
					}
					if(config.onsuccess == "REDIRECT"){
						window.location.href = config.redirect;
					}
				}
			})
		}else{		
			window.location.href = config.url
		}
	}
		
	if(!grid){alert("Component not found"); return;}
	
	if(config.confirmMsg){
		Ext.Msg.show({
			title:'Confirmation required.',
			msg: config.confirmMsg,
			buttons: Ext.Msg.YESNOCANCEL,
			fn: function(btn,text){
				if (btn == 'yes'){
			        proceed();
			    }
			},
			icon: Ext.MessageBox.QUESTION
		})
	}else{
		proceed();
	}
	
}