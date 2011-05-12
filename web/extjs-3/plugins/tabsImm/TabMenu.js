/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */


// Very simple plugin for adding a close context menu to tabs

Ext.ux.TabMenu = function(){
    var tabs, menu, ctxItem;
    this.init = function(tp){
        tabs = tp;
        tabs.on('contextmenu', onContextMenu);
    }

    function onContextMenu(ts, item, e){
        if(!menu){ // create context menu on first right click
            menu = new Ext.menu.Menu([{
                id: tabs.id + '-save',
                text: 'Save',
                handler : function(){
                     Ext.Ajax.request({

			          url: ctxItem.fileContentUrl
			
			          , method:'post'
			          
			          ,params: {
			          	'file':ctxItem.file,
			          	'code':ctxItem.getCode()			          	
			          }
			
			          , success:function(response, options){
			
			            Ext.Msg.alert("","The file '"+ctxItem.file+"' was saved !");
			            
			          }
			          
			          ,	failure: function() {
						Ext.Msg.alert("","The server can't save '"+ctxItem.file+"' !");
					  }
			
			        });
                }
            },{
                id: tabs.id + '-close',
                text: 'Close Tab',
                handler : function(){
                    tabs.remove(ctxItem);
                }
            },{
                id: tabs.id + '-close-others',
                text: 'Close Other Tabs',
                handler : function(){
                    tabs.items.each(function(item){
                        if(item.closable && item != ctxItem){
                            tabs.remove(item);
                        }
                    });
                }
            }]);
        }
        ctxItem = item;
        var items = menu.items;
        items.get(tabs.id + '-close').setDisabled(!item.closable);
        var disableOthers = true;
        tabs.items.each(function(){
            if(this != item && this.closable){
                disableOthers = false;
                return false;
            }
        });
        
        if(item.title=='No file')
        {
        	items.get(tabs.id + '-save').setDisabled(true);
        }
        else{
        	items.get(tabs.id + '-save').setDisabled(false);
        }
        
        items.get(tabs.id + '-close-others').setDisabled(disableOthers);
        menu.showAt(e.getPoint());
    }
};