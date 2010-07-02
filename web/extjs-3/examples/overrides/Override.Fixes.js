/**
 * author: Prakash Paudel
 * Override methods for custom requirements..
 */
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

Ext.override(Ext.ToolTip,{
	onMouseMove : function(e){		
		var x = e.getPageX(),y = e.getPageY();
		var t = this.delegate ? e.getTarget(this.delegate) : this.triggerElement = true;
	    if (t) {
	        this.targetXY = e.getXY();	        
	        if (t === this.triggerElement) {
	            if(!this.hidden && this.trackMouse){
	            	var box = this.getBox();
	    	    	if(box.x+box.width > Ext.getBody().getWidth()) {
	    	    		x = Ext.getBody().getWidth()-(box.width+10);
	    	    		this.targetXY = [x,y];
	    	    	}
	    	    	this.setPagePosition(this.getTargetXY());
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
});