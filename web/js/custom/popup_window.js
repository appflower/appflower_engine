var win;
if(!win){
    win = new Ext.Window({        
        width:700,
        height:500,
        autoScroll:true,
        maximizable:true,
        closeAction:'hide',
        plain: true,
        title:immParam1,
        items: new Ext.Panel ({        	
        	width: "auto",
        	frame: true,        	
        	collapsible: true,
        	style: "padding-right:5px;",
        	tools: [
        	{
        	id: "gear",
        	handler: function () { Ext.Msg.alert('Message', 'The Settings tool was clicked.'); }
        	},
        	{
        	id: "close",
        	handler: function (e,target,panel) { panel.ownerCt.remove(panel, true); }
        	}
        	],
        	items: [
        	popup_widget
        	]
        }),

        buttons: [{
            text: 'Close',
            handler: function(){
                win.hide();
            }
        }]
    });
}
win.show(this);
    
