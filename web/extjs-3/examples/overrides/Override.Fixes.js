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
