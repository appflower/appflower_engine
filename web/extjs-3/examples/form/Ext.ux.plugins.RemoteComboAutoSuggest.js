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
        this.preValue = this.getValue();
        this.firstTime = true;
    },	
    onRender: function(ct, position){
        Ext.ux.RemoteComboAutoSuggest.superclass.onRender.call(this, ct, position);
        combo = this;
        combo.displayField = "value";
        combo.valueField = "key";       
        this.store = store = new Ext.data.JsonStore({
			url: this.url,
			fields: [
				'key','value'
			]
		});
		store.load({params:{id:this.preValue}});
		store.on("load",function(){
			combo.bindStore(store);
			if(this.firstTime){
				combo.setValue(this.preValue);
				this.firstTime = false;
			}			
		},this)
		var delay = new Ext.util.DelayedTask(function(){
			keyword = combo.el.getValue();
			if(keyword != combo.preValue){
				combo.store.load({params:{like:keyword}});
				combo.preValue = keyword;
			}
		});
		onkeyup = function(){
			delay.delay(1000);				
		}
		
		
    }
});

Ext.reg("remotecomboautosuggest", Ext.ux.RemoteComboAutoSuggest);