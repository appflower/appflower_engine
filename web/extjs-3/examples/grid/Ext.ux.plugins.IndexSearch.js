Ext.namespace('Ext.ux.plugins'); 
/**
 * Ext.ux.plugins.IndexSearch plugin for Ext.grid.GridPanel
 *
 * @author  Prakash Paudel
 * @date    January 14, 2010
 *
 * @class Ext.ux.plugins.IndexSearch
 * @extends Ext.util.Observable
 */
Ext.ux.plugins.IndexSearch = function(config) {
    Ext.apply(this, config);
    Ext.ux.plugins.IndexSearch.superclass.constructor.call(this);
}; 

Ext.extend(Ext.ux.plugins.IndexSearch, Ext.util.Observable, {	
    init:function(grid) {
		Ext.apply(grid,{			
			onRender:grid.onRender.createSequence(function(ct, position){
				Ext.DomHelper.append(grid.container,{tag:'div',id:'_unique_search_panel'})
				this.initialCount = 0;
				this.facetGridServer = new Ext.grid.GridPanel({										
					listeners:{
						click: function(e){			 
							var t = e.getTarget();							
							if(t.className != 'x-grid3-header'){
					            var r = e.getRelatedTarget();
					            var v = this.view;
					            var ci = v.findCellIndex(t.parentNode);
					            var ri = v.findRowIndex(t);					            
					           
					            //alert(ci); alert(ri);            
					            if(ci === false || ri === false) return ;
					            var cell = this.getView().getCell(ri,ci);			          
					            
					            if(t.className == 'ux-grid-filter'){				            	
					            	var filtersObj = grid.filters;
					            	var filter = filtersObj.filters.get('reporter');
					            	//Get the data in cell
					            	var data = this.getView().getCell(ri,ci).innerHTML;					            	
					            	var valueNode = Ext.DomQuery.selectNode(".ux-grid-filter-hidden-value",this.getView().getCell(ri,ci));					            	
					            	var finalValue = valueNode.innerHTML;
					            	filter.setValue(finalValue);	
					            	filter.setActive(true);
					            	
					            }	
					        }          
				            
						}
					},
					store: new Ext.data.JsonStore({					   
					    url: '/loganalysis/facetJson?by=ip',					   
					    root: 'rows',					    
					    fields: ['facet_name', {name:'facet_count',sortType:'htmlAsInt'},'facet_id'],
					    autoLoad:false
					}),
				    colModel: new Ext.grid.ColumnModel({
				        defaults: {
				            width: 120,
				            sortable: true
				        },
				        columns: [
				            {id: 'server', header: 'Server', width: 200, sortable: true, sortType:"asIp", dataIndex: 'facet_name'},
				            {
				            	header: 'Count', dataIndex: 'facet_count', sortable:true, align:"right", renderer:function(value){
					            	var re = /<div class="ux-grid-filter-hidden-value">(.*)<\/div>/i;
					        		value = String(value).replace(re,"");
				            		var formatted = Ext.util.Format.number(Ext.util.Format.stripTags(value),"0,000");
				            		return value.toString().replace(Ext.util.Format.stripTags(value),formatted);
				            	}
				            }
				        ]
				    }),	
				    height:227,
				  
				    autoScroll:true,
				    sm: new Ext.grid.RowSelectionModel({singleSelect:true})
				})
				this.facetGridSev = new Ext.grid.GridPanel({
					listeners:{
					click: function(e){			 
						var t = e.getTarget();							
						if(t.className != 'x-grid3-header'){
				            var r = e.getRelatedTarget();
				            var v = this.view;
				            var ci = v.findCellIndex(t.parentNode);
				            var ri = v.findRowIndex(t);					            
				           
				            //alert(ci); alert(ri);            
				            if(ci === false || ri === false) return ;
				            var cell = this.getView().getCell(ri,ci);			          
				            
				            if(t.className == 'ux-grid-filter'){				            	
				            	var filtersObj = grid.filters;
				            	var filter = filtersObj.filters.get('sev');
				            	//Get the data in cell
				            	var data = this.getView().getCell(ri,ci).innerHTML;					            	
				            	var valueNode = Ext.DomQuery.selectNode(".ux-grid-filter-hidden-value",this.getView().getCell(ri,ci));					            	
				            	var finalValue = valueNode.innerHTML;
				            	filter.setValue(finalValue);	
				            	filter.setActive(true);
				            	
				            }	
				        }          
			            
					}
				},
					store: new Ext.data.JsonStore({					   
					    url: '/loganalysis/facetJson?by=sev',					   
					    root: 'rows',					    
					    fields: ['facet_name', {name:'facet_count',sortType:'htmlAsInt'},'facet_id'],
					    autoLoad:false
					}),
				    colModel: new Ext.grid.ColumnModel({
				        defaults: {
				            width: 120,
				            sortable: true
				        },
				        columns: [
				            {id: 'severity', header: 'Severity', width: 200, sortable: true, sortType:"asIp", dataIndex: 'facet_name'},
					        {
				            	header: 'Count', dataIndex: 'facet_count', sortable:true,align:"right", renderer:function(value){
					            	var re = /<div class="ux-grid-filter-hidden-value">(.*)<\/div>/i;
					        		value = String(value).replace(re,""); 
				            		var formatted = Ext.util.Format.number(Ext.util.Format.stripTags(value),"0,000");
				            		return value.toString().replace(Ext.util.Format.stripTags(value),formatted);
				            	}
				            }
				        ]
				    }),	
				    height:227,
				   
				    autoScroll:true,
				    sm: new Ext.grid.RowSelectionModel({singleSelect:true})
				})
				function renderBtn(val, p, record) {  
			        var contentId = Ext.id();
			        createGridButton.defer(1, this, [val, contentId, record]);
			        return('<div id="' + contentId + '"></div>');
			    }
				function createGridButton(value, contentid, record) {
			        new Ext.Button({text: 'Filter', handler : function(btn, e) {			            
			        	var id = record.get('facet_id');
			        	grid.filters.getFilter('reporter').setValue("s-0-"+id)
			        	grid.filters.getFilter('reporter').setActive(true)
			            
			        }}).render(document.body, contentid);
			    }
				this.searchPanel = new Ext.Panel({
					//title:'Faceted Result',
					height:270,
					layout:'column',
					autoWidth:true,
					collapsible:true,
					 hidden:true,
					 hideMode:'visibility',
					//collapsed:true,
					animCollapse:true,
					titleCollapse:true,
					headerAsText:true,
					frame:true,
					applyTo:'_unique_search_panel',
					items: [{
						title:'Grouped by server',
						items:this.facetGridServer,
						columnWidth:.49
					},{
						html:'&nbsp',
						columnWidth:.02
					},{
						title:'Grouped by severity',
						items:this.facetGridSev,
						columnWidth:.47
					}]
				})				
				
				grid.getStore().on('load',function(){
					if(!grid.getStore().getCount()){
						this.facetGridServer.getStore().removeAll();
						this.facetGridSev.getStore().removeAll();
						return;
					}
					var ls = grid.getStore().lastOptions;
					var bkp = ls.params.start;
					ls.params.start = 0;
					var json = Ext.util.JSON.encode(ls.params);
					ls.params.start = bkp;
					if(json == this.initialCount && ls.params.start){
						return;
					}
					
					this.initialCount = json;
					this.searchPanel.show();
					this.facetGridServer.getStore().reload();
					this.facetGridServer.getEl().mask('Getting data for group by server');
					this.facetGridSev.getEl().mask('Getting data for group by severity');			
					
				},this);
				this.facetGridServer.getStore().on("load",function(){
					this.facetGridServer.getEl().unmask();
					this.facetGridSev.getStore().reload();						
				},this)
				this.facetGridSev.getStore().on("load",function(){this.facetGridSev.getEl().unmask()},this)
			})
		});
    }	
});