Ext.lib.Ajax.isCrossDomain = function(u) {
	var match = /(?:(\w*:)\/\/)?([\w\.]*(?::\d*)?)/.exec(u);
	if (!match[1]) return false; // No protocol, not cross-domain
	return (match[1] != location.protocol) || (match[2] != location.host);
};

Ext.override(Ext.data.Connection, {

    request : function(o){
            var me = this;
            if(me.fireEvent("beforerequest", me, o)){
                if (o.el) {
                    if(!Ext.isEmpty(o.indicatorText)){
                        me.indicatorText = '<div class="loading-indicator">'+o.indicatorText+"</div>";
                    }
                    if(me.indicatorText) {
                        Ext.getDom(o.el).innerHTML = me.indicatorText;
                    }
                    o.success = (Ext.isFunction(o.success) ? o.success : function(){}).createInterceptor(function(response) {
                        Ext.getDom(o.el).innerHTML = response.responseText;
                    });
                }

                var p = o.params,
                    url = o.url || me.url,
                    method,
                    cb = {success: me.handleResponse,
                          failure: me.handleFailure,
                          scope: me,
                          argument: {options: o},
                          timeout : Ext.num(o.timeout, me.timeout)
                    },
                    form,
                    serForm;


                if (Ext.isFunction(p)) {
                    p = p.call(o.scope||WINDOW, o);
                }

                p = Ext.urlEncode(me.extraParams, Ext.isObject(p) ? Ext.urlEncode(p) : p);

                if (Ext.isFunction(url)) {
                    url = url.call(o.scope || WINDOW, o);
                }

                if((form = Ext.getDom(o.form))){
                    url = url || form.action;
                     if(o.isUpload || (/multipart\/form-data/i.test(form.getAttribute("enctype")))) {
                         return me.doFormUpload.call(me, o, p, url);
                     }
                    serForm = Ext.lib.Ajax.serializeForm(form);
                    p = p ? (p + '&' + serForm) : serForm;
                }

                method = o.method || me.method || ((p || o.xmlData || o.jsonData) ? 'POST' : 'GET');

                if(method === 'GET' && (me.disableCaching && o.disableCaching !== false) || o.disableCaching === true){
                    var dcp = o.disableCachingParam || me.disableCachingParam;
                    url = Ext.urlAppend(url, dcp + '=' + (new Date().getTime()));
                }

                o.headers = Ext.apply(o.headers || {}, me.defaultHeaders || {});

                if(o.autoAbort === true || me.autoAbort) {
                    me.abort();
                }

                if((method == 'GET' || o.xmlData || o.jsonData) && p){
                    url = Ext.urlAppend(url, p);
                    p = '';
                }
				
				if (o.scriptTag || this.scriptTag || Ext.lib.Ajax.isCrossDomain(url)) {
				   me.transId = this.scriptRequest(method, url, cb, p, o);
				} else {
				   me.transId = Ext.lib.Ajax.request(method, url, cb, p, o)
				}
				return me.transId;
					
               /// return (me.transId = Ext.lib.Ajax.request(method, url, cb, p, o));
            }else{
                return o.callback ? o.callback.apply(o.scope, [o,undefined,undefined]) : null;
            }
    },
    
    scriptRequest : function(method, url, cb, data, options) {
        var transId = ++Ext.data.ScriptTagProxy.TRANS_ID;
        var trans = {
            id : transId,
            cb : options.callbackName || "stcCallback"+transId,
            scriptId : "stcScript"+transId,
            options : options
        };

        url += (url.indexOf("?") != -1 ? "&" : "?") + data + String.format("&{0}={1}", options.callbackParam || this.callbackParam || 'callback', trans.cb);

        var conn = this;
        window[trans.cb] = function(o){
            conn.handleScriptResponse(o, trans);
        };

//      Set up the timeout handler
        trans.timeoutId = this.handleScriptFailure.defer(cb.timeout, this, [trans]);

        var script = document.createElement("script");
        script.setAttribute("src", url);
        script.setAttribute("type", "text/javascript");
        script.setAttribute("id", trans.scriptId);
        document.getElementsByTagName("head")[0].appendChild(script);

        return trans;
    },

    handleScriptResponse : function(o, trans){
        this.transId = false;
        this.destroyScriptTrans(trans, true);
        var options = trans.options;
        
//      Attempt to parse a string parameter as XML.
        var doc;
        if (typeof o == 'string') {
            if (window.ActiveXObject) {
                doc = new ActiveXObject("Microsoft.XMLDOM");
                doc.async = "false";
                doc.loadXML(o);
            } else {
                doc = new DOMParser().parseFromString(o,"text/xml");
            }
        }

//      Create the bogus XHR
        response = {
            responseObject: o,
            responseText: (typeof o == "object") ? Ext.util.JSON.encode(o) : String(o),
            responseXML: doc,
            argument: options.argument
        }
        this.fireEvent("requestcomplete", this, response, options);
        Ext.callback(options.success, options.scope, [response, options]);
        Ext.callback(options.callback, options.scope, [options, true, response]);
    },
    
    handleScriptFailure: function(trans) {
        this.transId = false;
        this.destroyScriptTrans(trans, false);
        var options = trans.options;
        response = {
            argument:  options.argument,
            status: 500,
            statusText: 'Server failed to respond',
            responseText: ''
        };
        this.fireEvent("requestexception", this, response, options, {
            status: -1,
            statusText: 'communication failure'
        });
        Ext.callback(options.failure, options.scope, [response, options]);
        Ext.callback(options.callback, options.scope, [options, false, response]);
    },
    
    // private
    destroyScriptTrans : function(trans, isLoaded){
        document.getElementsByTagName("head")[0].removeChild(document.getElementById(trans.scriptId));
        clearTimeout(trans.timeoutId);
        if(isLoaded){
            window[trans.cb] = undefined;
            try{
                delete window[trans.cb];
            }catch(e){}
        }else{
            // if hasn't been loaded, wait for load to remove it to prevent script error
            window[trans.cb] = function(){
                window[trans.cb] = undefined;
                try{
                    delete window[trans.cb];
                }catch(e){}
            };
        }
    }
});