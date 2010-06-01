Ext.lib.Event.resolveTextNode = Ext.isGecko ? function(node){
	if(!node){
		return;
	}
	var s = HTMLElement.prototype.toString.call(node);
	if(s == '[xpconnect wrapped native prototype]' || s == '[object XULElement]'){
		return;
	}
	return node.nodeType == 3 ? node.parentNode : node;
} : function(node){
	return node && node.nodeType == 3 ? node.parentNode : node;
};

Ext.override(Ext.grid.Column,{	
	renderer: function(value){	
		var intTypes = ["asInt","htmlAsInt"];	
		for(key in intTypes){
			if(intTypes[key] == this.sortType){				
				var formatted = Ext.util.Format.number(Ext.util.Format.stripTags(value),"0,000");
				if(value == Ext.util.Format.stripTags(value))
					return value.toString().replace(Ext.util.Format.stripTags(value),formatted);
				else
					return value.toString().replace(">"+Ext.util.Format.stripTags(value)+"<",">"+formatted+"<");
			}
		} 
		var floatTypes = ["asFloat","htmlAsFloat"];
		for(key in floatTypes){
			if(floatTypes[key] == this.sortType){
				var formatted = Ext.util.Format.number(Ext.util.Format.stripTags(value),"0,000.00");
				if(value == Ext.util.Format.stripTags(value))
					return value.toString().replace(Ext.util.Format.stripTags(value),formatted);
				else
					return value.toString().replace(">"+Ext.util.Format.stripTags(value)+"<",">"+formatted+"<");
			}
		}
		return value;
	} 
});
Ext.override(Ext.ToolTip,{
	onMouseMove : function(e){		
		var x = e.getPageX(),y = e.getPageY();
		var t = this.delegate ? e.getTarget(this.delegate) : this.triggerElement = true;
	    if (t) {
	        this.targetXY = e.getXY();	        
	        if (t === this.triggerElement) {
	            if(!this.hidden && this.trackMouse){
	            	var box = this.getBox();
	    	    	if(box.x+box.width > Ext.getBody().getWidth()) x = Ext.getBody().getWidth()-box.width;
	    	        this.setPagePosition(x,this.getTargetXY()[1]);
	    	       
	            }
	        } else {
	            this.hide();
	            this.lastActive = new Date(0);
	            this.onTargetOver(e);
	        }
	    } else if (!this.closable && this.isVisible()) {
	        this.hide();
	    }
	}
})