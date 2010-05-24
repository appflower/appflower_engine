/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.ux.Portal = Ext.extend(Ext.Panel, {
    layout: 'column',
    autoScroll:false,
    bodyStyle:'overflow-x:hidden;overflow-y:scroll;padding-right:5px;',
    cls:'x-portal',
    defaultType: 'portalcolumn',
    afterLayoutEvent: false,
    
    initComponent : function(){
        Ext.ux.Portal.superclass.initComponent.call(this);
        this.addEvents({
            validatedrop:true,
            beforedragover:true,
            dragover:true,
            beforedrop:true,
            drop:true,
            render:true
        });
        
        this.on('drop',this.onWidgetDrop,this);
        this.on('afterLayout',this.onPortalAfterLayout,this);        
    },

    onPortalAfterLayout : function(portal){
    	
    	if(!this.afterLayoutEvent)
    	{
    	  	var col;
	        for(var c = 0; c < this.items.getCount(); c++) {
	           col = this.items.get(c);    
	           
	           if(col.items) {
	                for(var s = 0; s < col.items.getCount(); s++) {
	                	var widget=col.items.get(s);
	                	
	                	if(widget.isXType('form'))
	                	{
	                		var items=widget.items.items;
	        			                		
	                		if(items.length>0)
	                		{
		                		for(var i=0;i<items.length;i++)
				        		{
				        			//this is a fieldset
				        			if(items[i].items&&items[i].items.items)
				        			{
				        				var fieldset_width=col.lastSize.width-46;
				        				
					        			var inputs=items[i].items.items;
					        			
					        			var label_width=fieldset_width*0.3;
					        			var input_width=fieldset_width*0.65;
					        			var static_width=fieldset_width*0.9;
					        			
					        			label_width=(label_width>75)?75:label_width;
					        			input_width=(input_width<250)?input_width:250;
					        			
					        			input_padding_left=label_width;
					        			
					        			for(var j=0;j<inputs.length;j++)
					        			{       		
					        				//console.log(inputs[j].getXType());
					        						
					        				if(!inputs[j].isXType('htmleditor')&&!inputs[j].isXType('textarea')&&!inputs[j].isXType('statictextfield'))
					        				{
					        					if(!inputs[j].isXType('itemselector'))
					        					{
					        						inputs[j].setSize(input_width);
					        					}
					        					else
					        					{
					        						//to do??
					        						inputs[j].msWidth=input_width;			        						
					        					}
					        				}       
					        				
					        				if(inputs[j].isXType('statictextfield'))
					        				{
					        					inputs[j].setSize(static_width);
					        				}
					        									        							
				        					if(inputs[j].wrap)
				        					{		
				        						inputs[j].wrap.dom.parentNode.style.paddingLeft=input_padding_left+'px';
				        					}
				        					if(inputs[j].container&&inputs[j].container.dom.previousSibling)
				        					{				        						
				        						inputs[j].container.dom.previousSibling.style.width=label_width+'px';
				        						inputs[j].container.dom.previousSibling.style.height='10px';
				        					}
					        			}  
				        			}
				        			else
				        			{
						        		var column_width=col.lastSize.width-24;
			        			
					        			var input=items[i];
					        			
					        			var label_width=column_width*0.3;
					        			var input_width=column_width*0.7;
					        			
					        			label_width=(label_width>75)?75:label_width;
					        			input_width=(input_width<250)?input_width:250;
					        			input_padding_left=label_width;
					        			
					        			//console.log(inputs[j].getXType());
					        						
				        				if(!input.isXType('htmleditor'))
				        				{
				        					if(!input.isXType('itemselector'))
				        					{
				        						input.setSize(input_width);
				        					}
				        					else
				        					{
				        						//to do??
				        						input.msWidth=input_width;			        						
				        					}
				        					       			
				        					if(input.wrap)
				        					{		
				        						input.wrap.dom.parentNode.style.paddingLeft=input_padding_left+'px';
				        					}
				        					/*if(input.container&&input.container.dom.previousSibling)
				        					{
				        						input.container.dom.previousSibling.style.width=label_width+'px';
				        						input.container.dom.previousSibling.style.height='10px';
				        					}*/
				        				}
				        			}
				        		}
	                		}
	                	}
	                }
	            }
	        }
	        
	        this.afterLayoutEvent=true;
    	}
    },
    
    initEvents : function(){
        Ext.ux.Portal.superclass.initEvents.call(this);
        this.dd = new Ext.ux.Portal.DropZone(this, this.dropConfig);
        //Ext.util.Observable.capture(this, function(e){if(console)console.info(e)});
    },
    
    beforeDestroy: function() {
        if(this.dd){
            this.dd.unreg();
        }
        Ext.ux.Portal.superclass.beforeDestroy.call(this);
    },
    
    onWidgetDrop:function(portal)
    {
    	var config=this.getConfig();
		    	
		Ext.Ajax.request({ 
			url: "/appFlower/savePortalState", 
			method:"post", 
			params:{"config":config}, 
			success:function(response, options){
			/*	response=Ext.decode(response.responseText);if(response.message){Ext.Msg.alert("Success",response.message);}*/
			},
			failure: function(response,options) {
				if(response.message){Ext.Msg.alert("Failure",response.message);}
			}				
		});
    },
    
    getPortalTitle:function()
    {
    	switch (this.getLayoutType())
    	{
    		case "NORMAL":
    			return this.title;
    		case "TABBED":
    			return this.ownerCt.ownerCt.getActiveTab().title;
    	}
    },
    
    getLayoutItem:function()
    {
    	switch (this.getLayoutType())
    	{
    		case "NORMAL":
    			return 0;
    		case "TABBED":
    			return this.ownerCt.ownerCt.items.indexOf(this.ownerCt.ownerCt.getActiveTab());
    	}
    },
    
    getLayoutType:function()
    {
    	if(this.layoutType)
    	{
	    	return this.layoutType;
    	}
    	else
    	{
	    	return this.ownerCt.ownerCt.layoutType;
    	}
    },
    
    getIdXml:function()
    {
    	switch (this.getLayoutType())
    	{
    		case "NORMAL":
    			return this.idxml;
    		case "TABBED":
    			return this.ownerCt.ownerCt.idxml;
    	}
    },
    
    //get the configuration to send as response to browser
    getConfig:function() {
        var o = {            
            idXml:this.getIdXml(), //element of identification
            layoutType:this.getLayoutType(), //element of identification
            layoutItem:this.getLayoutItem(),
       		content: {}
        };
        
        o.content.portalTitle=this.getPortalTitle();
        o.content.portalLayoutType=this.portalLayoutType;
        o.content.portalColumns=[[]];        
        o.content.portalLayoutNewType=this.layoutNewType || false;
        
        var col;
        for(var c = 0; c < this.items.getCount(); c++) {
            col = this.items.get(c);    
            o.content.portalColumns[c] = [];
            if(col.items) {
                for(var s = 0; s < col.items.getCount(); s++) {
                	o.content.portalColumns[c].push(col.items.get(s).getWidgetConfig());
                }
            }
        }
        var encoded=Ext.encode(o);
        //console.log(encoded);
        return encoded;
    },
    
    //show the window with widget selector
    showWidgetSelector:function(button,title) {
    	if(this.widgetSelectorWindow)
    	{
    		this.widgetSelectorWindow.show(button);
    	}
    	else
    	{
    		this.createWidgetSelector(title,button);
    	}
    },
    
    //retrieve widgets
    retrieveWidgets:function(button)
	{		
		//console.log(this.portalWidgets);
		var obj=this;
			
		var treereader = new Ext.data.JsonReader ({
			fields: [
				{
					name: "title",
					sortType: "asText"
				},
				{
					name: "description",
					sortType: "asText"
				},
				{
					name: "image",
					sortType: "asText"
				},
				{
					name: "widget",
					sortType: "asText"
				},
				{
					name: "message"
				},
				{
					name: "redirect"
				},
				{
					name: "_id",
					type: "int"
				},
				{
					name: "_parent",
					type: "auto"
				},
				{
					name: "_is_leaf",
					type: "bool"
				},
				{
					name: "_color",
					type: "auto"
				},
				{
					name: "_cell_color",
					type: "auto"
				},
				{
					name: "_selected",
					type: "auto"
				}
			],
			id: "_id",
			totalProperty: "totalCount",
			root: "rows",
			properties: "properties"
		});
		
		var treestore = new Ext.ux.maximgb.tg.AdjacencyListStore ({
			sortInfo: {
				field: "title",
				direction: "ASC"
			},
			reader: treereader,
			remoteSort: false,
			proxy: new Ext.data.HttpProxy ({
				url: "/appFlower/retrieveWidgetsInfo",
				method: "POST",
				disableCaching: false
			}),
			listeners: { 
				beforeload: function (object,options) { 
					if(!Ext.isIE&&!treegrid.disableLoadMask){
						treegrid.getEl().mask('Loading, please Wait...', 'x-mask-loading');
					}
				},						
				load: function (object,records,options) { 
					if(records.length>0&&records[0].json.redirect&&records[0].json.message){var rec=records[0].json;Ext.Msg.alert("Failure", rec.message, function(){window.location.href=rec.redirect;});}else{if(!Ext.isIE){treegrid.getEl().unmask();}} 
				},
				loadexception: function () {
					if(!Ext.isIE){treegrid.getEl().unmask();} 
				} 
			}
		});
		
		var treesm = new Ext.ux.CheckboxSelectionModel ();
		var treegrid = new Ext.ux.maximgb.tg.GridPanel ({
			loadMask: true,
			frame: false,
			bodyStyle: "border: 1px solid #8db2e3;",
			autoHeight: true,
			forceFit: true,
			select: true,
			listeners: { 
				render: function () { 
					this.store.load({
						params:{
							portalWidgets:Ext.encode(obj.portalWidgets),
							config:obj.getConfig()
						}
					});
					
					var gcm = treegrid.getColumnModel();
					
					if(gcm.getColumnHeader(gcm.getColumnCount()-1) == '<div class="x-grid3-hd-checker" id="hd-checker">&#160;</div>'){
						gcm.moveColumn(gcm.getColumnCount()-1,0);
					}									 
				} 
			},
			viewConfig: {
				forceFit: true
			},
			columns: [
			{
				dataIndex: "title",
				header: "Category > Title",
				sortable: true,
				hidden: false,
				hideable: false,
				align: "left",
				id: "title",
				width: 20
			},
			{
				dataIndex: "description",
				header: "Description",
				sortable: true,
				hidden: false,
				hideable: false,
				align: "left",
				id: "description",
				width: 50,
				renderer : function(value, metadata, record){
					var qtip = value;  return '<span qtip="' + qtip + '">' + value + '</span>';
				}
			}/*,
			{
				dataIndex: "image",
				header: "Image",
				sortable: true,
				hidden: false,
				hideable: true,
				align: "left",
				id: "image",
				width: 30
			}*/,
			treesm
			],
			master_column_id: "title",
			store: treestore,
			sm: treesm
		});
		
		//creating a classic form
		var formPanel = new Ext.FormPanel ({width: "100%",bodyStyle: "border:0px;padding-left:5px",buttonAlign:'center'});
		//adding the configuration of the portal to the form
		var configHiddenField = new Ext.form.Hidden({name: 'config', value:this.getConfig()});
		formPanel.add(configHiddenField);
		
		//adding the portal widgets to the form, for comparison
		var portalWidgetsHiddenField = new Ext.form.Hidden({name: 'portalWidgets', value:Ext.encode(this.portalWidgets)});
		formPanel.add(portalWidgetsHiddenField);
				
		//adding the tree grid
		formPanel.add(treegrid);
					
		//adding a submit button that submits the classic form
		var submitButton = new Ext.Button ({text:'Save & Refresh Page',
											icon: "/images/famfamfam/accept.png",
											cls: "x-btn-text-icon",
											handler: function () { 
												
												formPanel.getForm().submit({
													url:'/appFlower/changePortalWidgets',
													method:'POST',
													params:{"selections":treegrid.getSelectionModel().getSelectionsJSON(["widget"])},
													waitMsg:'loading...',
													failure:function(form,action){
														var onclose=function(){if(action.result && action.result.redirect){window.location.href=action.result.redirect;}}; if(action.result){ if(action.result.message){Ext.Msg.alert("Failure", action.result.message, onclose);}}else{Ext.Msg.alert("Failure", "Some error appeared!", onclose);}
													},
													success:function(form,action){
														if(action.result.message)
														{
															Ext.Msg.alert("Success", action.result.message, function(){
																if(action.result.redirect){
																	window.location.href=action.result.redirect;
																}
															});
														}
														else{
															if(action.result.redirect){
																window.location.href=action.result.redirect;
															}
														}
													}
												}); 
											}						
											});

		//create a portal inside the window, with two columns
		this.portal = new Ext.ux.Portal ({
			region: 'center',
			buttonAlign: 'center',
			buttons: [submitButton],
			items: [
			{
				columnWidth: 1,
				style: 'overflow-x:hidden;overflow-y:scroll;padding:5px;'
			}
			]
		});				
		
		this.portal.items.items.push(formPanel);
		
		this.widgetSelectorWindowConfig.items.push(this.portal);				
		this.widgetSelectorWindow = new Ext.Window (this.widgetSelectorWindowConfig);
		
		this.widgetSelectorWindow.show(button);
		
		this.mask.hide();
	},
    
    //create the window widget selector
    createWidgetSelector:function(title,button) {
    	this.widgetSelectorWindowConfig = this.widgetSelectorWindowConfig || {};
		Ext.applyIf(this.widgetSelectorWindowConfig, {
			constrain: true,
			layout: 'fit',
			width: '90%',
			height: 500,
			maximizable: true,
			closeAction: 'hide',
			plain: true,
			modal: true,
			items:[],
			title:title
		});
		
		this.mask = new Ext.LoadMask(Ext.get("body"), {msg: "<b>Opening Widget Selector</b> <br>Please Wait...",removeMask:true});
		this.mask.show();
		
		this.retrieveWidgets(button); 
    },
    
    //show the window with layout selector
    showLayoutSelector:function(button,title,layouts) {
    	if(this.layoutSelectorWindow)
    	{
    		this.layoutSelectorWindow.show(button);
    	}
    	else
    	{
    		this.createLayoutSelector(title,layouts);
    		this.layoutSelectorWindow.show(button);
    	}
    },
    
    //create the window layout selector
    createLayoutSelector:function(title,layouts) {
    	this.layoutSelectorWindowConfig = this.layoutSelectorWindowConfig || {};
		Ext.applyIf(this.layoutSelectorWindowConfig, {
			constrain: true,
			layout: 'fit',
			width: '60%',
			height: 500,
			closeAction: 'hide',
			plain: true,
			modal: true,
			items:[],
			title:title
		});
		//left column
		this.portalLC = [];
		//right column
		this.portalRC = [];

		for(var i=0;i<layouts.length;i++)
		{
			var items=[];
			for(var j=0;j<layouts[i].length;j++)
			{
				items[j] = {};
				items[j].title=layouts[i][j]+'%';
				items[j].columnWidth=layouts[i][j]/100;
				if(j<(layouts[i].length-1))
				{
					items[j].style='border-right:1px solid #cc0000;';
				}
			}
			
			if(i % 2 == 0)
			{
				//add to left column a panel with some columns configuration inside
				this.createLayoutSelectorColumn(this.portalLC,items,layouts[i]);
			}
			else
			{
				//add to right column a panel with some columns configuration inside
				this.createLayoutSelectorColumn(this.portalRC,items,layouts[i]);
			}
		}
		
		//create a portal inside the window, with two columns
		this.portal = new Ext.ux.Portal ({
			region: 'center',
			items: [
			{
				columnWidth: 0.50,
				style: 'padding:10px 5px 10px 10px',
				items: this.portalLC
			},
			{
				columnWidth: 0.50,
				style: 'padding:10px 10px 10px 5px',
				items: this.portalRC
			}
			]
		});

		this.layoutSelectorWindowConfig.items.push(this.portal);				
		this.layoutSelectorWindow = new Ext.Window (this.layoutSelectorWindowConfig); 
    },
    
    //create a panel for layout selector, sending a column, some items, and a layout configuration array
    createLayoutSelectorColumn:function(column,items,layout)
    {
    	var p = new Ext.Panel({
		    layout:'column',
		    hideBorders:true,
		    style:'border:1px solid #cc0000;cursor:pointer;',
		    items: items,
		    clickEventInstalled:false
		});
							
		p.on('afterlayout',function(panel){

			if(column[0].id!=panel.id)
	    	{
	    		p.el.setStyle('marginTop','20px');
	    	}
									
	        for(var i = 0; i < panel.items.items.length; i++) {
	        	panel.items.items[i].header.setStyle('height','137px');
				panel.items.items[i].header.setStyle('border','0');
				panel.items.items[i].header.dom.firstChild.style.marginLeft='40%';
	        }			
				        	        
	        if(!panel.clickEventInstalled)
	        {	        		        	
		        panel.el.on('click', function( panel, e ){            
		            
		        	//if panel is clicked change layoutNewType
		        	this.layoutNewType=Ext.encode(layout);		
		        	
		        	var config=this.getConfig();
		        	
		        	Ext.Ajax.request({ 
						url: "/appFlower/savePortalState", 
						method:"post", 
						params:{"config":config}, 
						success:function(response, options){
						/*	response=Ext.decode(response.responseText);if(response.message){Ext.Msg.alert("Success",response.message);}*/
							window.location.href=window.location.href;
						},
						failure: function(response,options) {
							if(response.message){Ext.Msg.alert("Failure",response.message);}
						}				
					});
		        	
			    },this);
	        }
	        
	        panel.clickEventInstalled=true;
	        
		},this);
						
		column.push(p);	
    }
});
Ext.reg('portal', Ext.ux.Portal);


Ext.ux.Portal.DropZone = function(portal, cfg){
    this.portal = portal;
    Ext.dd.ScrollManager.register(portal.body);
    Ext.ux.Portal.DropZone.superclass.constructor.call(this, portal.bwrap.dom, cfg);
    portal.body.ddScrollConfig = this.ddScrollConfig;
};

Ext.extend(Ext.ux.Portal.DropZone, Ext.dd.DropTarget, {
    ddScrollConfig : {
        vthresh: 50,
        hthresh: -1,
        animate: true,
        increment: 200
    },

    createEvent : function(dd, e, data, col, c, pos){
        return {
            portal: this.portal,
            panel: data.panel,
            columnIndex: col,
            column: c,
            position: pos,
            data: data,
            source: dd,
            rawEvent: e,
            status: this.dropAllowed
        };
    },

    notifyOver : function(dd, e, data){
        var xy = e.getXY(), portal = this.portal, px = dd.proxy;

        // case column widths
        if(!this.grid){
            this.grid = this.getGrid();
        }

        // handle case scroll where scrollbars appear during drag
        var cw = portal.body.dom.clientWidth;
        if(!this.lastCW){
            this.lastCW = cw;
        }else if(this.lastCW != cw){
            this.lastCW = cw;
            portal.doLayout();
            this.grid = this.getGrid();
        }

        // determine column
        var col = 0, xs = this.grid.columnX, cmatch = false;
        for(var len = xs.length; col < len; col++){
            if(xy[0] < (xs[col].x + xs[col].w)){
                cmatch = true;
                break;
            }
        }
        // no match, fix last index
        if(!cmatch){
            col--;
        }

        // find insert position
        var p, match = false, pos = 0,
            c = portal.items.itemAt(col),
            items = c.items.items;

        for(var len = items.length; pos < len; pos++){
            p = items[pos];
            var h = p.el.getHeight();
            if(h !== 0 && (p.el.getY()+(h/2)) > xy[1]){
                match = true;
                break;
            }
        }

        var overEvent = this.createEvent(dd, e, data, col, c,
                match && p ? pos : c.items.getCount());

        if(portal.fireEvent('validatedrop', overEvent) !== false &&
           portal.fireEvent('beforedragover', overEvent) !== false){

            // make sure proxy width is fluid
            px.getProxy().setWidth('auto');

            if(p){
                px.moveProxy(p.el.dom.parentNode, match ? p.el.dom : null);
            }else{
                px.moveProxy(c.el.dom, null);
            }

            this.lastPos = {c: c, col: col, p: match && p ? pos : false};
            this.scrollPos = portal.body.getScroll();

            portal.fireEvent('dragover', overEvent);

            return overEvent.status;;
        }else{
            return overEvent.status;
        }

    },

    notifyOut : function(){
        delete this.grid;
    },
    
    notifyDrop : function(dd, e, data){
        delete this.grid;
        if(!this.lastPos){
            return;
        }
        var c = this.lastPos.c, col = this.lastPos.col, pos = this.lastPos.p;

        var dropEvent = this.createEvent(dd, e, data, col, c,
                pos !== false ? pos : c.items.getCount());

        if(this.portal.fireEvent('validatedrop', dropEvent) !== false &&
           this.portal.fireEvent('beforedrop', dropEvent) !== false){

           	dd.proxy.getProxy().remove();
            dd.panel.el.dom.parentNode.removeChild(dd.panel.el.dom);
            if(pos !== false){
                c.insert(pos, dd.panel);
            }else{
                c.add(dd.panel);
            }
            
            var widget=dd.panel;
            
            //if(console)console.log(widget);
            //if(console)console.log('type:'+widget.getXType());
            //if(console)console.log('cw:'+c.lastSize.width);
            
            //resize to column width
            widget.el.resize(c.lastSize.width-10);
                   
            //Ext.util.Observable.capture(widget, function(e){if(console)console.info(e)});      
            //Ext.util.Observable.capture(widget.view, function(e){if(console)console.info(e)});      
            //Ext.util.Observable.capture(widget.store, function(e){if(console)console.info(e)});      
                          
            //grid widget
            if(widget.getXType()=='grid'||widget.getXType()=='ux-maximgb-treegrid')
            {
            	widget.store.reload();	
            	
            	widget.body.resize(c.lastSize.width-14);
        	}
        	else if(widget.getXType()=='form')
        	{
        		widget.body.resize(c.lastSize.width-14);
        		
        		//if(console)console.log(widget);
        		        		
        		var items=widget.items.items;
        		       		
        		for(var i=0;i<items.length;i++)
        		{
        			//this is a fieldset
        			if(items[i].items)
        			{
	        			items[i].setSize(c.lastSize.width-24);
	        			
	        			var fieldset_width=c.lastSize.width-46;
	        			
	        			var inputs=items[i].items.items;
	        			
	        			var label_width=fieldset_width*0.3;
	        			var input_width=fieldset_width*0.65;
	        			
	        			label_width=(label_width>75)?75:label_width;
	        			input_width=(input_width<250)?input_width:250;
	        			input_padding_left=label_width;
	        			
	        			for(var j=0;j<inputs.length;j++)
	        			{       				
	        				if(!inputs[j].isXType('htmleditor'))
	        				{
	        					if(!inputs[j].isXType('itemselector'))
	        					{
	        						inputs[j].setSize(input_width);
	        					}
	        					else
	        					{
	        						//to do??
	        						inputs[j].msWidth=input_width;
	        					}
	        					       					
	        					if(inputs[j].wrap)
	        					{		
	        						inputs[j].wrap.dom.parentNode.style.paddingLeft=input_padding_left+'px';
	        					}
	        					if(inputs[j].container&&inputs[j].container.dom.previousSibling)
	        					{
	        						inputs[j].container.dom.previousSibling.style.width=label_width+'px';
	        						inputs[j].container.dom.previousSibling.style.height='40px';
	        					}
	        				}
	        			}
        			}
        			else
        			{
        				var column_width=c.lastSize.width-24;
			        			
	        			var input=items[i];
	        			
	        			var label_width=column_width*0.3;
	        			var input_width=column_width*0.7;
	        			
	        			label_width=(label_width>75)?75:label_width;
	        			input_width=(input_width<250)?input_width:250;
	        			input_padding_left=label_width;
	        			
	        			//console.log(inputs[j].getXType());
	        						
        				if(!input.isXType('htmleditor'))
        				{
        					if(!input.isXType('itemselector'))
        					{
        						input.setSize(input_width);
        					}
        					else
        					{
        						//to do??
        						input.msWidth=input_width;			        						
        					}
        					       			
        					if(input.wrap)
        					{		
        						input.wrap.dom.parentNode.style.paddingLeft=input_padding_left+'px';
        					}
        					/*if(input.container&&input.container.dom.previousSibling)
        					{
        						input.container.dom.previousSibling.style.width=label_width+'px';
        						input.container.dom.previousSibling.style.height='10px';
        					}*/
        				}        				
        			}
        		}
        	}
        	else if(widget.getXType()=='panel')
        	{
        		widget.body.resize(c.lastSize.width-14);
        	}
        	
        	c.doLayout();

            this.portal.fireEvent('drop', dropEvent);

            // scroll position is lost on drop, fix it
            var st = this.scrollPos.top;
            if(st){
                var d = this.portal.body.dom;
                setTimeout(function(){
                    d.scrollTop = st;
                }, 10);
            }

        }
        delete this.lastPos;
    },
    
    // internal cache of body and column coords
    getGrid : function(){
        var box = this.portal.bwrap.getBox();
        box.columnX = [];
        this.portal.items.each(function(c){
             box.columnX.push({x: c.el.getX(), w: c.el.getWidth()});
        });
        return box;
    },

    // unregister the dropzone from ScrollManager
    unreg: function() {
        //Ext.dd.ScrollManager.unregister(this.portal.body);
        Ext.ux.Portal.DropZone.superclass.unreg.call(this);
    }
});
