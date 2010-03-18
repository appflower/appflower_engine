/*
 *  Override for form fields
 *  @author: Prakash Paudel
 *  
 */

/**
 * Fix for the checkbox focus visible
 */
Ext.override(Ext.form.Checkbox, {
	onFocus: function(){
		var wrap = this.wrap;
		if(!wrap) return
		wrap.setStyle("float","left")
		wrap.setStyle("height","auto")
		wrap.setStyle("border","1px solid #7eadd9")
		Ext.DomHelper.insertAfter(wrap,{tag:'div',style:'clear:both'})
		
	},
	onBlur: function(){
		var wrap = this.wrap;
		if(!wrap) return
		wrap.setStyle("border","0px solid #7eadd9")
	}
});

/**
 * Fix for the radio focus visible
 */
Ext.override(Ext.form.Radio, {
	onFocus: function(){
		var wrap = this.wrap;
		if(!wrap) return
		wrap.setStyle("float","left")
		wrap.setStyle("height","auto")
		wrap.setStyle("border","1px solid #7eadd9")
		Ext.DomHelper.insertAfter(wrap,{tag:'div',style:'clear:both'})
		
	},
	onBlur: function(){
		var wrap = this.wrap;
		if(!wrap) return
		wrap.setStyle("border","0px solid #7eadd9")
	}
});


/**
 * Fix for the button focus visible, looks like mouse overed when focused
 */
Ext.override(Ext.Button, {
    onFocus: function(){	
		this.addClass("x-btn-over")
    },
    onBlur: function(){
    	this.removeClass("x-btn-over")
    }
});

/**
 * Fix for enter key press form submit
 */
Ext.override(Ext.form.Field,{
	fireKeys : function(e) {	
	    if(((Ext.isIE && e.type == 'keydown') || e.type == 'keypress') && e.isSpecialKey()) {
	    	if(e.getKey() == e.ENTER){
	    		if(this.getXType() != "textarea"){
		    		var form = this.findParentByType('form');
		    		this.fireEvent("specialkey",this);
		    		if(form){	    			
			    		Ext.each(form.buttons,function(button){	    			
			    			if(button.url && button.url == form.url){	    				
			    				button.handler.call(button.scope);
			    			}
			    		})	    			
		    		}
	    		}
	    	}
	    }	    
	},
	initEvents : function() {		
	    this.el.on("keydown", this.fireKeys, this);
	    this.el.on("keypress", this.fireKeys, this);
	    this.el.on("keyup", this.fireKeys, this);
	}
})







