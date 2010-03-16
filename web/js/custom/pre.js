var grid = center_panel.items.items[0].items.items[1];
var selected = grid.getSelectionModel().getSelectionsJSON();
Ext.Ajax.request({
	url:'/wizard/saveJson?step=5',
	method:"POST",
	params:{
		selections:selected
	},
	success: function(){
		eval(callback)
	},
	failure: function(){
		alert('Selections could not be saved. Please try again!')
	}
})

