/*
 *  Dynamic widget popup plugin
 *  @author: Prakash Paudel  
 */
function strstr (haystack, needle, bool) {   
    var pos = 0;    
    haystack += '';
    pos = haystack.indexOf( needle );
    if (pos == -1) {
        return false;
    } else{
        if (bool){
            return haystack.substr( 0, pos );
        } else{
            return haystack.slice( pos );
        }
        return true;
    }
}
function in_array (needle, haystack, argStrict) {   
    var key = '', strict = !!argStrict;
    if (strict) {
        for (key in haystack) {
            if (strstr(haystack[key],needle)) {
                return true;
            }
        }
    } else {
        for (key in haystack) {
        	
            if (strstr(haystack[key],needle)) {            	
                return true;
            }
        }
    }
    return false;
}

function executeAddons(addons,json,mask,title,superClass){
	
	var counter = -1;
	var backup = new Array();
	var ajax = function(){	
		mask = new Ext.LoadMask(Ext.get("body"), {msg: "<b>Loading additional addons.....</b> <br>Please wait..<br>Loading on progress: "+(counter+2)+"/"+addons.length,removeMask:true});
		mask.show();		
		Ext.Ajax.request({
			url : addons[++counter],
			method: "POST",
			success:function(r){				
				eval(r.responseText);
				
				if(counter < addons.length-1){				
					ajax();				
				}else counter++;
			}
		})
	};
	if(addons.length) ajax();
	
	var runner = new Ext.util.TaskRunner();
	runner.start({
	    run: function(){
			if(counter == -1 || counter >= addons.length){			
				backupForms();
				runner.stopAll();
				eval(json.source);				
				
				var win = new Ext.Window( {
//					width : (superClass && superClass.windowConfig && superClass.windowConfig.width)?superClass.windowConfig.width:Ext.get("body").getWidth()-200,
//					height:(superClass && superClass.windowConfig && superClass.windowConfig.width)?superClass.windowConfig.height:Ext.get("body").getHeight()-100,
					width:800,
					height:500,
					//height: "auto",
					autoScroll : true,
					maximizable : true,
					draggable:true,	
					title:title,
					closeAction:'hide',
					
					items : new Ext.Panel( {
						frame : true,	
						width:"auto",
						layout:"form",
						items : eval(json.center_panel)
					})
				});	
				win.on("show",function(win){var pos = win.getPosition(); if(pos[1]<0) win.setPosition(pos[0],0);});
				//win.items.items[0].items.items[0].frame = false;
				win.doLayout()
				win.show();
				
				
				win.on("render",function(win){eval(json.public_source);});
				win.on("move",function(win,x,y){
					if(y<0) win.setPosition(x,0);
					if(x < 100-win.getWidth()) win.setPosition(100-win.getWidth(),y);
					if(x > Ext.getBody().getWidth()-100) win.setPosition(Ext.getBody().getWidth()-100,y);
					if(y > Ext.getBody().getHeight()-100) win.setPosition(x,Ext.getBody().getHeight()-100);
				})
				
				mask.hide();			
				
				win.on("hide",function(){	
					if(superClass)superClass.onHide(win);									
					win.destroy();
					win.close();
					restoreBackup();
				})
			}
		},
		hideForms: function(){
			document.body.removeChild(document.getElementById("center_panel"))
		},
	    interval: 1000
	});
	function restoreBackup(){
		for(id in backup){
			var el = document.getElementById(id);
			if(el){
				el.id = backup[id]
			}
		}
	}
	function backupForms(comp){		
		var randomnumber=Math.floor(Math.random()*11);
		var randomId = "x-form-el-random-"+randomnumber;
		var inputs = document.getElementsByTagName("input");
		var textareas = document.getElementsByTagName("textarea");
		var selects = document.getElementsByTagName("select");
		
		var arr = new Array();
		arr.push(inputs);
		arr.push(textareas);
		arr.push(selects);
		for(var j=0;j<arr.length;j++){
			var forms = arr[j];
			for(var i=0;i<forms.length;i++){
				if(forms[i].id){
					if(forms[i].id.match("edit")){					
						var el = document.getElementById("x-form-el-"+forms[i].id);							
						if(el){ 
							backup[randomId+"-"+i] = el.id;
							el.id = randomId+"-"+i;
						}						
					}
				}
			}
		}
		
	}
}
function createAddon(filename, filetype) {
	//console.log(filename+":"+filetype);
	if (filetype == "js") { // if filename is a external JavaScript file
		var fileref = document.createElement('script')
		fileref.setAttribute("type", "text/javascript")
		fileref.setAttribute("src", filename)
	} else if (filetype == "css") { // if filename is an external CSS file
		var fileref = document.createElement("link")
		fileref.setAttribute("rel", "stylesheet")
		fileref.setAttribute("type", "text/css")
		fileref.setAttribute("href", filename)
	}
	
	if (typeof fileref != "undefined")
		document.getElementsByTagName("head")[0].appendChild(fileref)
}
function ajax_widget_popup(widget,title,superClass) {
	
	var getWidgetText = function(widget){
		if(widget.length > 45){
			return widget.substring(0,20)+"...."+widget.substring(widget.length-20,widget.length);
		}return widget;
	}
	var mask = new Ext.LoadMask(Ext.get("body"), {msg: "<b>Opening widget</b> <br>Please Wait...",removeMask:true});
	mask.show();
	var ajax = Ext.Ajax.request( {
		url : widget,
		method : "GET",		
		success : function(r) {
			var json = Ext.util.JSON.decode(r.responseText);
			// Load CSS
			var scripts = document.getElementsByTagName("script");
			
			var scripts_srcs = new Array(),styles_hrefs = new Array(),total_addons = new Array();
			
			//createAddon("/js/swfobject.js", "js");	
			for(var i = 0;i<scripts.length;i++) if(scripts[i].src) scripts_srcs[i] = scripts[i].src;
			var styles = document.getElementsByTagName("link");
			for(var i = 0;i<styles.length;i++) if(styles[i].href) styles_hrefs[i] = styles[i].href;
			if(json.addons && json.addons.css)
			for ( var i = 0; i < json.addons.css.length; i++) {
				var addon = json.addons.css[i];
				if(!in_array(addon,styles_hrefs)){
					//total_addons.push(addon);
					createAddon(addon, "css");
				}
			}
			if(json.addons && json.addons.js)
			for ( var i = 0; i < json.addons.js.length; i++) {
				var addon = json.addons.js[i];
				if(!in_array(addon,scripts_srcs)){
					if(addon != null)
					total_addons.push(addon);
					createAddon(addon, "js");				
				}
			}
			if(json.public_source)
			if(!in_array("swfobject.js",scripts_srcs)){
				total_addons.push("/js/swfobject.js");
				createAddon("/js/swfobject.js", "js");
			}
			executeAddons(total_addons,json,mask,title,superClass);					
		},
		params : {
			widget_popup_request : true
		}
	});
}