Ext.namespace('Ext.ux.plugins');
Ext.ux.plugins.ExtendedComboBox = {
    init: function(container){
        Ext.apply(container, {
            onRender: container.onRender.createSequence(function(ct, position){
                // adjust styles
            	var combo = this;
                this.wrap.applyStyles({position:'relative'});
                this.el.applyStyles({border:'0px',backgroundColor:'transparent', textDecoration:'underline'});
                
                this.el.removeClass('x-form-text'); 
                this.el.removeClass('x-form-field'); 
                this.el.on("click",function(){combo.expand()})
                this.trigger.removeClass('x-form-trigger');
                this.trigger.removeClass('x-form-arrow-trigger');                    
                this.trigger.applyStyles({backgroundImage:'none',border:'0px',width:'0px'});
                //this.emptyText = "Select One";
                this.on('focus', function(boundEl,value) {
                  	combo.expand();// some code goes in here
                });
            }) // end of function onRender
        });
     
        
    }
};

Ext.ux.plugins.ExtendedComboBox = Ext.extend(Ext.ux.plugins.ExtendedComboBox, Ext.util.Observable);
// end of file