/**
Extended Item Selector
Ability to auto suggest
@author: Prakash Paudel
*/
Ext.ux.RemoteComboAutoSuggest = Ext.extend(Ext.form.ComboBox,  {
    
    initComponent: function(){
        Ext.ux.RemoteComboAutoSuggest.superclass.initComponent.call(this);
         this.addEvents({
            'keyup' : true,
            'change' : true
        });        
    },	
    onRender: function(ct, position){
        Ext.ux.RemoteComboAutoSuggest.superclass.onRender.call(this, ct, position);
        combo = this;
        combo.displayField = "value";
        combo.valueField = "key";       
        
        store = new Ext.data.JsonStore({
			url: this.url,
			fields: [
				'key','value'
			]
		});
		
		combo.bindStore(store);
				
		var delay = new Ext.util.DelayedTask(function(){
			keyword = combo.el.getValue();
			
			if(keyword.length >= combo.minChars&&keyword != combo.preValue){
				combo.store.load({params:{like: keyword, storeParams:Ext.encode(combo.storeParams)}});
			}
		});
		onkeyup = function(){
			delay.delay(1000);
		}	
    }
});

Ext.reg("remotecomboautosuggest", Ext.ux.RemoteComboAutoSuggest);