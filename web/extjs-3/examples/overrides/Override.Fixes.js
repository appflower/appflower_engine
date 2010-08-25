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

Ext.override(Ext.Panel, {
	setIconClass : function(cls){	
		var old = this.iconCls;
		this.iconCls = cls;		
		if(this.rendered && this.header){
			/**
			* Skip frame check to fix window icon issue...
			*
			if(this.frame){
				this.header.addClass('x-panel-icon');
				this.header.replaceClass(old, this.iconCls);				
			}else*/
			{
				var hd = this.header.dom;				
				var img = hd.firstChild && String(hd.firstChild.tagName).toLowerCase() == 'img' ? hd.firstChild : null;				
				if(img){
					Ext.fly(img).replaceClass(old, this.iconCls);
				}else{
					Ext.DomHelper.insertBefore(hd.firstChild, {
						tag:'img', src: Ext.BLANK_IMAGE_URL, cls:'x-panel-inline-icon '+this.iconCls
					});
				 }
			}
		}
	}
});
Ext.override(Ext.TabPanel, {	
    initTab : function(item, index){
		var tt = new Ext.Template(
			'<li class="{cls}" id="{id}" style="overflow:hidden">',
			    '<tpl if="closable">',
			       '<a class="x-tab-strip-close"></a>',
			    '</tpl>',
			    '<a class="x-tab-right" href="#" style="padding-left:6px">',
			       '<em class="x-tab-left">',
			           '<span class="x-tab-strip-inner">',
			               '<img src="'+Ext.BLANK_IMAGE_URL+'" style="float:left;margin:3px 3px 0 0">',
			               '<span  class="x-tab-strip-text {iconCls}">{text} {extra}</span>',
			           '</span>',
			       '</em>',
			   '</a>',
		   '</li>'
		);
		tt.disableFormats = true;
		tt.compile();
		Ext.TabPanel.prototype.itemTpl = tt;
    	/**
    	 * Fixes for iconCls, icon with sprite image
    	 */
    	var src = Ext.BLANK_IMAGE_URL;
    	var iconCls = "";
    	
    	if(item.icon && item.icon != ''){
    		src = item.icon;    		
    	}
    	if(item.iconCls && item.iconCls != ''){
    		iconCls = "{iconCls}";
    	}    	
    	if(item.icon || item.iconCls)
    	{
	    	var tt = new Ext.Template(
	    		'<li class="{cls}" id="{id}" style="overflow:hidden">',
	    	         '<tpl if="closable">',
	    	            '<a class="x-tab-strip-close"></a>',
	    	         '</tpl>',
	    	         '<a class="x-tab-right" href="#" style="padding-left:6px">',
	    	            '<em class="x-tab-left">',
	    	                '<span class="x-tab-strip-inner">',
	    	                    '<img src="'+src+'" class="x-tab-strip-text '+iconCls+'" width="16" height="16" style="padding:0px;float:left;margin-top:2px; margin-right:4px">',
	    	                    '<span style="'+(item.icon?"margin-left:20px":"")+'" class="x-tab-strip-text ">{text} {extra}</span>',
	    	                '</span>',
	    	            '</em>',
	    	        '</a>',
	    	    '</li>'
			);	    	
			tt.disableFormats = true;
			tt.compile();
			Ext.TabPanel.prototype.itemTpl = tt;
    	}
		/***********************************************************/
        var before = this.strip.dom.childNodes[index],
            p = this.getTemplateArgs(item),
            el = before ?
                 this.itemTpl.insertBefore(before, p) :
                 this.itemTpl.append(this.strip, p),
            cls = 'x-tab-strip-over',
            tabEl = Ext.get(el);

        tabEl.hover(function(){
            if(!item.disabled){
                tabEl.addClass(cls);
            }
        }, function(){
            tabEl.removeClass(cls);
        });

        if(item.tabTip){
            tabEl.child('span.x-tab-strip-text', true).qtip = item.tabTip;
        }
        item.tabEl = el;

        // Route *keyboard triggered* click events to the tab strip mouse handler.
        tabEl.select('a').on('click', function(e){
            if(!e.getPageX()){
                this.onStripMouseDown(e);
            }
        }, this, {preventDefault: true});

        item.on({
            scope: this,
            disable: this.onItemDisabled,
            enable: this.onItemEnabled,
            titlechange: this.onItemTitleChanged,
            iconchange: this.onItemIconChanged,
            beforeshow: this.onBeforeShowItem
        });
    }
});
/*!
 * Ext JS Library 3.2.1
 * Copyright(c) 2006-2010 Ext JS, Inc.
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
/**
 * @class Ext.Loader
 * @singleton
 * Simple class to help load JavaScript files on demand
 */
Ext.Loader = Ext.apply({}, {
    /**
     * Loads a given set of .js files. Calls the callback function when all files have been loaded
     * Set preserveOrder to true to ensure non-parallel loading of files if load order is important
     * @param {Array} fileList Array of all files to load
     * @param {Function} callback Callback to call after all files have been loaded
     * @param {Object} scope The scope to call the callback in
     * @param {Boolean} preserveOrder True to make files load in serial, one after the other (defaults to false)
     */
    load: function(fileList, callback, scope, preserveOrder) {
        var scope       = scope || this,
            head        = document.getElementsByTagName("head")[0],
            fragment    = document.createDocumentFragment(),
            numFiles    = fileList.length,
            loadedFiles = 0,
            me          = this;
        
        /**
         * Loads a particular file from the fileList by index. This is used when preserving order
         */
        var loadFileIndex = function(index) {
            head.appendChild(
                me.buildScriptTag(fileList[index], onFileLoaded)
            );
        };
        
        /**
         * Callback function which is called after each file has been loaded. This calls the callback
         * passed to load once the final file in the fileList has been loaded
         */
        var onFileLoaded = function() {
            loadedFiles ++;
            
            //if this was the last file, call the callback, otherwise load the next file
            if (numFiles == loadedFiles && typeof callback == 'function') {
                callback.call(scope);
            } else {
                if (preserveOrder === true) {
                    loadFileIndex(loadedFiles);
                }
            }
        };
        
        if (preserveOrder === true) {
            loadFileIndex.call(this, 0);
        } else {
            //load each file (most browsers will do this in parallel)
            Ext.each(fileList, function(file, index) {
                fragment.appendChild(
                    this.buildScriptTag(file, onFileLoaded)
                );  
            }, this);
            
            head.appendChild(fragment);
        }
    },
    
    /**
     * @private
     * Creates and returns a script tag, but does not place it into the document. If a callback function
     * is passed, this is called when the script has been loaded
     * @param {String} filename The name of the file to create a script tag for
     * @param {Function} callback Optional callback, which is called when the script has been loaded
     * @return {Element} The new script ta
     */
    buildScriptTag: function(filename, callback) {
        var script  = document.createElement('script');
        script.type = "text/javascript";
        script.src  = filename;
        
        //IE has a different way of handling <script> loads, so we need to check for it here
        if (script.readyState) {
            script.onreadystatechange = function() {
                if (script.readyState == "loaded" || script.readyState == "complete") {
                    script.onreadystatechange = null;
                    callback();
                }
            };
        } else {
            script.onload = callback;
        }    
        
        return script;
    }
});

/**
 * Fixes for the IE portal page, where the vertical scrollbar introduces a horizontal one too.
 */
var pxMatch = /(\d+(?:\.\d+)?)px/;
Ext.override(Ext.Element, {
        getViewSize : function(contentBox){
            var doc = document,
                me = this,
                d = me.dom,
                extdom = Ext.lib.Dom,
                isDoc = (d == doc || d == doc.body),
                isBB, w, h, tbBorder = 0, lrBorder = 0,
                tbPadding = 0, lrPadding = 0;
            if (isDoc) {
                return { width: extdom.getViewWidth(), height: extdom.getViewHeight() };
            }
            isBB = me.isBorderBox();
            tbBorder = me.getBorderWidth('tb');
            lrBorder = me.getBorderWidth('lr');
            tbPadding = me.getPadding('tb');
            lrPadding = me.getPadding('lr');

            // Width calcs
            // Try the style first, then clientWidth, then offsetWidth
            if (w = me.getStyle('width').match(pxMatch)){
                if ((w = Math.round(w[1])) && isBB){
                    // Style includes the padding and border if isBB
                    w -= (lrBorder + lrPadding);
                }
                if (!contentBox){
                    w += lrPadding;
                }
                // Minimize with clientWidth if present
                d.clientWidth && (d.clientWidth < w) && (w = d.clientWidth);
            } else {
                if (!(w = d.clientWidth) && (w = d.offsetWidth)){
                    w -= lrBorder;
                }
                if (w && contentBox){
                    w -= lrPadding;
                }
            }

            // Height calcs
            // Try the style first, then clientHeight, then offsetHeight
            if (h = me.getStyle('height').match(pxMatch)){
                if ((h = Math.round(h[1])) && isBB){
                    // Style includes the padding and border if isBB
                    h -= (tbBorder + tbPadding);
                }
                if (!contentBox){
                    h += tbPadding;
                }
                // Minimize with clientHeight if present
                d.clientHeight && (d.clientHeight < h) && (h = d.clientHeight);
            } else {
                if (!(h = d.clientHeight) && (h = d.offsetHeight)){
                    h -= tbBorder;
                }
                if (h && contentBox){
                    h -= tbPadding;
                }
            }

            return {
                width : w,
                height : h
            };
        }
});
Ext.override(Ext.layout.ColumnLayout, {
    onLayout : function(ct, target, targetSize){
        var cs = ct.items.items, len = cs.length, c, i;

        if(!this.innerCt){
            // the innerCt prevents wrapping and shuffling while
            // the container is resizing
            this.innerCt = target.createChild({cls:'x-column-inner'});
            this.innerCt.createChild({cls:'x-clear'});
        }
        this.renderAll(ct, this.innerCt);

        var size = targetSize || target.getViewSize(true);

        if(size.width < 1 && size.height < 1){ // display none?
            return;
        }

        var w = size.width - this.scrollOffset,
            h = size.height,
            pw = w;

        this.innerCt.setWidth(w);

        // some columns can be percentages while others are fixed
        // so we need to make 2 passes

        for(i = 0; i < len; i++){
            c = cs[i];
            if(!c.columnWidth){
                pw -= (c.getSize().width + c.getPositionEl().getMargins('lr'));
            }
        }

        pw = pw < 0 ? 0 : pw;

        for(i = 0; i < len; i++){
            c = cs[i];
            if(c.columnWidth){
                c.setSize(Math.floor(c.columnWidth * pw) - c.getPositionEl().getMargins('lr'));
            }
        }
        // Do a second pass if the layout resulted in a vertical scrollbar (changing the available width)
        if (!targetSize && ((size = target.getViewSize(true)).width != w)) {
            this.onLayout(ct, target, size);
        }
    }
});