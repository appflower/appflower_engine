Ext.ns("Ext.ux");
Ext.ux.MaximizeTool = function() {
    this.init= function(ct) {
        var maximizeTool = {
            id: 'maximize', 
            handler: handleMaximize, 
            scope: ct, 
            qtip: 'Maximize'
        }; 
        ct.tools = ct.tools || [];
        var newTools = ct.tools.slice();
        ct.tools =newTools;
        for(var i=0, len=ct.tools.length;i<len;i++) {
            if (ct.tools[i].id=='maximize') return;
        }
        
        /*
         * Add maximize just before last tool
         * Just to allow a close tool to be at the last. if any
         */
        if(ct.tools.length > 0){
	        var lastTool = ct.tools[ct.tools.length-1];
	        if(lastTool.id == 'close'){
		        ct.tools[ct.tools.length-1] = maximizeTool;
		        ct.tools[ct.tools.length] = lastTool;
	        }else{
	        	ct.tools[ct.tools.length] = maximizeTool;
	        }
        }else{
        	 ct.tools[ct.tools.length] = maximizeTool;
        }       
    };

    function handleMaximize(event, toolEl, panel){
        panel.originalOwnerCt = panel.ownerCt;
        panel.originalPosition = panel.ownerCt.items.indexOf(panel);
        panel.originalSize=panel.getSize();

        if (!toolEl.window) {
            var defaultConfig = {
                id: (panel.getId() + '-MAX'),
                width: (Ext.getBody().getSize().width - 100),
                height: (Ext.getBody().getSize().height - 100),
                resizable: true,
                draggable: true,
                closable: true,
                closeAction: 'hide',
                hideBorders: true,
                plain: true,
                layout: 'fit',
                autoScroll: true,
                border: false,
                bodyBorder: false,
                frame: true,
                pinned: true,  
                maximizable:true,               
                bodyStyle: 'background-color: #ffffff;'
            };
            toolEl.window = new Ext.Window(defaultConfig);
            toolEl.window.on('hide', handleMinimize, panel);
        }
        if (!panel.dummyComponent) {
            var dummyCompConfig = {
                title: panel.title,
                width: panel.getSize().width,
                height: panel.getSize().height,
                html: '<div style="margin-top:20px;  text-align:center; font-family:verdana;font-size:10px"><b>'+panel.title+'</b><br>This widget is maximized<br><br>Closing the maximized widget window will restore the widget in this area.</div>'
            };
            panel.dummyComponent = new Ext.Panel(dummyCompConfig);
        }
        
        toolEl.window.add(panel);
        if (panel.tools['toggle']) panel.tools['toggle'].setVisible(false);
        if (panel.tools['close']) panel.tools['close'].setVisible(false);
        panel.tools['maximize'].setVisible(false);
        

        panel.originalOwnerCt.insert(panel.originalPosition, panel.dummyComponent);
        panel.originalOwnerCt.doLayout();       
        panel.dummyComponent.setSize(panel.originalSize);
        panel.dummyComponent.setVisible(true);
        toolEl.window.show(this);
    };
    
    function handleMinimize(window) {        
        this.dummyComponent.setVisible(false);
        this.originalOwnerCt.insert(this.originalPosition, this);
        this.originalOwnerCt.doLayout(); 
        this.setSize(this.originalSize);
        this.tools['maximize'].setVisible(true);
        if (this.tools['toggle']) this.tools['toggle'].setVisible(true);
        if (this.tools['close']) this.tools['close'].setVisible(true);
    }
    
    
};