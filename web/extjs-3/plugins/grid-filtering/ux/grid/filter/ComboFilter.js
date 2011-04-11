Ext.ux.grid.filter.ComboFilter = Ext.extend(Ext.ux.grid.filter.Filter, {
	updateBuffer: 500,
	icon: 'ux-gridfilter-text-icon',
	
	init: function(){
		//var store = [['1',"one"],['2',"two"]]
		var value = this.value = new Ext.ux.menu.ComboMenu({lovcombo:this.lovcombo,iconCls: this.icon,store:this.options});
		
        
		value.editor.on('select', function(){
			this.setActive(true);
			this.fireUpdate();			
		},this);
		
		this.menu.add(value);
		
		this.updateTask = new Ext.util.DelayedTask(this.fireUpdate, this);
	},
	
	onKeyUp: function(event){
		if(event.getKey() == event.ENTER){
			this.menu.hide(true);
			return;
		}			
		this.updateTask.delay(this.updateBuffer);
	},
	
	isActivatable: function(){
		return this.value.getValue()?true:false;
	},
	
	fireUpdate: function(){		
		if(this.active)
			this.fireEvent("update", this);
		
		this.setActive(this.isActivatable());
	},
	
	setValue: function(value){		
		this.value.setValue(value);
		this.fireEvent("update", this);
	},
	
	getValue: function(){
		return this.value.getValue()?this.value.getValue().toString():null;
	},
	
	serialize: function(){
		var args = {type: 'combo', value: this.getValue()};
		this.fireEvent('serialize', args, this);
		return args;
	},
	
	validateRecord: function(record){
		var val = record.get(this.dataIndex);
		
		if(typeof val != "combo")
			return this.getValue().length == 0;
			
		return val.toLowerCase().indexOf(this.getValue().toLowerCase()) > -1;
	},
	// Get the display value
	getDisplayValue: function(){
		return this.value.getDisplayValue();
	}
});