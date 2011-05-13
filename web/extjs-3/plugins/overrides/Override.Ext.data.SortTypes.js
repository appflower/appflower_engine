/*
 *  Override for Ext.data.SortTypes
 *  @author: Prakash Paudel
 *  
 */
Ext.apply(Ext.data.SortTypes,{
	asText: function(s){
		return Ext.data.SortTypes.asUCText(s);
	},
	htmlAsInt : function(s) {		
		return Ext.data.SortTypes.asInt(Ext.data.SortTypes.asUCText(s));		
	},
	htmlAsFloat : function(s) {		
		return Ext.data.SortTypes.asFloat(Ext.data.SortTypes.asUCText(s));
	},
	htmlAsText: function(s){
		var re= /<\S[^>]*>/gi; 
		return String(s).replace(re,""); 
	},
	asIp: function(s){		
		var parts = String(s).split(".");
		var ret = '';
		for(var i=0;i<parts.length;i++){
			var k = 3-parts[i].length;
			for(var j=0;j<k;j++){
				ret += "0";
			}
			ret += parts[i];
		}		
		return Ext.data.SortTypes.asText(ret==''?s:ret);
	},
	htmlAsIp: function(s){
		return Ext.data.SortTypes.asIp(Ext.data.SortTypes.asUCText(s));
	},
	asSize: function(s){
		s = s.toUpperCase();
		var str = s.match(/([a-zA-Z\s]+)/)
		if(str) str = str[0].replace(/^\s*/, "").replace(/\s*$/, "");
		var factor = 1;
		var patterns = [
            [/^BYTE/,/^KILO/,/^MEGA/,/^GIGA/,/^TERA/,/^PETA/,/^EXA/,/^ZETTA/,/^YOTTA/],
            [/^B/,/^KB/,/^MB/,/^GB/,/^TB/,/^PB/,/^EB/,/^ZB/,/^YB/],
            [/^B/,/^K/,/^M/,/^G/,/^T/,/^P/,/^E/,/^Z/,/^Y/]
		];			
		for(var i=0;i<patterns.length;i++){
			for(var j=0;j<patterns[i].length;j++){
				if(patterns[i][j].test(str)){
					factor = 1024;
					factor = Math.pow(factor,j);
					break;
				}
			}
		}
		return parseFloat(s)*factor;
	},
	htmlAsSize: function(s){
		return Ext.data.SortTypes.asSize(Ext.data.SortTypes.asUCText(s));
	},
	maskAsIp: function(s){
		var pattern = /\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b/;
		var ip = s.match(pattern);
		if(!ip){			
			return;
		}
		return Ext.data.SortTypes.htmlAsIp(ip[0]);
	}
});