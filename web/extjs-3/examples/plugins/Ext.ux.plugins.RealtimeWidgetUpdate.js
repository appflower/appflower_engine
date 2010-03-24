/*
* Extended Panel
* Ability to update the data of widget on realtime
* @author: Prakash Paudel
*/
Ext.namespace('Ext.ux.plugins');
/*
 * Realtime widgets update.
 * Config variables:
 *     startInitial ... whether to start the autoreload automaticaly
 *     reloadToolVisible
 *     rate ... reload delay in milliseconds
 *
 * @author: prakash paudel
 */
Ext.ns("Ext.ux.plugins");
Ext.ux.plugins.RealtimeWidgetUpdate = function(config){
	Ext.apply(this,config);
}
Ext.extend(Ext.ux.plugins.RealtimeWidgetUpdate, Ext.util.Observable,{		
	init: function(widget){
		var me = this;
		var ajax;
		var numRequests = 0;  // The number of requests in the air.
		var autoReload;
		var stopTool = {
			id: "stop-reload",
			hidden:this.startInitial?false:true,
			handler: function(){
				autoReload.started = false;
				widget.tools['stop-reload'].setVisible(false);
				widget.tools['start-reload'].setVisible(true);
				if(ajax) Ext.Ajax.abort(ajax);
				ajax = null;
			},
			scope:widget,
			qtip:"Pause auto widget reload."
		};
		var startTool = {
				id: "start-reload",
				hidden:this.startInitial?true:false,
				handler: function(){
					autoReload.started = true;
					widget.tools['start-reload'].setVisible(false);
					widget.tools['stop-reload'].setVisible(true);
					this.reloadNow();
				},
				scope:widget,
				qtip:"Start auto widget reload."
		};
		widget.tools = widget.tools || [];
		if(this.reloadToolVisible){
			for(var i=0, len=widget.tools.length;i<len;i++) {
	            if (widget.tools[i].id=='start-reload') return;
	        }
			widget.tools.reverse();			
			widget.tools[widget.tools.length] = stopTool;
			widget.tools[widget.tools.length] = startTool;
			widget.tools.reverse();
		}		

		autoReload = {
			started: me.startInitial,
			isEnabled: function(){
				return autoReload.started;
			},
			task: new Ext.util.DelayedTask(function(){
				if(autoReload.isEnabled()) {
					widget.reloadNow();
				}
			}),
			beforeLoad: function() {
				numRequests += 1;
			},
			afterLoad: function() {
				ajax = null;
				numRequests -= 1;
				if(autoReload.isEnabled()) {
					autoReload.task.delay(me.rate);
				}
			},
			afterFailure: function() {
				// Wait a bit before hammering the server again.
				window.setTimeout(autoReload.afterLoad, 10000);
			}
		};
		if(widget.getStore) {
			widget.getStore().on('beforeload', autoReload.beforeLoad);
			widget.getStore().on('load', autoReload.afterLoad);
			widget.getStore().on('exception', autoReload.afterFailure);
		}

		Ext.apply(widget,{
			gridReload: function(){
				if(numRequests <= 0){
					widget.disableLoadMask = true;
					if(widget.loadMask && widget.loadMask.disable) {
						widget.loadMask.disable();
					}

					var store = widget.getStore();
					if(store.proxy.conn.disableCaching === false) {
						store.proxy.conn.disableCaching = true;
					}
					if(store.proxy.setUrl) {
						// Gives a signal that waiting for new news is OK.
						var url = store.proxy.url;
						if(url.indexOf('?') > 0){
							url = url + '&_wait=1';
						} else {
							url = url + '?_wait=1';
						}
						store.proxy.setUrl(url);
					}
					store.reload();
				}
			},
			htmlReload: function(){
				var owner = this;
				if(!ajax){
			       ajax = Ext.Ajax.request({
			    	   url:me.url,
			    	   method:'POST',
			    	   params:Ext.apply(me.requestParams,{
			    	   		reload:'true'
			       	   }),
			       	   success: owner.onSuccess,
			       	   failure: function(){
							autoReload.afterFailure();
			       	   }
			       });
    		   }
			},
			reloadNow: function(){
				var owner = this;
				if(widget.getStore){
					owner.gridReload();
    		   }else {
    			    owner.htmlReload();
    		   }
			},
			onHide: function(){
				this.reloadNow();
			},
			onSuccess: function(r){
			   autoReload.afterLoad();
			   ajax = null;
			   var rc = null;
			   try{rc=new RegExp('^("(\\\\.|[^"\\\\\\n\\r])*?"|[,:{}\\[\\]0-9.\\-+Eaeflnr-u \\n\\r\\t])+?$')}
			   catch(z){rc=/^(true|false|null|\[.*\]|\{.*\}|".*"|\d+|\d+\.\d+)$/}
			   if(!rc.test(r.responseText)) { return;}
			   var json = Ext.util.JSON.decode(r.responseText);				       		  
			   if(json.html && json.js){
				   var html = json.html;
				   var js = json.js;					       		  
				   if(widget && widget.items && widget.items.items[0] && widget.items.items[0].getEl() && widget.items.items[0].getEl().dom){
					   widget.items.items[0].getEl().dom.innerHTML = html;
					 if(js) eval(js);
				   } 					       		
			   }else if(json.html){
				   var html = json.html;					       		   				       		   
				   if(widget && widget.items && widget.items.items[0] && widget.items.items[0].getEl() && widget.items.items[0].getEl().dom){
					   // Restoring cheated height for the div if any
					   var div = document.createElement("div");
					   div.innerHTML = html;					   
					   if(div.firstChild.onmouseover){
						   if(widget.items.items[0].getEl().dom.firstChild){
							   div.firstChild.style.height = widget.items.items[0].getEl().dom.firstChild.style.height;
							   html = div.innerHTML;
						   }
					   }
					   //....................................
					   widget.items.items[0].getEl().dom.innerHTML = html;					       			
				   }else if(widget && widget.getEl() && widget.getEl()){
					   // For the html texts display like in diagnostics.......
					   // Compatible with cheats used in html panel............
					   // Divisions hirarchy must match..........
					   var el = widget.getEl().dom;				       			   
					   if(el){
						   var p = el.firstChild.childNodes[0];
						   if(p){
							   p.innerHTML = html;
						   }
					   }
				   }
			   }
			}
		});
	}
});
