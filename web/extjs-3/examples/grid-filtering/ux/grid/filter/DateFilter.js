Ext.ux.grid.filter.DateFilter = Ext.extend(Ext.ux.grid.filter.Filter, {
	dateFormat: 'Y-m-d H:i:s',
	pickerOpts: {},
	
	
    beforeText: 'To',
    afterText:  'From',
    onText:     'On',
	
	init: function(){
		var opts = Ext.apply(this.pickerOpts, {
			minDate: this.minDate, 
			maxDate: this.maxDate, 
			format:  this.dateFormat,
			showToday:false,
			pickTime:true
		});
		
		var dates = this.dates = {
			'before': new Ext.menu.CheckItem({text: this.beforeText, menu: new Ext.menu.DateMenu(opts)}),
			'after':  new Ext.menu.CheckItem({text: this.afterText, menu: new Ext.menu.DateMenu(opts)}),
			'on':     new Ext.menu.CheckItem({text: this.onText, menu: new Ext.menu.DateMenu(Ext.apply(opts,{pickTime:false}))})};
				
		this.menu.add( dates.after, dates.before , "-", dates.on);
		
		for(var key in dates){
			var date = dates[key];
			date.menu.on('select', function(date, menuItem, value, picker){
				date.setChecked(true);
				if(date == dates.on){				
					
					dates.after.menu.picker.setValue(new Date(value));
					dates.before.menu.picker.setValue(new Date(value).add(Date.HOUR,23).add(Date.MINUTE,59));
					
					dates.before.setChecked(true,true);
					dates.after.setChecked(true,true);
					
					dates.on.setChecked(false,true);
				}
				
				/*if(date == dates.on){
					dates.before.setChecked(false, true);
					dates.after.setChecked(false, true);
				} */else {
					dates.on.setChecked(false, true);
					
					if(date == dates.after && dates.before.menu.picker.value < value)
						dates.before.setChecked(false, true);
					else if (date == dates.before && dates.after.menu.picker.value > value)
						dates.after.setChecked(false, true);
				}
				
				this.fireEvent("update", this);
			}.createDelegate(this, [date], 0));
				
			date.on('checkchange', function(){
				this.setActive(this.isActivatable());
			}, this);
		};
	},
	
	getFieldValue: function(field){
		return this.dates[field].menu.picker.getValue();
	},
	
	getPicker: function(field){
		return this.dates[field].menu.picker;
	},
	
	isActivatable: function(){
		return this.dates.on.checked || this.dates.after.checked || this.dates.before.checked;
	},
	
	setValue: function(value){
		for(var key in this.dates)
			if(value[key]){
				var d = value[key];				
				value[key] = new Date(d.getUTCFullYear(),d.getUTCMonth(),d.getUTCDate(),d.getUTCHours(),d.getUTCMinutes(),0,0);
				
				this.dates[key].menu.picker.setValue(value[key]);
				this.dates[key].setChecked(true);
			} else {
				this.dates[key].setChecked(false);
			}
	},
	
	getValue: function(){
		var result = {};
		for(var key in this.dates)
			if(this.dates[key].checked)
				result[key] = this.dates[key].menu.picker.getValue();
				
		return result;
	},
	
	serialize: function(){
		var args = [];
		if(this.dates.before.checked)
			args = [{type: 'date', comparison: 'lt', value: this.getFieldValue('before').format(this.dateFormat)}];
		if(this.dates.after.checked)
			args.push({type: 'date', comparison: 'gt', value: this.getFieldValue('after').format(this.dateFormat)});
		if(this.dates.on.checked)
			args = {type: 'date', comparison: 'eq', value: this.getFieldValue('on').format(this.dateFormat)};

    this.fireEvent('serialize', args, this);
		return args;
	},
	
	validateRecord: function(record){
		var val = record.get(this.dataIndex).clearTime(true).getTime();
		
		if(this.dates.on.checked && val != this.getFieldValue('on').clearTime(true).getTime())
			return false;
		
		if(this.dates.before.checked && val >= this.getFieldValue('before').clearTime(true).getTime())
			return false;
		
		if(this.dates.after.checked && val <= this.getFieldValue('after').clearTime(true).getTime())
			return false;
			
		return true;
	},
	getDisplayValue: function(){		
		if(this.getValue().on) return this.getFormatedDate(this.getValue().on);		
		if(this.getValue().after && this.getValue().before) return "From: "+this.getFormatedDate(this.getValue().after)+", To: "+this.getFormatedDate(this.getValue().before);
		if(this.getValue().after) return "From: "+this.getFormatedDate(this.getValue().after)
		if(this.getValue().before) return "To: "+this.getFormatedDate(this.getValue().before)
		
	},
	getFormatedDate: function(d){
		return d.format("Y-m-d H:i");
		return d.getFullYear()+"-"+(d.getMonth()+1)+'-'+d.getDate();
	}
});