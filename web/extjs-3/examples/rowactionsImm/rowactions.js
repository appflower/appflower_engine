// vim: ts=4:sw=4:nu:fdc=4:nospell
/**
 * Ext.ux.GridRowActions Plugin Example Application
 *
 * @author    Ing. Jozef Sakáloš
 * @date      22. March 2008
 * @version   $Id: rowactions.js 152 2008-04-08 21:56:11Z jozo $
 *
 * @license rowactions.js is licensed under the terms of
 * the Open Source LGPL 3.0 license.  Commercial use is permitted to the extent
 * that the code/component(s) do NOT become part of another Open Source or Commercially
 * licensed development library or toolkit without explicit permission.
 * 
 * License details: http://www.gnu.org/licenses/lgpl.html
 */

/*global Ext, WebPage, Example, console, window */

Ext.BLANK_IMAGE_URL = '../ext/resources/images/default/s.gif';
Ext.ns('Example');
Example.version = '1.0';

var actions=new Ext.ux.GridRowActions ({
			header: 'Actions',
			groupActions: [],
			actions: [
			{
			iconCls: 'icon-edit-record',
			tooltip: 'Edit',
			text: 'edit',
			style: '',
			urlIndex: 'action1',
			hideIndex: 'hide1'
			},
			{
			iconCls: 'icon-minus',
			tooltip: 'Delete',
			text: 'Delete',
			style: '',
			urlIndex: 'action2',
			hideIndex: 'hide2'
			}
			]
		});

// create pre-configured grid class
Example.Grid = Ext.extend(Ext.grid.GridPanel, {

	// {{{
	 initComponent:function() {
		
		// configure the grid
		Ext.apply(this, {
			 store:new Ext.data.GroupingStore({
				reader:new Ext.data.JsonReader({
					 id:'company'
					,totalProperty:'totalCount'
					,root:'rows'
					,fields:[
						{name: 'company'}
					   ,{name: 'lastChange', type: 'date', dateFormat: 'n/j h:ia'}
					   ,{name: 'industry'}
					   ,{name: 'action1', type: 'string'}
					   ,{name: 'action2', type: 'string'}
					   ,{name: 'hide1', type: 'boolean'}
					   ,{name: 'hide2', type: 'boolean'}
					]
				})
				,proxy:new Ext.data.HttpProxy({url:'https://192.168.80.128/interface/jsonactions'})
				,groupField:'industry'
				,sortInfo:{field:'company', direction:'ASC'}
			})
			,columns:[
				 {id:'company',header: "Company", width: 40, sortable: true, dataIndex: 'company'}
				,{header: "Industry", width: 20, sortable: true, dataIndex: 'industry'}
				,{header: "Last Updated", width: 20, sortable: true, renderer: Ext.util.Format.dateRenderer('m/d/Y'), dataIndex: 'lastChange'}
				,actions
			]
			,plugins:[actions]
			,view: new Ext.grid.GroupingView({
				 forceFit:true
				,groupTextTpl:' {text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})'
			})
//			,viewConfig:{forceFit:true}
		}); // eo apply

		// add paging toolbar
		this.bbar = new Ext.PagingToolbar({
			 store:this.store
			,displayInfo:true
			,pageSize:20
		});

		// call parent
		Example.Grid.superclass.initComponent.apply(this, arguments);
	} // eo function initComponent
	// }}}
	// {{{
	,onRender:function() {

		// call parent
		Example.Grid.superclass.onRender.apply(this, arguments);

		// start w/o grouping
		this.store.clearGrouping();

		// load the store
		this.store.load({params:{start:0, limit:20}});

	} // eo function onRender
	// }}}

}); // eo extend

// register component
Ext.reg('examplegrid', Example.Grid);

// application entry point
Ext.onReady(function() {
    Ext.QuickTips.init();

	var adsenseHost = 
		   'rowactions.localhost' === window.location.host
		|| 'rowactions.extjs.eu' === window.location.host
	;
	var page = new WebPage({
		 version:Example.version
		,westContent:'west-content'
		,centerContent:'center-content'
		,adRowContent:adsenseHost ? 'adrow-content' : undefined
	});

	var ads = Ext.getBody().select('div.adsense');
	if(adsenseHost) {
		ads.removeClass('x-hidden');
	}
	else {
		ads.remove();
	}

	// window with grid
    var win = new Ext.Window({
         width:600
        ,id:'agwin'
        ,height:400
        ,layout:'fit'
        ,border:false
		,plain:true
        ,closable:false
        ,title:Ext.get('page-title').dom.innerHTML
		,items:{xtype:'examplegrid',id:'actiongrid'}
    });
    win.show();
});

// eof