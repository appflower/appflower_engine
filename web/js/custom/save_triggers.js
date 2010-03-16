var grid = center_panel.items.items[0].items.items[1];
var store = grid.getStore();
if(!store.getCount()){
	Ext.Msg.alert("Error","Please add at least one trigger");
	return;
}
var json = new Array();
var fields = [];
store.fields.each(function(f){
	fields.push(f.name);
})
for(var i=0;i<store.getCount();i++){
	var record = store.getAt(i);
	var row = [];
	for(var j=0;j<fields.length;j++){		
		row.push([fields[j],record.get(fields[j])]);
	}
	json.push(row);	
}
var preserve_row_order = false;
var tb = grid.getTopToolbar();
for(var i=0;i<tb.items.items.length;i++){
	var ind = tb.items.items[i];
	if(ind.name && ind.name == 'preserve-row-order'){
		preserve_row_order = ind.checked;		
	}
}

var result = {
	data:json,
	order:preserve_row_order
}
var mask = new Ext.LoadMask(Ext.get("body"), {msg: "Please Wait...", removeMask: true});
mask.show();
Ext.Ajax.request({
	url:'/eventmanagement/storeTriggers',
	method:"POST",
	params:{
		triggers:Ext.util.JSON.encode(result)
	},
	success: function(){
		mask.hide();
		if(callback)
		eval(callback)
	},
	failure: function(){
		mask.hide();
		Ext.Msg.alert('Error','Connection to server has been lost<br>Triggers could not be saved. Please try again!')
	}
})

