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
	noStep:false,
	width:250,
	errors:{
		noStep: null,
		title:null
	},
	
	start : function()
	{
		this.comet = new Ext.Comet({url:this.url, interval:this.interval, timeout:this.timeout});
		this.comet.on("receive", this.onReceive, this);
		this.comet.start(this);
	},
			
	onReceive : function(r)
	{
		//console.log('Updater:',r);
		//console.log(r.msg);		
		
		var nbInfos = r.length;
		var delay=0;
		for (i = 0; i < nbInfos; i++) {
			if (r[i] === "") { continue; }
			
			try {
				r[i] = eval("("+r[i]+")");
				if (!r[i]) { r[i] = r[i]; }
			} catch(ex) {
				r[i] = r[i];
			}		
		
			delay+=2000;
									
			if(!r[i].step)
			{
				this.errors.noStep = this.errors.noStep || 'There is an error in the Updater! No step defined !';
				
				this.createErrorMsg({msg:this.errors.noStep});
				
				this.noStep=true;
			}
					
			if(Ext.isIE)
			{
				if(!this.noStep) 
				{
					if(r[i].step=='start')
					{
						this.createMsg.defer(delay,this,[r[i]]);	
					}
					else if(r[i].step=='in')
					{
						this.updateMsg.defer(delay,this,[r[i]]);
					}
					else if(r[i].step=='error')
					{
						this.createErrorMsg.defer(delay,this,[r[i]]);
					}
					else if(r[i].step=='stop')
					{
						this.hideMsg.defer(delay,this,[r[i]]);
					}
				}
			}
			else
			{
				if(!this.noStep) 
				{
					if(r[i].step=='start')
					{
						this.createMsg(r[i]);	
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
						this.percentText+=' done';
						
						this.hideMsg(r[i]);
					}
				}
			}
		}
	},
	
	createMsg : function(r)
	{		
		if(r.title&&r.msg&&r.percent)
		{
			this.percentText=this.getPercentText(r);
			this.percentValue=this.getPercentValue(r);
			
			Ext.MessageBox.minProgressWidth=this.width;
			
			this.msg=Ext.Msg.progress(r.title,r.msg,this.percentText);
		}
	},
	
	createErrorMsg : function(r)
	{
		if(this.msg)
		{
			this.msg.hide();
			delete this.msg;
		}
		
		if(r.msg)
		{
			this.errors.title = this.errors.title || 'Error';
			
			Ext.MessageBox.minProgressWidth='250';
			
			Ext.Msg.alert(this.errors.title, r.msg);
		}		
		
	},
	
	hideMsg : function(r)
	{
		if(this.msg&&r.msg&&r.percent)
		{
			this.percentText=this.getPercentText(r);
			this.percentValue=this.getPercentValue(r);
			
			if(this.percentValue==1)
			this.percentText+=' done';
			
			this.msg.updateProgress(this.percentValue,this.percentText,r.msg);
			
			r.hideAfter = (r.hideAfter*1000) || 2000;
			
			Ext.MessageBox.minProgressWidth='250';
						
			this.msg.hide.defer(r.hideAfter,this.msg);
		}
	},
	
	updateMsg : function(r)
	{
		if(this.msg&&r.msg&&r.percent)
		{
			this.percentText=this.getPercentText(r);
			this.percentValue=this.getPercentValue(r);
			
			if(this.percentValue==1)
			this.percentText+=' done';
			
			this.msg.updateProgress(this.percentValue,this.percentText,r.msg);
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