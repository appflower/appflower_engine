// vim: ts=4:sw=4:nu:fdc=4:nospell
/**
 * Main Web Layout based on Ext.TableLayout
 *
 * @author    Ing. Jozef Sakáloš
 * @copyright (c) 2008, by Ing. Jozef Sakáloš
 * @date      5. April 2008
 * @version   $Id: Web.js 143 2008-04-06 12:10:37Z jozo $
 */
 
/*global Ext, Web, window */

Ext.ns('Web');

/**
 * @class Web.LayoutPanel
 * @extends Ext.Panel
 */
Web.LayoutPanel = Ext.extend(Ext.Panel, {
 
    // configurables
     border:false
	,renderTo:'ct'
	,langSelect:false
	/**
	 * @cfg {Ext.Element/DOM/String} westContent Element to use for contentEl
	 */
	/**
	 * @cfg {Ext.Element/DOM/String} centerContent Element to use for contentEl
	 */
	/**
	 * @cfg {Ext.Element/DOM/String} eastContent Element to use for contentEl (defaults to 'adsense')
	 */
	/**
	 * @cfg {Ext.Element/DOM/String} southContent Element to use for contentEl
	 */
	,navlinks:[{
		 text:'Home'
		,href:'http://extjs.eu'
		,target:'_self'
	},{
		 text:'Blog'
		,href:'http://blog.extjs.eu'
		,target:'_self'
	},{
		 text:'ExtJS'
		,href:'http://extjs.com'
		,target:'_self'
	},{
		 text:'Forum'
		,href:'http://extjs.com/forum'
		,target:'_self'
	},{
		 text:'Learn'
		,href:'http://extjs.com/learn'
		,target:'_self'
	},{
		 text:'Docs'
		,href:'http://extjs.com/deploy/dev/docs'
		,target:'_blank'
	},{
		 text:'Samples'
		,href:'http://extjs.com/deploy/dev/examples'
		,target:'_blank'
	},{
		 text:'Profile'
		,href:'http://extjs.com/forum/member.php?u=2178'
		,target:'_blank'
	}]
	,navlinksTpl: new Ext.XTemplate(
		 '<ul>'
		+'<tpl for="navlinks">'
		+'<li><a href="{href}" target="{target}">{text}</a></li>'
		+'</tpl>'
		+'</ul><div class="cleaner"></div>'
	)
 
    // {{{
    ,initComponent:function() {
        // {{{
		Ext.apply(this, {
			 id:'main-layout'
			,layout:'table'
			,layoutConfig:{columns:3}
			,defaults:{border:false}
			,items:[{
				 id:'north'
				,colspan:3
				,cellCls:'td-north'
				,html:'<div id="north-title"><h1>' 
					  + Ext.fly('page-title').dom.innerHTML 
					  + (this.version ? ' - ver.: ' + this.version : '')
					  + '</h1></div>'
					  + '<div id="themect"></div><div id="langct"></div>'
					  + '<div class="cleaner"></div>'
			},{
				 id:'navlinks'
				,colspan:3
				,cellCls:'td-navlinks'
			},{
				 id:'west'
				,cellCls:'td-west'
				,border:false
				,contentEl:this.westContent
			},{
				 id:'center'
				,cellCls:'td-center'
				,contentEl:this.centerContent
			},{
			 	 id:'east'
				,cellCls:'td-east'
				,contentEl:(function() {
					if(this.eastContent) {
						return this.eastContent;
					}
					if(window.location.host.match(/extjs.eu/) || 'perseus.localhost' === window.location.host) {
						return 'adsense';
					}
					else {
						return undefined;
					}
					}).createDelegate(this)()
			},{
				 id:'south'
				,colspan:3
				,cellCls:'td-south'
				,contentEl:this.southContent
			}]
		});
        // }}}
        // call parent
        Web.LayoutPanel.superclass.initComponent.apply(this, arguments);

		this.on({scope:this, afterlayout:this.afterLayout});
 
    } // e/o function initComponent
    // }}}
	// {{{
	,afterLayout:function() {
		this.themeCombo = new Ext.ux.ThemeCombo({renderTo:'themect',width:150});
		this.navlinksTpl.overwrite(Ext.fly('navlinks'), {navlinks:this.navlinks});

		if(this.langSelect) {
			this.langCombo = new Ext.ux.LangSelectCombo({renderTo:'langct', width:140, editable:false});
			this.langCombo.on('select', function() {document.cookie = 'locale=' + this.getValue()});
		}

	} // eo function afterLayout
	// }}}
 
}); // e/o extend

// eof
