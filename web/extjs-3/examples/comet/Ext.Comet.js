/**
 * immune custom comet like script
 * @author radu
 */

Ext.Comet = function(config) {
	this.events = {
		receive:true
	};
	Ext.apply(this, config);
};

Ext.extend(Ext.Comet, Ext.util.Observable, {

	id : 'comet',
	_intervalId : null,
	request : null,
	interval : 200, //in miliseconds
	lastTextPosition: 0,
	autoReconnect : false,
	reconnectIntervalOnFailure : 5000,
	url : null,
	timeout : 30000, //in miliseconds
	external: null,

	start : function(external) {
		this.external=external;
		if(Ext.isIE)
		{
			this.external.percentText=this.external.getPercentText({percent:'0'});
			this.external.createMsg({title:'Waiting...',msg:'Waiting for server response !',percent:'0'});
		}
		Ext.Ajax.timeout=this.timeout;
		this.request = Ext.Ajax.request({
			url:this.url,
			callback:this.requestCallback,
			scope:this
		});
		this._intervalId = setInterval(this.watch.createDelegate(this),
			this.interval);
	},

	requestCallback : function(o, success, r) {
		//console.log("End :", o, success, r);
		this.watch();
		this.stop();
		if (this.autoReconnect) {
			if (success) {
				this.start();
			} else {
				this.start.defer(this.reconnectIntervalOnFailure, this);
			}
		}
	},

	watch : function() {
		if(typeof(this.request.conn)!="unknown"&&typeof(this.request.conn.responseText)!="unknown")
		{
			var text = this.request.conn.responseText;
			if (text.length == this.lastTextPosition) { return; }
			var last = text.substring(this.lastTextPosition);
			this.lastTextPosition = text.length;
			var lasts = last.split("\n");
			var nbInfos = lasts.length;
			//console.log(lasts);
			for (i = 0; i < nbInfos; i++) {
				if (lasts[i] === "") { continue; }
				o = "";
				try {
					o = eval("("+lasts[i]+")");
					if (!o) { o = lasts[i]; }
				} catch(ex) {
					o = lasts[i];
				}
			}
			
			if(!Ext.isIE)
			{
				this.fireEvent("receive", new Array(o));				
			}
			else
			{
				this.fireEvent("receive", lasts);
			}
		}
	},
		
	stop : function() {
		clearInterval(this._intervalId);
		this.request.conn.abort();
		Ext.Ajax.timeout=30000;
	}


});