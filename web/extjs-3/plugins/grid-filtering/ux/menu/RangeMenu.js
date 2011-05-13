Ext.namespace("Ext.ux.menu");
Ext.ux.menu.RangeMenu = function(){
	Ext.ux.menu.RangeMenu.superclass.constructor.apply(this, arguments);
	this.updateTask = new Ext.util.DelayedTask(this.fireUpdate, this);

	var cfg = this.fieldCfg;
	var cls = this.fieldCls;
	var fields = this.fields = Ext.applyIf(this.fields || {}, {
		'gt': new Ext.ux.menu.EditableItem({
			iconCls:  this.icons.gt,
			editor: new cls(typeof cfg == "object" ? cfg.gt || '' : cfg)}),
		'lt': new Ext.ux.menu.EditableItem({
			iconCls:  this.icons.lt,
			editor: new cls(typeof cfg == "object" ? cfg.lt || '' : cfg)}),
		'eq': new Ext.ux.menu.EditableItem({
			iconCls:   this.icons.eq, 
			editor: new cls(typeof cfg == "object" ? cfg.gt || '' : cfg)}),
		'ne': new Ext.ux.menu.EditableItem({
			iconCls:   this.icons.ne, 
			editor: new cls(typeof cfg == "object" ? cfg.gt || '' : cfg)})
	});
			
	this.add(fields.gt, fields.lt, '-', fields.eq,fields.ne);
	
	for(var key in fields)
		fields[key].on('keyup', function(event, input, notSure, field){
			if(event.getKey() == event.ENTER && field.isValid()){
				this.updateTask.delay(this.updateBuffer);
				this.hide(true);
				return;
			}
			
			if(field == fields.eq){
				fields.gt.setValue(null);
				fields.lt.setValue(null);
				fields.ne.setValue(null);
			} else if(field == fields.ne){
				fields.gt.setValue(null);
				fields.lt.setValue(null);
				fields.eq.setValue(null);
			}else{
				fields.eq.setValue(null);
				fields.ne.setValue(null);
			}			
		}.createDelegate(this, [fields[key]], true));

	this.addEvents({'update': true});
};
Ext.extend(Ext.ux.menu.RangeMenu, Ext.menu.Menu, {
	fieldCls:     Ext.form.NumberField,
	fieldCfg:     '',
	updateBuffer: 500,
	icons: {
		gt: 'ux-rangemenu-gt', 
		lt: 'ux-rangemenu-lt',
		eq: 'ux-rangemenu-eq',
		ne: 'ux-rangemenu-ne'},
		
	fireUpdate: function(){
		this.fireEvent("update", this);
	},
	
	setValue: function(data){		
		for(var key in this.fields)
			this.fields[key].setValue(data[key] !== undefined ? data[key] : '');
		
		this.fireEvent("update", this);
	},
	
	getValue: function(){
		var result = {};
		
		for(var key in this.fields){
			var field = this.fields[key];
			if(field.isValid() && String(field.getValue()).length > 0)
				result[key] = field.getValue();
		}
		return result;
	}
});