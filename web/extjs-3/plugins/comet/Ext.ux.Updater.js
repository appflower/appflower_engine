/**
 * immune custom updater using some comet like script
 * @author radu
 */

Ext.ux.Updater = function(config) {
	Ext.apply(this, config);
};

Ext.extend(Ext.ux.Updater, Ext.util.Observable, {
	url:null,
	interval:200, //in miliseconds
	timeout:300000, //in miliseconds
	width:250,
	errors:{
		noStep: null,
		title:null
	},
	
	start : function()
	{
		this.comet = new Ext.Comet({url:this.url, interval:this.interval, timeout:this.timeout});
		this.comet.on("receive", this.onReceive, this);
		this.comet.start();
		this.updateMsg({title:'Waiting...',msg:'Waiting for server response !',percent:'0'});
	},
			
	onReceive : function(r)
	{
		var nbInfos = r.length;
		for (i = 0; i < nbInfos; i++) {
			if(!r[i].step)
			{
				this.errors.noStep = this.errors.noStep || 'There is an error in the Updater! No step defined !';
				
				this.createErrorMsg({msg:this.errors.noStep});
                continue;
			}
					
			if(r[i].step=='start')
			{
				this.updateMsg(r[i]);	
			}
			else if(r[i].step=='in')
			{
				this.updateMsg(r[i]);
			}
			else if(r[i].step=='error')
			{
				this.createErrorMsg(r[i]);	
			}
			else if(r[i].step=='stop')
			{
				this.hideMsg(r[i]);
			}
		}
	},
	
	createMsg : function(r)
	{		
		var percentText=this.getPercentText(r);
		var percentValue=this.getPercentValue(r);
		this.msg=Ext.Msg.show({
			title: r.title,
			msg: r.msg,
			buttons: false,
			progress: true,
			closable: false,
			minWidth: this.width,
			progressText: percentText
		});
		this.msg.updateProgress(percentValue, percentText, r.msg);
	},
	
	createErrorMsg : function(r)
	{
		if(this.msg)
		{
			this.msg.hide();
			this.msg = null;
		}
		
		if(r.msg)
		{
			this.errors.title = this.errors.title || 'Error';
			Ext.Msg.alert(this.errors.title, r.msg);
		}		
		
	},
	
	hideMsg : function(r)
	{
		this.updateMsg(r);
		
		var updater = this;
		r.hideAfter = (r.hideAfter*1000) || 2000;
		window.setTimeout(function() {
			updater.msg.hide();
			updater.msg = null;
			if (r.redirect) {
				afApp.load(r.redirect);
			}
		}, r.hideAfter);
	},
	
	updateMsg : function(r)
	{
		if(!this.msg) {
			this.createMsg(r);
		} else {
			var percentText=this.getPercentText(r);
			var percentValue=this.getPercentValue(r);
			
			if(percentValue==1) {
				percentText+=' done';
			}
			
			this.msg.updateProgress(percentValue,percentText,r.msg);
			if(r.title) {
				this.msg.getDialog().setTitle(r.title);
			}
		}
	},
	
	getPercentText : function(r)
	{
		return r.percent+' %';
	},
	
	getPercentValue : function(r)
	{
		return r.percent/100;
	}

});
