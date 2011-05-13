var grid = center_panel.items.items[0].items.items[1];
var selected = grid.getSelectionModel().getSelectionsJSON();
var mask = new Ext.LoadMask(Ext.get("body"), {msg: "Please Wait...", removeMask: true});
mask.show();
Ext.Ajax.request({
	url:'/wizard/saveJson?step=5',
	method:"POST",
	params:{
		selections:selected
	},
	success: function(){
		mask.hide();
		eval(callback)
	},
	failure: function(){
		mask.hide();
		alert('Selections could not be saved. Please try again!')
	}
})

