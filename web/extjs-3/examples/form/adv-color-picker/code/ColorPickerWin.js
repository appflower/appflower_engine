Ext.ns("Ext.ux");
Ext.ux.ColorPickerWin = function(config){
	confing = config || {}
	var Cwin = new Ext.Window(Ext.apply({
		title:"Color Picker",
		width:500,
		height:500,
		closeAction:'hide',
		layout:'fit',
		items: new Ext.Panel({
			items: new Ext.ux.color.ColorPickerPanel({
				width:300,
				height:400
			})
		})
	},config));	
	return Cwin;
}
