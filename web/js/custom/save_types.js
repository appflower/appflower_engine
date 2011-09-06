var grid = center_panel.items.items[0].items.items[1];
var selected = grid.getSelectionModel().getSelectionsJSON();
var mask = new Ext.LoadMask(Ext.getBody(), {msg: "Please Wait...", removeMask: true});

if(selected.length == 2) {
	Ext.Msg.show({
		   title:"Selection Required",
		   msg: "Please select at least one item..",
		   buttons: Ext.Msg.OK,
		   icon: Ext.MessageBox.INFO								   
		});
	return false;
}

mask.show();

Ext.Ajax.request({
	url:'/wizard/saveJson?step=6&props=name,order',
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

