/*
* Extended Grid
* Ability to reorder the grid's row
* @author: Prakash Paudel
*/
Ext.namespace('Ext.ux.plugins');

Ext.ux.plugins.GridRowOrder = function(config){    
	Ext.apply(this,config);
}
Ext.extend(Ext.ux.plugins.GridRowOrder, Ext.util.Observable,{		    
	init: function(grid){		
		Ext.apply(grid,{
            
		});
	}
});


/**
 * Event Correlation Trigger Plugin
 * 
 * This is the custom plugin created for the event correlation triggers wizard
 * 
 * @author Prakash Paudel * 
 */

//Create a constructor for the plugin
Ext.ux.plugins.EventCorrelationTriggers = function(config){	
	Ext.apply(this,config,{
		
	});
}

//Extend the plugin to Ext.util.Observable
Ext.extend(Ext.ux.plugins.EventCorrelationTriggers, Ext.util.Observable,{
	init: function(grid){	
		Ext.apply(grid,{
			/**
			 *  Grid row reorder plugin, if includes row up button
			 */
			rowUp:true,
			
			/**
			 *  Grid row reorder plugin, if includes row down button
			 */
            rowDown:true,
            
            /**
			 *  Grid row reorder plugin, if includes row remove button
			 */
            rowRemove: true,
            
            /**
			 *  Grid row reorder plugin, if includes row edit button
			 */
            rowEdit: true,
            
            /**
			 *  Grid row reorder plugin, the header of the row reorder column
			 */
            colHeader:'',
            
            /**
             *  Preserve order state
             */
            preserveOrder:false,
            
            restorePerserveOrder: function(){
				Ext.Ajax.request({
					url:'/eventmanagement/getPreserveOrder',
					method:"POST",
					success: function(r){
						var tb = grid.getTopToolbar();
						for(var i=0;i<tb.items.items.length;i++){
							var ind = tb.items.items[i];
							if(ind.name && ind.name == 'preserve-row-order'){
								ind.setValue(r.responseText);		
							}
						}
					},
					failure: function(){						
						Ext.Msg.alert('Error','Connection to server has been lost<br>Triggers could not be saved. Please try again!')
					}
				})
				
			},
            // Customize the grid on render
			onRender: grid.onRender.createSequence(function(ct, position) {	
				
				/**
				 * Restore the preserve order state on render
				 */
				this.restorePerserveOrder();
				/**
				 * Create a temporary record object
				 */
				// create a Record constructor from a description of the fields
        		var triggerAttr = Ext.data.Record.create([ // creates a subclass of Ext.data.Record
        		    {name: 'event'},
        		    {name: 'event_classification'},
        		    {name: 'source_ip'},
        		    {name: 'attacking_ip'},
        		    {name: 'username'},
        		    {name: 'service'}
        		]);

        		
				/**
				 * Create a form to add new triggers to the gird
				 */
                Ext.QuickTips.init(true);
                //Form panel
                var eventCombo = new Ext.form.ComboBox({
    		    	name:'event',    		    	
    		    	tpl:new Ext.XTemplate(
    		    		'<tpl for=".">',
    		    			'<tpl if="flag == \'parent\'"><div class="x-combo-list-item" style=" background-color:#dfe8f6;display:block"><span style="font-weight:bold;">{value}</span></div></tpl>',
    		    			'<tpl if="flag == \'child\'"><div class="x-combo-list-item"><span style="padding-left:10px;">{value}</span></div></tpl>',
    		    		'</tpl>'
				    ),
				    resizable:true,
				    editable:false,
        		    typeAhead: true,
        		    fieldLabel:'Event',
        		    triggerAction: 'all',
        		    lazyRender:true,
        		    mode: 'local',
        		    store: new Ext.data.JsonStore({
        		        url:'/eventmanagement/getEventCorrelationFormData?event',
        		        root:'data',
        		        autoLoad:true,
        		        fields: [
        		            'key',
        		            'value',
        		            'flag'
        		        ]
        		    }),
        		    valueField: 'key',
        		    displayField: 'value',
        		    flagField:'flag',
        		    allowBlank:false
                });
                var form = new Ext.form.FormPanel({
                	bodyStyle:'padding:5px',
                	isEdit: false,
                	record:null,
                	rowIndex:null,
                	items:[{
                		xtype: 'fieldset',
                		title:'Add Trigger',
                		defaultType: 'textfield',
                		autoHeight:true,
                		defaults:{width:200,msgTarget:'title'},
                		items:[
                		    eventCombo,
                		    
                		{
                    		name:'source_ip',
                    		xtype:'textfield',
                    		fieldLabel:'Source Ip'
                    	},{
                    		name:'attacking_ip',
                    		xtype:'textfield',
                    		fieldLabel:'Attacking Ip'
                    	},{
                    		name:'username',
                    		xtype:'textfield',
                    		fieldLabel:'Username'                    		
                    	},{
                    		name:'service',
                    		xtype:'textfield',
                    		fieldLabel:'Service'                    		
                    	}]
                	}],
                	buttons:[{
                		text:'Save',
                		handler: function(){
                			if(form.getForm().isValid()){ 		
		                		
		                		// create Record instance
		                		var values = [];
		                		if(form.getForm().findField('event').getValue().match(/child:/)){
		                			values['event'] = "("+form.getForm().findField('event').getValue().replace("child:",'')+") "+form.getForm().findField('event').getEl().dom.value;
		                		}else{
		                			values['event'] = '-';
		                		}
		                		if(form.getForm().findField('event').getValue().match(/parent:/)){
		                			values['event_classification'] = "("+form.getForm().findField('event').getValue().replace("parent:",'')+") "+form.getForm().findField('event').getEl().dom.value;
		                		}else{
		                			values['event_classification'] = '-';
		                		}
		                		var newTrigger = new triggerAttr({
		                		        event: values['event'],
		                		        event_classification: values['event_classification'],
		                		        source_ip:form.getForm().findField('source_ip').getValue(),
		                		        attacking_ip: form.getForm().findField('attacking_ip').getValue(),
		                		        username: form.getForm().findField('username').getValue(),
		                		        service: form.getForm().findField('service').getValue()
		                		});
		                		
		                		// Add the data from the form to the grid's store
		                		// This will add the record at the end of the grid
		                		if(form.isEdit){
		                			grid.getStore().remove(form.record);
		                			grid.getStore().insert(form.rowIndex,newTrigger);
		                			grid.getStore().commitChanges();
		                		}else{
			                		grid.getStore().insert(grid.getStore().getCount(),newTrigger);
			                		grid.getStore().commitChanges();
		                		}
		                		form.getForm().reset();
		                		win.hide();
                			}
                		}
                	}]
                	
                })
                
                //Window for the popup add trigger form
                var win = new Ext.Window({
                	layout:'form',
                	autoWidth:true,
                	autoHeight:true,
                	closeAction:'hide',
                	items: form
                })
                
                // Get the toolbar of the grid
                var tb = grid.getTopToolbar();
                
                // Add a new button to add triggers to the toolbar
                tb.addButton({
                	text:'Add Trigger',
                	tooltip:'Add new trigger to the rule',
                	iconCls:'icon-plus',
                	handler: function(){
	                	form.isEdit=false;
	                    form.rowIndex = null;
                		win.show();
                	}
                })
                
                
                // Add a tool separator to the toolbar
                tb.addSeparator();
                
                // Add a new button to reset the grid's data to the toolbar
                tb.addButton({
                	text:'Reset',
                	tooltip:'Reset the list to original triggers',
                	iconCls:'icon-default',
                	handler: function(){
	                	Ext.Ajax.request({
	                		url:'/eventmanagement/storeTriggers?reset',
	                		method:"POST",
	                		success: function(){
		                		grid.getStore().reload(Ext.apply(grid.getStore().lastOptions.params,{		                			
		                			fresh:true		                			
		                		}));
	                		},
	                		failure: function(){
	                			mask.hide();
	                			Ext.Msg.alert('Error','Connection to server has been lost<br>Please try again!')
	                		}
	                	})
                		
                	}
                })
                
                // Add a tool separator to the toolbar
                tb.addSeparator();
                
                // Add a checkbox for preserving the grid's row order to the toolbar
                tb.addField(new Ext.form.Checkbox({
                	boxLabel:'Preserve order',                	      	
                	name:'preserve-row-order'
                }))
                
                
                // Get the column model of the gird and add a new column at the end
                // for the new grid's row reorder plugin
                var cm = grid.getColumnModel();
                var columns = cm.getColumnsBy(function(c){return true});
                columns.push({
                	header:this.colHeader,
                    menuDisabled:true,
                    dataIndex:null,
                    renderer:function(value,metadata,record,rowIndex,colIndex,store){
                		var r = '<div>';
                		if(grid.rowUp) r+='<a href="#" class="row-re-order-up" qtip="Move record up"></a>';
                		if(grid.rowDown) r+='<a href="#" class="row-re-order-down" qtip="Move record down"></a>';
                		if(grid.rowEdit) r+='<a href="#" class="row-re-order-edit" qtip="Edit record"></a>';
                		if(grid.rowRemove) r+='<a href="#" class="row-re-order-remove" qtip="Remove record"></a>';   
                		r+='</div>';
                		return r;
                    },
                    toolTip:"Re-order the rows",
                    width: (this.rowUp?30:0)+(this.rowDown?30:0)+(this.rowEdit?30:0)+(this.rowRemove?30:0)
                });              
                /* Alternative for reconfigure *****************/
                grid.view.refresh(true)
                grid.view.initData(grid.getStore(), new Ext.grid.ColumnModel(columns));
                grid.store = grid.getStore();
                grid.colModel = new Ext.grid.ColumnModel(columns);
                if(grid.rendered){
                	grid.view.refresh(true);
                }
                /***********************************************/
                
                // Add cell click event to the grid to catch the row reorder requests..
                grid.on('cellclick',function(grid,rowIndex,columnIndex,e){
                    var target = e.getTarget();
                    
                    // If clicked row up button
                    if(target.className && target.className == 'row-re-order-up'){
                        var record = grid.getStore().getAt(rowIndex);
                        if(rowIndex>0){
                            grid.getStore().remove(record);
                            grid.getStore().insert(rowIndex-1,record);
                            var row = grid.getView().getRow(rowIndex-1);
                            Ext.get(row).syncFx();
                            Ext.get(row).slideIn('b',{
                                easing: 'easeOut',
                                duration: .5,
                                remove: false,
                                useDisplay: false,
                                callback: function(){
                                   
                                }
                            });
                            Ext.get(row).highlight("dfe8f6", {
                                attr: "background-color",                                
                                easing: 'easeIn',
                                duration: 1
                            });                          
                        }
                    }
                    
                    // If clicked row down button
                    if(target.className && target.className == 'row-re-order-down'){
                        var record = grid.getStore().getAt(rowIndex);
                        if(rowIndex < grid.getStore().getCount()-1){
                            grid.getStore().remove(record);
                            grid.getStore().insert(rowIndex+1,record);
                            var row = grid.getView().getRow(rowIndex+1);
                            Ext.get(row).syncFx();
                            Ext.get(row).slideIn('t', {
                                easing: 'easeOut',
                                duration: .5,
                                remove: false,
                                useDisplay: false,
                                callback: function(){
                                   
                                }
                            });
                            Ext.get(row).highlight("dfe8f6", {
                                attr: "background-color",
                                easing: 'easeIn',
                                duration: 1
                            });  
                           
                        }
                    }
                    
                    // If clicked row edit button
                    if(target.className && target.className == 'row-re-order-edit'){
                        var record = grid.getStore().getAt(rowIndex);
                        var event,event_classification,source_ip;
                        if(record.get('event') != "-" && record.get('event') != ''){
                        	var replace =record.get('event').toString().replace("(","").replace(")","");
                        	event = "child:"+replace.match("[0-9]+");                        	
                        }
                        if(record.get('event_classification') != "-" && record.get('event_classification') != ''){
                        	var replace = record.get('event_classification').toString().replace("(","").replace(")","");
                        	event = "parent:"+replace.match("[0-9]+");                        	
                        }
                        var editTrigger = new triggerAttr({
            		        event: event,            		        
            		        source_ip:record.get('source_ip'),
            		        attacking_ip: record.get('attacking_ip'),
            		        username: record.get('username'),
            		        service: record.get('service')
                        });
                       
                        win.show();
                        form.isEdit=true;
                        form.record = record;
                        form.rowIndex = rowIndex;
                        form.getForm().loadRecord(editTrigger);
                        
                    }
                    
                    // If clicked row remove button
                    if(target.className && target.className == 'row-re-order-remove'){
                        var record = grid.getStore().getAt(rowIndex);                                                                            
                        var row = grid.getView().getRow(rowIndex);
                        Ext.get(row).syncFx();
                        Ext.get(row).fadeOut({
                            duration: .5,
                            callback: function(){
                                grid.getStore().remove(record);
                                grid.getStore().commitChanges();
                            }
                        });                        
                    }
                },this);
			})			
		})
	}
})
