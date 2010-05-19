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
	task:null,
	lastTaskPercentValue:0,

	start : function(external) {
		this.external=external;
		//if(Ext.isIE)
		//{
			this.external.percentText=this.external.getPercentText({percent:'0'});
			this.external.createMsg({title:'Waiting...',msg:'Waiting for server response !',percent:'0'});
			this.task = {
			    run: function(){
			        this.external.msg.updateProgress(this.lastTaskPercentValue,'0%','Waiting for server response !');
			        this.lastTaskPercentValue=(this.lastTaskPercentValue==0)?100:0;
			    },
			    scope: this,
			    interval: 1000 //1 second
			}
			Ext.TaskMgr.start(this.task);
			
		//}
		//console.log(this.timeout);
		Ext.Ajax.timeout=this.timeout;
		this.request = Ext.Ajax.request({
			url:this.url,
			callback:this.requestCallback,
			scope:this,
			disableCaching:false
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
			var lr=new Array();
			//console.log('Lasts',lasts);
			for (i = 0; i < nbInfos; i++) {
				//console.log(lasts[i]);
				if (lasts[i] === "") { continue; }
				
				lr.push(eval("("+lasts[i]+")"));
			}
			//console.log('Objects',o);
			/*if(!Ext.isIE)
			{
				this.fireEvent("receive", new Array(o));				
			}
			else
			{*/
				this.fireEvent("receive", lr);
			/*}*/
		}
	},
		
	stop : function() {
		clearInterval(this._intervalId);
		this.request.conn.abort();
		Ext.Ajax.timeout=30000;
		Ext.TaskMgr.stop(this.task);
	}


});