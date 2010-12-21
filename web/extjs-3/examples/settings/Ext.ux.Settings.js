/*
* Ext.ux.Settings
* @author: Prakash Paudel
* Setting layout with tabbed structrue
*/
Ext.ns("Ext.ux");
Ext.ux.Settings = function(config){
	
	var newConfig = [];
	var windowConfig = {		
	};
	config.windowConfig = Ext.apply(windowConfig,config.windowConfig || []);
	Ext.apply(config.windowConfig,config.windowConfig || []);
	Ext.apply(newConfig,config,{
		windowTitle: 'Settings',				
		activeTab: 0,
		tabPosition: 'left',
		window:null,
		deferredRender:false,
		hideHeading:false
	});
	Ext.ux.Settings.superclass.constructor.call(this,config);
}
Ext.extend(Ext.ux.Settings, Ext.TabPanel,{	
	afterRender:function(){		
		Ext.ux.Settings.superclass.afterRender.call(this);
		var el = this.getEl().dom;
		var win = this.findParentByType('window');
		if(win && win.widgetTitle) this.title = win.widgetTitle+" Settings";
		var headPanelDiv = Ext.DomHelper.insertFirst(el,{tag:'div'});
		if(!this.hideHeading)
		this.headPanel = new Ext.Panel({
			renderTo:headPanelDiv,
			frame:true,
			html:'<div float:left; width:100%><span style="font-size:15px;float:left;margin-bottom:5px;font-weight:bold">'+this.title+'</span>'+
				 '<span style="float:right">'+(this.user?'User: '+this.user:'')+'</span></div>'+
				 '<div style="clear:both; padding-bottom:5px;">'+this.description+'</div>'
		});	
	}
});
Ext.reg("settings",Ext.ux.Settings);