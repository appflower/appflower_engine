Ext.ux.NotificationMgr = {
    positions: [],
    heights: []
};
Ext.ux.Notification = function(config){
	this.config = Ext.apply({},config); 
}
Ext.ux.Notification = Ext.extend(Ext.Window, {
	notificationType:'ERROR',
	initComponent : function(){
		Ext.apply(this, {
			//iconCls: this.iconCls || 'icon-notification-info',
			autoHeight: true,
			closeAction:'close',			
			plain: false,
			shadow:false,
			draggable: false,
			bodyStyle: 'text-align:left;padding:10px;',
			resizable: false
		});
		if(this.autoDestroy){
			this.task = new Ext.util.DelayedTask(this.close, this);
		}else{
			this.closable = true;
		}
		Ext.ux.Notification.superclass.initComponent.call(this);
    },
	setMessage : function(msg){
		this.body.update(msg);
	},
	/**
	* Comment this section for now. Since for window when setting iconClass frame is checked, 
	* which is currently overridden to avoid, that may result double icons in window title. 
	* Whenever we decide not to override that, we may want to keep this method.
	* Because I saw that the overriden section is refactored in latest version of extjs.
	 
	setTitle : function(title, iconCls){
        Ext.ux.Notification.superclass.setTitle.call(this, title, iconCls||this.iconCls);
    },*/
    onRender : function(ct, position) {
		Ext.ux.Notification.superclass.onRender.call(this, ct, position);
	},
	onDestroy : function(){
		Ext.ux.NotificationMgr.positions.remove(this.pos);
		Ext.ux.Notification.superclass.onDestroy.call(this);
	},
	afterShow : function(){
		Ext.ux.Notification.superclass.afterShow.call(this);
		this.on('move', function(){
			Ext.ux.NotificationMgr.positions.remove(this.pos);
			if(this.autoDestroy){
				this.task.cancel();
			}
		}, this);
		if(this.autoDestroy){
			this.task.delay(this.hideDelay || 5000);
		}
	},
	animShow : function(){			
		this.pos = 0;
		var h = 0;
		while(Ext.ux.NotificationMgr.positions.indexOf(this.pos)>-1){
			h+=Ext.ux.NotificationMgr.heights[this.pos];
			this.pos++;			
		}
		Ext.ux.NotificationMgr.positions.push(this.pos);		
		Ext.ux.NotificationMgr.heights[this.pos] = this.getSize().height+10;
		
		this.el.alignTo(this.animateTarget || document, "br-tr", [ -1, -1-(h) ]);
		this.el.slideIn('b', {
			duration: .7
			, callback: this.afterShow
			, scope: this
		});
	},
	animHide : function(){
		Ext.ux.NotificationMgr.positions.remove(this.pos);
		if(this.el)
		this.el.ghost("b", {
			duration: 1
			, remove: false
		});		
	},
	start: function(url){
		new Ext.ux.Notification.start(url);
	}
});
Ext.ns("Ext.ux.Notification");
Ext.ux.Notification.Base = function(){};
Ext.ux.Notification.Base = Ext.extend(Ext.util.Observable,{
	showNotification:  function(config) {
	    var win = new Ext.ux.Notification(Ext.apply({
	    	animateTarget: this.notificationEl
			, autoDestroy: false					
			,notificationType:'ERROR'
	    }, config));
	    if(win.notificationType == "ERROR"){
	    	win.iconCls = 'icon-notification-error';
	    	win.bodyStyle += 'background-color:#fddcdc;';
	    }
	    if(win.notificationType == "WARNING"){
	    	win.iconCls = 'icon-notification-warning';
	    	win.bodyStyle += 'background-color:#fefcc7;';
	    }
	    if(win.notificationType == "INFO"){
	    	win.iconCls = 'icon-notification-info';
	    	win.bodyStyle += 'background-color:#fff;';
	    }
	    win.bodyStyle += 'padding-bottom:20px;';	    
	    win.show();	   
	    return win;
	},
	hideNotification: function(win, delay) {
	    if (win) {
	      (function() { 
	        win.animHide();	        
	      }).defer(delay || 3000);
	    }
	},
	growl: function(TITLE,MESSAGE,TYPE,DURATION){			
		var n = this.showNotification({
			width:300,	
			notificationType:TYPE,
			title:TITLE,
			html:MESSAGE
		});
		this.hideNotification(n, DURATION*1000);			
	}
})
Ext.ux.InstantNotification = Ext.extend(Ext.ux.Notification.Base,{
	constructor: function(config) {	
		Ext.ux.InstantNotification.superclass.constructor.call(this, config);		
		this.notificationEl = Ext.get("growl-notification-el")?Ext.get("growl-notification-el"):Ext.DomHelper.append(Ext.getBody(),{tag:'div',id:'growl-notification-el',style:'width:300px;position:absolute;bottom:0;right:0'});
		this.growl(config.title,config.message,"INFO",10)
	}
});

Ext.ux.Notification.start = function(url){	
	Ext.onReady(function(){	
		var notificationEl = Ext.get("growl-notification-el")?Ext.get("growl-notification-el"):Ext.DomHelper.append(Ext.getBody(),{tag:'div',id:'growl-notification-el',style:'width:300px;position:absolute;bottom:0;right:0'});			
		this.showNotification = function(config) {
		    var win = new Ext.ux.Notification(Ext.apply({
		    	animateTarget: notificationEl
				, autoDestroy: false					
				,notificationType:'ERROR'
		    }, config));
		    if(win.notificationType == "ERROR"){
		    	win.iconCls = 'icon-notification-error';
		    	win.bodyStyle += 'background-color:#fddcdc;';
		    }
		    if(win.notificationType == "WARNING"){
		    	win.iconCls = 'icon-notification-warning';
		    	win.bodyStyle += 'background-color:#fefcc7;';
		    }
		    if(win.notificationType == "INFO"){
		    	win.iconCls = 'icon-notification-info';
		    	win.bodyStyle += 'background-color:#fff;';
		    }
		    win.bodyStyle += 'padding-bottom:20px;';
		    win.show();
	
		    return win;
		};
		this.hideNotification = function(win, delay) {
		    if (win) {
		      (function() { 
		        win.animHide();	        
		      }).defer(delay || 3000);
		    }
		};
		this.growl = function(TITLE,MESSAGE,TYPE,DURATION){			
			var n = this.showNotification({
				width:300,	
				notificationType:TYPE,
				title:TITLE,
				html:MESSAGE
			});
			this.hideNotification(n, DURATION*1000);			
		}		
		this.sendRequest = {
			url: url,
			success: function(response){
				var obj = Ext.decode(response.responseText);
				if(obj){
					var count = 0;
					var max_duration = 0;
					var defer = 500;
					Ext.each(obj.data,function(d){
						count++;
						if(d.duration > max_duration) max_duration = d.duration;
						var arr = [];
						arr.push(d.title);
						arr.push(d.message);
						arr.push(d.type);
						arr.push(d.duration);
						this.growl.defer(defer*count,this,arr);						
					},this)					
					new Ext.util.DelayedTask(function(){
						this.ajax.request(Ext.apply(this.sendRequest,{
							params:{
								limit:(3-Ext.ux.NotificationMgr.positions.length)
							}
						}))
					}).delay(count*defer+10000);
				}
			}
		}
		this.ajax = Ext.Ajax;
		this.ajax.timeout = 0;  
		this.ajax.request(this.sendRequest);
		this.ajax.on("requestcomplete",function(con,response,options){
			
		},this)
		function newExcitingAlerts() {
		    var oldTitle = document.title;
		    var msg = "New Notification !! " +oldTitle;
		    var timeoutId = setInterval(function() {
		    	//window.focus();
		        document.title = document.title == msg ? ' ' : msg;
		        
		    }, 1000);
		    window.onmousemove = function() {
		        clearInterval(timeoutId);
		        document.title = oldTitle;
		        window.onmousemove = null;
		    };
		}
		//newExcitingAlerts()
	})
}

