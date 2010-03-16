Ext.namespace("Ext.ux.menu");
Ext.ux.menu.ComboMenu = Ext.extend(Ext.menu.BaseItem, {
    itemCls : "x-menu-item",
    hideOnClick: false, 
    tempValue:null,
    
    initComponent: function(){
    	this.addEvents({keyup: true});
    	var combo;
    	if(this.lovcombo){
    		combo = new Ext.ux.form.LovCombo({
	            store: this.store,
	            mode: 'local',            
	            triggerAction: 'all',
	            emptyText:'Select an option...',
	            selectOnFocus:true,
	            resizable:true
	        });
    	}else{
	        combo = new Ext.form.ComboBox({
	            store: this.store,            
	            typeAhead: true,
	            mode: 'local',            
	            triggerAction: 'all',
	            emptyText:'Select an option...',
	            selectOnFocus:true,
	            resizable:true
	        });   
    	}
        this.editor = combo;
		if(this.text)
			this.editor.setValue(this.text);		
    },
    onRender: function(container){
    	
        var s = container.createChild({
        	cls: this.itemCls
        });
        
        Ext.apply(this.config, {width: 225});
        this.editor.render(s);
        
        this.el = s;
        this.relayEvents(this.editor.el, ["keyup"]);
       
        if(Ext.isGecko)
			s.setStyle('overflow', 'auto');
		if(this.tempValue !== null) this.setValue(this.tempValue);
        Ext.ux.menu.ComboMenu.superclass.onRender.apply(this, arguments);
    },
    
    getValue: function(){    	
    	if(this.editor.el){
    		return this.editor.getValue();
    	}else{
    		return this.tempValue;
    	}
    },
    
    setValue: function(value){    	
    	if(this.editor.el){    		
    		this.editor.setValue(value);
    	}else{
    		this.tempValue = value;
    		this.tempDisplayValue = value;
    	}
    	
    },
    
    isValid: function(preventMark){
    	return this.editor.isValid(preventMark);
    },
    //Get display value
    getDisplayValue: function(){
    	var values = new Array();
    	var displayValues = [];    	
    	v = this.getValue();    	
    	var store = this.editor.getStore();    	
    	if(v.toString().match(/,/)){
    		values = v.toString().split(",");
    	}else{
    		values[0] = v;
    	}
    	
    	for(var i=0;i<values.length;i++){
    		var index = store.find('field1',values[i]);
    		
        	var record = store.getAt(index);
        	displayValues.push(record.get('field2'));
    	}	
    	return displayValues.join(",");
    }
});