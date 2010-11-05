// vim: ts=4:sw=4:nu:fdc=4:nospell
/**
 * RowActions plugin for Ext grid
 *
 * Contains renderer for icons and fires events when an icon is clicked
 *
 * @author    Ing. Jozef Sakáloš
 * @date      22. March 2008
 * @version   $Id: Ext.ux.GridRowActions.js 150 2008-04-08 21:50:58Z jozo $
 *
 * @license Ext.ux.GridRowActions is licensed under the terms of
 * the Open Source LGPL 3.0 license.  Commercial use is permitted to the extent
 * that the code/component(s) do NOT become part of another Open Source or Commercially
 * licensed development library or toolkit without explicit permission.
 * 
 * License details: http://www.gnu.org/licenses/lgpl.html
 */

/**
 * @class Ext.ux.GridRowActions
 * @extends Ext.util.Observable
 *
 * CSS rules from Ext.ux.RowActions.css are mandatory
 *
 * Important general information: Actions are identified by iconCls. Wherever an <i>action</i>
 * is referenced (event argument, callback argument), the iconCls of clicked icon is used.
 * In another words, action identifier === iconCls.
 *
 * Creates new RowActions plugin
 * @constructor
 * @param {Object} config The config object
 */

// add RegExp.escape if it has not been already added
if('function' !== typeof RegExp.escape) {
	RegExp.escape = function(s) {
		if('string' !== typeof s) {
			return s;
		}
		// Note: if pasting from forum, precede ]/\ with backslash manually
		return s.replace(/([.*+?\^=!:${}()|\[\]\/\\])/g, '\\$1');
	}; // eo function escape
}

Ext.ux.GridRowActions = function(config) {
	Ext.apply(this, config);

	// {{{
	this.addEvents(
		/**
		 * @event beforeaction
		 * Fires before action event. Return false to cancel the subsequent action event.
		 * @param {Ext.grid.GridPanel} grid
		 * @param {Ext.data.Record} record Record corresponding to row clicked
		 * @param {String} action Identifies the action icon clicked. Equals to icon css class name.
		 * @param {Integer} rowIndex Index of clicked grid row
		 * @param {Integer} colIndex Index of clicked grid column that contains all action icons
		 */
		 'beforeaction'
		/**
		 * @event action
		 * Fires when icon is clicked
		 * @param {Ext.grid.GridPanel} grid
		 * @param {Ext.data.Record} record Record corresponding to row clicked
		 * @param {String} action Identifies the action icon clicked. Equals to icon css class name.
		 * @param {Integer} rowIndex Index of clicked grid row
		 * @param {Integer} colIndex Index of clicked grid column that contains all action icons
		 */
		,'action'
		/**
		 * @event beforegroupaction
		 * Fires before group action event. Return false to cancel the subsequent groupaction event.
		 * @param {Ext.grid.GridPanel} grid
		 * @param {Array} records Array of records in this group
		 * @param {String} action Identifies the action icon clicked. Equals to icon css class name.
		 * @param {String} groupId Identifies the group clicked
		 */
		,'beforegroupaction'
		/**
		 * @event groupaction
		 * Fires when icon in a group header is clicked
		 * @param {Ext.grid.GridPanel} grid
		 * @param {Array} records Array of records in this group
		 * @param {String} action Identifies the action icon clicked. Equals to icon css class name.
		 * @param {String} groupId Identifies the group clicked
		 */
		,'groupaction'
	);
	// }}}

	// call parent
	Ext.ux.GridRowActions.superclass.constructor.call(this);
};

Ext.extend(Ext.ux.GridRowActions, Ext.util.Observable, {

	// configuration options
	// {{{
	/**
	 * @cfg {Array} actions Mandatory. Array of action configuration objects. The following
	 * configuration options of action are recognized:
	 *
	 * - @cfg {Function} callback Optional. Function to call if the action icon is clicked.
	 *   This function is called with same signature as action event and in its original scope.
	 *   If you need to call it in different scope or with another signature use 
	 *   createCallback or createDelegate functions. Works for statically defined actions. Use
	 *   callbacks configuration options for store bound actions.
	 *
	 * - @cfg {Function} cb Shortcut for callback.
	 *
	 * - @cfg {String} iconIndex Optional, however either iconIndex or iconCls must be
	 *   configured. Field name of the field of the grid store record that contains
	 *   css class of the icon to show. If configured, shown icons can vary depending
	 *   of the value of this field.
	 *
	 * - @cfg {String} iconCls. css class of the icon to show. It is ignored if iconIndex is
	 *   configured. Use this if you want static icons that are not base on the values in the record.
	 *
	 * - @cfg {Boolean} hide Optional. True to hide this action while still have a space in 
	 *   the grid column allocated to it. IMO, it doesn't make too much sense, use hideIndex instead.
	 *
	 * - @cfg (string} hideIndex Optional. Field name of the field of the grid store record that
	 *   contains hide flag (falsie [null, '', 0, false, undefined] to show, anything else to hide).
	 *
	 * - @cfg {String} qtipIndex Optional. Field name of the field of the grid store record that 
	 *   contains tooltip text. If configured, the tooltip texts are taken from the store.
	 *
	 * - @cfg {String} tooltip Optional. Tooltip text to use as icon tooltip. It is ignored if 
	 *   qtipIndex is configured. Use this if you want static tooltips that are not taken from the store.
	 *
	 * - @cfg {String} qtip Synonym for tooltip
	 *
	 * - @cfg {String} textIndex Optional. Field name of the field of the grids store record
	 *   that contains text to display on the right side of the icon. If configured, the text
	 *   shown is taken from record.
	 *
	 * - @cfg {String} text Optional. Text to display on the right side of the icon. Use this
	 *   if you want static text that are not taken from record. Ignored if textIndex is set.
	 *
	 * - @cfg {String} style Optional. Style to apply to action icon container.
	 */

	/**
	 * @cfg {String} actionEvnet Event to trigger actions, e.g. click, dblclick, mouseover (defaults to 'click')
	 */
	 actionEvent:'click'

	/**
	 * @cfg {Boolean} autoWidth true to calculate field width for iconic actions only.
	 */
	,autoWidth:true

	/**
	 * @cfg {Array} groupActions Array of action to use for group headers of grouping grids.
	 * These actions support static icons, texts and tooltips same way as actions. There is one
	 * more action config recognized:
	 * - @cfg {String} align Set it to 'left' to place action icon next to the group header text.
	 *   (defaults to undefined = icons are placed at the right side of the group header.
	 */

	/**
	 * @cfg {Object} callbacks iconCls keyed object that contains callback functions. For example:
	 * callbacks:{
	 *      'icon-open':function(...) {...}
	 *     ,'icon-save':function(...) {...}
	 * }
	 */

	/**
	 * @cfg {String} header Actions column header
	 */
	,header:''
	
	/**
	 * @cfg {Boolean} isColumn
	 * Tell ColumnModel that we are column. Do not touch!
	 * @private
	 */
	,isColumn:true
	
	/**
	 * @cfg {Boolean} keepSelection
	 * Set it to true if you do not want action clicks to affect selected row(s) (defaults to false).
	 * By default, when user clicks an action icon the clicked row is selected and the action events are fired.
	 * If this option is true then the current selection is not affected, only the action events are fired.
	 */
	,keepSelection:false

	/**
	 * @cfg {Boolean} menuDisabled No sense to display header menu for this column
	 */
	,menuDisabled:true

	/**
	 * @cfg {Boolean} sortable Usually it has no sense to sort by this column
	 */
	,sortable:false

	/**
	 * @cfg {String} tplGroup Template for group actions
	 * @private
	 */
	,tplGroup:
		 '<tpl for="actions">'
		+'<div class="ux-grow-action-item<tpl if="\'right\'===align"> ux-action-right</tpl> '
		+'{cls}" style="{style}" qtip="{qtip}">{text}</div>'
		+'</tpl>'

	/**
	 * @cfg {String} tplRow Template for row actions with a url
	 * @private
	 */
	,tplRow:
		 '<div class="ux-row-action">'
		+'<tpl for="actions">'
		+'{url_start}'
		+'<div class="ux-row-action-item {cls} <tpl if="text">'
		+'ux-row-action-text</tpl>" style="{hide}{style}" qtip="{qtip}">'
		+'<tpl if="text"><span qtip="{qtip}">{text}</span></tpl></div>'
		+'{url_end}'
		+'</tpl>'
		+'</div>'
	
	/**
	 * @private {Number} widthIntercept constant used for auto-width calculation
	 */
	,widthIntercept:4

	/**
	 * @private {Number} widthSlope constant used for auto-width calculation
	 */
	,widthSlope:21
	// }}}

	// methods
	// {{{
	/**
	 * Init function
	 * @param {Ext.grid.GridPanel} grid Grid this plugin is in
	 */
	,init:function(grid) {
		/**
		 * Find the effective actions		 * 
		 */
		var effectiveActionsCount = 0;			
		for(var i=0;i<this.actions.length;i++){
			if(this.actions[i].hidden) continue; effectiveActionsCount++
		};
		
		this.grid = grid;
		/**
		 * Change the renderer method of the conditional_row_action  
		 * 
		 */
		var cm = grid.getColumnModel();
		var columns = cm.getColumnsBy(function(c){
			return c.dataIndex == "conditional_row_action";
		});
		if(columns && columns[0]){
			columns[0].renderer = function(value){return value?"<span style='color:green'>Yes</span>":"<span style='color:red'>No</span>"}
		}
		/***********************************************************/
		// the actions column must have an id for Ext 3.x
		this.id = this.id || Ext.id();

		// for Ext 3.x compatibility
		var lookup = grid.getColumnModel().lookup;
		delete(lookup[undefined]);
		lookup[this.id] = this;
		
		// {{{
		// setup template
		if(!this.tpl) {
			this.tpl = this.processActions(this.actions);
		} // eo template setup
		// }}}
		//console.log(this.tpl);
		// calculate width		
		// calculate width
		if(this.autoWidth) {			
			this.width =  this.widthSlope * effectiveActionsCount + this.widthIntercept;			
			this.fixed = true;
			if(this.width < 45) this.width = 45;
		}

		// body click handler
		var view = grid.getView();
		var cfg = {scope:this};
		cfg[this.actionEvent] = this.onClick;
		grid.on({
			render:{scope:this, fn:function() {
				view.mainBody.on(cfg);
			}}
		});

		// setup renderer
		if(!this.renderer) {
			this.renderer = function(value, cell, record, row, col, store) {
				cell.css += (cell.css ? ' ' : '') + 'ux-row-action-cell';
				var allow_modify = record.get('conditional_row_action');				
				if(allow_modify != false){					
					return this.tpl.apply(this.getData(value, cell, record, row, col, store));
				}
				
			}.createDelegate(this);
		}

		// actions in grouping grids support
		if(view.groupTextTpl && this.groupActions) {
			view.interceptMouse = view.interceptMouse.createInterceptor(function(e) {
				if(e.getTarget('.ux-grow-action-item')) {
					return false;
				}
			});
			view.groupTextTpl = 
				 '<div class="ux-grow-action-text">' + view.groupTextTpl +'</div>' 
				+this.processActions(this.groupActions, this.tplGroup).apply()
			;
		}
		
	} // eo function init
	// }}}
	// {{{
	/**
	 * Returns data to apply to template. Override this if needed.
	 * @param {Mixed} value 
	 * @param {Object} cell object to set some attributes of the grid cell
	 * @param {Ext.data.Record} record from which the data is extracted
	 * @param {Number} row row index
	 * @param {Number} col col index
	 * @param {Ext.data.Store} store object from which the record is extracted
	 * @returns {Object} data to apply to template
	 */
	,getData:function(value, cell, record, row, col, store) {
		return record.data || {};
	} // eo function getData
	// }}}
	// {{{
	/**
	 * Processes actions configs and returns template.
	 * @param {Array} actions
	 * @param {String} template Optional. Template to use for one action item.
	 * @return {String}
	 * @private
	 */
	,processActions:function(actions, template) {
		var acts = [];

		// actions loop
		Ext.each(actions, function(a, i) {
			if(a.hidden) return;
			// save callback
			if(a.iconCls && 'function' === typeof (a.callback || a.cb)) {
				this.callbacks = this.callbacks || {};
				this.callbacks[a.iconCls] = a.callback || a.cb;
			}
			if(!a.message) a.message = a.confirmMsg;
			//if(a.message) a.confirm = true;
			if(!a.message) a.message = "Are you sure to perform this operation?";
			
			if(a.icon){
				if(a.style)
				a.style += ";background-image:url("+a.icon+");background-repeat:no-repeat;";
				else
				a.style = "background-image:url("+a.icon+");background-repeat:no-repeat;";
			}
			// data for intermediate template
			
			//load attribute
			a.load = a.load? a.load : 'center';
			
			/*
			 * Add expand action in row action
			 * row action containing name expand triggers is action
			 */
			
			if(a.script){
				var urlStart = a.urlIndex ? ('<tpl if="this.isUrl(' + a.urlIndex + ')"><a href="javascript:void(0);" onclick="'+a.script+'">') : '';
			}else if(a.name&&a.name.match("_expand$")){
				
				/**
				* add af-expand-row to current iconCls or create a new one
				*/
				if(a.iconCls)a.iconCls += " icon-expand-row af-expand-row"
				else a.iconCls = " icon-expand-row af-expand-row";
				/**
				* add af-confirm-row to current iconCls, if is the be confirmed first
				*/
				if(a.confirm)a.iconCls += "  af-confirm-row";
				
				var urlStart = a.urlIndex ? ('<tpl if="this.isUrl(' + a.urlIndex + ')"><a href="'+afApp.urlPrefix+'{' + a.urlIndex + '}" confirmmsg="'+a.confirmMsg+'">') : '';
				
			}else{
				var urlStart = a.urlIndex ? ('<tpl if="this.isUrl(' + a.urlIndex + ')"><a href="<tpl if="!'+a.confirm+'&&this.isLoadCenter(\''+a.load+'\')">'+afApp.urlPrefix+'/'+afApp.sharpPrefix+'#</tpl>{' + a.urlIndex + '}" <tpl if="'+a.confirm+'">onclick="Ext.Msg.confirm(\'Confirmation\',\''+a.message+'\', function(btn){if (btn==\'yes\'){ afApp.load(\''+afApp.urlPrefix+'{' + a.urlIndex + '}\',\'' + a.load + '\'); return false; }else{ return true;}});return false;"</tpl>>') : '';
			}			
			
			/*
			 * Support for post {Ajax call for the url}
			 */
			
			var ajaxCall = '';
			
			if(a.post && a.name && !a.name.match("_expand")){
				ajaxCall = 'Ext.Ajax.request({'+ 
						'url: "'+afApp.urlPrefix+'{'+a.urlIndex+'}",'+
						'method:"post",'+						
						'success: Ext.ux.GridRowActions.onActionSuccess,'+
						'failure: Ext.ux.GridRowActions.onActionFailure'+
					'});'+
				';';
				urlStart = a.urlIndex ? ('<tpl if="this.isUrl(' + a.urlIndex + ')"><a href="'+afApp.urlPrefix+'{'+a.urlIndex+'}" <tpl if="!'+a.confirm+'">onclick=\''+ajaxCall+'; return false;\'</tpl><tpl if="'+a.confirm+'">onclick=\'Ext.Msg.confirm("Confirmation","'+a.message+'", function(btn){if (btn=="yes"){ '+ajaxCall+' }}); return false;\'</tpl>>') : '';
			}
			
			
			/**
			 * Support for the popup widget option
			 */
			if(a.popup){
				
				a.popupSettings=escape(a.popupSettings);
				
				urlStart = a.urlIndex ? ('<tpl if="this.isUrl(' + a.urlIndex + ')"><a href="'+afApp.urlPrefix+'{'+a.urlIndex+'}" <tpl if="!'+a.confirm+'">onclick=\'afApp.widgetPopup("{'+a.urlIndex+'}","","","'+a.popupSettings+'"); return false;\'</tpl><tpl if="'+a.confirm+'">onclick=\'Ext.Msg.confirm("Confirmation","'+a.message+'", function(btn){if (btn=="yes"){ afApp.widgetPopup("'+afApp.urlPrefix+'{'+a.urlIndex+'}"); }}); return false;\'</tpl>>') : '';
			}
			/***************************************************************************/
			
			
			var o = {
				 cls:a.iconIndex ? '{' + a.iconIndex + '}' : (a.iconCls ? a.iconCls : '')
				,qtip:a.qtipIndex ? '{' + a.qtipIndex + '}' : (a.tooltip || a.qtip ? a.tooltip || a.qtip : '')
				,text:a.textIndex ? '{' + a.textIndex + '}' : (a.text ? a.text : '')
				,url_start:urlStart
				,url_end:a.urlIndex ? ('</a></tpl>') : ''
				,hide:a.hideIndex ? '<tpl if="' + a.hideIndex + '">visibility:hidden;</tpl>' : (a.hide ? 'visibility:hidden;' : '')
				,align:a.align || 'right'
				,style:a.style ? a.style : ''
				,confirmMsg:a.confirmMsg ? a.confirmMsg :''
			};
			acts.push(o);

		}, this); // eo actions loop
				
		var xt = new Ext.XTemplate(template || this.tplRow,{
				isUrl : function(url)
				{
					if(url.length>0){return true;}else{return false;}
				}
			}
		);
		
		var xt2 = new Ext.XTemplate(xt.apply({actions:acts}),{
				isUrl : function(url)
				{
					if(url.length>0){return true;}else{return false;}
				},
				isLoadCenter :function(load)
				{
					if(load=='center'){return true;}else{return false;}
				}
			}
		);
		
		return xt2;

	} // eo function processActions
	// }}}
	// {{{
	
	/*
	 * Insert expand div in the row if it does not have. If it has already, hide/unhide it on request
	 * 
	 * methods for functionality:
	 * 	1. expandRequest
	 *  2. findByClassName
	 *  3. isRowExpanded
	 *  4. getExpandedDiv
	 *  
	 *  @author: Prakash Paudel
	 */
	,expandRequest:function(e){
		e.stopEvent();
		var row = e.getTarget('.x-grid3-row');	
		var grid = this.grid;
		var obj = this;
		var url = e.getTarget().parentNode;
		var link = e.getTarget();
		var extlink= Ext.get(link);
		
		if(!this.isRowExpanded(row)){
			var mask = new Ext.LoadMask(Ext.get("body"), {msg: "<b>Getting data from server.....</b> <br>Please wait..",removeMask:true});
			mask.show();
			var ajax = Ext.Ajax.request({
				url: url,
				method:"POST",
				success:function(response){
					var rc = null;
					try{rc=new RegExp('^("(\\\\.|[^"\\\\\\n\\r])*?"|[,:{}\\[\\]0-9.\\-+Eaeflnr-u \\n\\r\\t])+?$')}
				    catch(z){rc=/^(true|false|null|\[.*\]|\{.*\}|".*"|\d+|\d+\.\d+)$/}
				    if(rc.test(response.responseText)){
				    	json = Ext.util.JSON.decode(response.responseText);
				    	if(!json.success){
				    		Ext.Msg.alert("Error",json.message);
				    		mask.hide();
				    		return;
				    	}
				    	if(json.html){
				    		Ext.DomHelper.append(row,{tag:"div",cls:"imm-row-expand",style:"padding:5px;",html:json.html})
				    	}
				    	if(json.script){
				    		eval(json.script);
				    	}
				    	
				    }else{
				    	Ext.DomHelper.append(row,{tag:"div",cls:"imm-row-expand",style:"padding:5px;",html:response.responseText})
					}
					
					extlink.removeClass('icon-expand-row');
					extlink.addClass('icon-collapse-row');										
					mask.hide();
				},
				failure:function(){
					mask.hide();
					Ext.Msg.alert("Error !","Some error has occured while connecting to server. <br>Please try again.");
				}
			});			
		}else{
			var div = this.getExpandedDiv(row);
			if(div.style.display != "none"){
				div.style.display = "none";
				extlink.addClass('icon-expand-row');
				extlink.removeClass('icon-collapse-row');
			}else{
				div.style.display = "block";
				extlink.removeClass('icon-expand-row');
				extlink.addClass('icon-collapse-row');							
			}
		}
		
	},
	/*
	 * Cross browser compatibility for the elements getting by className
	 * This method is alternative for the getElementsByClassName [incompatible with IE]
	 */
	findByClassName:function(el,className){
		var elements = el.getElementsByTagName("div");
		var els = new Array();
		for(var i=0;i<elements.length;i++){
			if(elements[i].className == className){
				els.push(elements[i]);
			}
		}
		return els;
	},
	isRowExpanded: function(row){		
		return this.findByClassName(row,"imm-row-expand")[0]?true:false;		
	},
	getExpandedDiv: function(row){
		return this.isRowExpanded(row)?this.findByClassName(row,"imm-row-expand")[0]:null;
	}
	/****************************************************************************************************/
	
	/**
	 * Grid body actionEvent event handler
	 * @private
	 */
	,onClick:function(e, target) {

		var view = this.grid.getView();
		var action = false;

		// handle row action click
		var row = e.getTarget('.x-grid3-row');
		
		if(e.getTarget(".af-expand-row")){
			
			if(e.getTarget(".af-confirm-row"))
			{			
				var obj=this;
				
				Ext.Msg.confirm('Confirmation',e.getTarget(".af-confirm-row").parentNode.getAttribute('confirmmsg'), function(btn){if (btn=='yes'){ obj.expandRequest(e); return false; }else{ return true;}});
				
				e.stopEvent();
			}
			else
			{
				this.expandRequest(e);return false;
			}
		}
		
		var col = view.findCellIndex(target.parentNode.parentNode);

		var t = e.getTarget('.ux-row-action-item');
		if(t) {
			action = t.className.replace(/ux-row-action-item /, '');
			
			if(action) {
				action = action.replace(/ ux-row-action-text/, '');
				action = action.trim();
			}
		}
		if(false !== row && false !== col && false !== action) {
			var record = this.grid.store.getAt(row.rowIndex);
			
			// call callback if any
			if(this.callbacks && 'function' === typeof this.callbacks[action]) {
				this.callbacks[action](this.grid, record, action, row.rowIndex, col);
			}

			// fire events
			if(true !== this.eventsSuspended && false === this.fireEvent('beforeaction', this.grid, record, action, row.rowIndex, col)) {
				return;
			}
			else if(true !== this.eventsSuspended) {
				this.fireEvent('action', this.grid, record, action, row.rowIndex, col);
			}

		}

		// handle group action click
		t = e.getTarget('.ux-grow-action-item');
		if(t) {
			// get groupId
			var group = view.findGroup(target);
			var groupId = group ? group.id.replace(/ext-gen[0-9]+-gp-/, '') : null;

			// get matching records
			var records;
			if(groupId) {
				var re = new RegExp(groupId);
				records = this.grid.store.queryBy(function(r) {
					return r._groupId.match(re);
				});
				records = records ? records.items : [];
			}
			action = t.className.replace(/ux-grow-action-item (ux-action-right )*/, '');

			// call callback if any
			if('function' === typeof this.callbacks[action]) {
				this.callbacks[action](this.grid, records, action, groupId);
			}

			// fire events
			if(true !== this.eventsSuspended && false === this.fireEvent('beforegroupaction', this.grid, records, action, groupId)) {
				return false;
			}
			this.fireEvent('groupaction', this.grid, records, action, groupId);
		}
	} // eo function onClick
	// }}}

});

/**
 * A handler for an JSON response.
 */
Ext.ux.GridRowActions.onActionSuccess = function(response, options) {
	response = Ext.decode(response.responseText);
	if(!response.success) {
		return Ext.ux.GridRowActions.onActionFailure(response, options);
	}
	
	if(response.executeAfter)
	{
		eval(response.executeAfter);
	}

	if(response.message) {
		Ext.Msg.alert('Success', response.message, function(){
			if(response.redirect) {
				afApp.load(response.redirect,response.load);
			}
		});
	} else {
		if(response.redirect) {
			afApp.load(response.redirect,response.load);
		}
	}
}

/**
 * A handler for an Ajax failure or a unsuccessful response.
 */
Ext.ux.GridRowActions.onActionFailure = function(response, options) {
	if(response.responseText) {
		response = Ext.decode(response.responseText);
	}
	var message = response.message || 'Unable to do the operation.';
	Ext.Msg.alert('Failure', message, function(){
		if(response.redirect) {
			afApp.load(response.redirect,response.load);
		}
	});
}

// registre xtype
Ext.reg('rowactions', Ext.ux.GridRowActions);

// eof
