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

	start : function() {
		this.request = Ext.Ajax.request({
			url:this.url,
			timeout:this.timeout,
			callback:this.requestCallback,
			scope:this
		});
		this._intervalId = setInterval(this.watch.createDelegate(this),
			this.interval);
	},

	requestCallback : function(o, success, r) {
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
			var steps = new Array();
			for (i = 0; i < nbInfos; i++) {
				if (lasts[i] === "") { continue; }
				steps.push(Ext.util.JSON.decode(lasts[i]));
			}
			
			this.fireEvent("receive", steps);
		}
	},
		
	stop : function() {
		clearInterval(this._intervalId);
		this.request.conn.abort();
	}


});
