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
	},
	onBlur: function(){
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
	    		if(this.getXType() != "textarea" && this.getXType() != "superboxselect"){
		    		var form = this.findParentByType('form');
		    		//this.fireEvent("specialkey",this);
		    		if(form){	    			
			    		Ext.each(form.buttons,function(button){	    			
			    			if(button.url && button.url == form.url){	    				
			    				button.handler.call(button.scope);
			    			}
			    		})	    			
		    		}else{
					this.fireEvent("specialkey",this,e);
				}
	    		}
	    	}
	    }	    
	},
	initEvents : function() {		
	    this.el.on("keydown", this.fireKeys, this);
	    this.el.on("keypress", this.fireKeys, this);
	    this.el.on("keyup", this.fireKeys, this);
	    this.el.on("focus", this.onFocus, this);
	    this.el.on("blur", this.onBlur, this);
	}
});

/**
 * The textfield shows 10.00 as 10, but 10.01 as 10.01. We need 10.00 to be 10.00 not 10
 * Fix for http://tickets.appflower.com/ticket/editView?id=153
 * @author: Prakash Paudel
 */
Ext.override(Ext.form.TextField,{
	initValue : function(){
		if(this.value){			
			this.value = this.value.toString().trim();
		}
	    if(this.value !== undefined){
	        this.setValue(this.value);
	    }else if(!Ext.isEmpty(this.el.dom.value) && this.el.dom.value != this.emptyText){
	        this.setValue(this.el.dom.value);
	    }
	    
	    /**
	     * The original value of the field as configured in the {@link #value} configuration, or
	     * as loaded by the last form load operation if the form's {@link Ext.form.BasicForm#trackResetOnLoad trackResetOnLoad}
	     * setting is true.
	     * @type mixed
	     * @property originalValue
	     */
	    this.originalValue = this.getValue();
	}
});

/**
* Override for error markup on HtmlEditor
*
* @author radu
*/
Ext.override(Ext.form.HtmlEditor, {
    markInvalid: function(msg){
        if(!this.rendered || this.preventMark){
            return;
        }
        msg = msg || this.invalidText;
        
        switch(this.msgTarget){
            case 'qtip':
            	this.iframe.qtip = msg;
                this.iframe.qclass = 'x-form-invalid-tip';
                Ext.get(this.iframe).addClass(this.invalidClass);
                break;
            case 'side':
            	Ext.get(this.iframe).addClass(this.invalidClass);
            	
            	this.errorDiv=Ext.DomHelper.append(this.wrap,{tag:'div',style:'top:0px;left:'+(this.wrap.getWidth()+85)+'px;z-index:1000;position:absolute;visibility:visible;',cls:'x-form-invalid-icon',qtip:msg,qclass:'x-form-invalid-tip'});            	
	            break;
        }
        return Ext.form.TextArea.superclass.markInvalid.call(this, [msg]);
    },
    clearInvalid: function(){
        if(!this.rendered || this.preventMark){
            return;
        }
        switch(this.msgTarget){
            case 'qtip':
                this.iframe.qtip = '';
                Ext.get(this.iframe).removeClass(this.invalidClass);
                break;
            case 'side':
            	if(this.errorDiv)
            	{            		
                	Ext.get(this.errorDiv).remove();
            	}
                Ext.get(this.iframe).removeClass(this.invalidClass);
                break;
        }
        return Ext.form.TextArea.superclass.clearInvalid.call(this);
    }
});





