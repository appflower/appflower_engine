/**
* Export UI for grids
* 
* @author:Prakash Paudel
*/
Ext.ns("Ext.ux");
Ext.ux.ExportUI = function(config){
	var cfg = {
		shadow:false,
		title:"Exports"
	};	
	this.exportOptions = new Ext.form.FieldSet({
		columnWidth:.5,
		height:120,
		xtype:'fieldset',
		bodyStyle:'padding:10px',
		style:'margin:5px',
		title:'Export options',	
		items:[{html:'Please select an export format'}]
	});
	Ext.apply(cfg,config);
	Ext.apply(this,cfg);	
	Ext.ux.ExportUI.superclass.constructor.call(this,cfg);	
};
Ext.extend(Ext.ux.ExportUI,Ext.Window,{
	findLabel: function(key){
		var label = key;
		if(this.labels){
			Ext.iterate(this.labels,function(k,v){
				if(key == k) label = v;
			});
		}
		return label;
	},
	formatHandler: function(b,s){
		this.exportOptions.removeAll();
		this.exportOptions.add(
			new Ext.form.RadioGroup({
				hideLabel:true,				
				columns:1,					
				defaults:{anchor:'95%'},					
				items:this.findOptions(Ext.isObject(b)?b.getGroupValue():b)
			})
		);		
		this.exportOptions.doLayout();
	},
	findOptions: function(key){
		var options = [];
		var format = null;
		if(this.exportConfig){			
			Ext.iterate(this.exportConfig,function(k,v){
				if(k == key) format = v;
			},this);
		}
		if(format){
			Ext.iterate(format,function(k,v){				
				options.push({
					boxLabel:this.findLabel(k),						
					name:'option',
					inputValue:k
				})
			},this);
		}
		return options;
	},
	findFormats: function(){	
		var formats = [];
		var initiated = false;
		if(this.exportConfig){			
			Ext.iterate(this.exportConfig,function(key,value){				
				formats.push({						
					boxLabel:this.findLabel(key),						
					name:'format',
					inputValue:key,
					checked:initiated?false:true,
					listeners:{
						check: this.formatHandler.createDelegate(this)
					}
				});	
				if(!initiated){
					this.formatHandler(key);
					initiated = true;
				}
				
			},this);			
		}
		return formats;
	},
	onRender: function(ct,position){	
		Ext.ux.ExportUI.superclass.onRender.call(this, ct, position);
		var win = this;
		var hideWindow = function(button){
			win.hide();
		}		
		var uiPanel = new Ext.FormPanel({
			frame:true,
			layout:'column',			
			buttons:[{
				text:"Ok",
				handler: function(b){
					var form = uiPanel.getForm();
					var vals = form.getValues();
					if(!vals.format || !vals.option){
						Ext.Msg.alert("Error","Please select both export format and export option");
						return;
					}
					eval("win."+vals.format+vals.option+"()");
					//hideWindow(b);
				}				
			},{
				text:"Cancel",
				handler: hideWindow	
			}],
			items:[{		
				columnWidth:.5,	
				height:120,				
				bodyStyle:'padding:10px',
				style:'margin:5px',
				xtype:'fieldset',
				title:'Export formats',	
				items:[{
					hideLabel:true,
					xtype:'radiogroup',					
					columns:1,					
					defaults:{anchor:'95%'},					
					items:this.findFormats()
				}]
			},this.exportOptions]
		});
		this.add(uiPanel);
	}	
});