Ext.namespace('Ext.ux.plugins'); 
/**
 * Ext.ux.plugins.AddExpandListButton plugin for Ext.grid.GridPanel
 *
 * @author  Prakash Paudel
 * @date    November 6, 2009
 *
 * @class Ext.ux.plugins.AddExpandListButton
 * @extends Ext.util.Observable
 */
Ext.ux.plugins.AddExpandListButton = function(config) {
    Ext.apply(this, config);
    Ext.ux.plugins.AddExpandListButton.superclass.constructor.call(this);
}; 

Ext.extend(Ext.ux.plugins.AddExpandListButton, Ext.util.Observable, {	
    init:function(grid) {
		Ext.apply(grid,{
			/*
			 * Configs
			 */
			expandedText : "Expanded View",
			listText : "List View",
			buttonState:"list",
			expandedIconCls:"icon-expanded-view",
			listIconCls:"icon-list-view",	
			selectedIconCls:"icon-selected-view",
			/************************************************************************************************/
			
			onRender:grid.onRender.createSequence(function(ct, position){	
				this.expandListButton = new Ext.Button({
					text: grid.expandedText,					
				   	handler: this.mainButtonHandler,				   
				    iconCls:this.expandedIconCls,
				    tooltip:"Click to toggle the view"
				});				
				this.topToolbar = grid.getTopToolbar();				
				this.topToolbar.addFill();
				this.topToolbar.add(this.expandListButton);
				grid.getView().on("refresh",this.reconfigureGrid);
			}),
			reconfigureGrid:function(view){			
				grid.isExpanded()?grid.setExpanded():grid.unsetExpanded();
			},
			setExpanded:function(){
				grid.buttonState = "expanded";
				grid.expandListButton.setText(grid.listText);
				grid.expandListButton.setIconClass(grid.listIconCls);
				grid.getView().el.select('.x-grid3-cell-inner').setStyle({'white-space':'normal'});				
			},
			unsetExpanded:function(){
				grid.buttonState = "list";
				grid.expandListButton.setText(grid.expandedText);
				grid.expandListButton.setIconClass(grid.expandedIconCls);
				grid.getView().el.select('.x-grid3-cell-inner').setStyle({'white-space':'nowrap'})
			},
			isExpanded:function(){
				return this.buttonState == "list"?false:true;
			},			
			mainButtonHandler:function(button){
				grid.isExpanded()?grid.unsetExpanded():grid.setExpanded();
			}
		});
    }	
});