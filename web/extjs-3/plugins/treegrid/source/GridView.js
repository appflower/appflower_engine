Ext.ux.maximgb.treegrid.GridView = Ext.extend(Ext.grid.GridView, 
{
	// private
	breadcrumbs_el : null,
	
	// private - overriden
	initTemplates : function()
	{
		var ts = this.templates || {};
		
    ts.master = new Ext.Template(
			'<div class="x-grid3" hidefocus="true">',
				'<div class="x-grid3-viewport">',
					'<div class="x-grid3-header">',
						// Breadcrumbs
						'<div class="x-grid3-header-inner">',
							'<div class="x-grid3-header-offset">',
								'<div class="ux-maximgb-treegrid-breadcrumbs">&#160;</div>',
							'</div>',
						'</div>',
						'<div class="x-clear"></div>',
						// End of breadcrumbs
						// Header
						'<div class="x-grid3-header-inner">',
							'<div class="x-grid3-header-offset">{header}</div>',
						'</div>',
						'<div class="x-clear"></div>',
						// End of header
					'</div>',
					// Scroller
					'<div class="x-grid3-scroller">',
						'<div class="x-grid3-body">{body}</div>',
						'<a href="#" class="x-grid3-focus" tabIndex="-1"></a>',
					'</div>',
					// End of scroller
				'</div>',
				'<div class="x-grid3-resize-marker">&#160;</div>',
				'<div class="x-grid3-resize-proxy">&#160;</div>',
			'</div>'
		);
		
    ts.row = new Ext.Template(
			'<div class="x-grid3-row {alt} ux-maximgb-treegrid-level-{level}" style="{tstyle} {display_style}">',
				'<table class="x-grid3-row-table" border="0" cellspacing="0" cellpadding="0" style="{tstyle}">',
        	'<tbody>',
        		'<tr>{cells}</tr>',
            (
            	this.enableRowBody ? 
            		'<tr class="x-grid3-row-body-tr" style="{bodyStyle}">' +
            			'<td colspan="{cols}" class="x-grid3-body-cell" tabIndex="0" hidefocus="on">'+
            				'<div class="x-grid3-row-body">{body}</div>'+
            			'</td>'+
            		'</tr>' 
            			: 
            		''
            ),
          '</tbody>',
         '</table>',
        '</div>'
		);
		
    ts.cell = new Ext.Template(
			'<td class="x-grid3-col x-grid3-cell x-grid3-td-{id} {css}" style="{style}" tabIndex="0" {cellAttr}>',
				'{treeui}',
				'<div class="x-grid3-cell-inner x-grid3-col-{id}" unselectable="on" {attr}>{value}</div>',
			'</td>'
		);
		
		ts.treeui = new Ext.Template(
			'<div class="ux-maximgb-treegrid-uiwrap" style="width: {wrap_width}px">',
				'{elbow_line}',
				'<div style="left: {left}px" class="{cls}">&#160;</div>',
			'</div>'
		);
		
		ts.elbow_line = new Ext.Template(
			'<div style="left: {left}px" class="{cls}">&#160;</div>'
		);
		
		ts.brd_item = new Ext.Template(
			'<a href="#" id="ux-maximgb-treegrid-brditem-{id}" class="ux-maximgb-treegrid-brditem" title="{title}">{caption}</a>'
		);
		
		this.templates = ts;
		Ext.ux.maximgb.treegrid.GridView.superclass.initTemplates.call(this);
	},
	
	// private - overriden
  initElements : function()
  {
		var E = Ext.Element;
		
		var el = this.grid.getGridEl().dom.firstChild;
		var cs = el.childNodes;
		
		this.el = new E(el);
		
		this.mainWrap = new E(cs[0]);
		this.mainHd = new E(this.mainWrap.dom.firstChild);
		
		if(this.grid.hideHeaders){
		    this.mainHd.setDisplayed(false);
		}
		
		// ----- Modification start
		//Original: this.innerHd = this.mainHd.dom.firstChild;
		this.innerHd = this.mainHd.dom.childNodes[2];
		// ----- End of modification
		this.scroller = new E(this.mainWrap.dom.childNodes[1]);
		
		if(this.forceFit){
		    this.scroller.setStyle('overflow-x', 'hidden');
		}
		this.mainBody = new E(this.scroller.dom.firstChild);
		
		this.focusEl = new E(this.scroller.dom.childNodes[1]);
		this.focusEl.swallowEvent("click", true);
		
		this.resizeMarker = new E(cs[1]);
		this.resizeProxy = new E(cs[2]);
		
		this.breadcrumbs_el = this.el.child('.ux-maximgb-treegrid-breadcrumbs');
		this.setRootBreadcrumbs();
	},
	
	// Private - Overriden
	doRender : function(cs, rs, ds, startRow, colCount, stripe)
	{
		var ts = this.templates, ct = ts.cell, rt = ts.row, last = colCount-1;
		var tstyle = 'width:'+this.getTotalWidth()+';';
		// buffers
		var buf = [], cb, c, p = {}, rp = {tstyle: tstyle}, r;
		for (var j = 0, len = rs.length; j < len; j++) {
			r = rs[j]; cb = [];
			var rowIndex = (j+startRow);
			for (var i = 0; i < colCount; i++) {
        c = cs[i];
        p.id = c.id;
        p.css = i == 0 ? 'x-grid3-cell-first ' : (i == last ? 'x-grid3-cell-last ' : '');
        p.attr = p.cellAttr = "";
        p.value = c.renderer(r.data[c.name], p, r, rowIndex, i, ds);
        p.style = c.style;
				if (p.value == undefined || p.value === "") {
					p.value = "&#160;";
				}
				if (r.dirty && typeof r.modified[c.name] !== 'undefined') {
					p.css += ' x-grid3-dirty-cell';
				}
				// ----- Modification start
				if (c.id == this.grid.master_column_id) {
					p.treeui = this.renderCellTreeUI(r, ds);
				}
				else {
					p.treeui = '';
				}
				// ----- End of modification
				cb[cb.length] = ct.apply(p);
			}
			var alt = [];
      if (stripe && ((rowIndex+1) % 2 == 0)) {
				alt[0] = "x-grid3-row-alt";
      }
      if (r.dirty) {
				alt[1] = " x-grid3-dirty-row";
      }
      rp.cols = colCount;
      if(this.getRowClass){
          alt[2] = this.getRowClass(r, rowIndex, rp, ds);
      }
      rp.alt = alt.join(" ");
      rp.cells = cb.join("");
      // ----- Modification start
      if (!ds.isVisibleNode(r)) {
      	rp.display_style = 'display: none;';
      }
      else {
      	rp.display_style = '';
      }
      rp.level = ds.getNodeDepth(r);
      // ----- End of modification
      buf[buf.length] =  rt.apply(rp);
    }
    return buf.join("");
  },
  
  renderCellTreeUI : function(record, store)
  {
  	var tpl = this.templates.treeui,
  			line_tpl = this.templates.elbow_line,
  			tpl_data = {},
  			rec, parent,
  			depth = level = store.getNodeDepth(record);
  		
  	tpl_data.wrap_width = (depth + 1) * 16;	
  	if (level > 0) {
  		tpl_data.elbow_line = '';
  		rec = record;
  		left = 0;
  		while(level--) {
  			parent = store.getNodeParent(rec);
  			if (parent) {
	  			if (store.hasNextSiblingNode(parent)) {
	  				tpl_data.elbow_line = 
	  					line_tpl.apply({
	  						left : level * 16, 
	  						cls : 'ux-maximgb-treegrid-elbow-line'}) + 
	  					tpl_data.elbow_line;
	  			}
	  			else {
	  				tpl_data.elbow_line = 
	  					line_tpl.apply({
	  						left : level * 16,
	  						cls : 'ux-maximgb-treegrid-elbow-empty'
	  					}) +
	  					tpl_data.elbow_line;
	  			}
	  		}
	  		else {
	  			throw [
	  				"Tree inconsistency can't get level ",
	  				level + 1,
	  				" node(id=", rec.id, ") parent."
	  			].join("")
	  		}
	  		rec = parent;
  		}
  	}
		if (store.isLeafNode(record)) {
			if (store.hasNextSiblingNode(record)) {
				tpl_data.cls = 'ux-maximgb-treegrid-elbow';
			}
			else {
				tpl_data.cls = 'ux-maximgb-treegrid-elbow-end';
			}
		}
		else {
			tpl_data.cls = 'ux-maximgb-treegrid-elbow-active ';
			if (store.isExpandedNode(record)) {
				if (store.hasNextSiblingNode(record)) {
					tpl_data.cls += 'ux-maximgb-treegrid-elbow-minus';
				}
				else {
					tpl_data.cls += 'ux-maximgb-treegrid-elbow-end-minus';
				}
			}
			else {
				if (store.hasNextSiblingNode(record)) {
					tpl_data.cls += 'ux-maximgb-treegrid-elbow-plus';
				}
				else {
					tpl_data.cls += 'ux-maximgb-treegrid-elbow-end-plus';
				}
			}
		}
		tpl_data.left = 1 + depth * 16;
  			
  	return tpl.apply(tpl_data);
  },
	
	// Private
	getBreadcrumbsEl : function()
	{
		return this.breadcrumbs_el;
	},
	
	// Private
	expandRow : function(record, initial)
	{
		var ds = this.ds,
				i, len, row, pmel, children, index, child_index;
		
		if (typeof record == 'number') {
			index = record;
			record = ds.getAt(index);
		}
		else {
			index = ds.indexOf(record);
		}
		
		row = this.getRow(index);
		pmel = Ext.fly(row).child('.ux-maximgb-treegrid-elbow-active');
		if (pmel) {
			if (ds.hasNextSiblingNode(record)) {
				pmel.removeClass('ux-maximgb-treegrid-elbow-plus');
				pmel.removeClass('ux-maximgb-treegrid-elbow-end-plus');
				pmel.addClass('ux-maximgb-treegrid-elbow-minus');
			}
			else {
				pmel.removeClass('ux-maximgb-treegrid-elbow-plus');
				pmel.removeClass('ux-maximgb-treegrid-elbow-end-plus');
				pmel.addClass('ux-maximgb-treegrid-elbow-end-minus');
			}
			if (ds.isVisibleNode(record)) {
				children = ds.getNodeChildren(record);
				for (i = 0, len = children.length; i < len; i++) {
					child_index = ds.indexOf(children[i]);
					row = this.getRow(child_index);
					Ext.fly(row).setStyle('display', 'block');
					if (ds.isExpandedNode(children[i])) {
						this.expandRow(child_index);
					}
				}
			}
		}
	},
	
	collapseRow : function(record)
	{
		var ds = this.ds,
				i, len, children, row, index;
				
		if (typeof record == 'number') {
			index = record;
			record = ds.getAt(index);
		}
		else {
			index = ds.indexOf(record);
		}
		
		row = this.getRow(index);
		pmel = Ext.fly(row).child('.ux-maximgb-treegrid-elbow-active');
		if (pmel) {
			if (ds.hasNextSiblingNode(record)) {
				pmel.removeClass('ux-maximgb-treegrid-elbow-minus');
				pmel.removeClass('ux-maximgb-treegrid-elbow-end-minus');
				pmel.addClass('ux-maximgb-treegrid-elbow-plus');
			}
			else {
				pmel.removeClass('ux-maximgb-treegrid-elbow-minus');
				pmel.removeClass('ux-maximgb-treegrid-elbow-end-minus');
				pmel.addClass('ux-maximgb-treegrid-elbow-end-plus');
			}
			children = ds.getNodeChildren(record);
			for (i = 0, len = children.length; i < len; i++) {
				index = ds.indexOf(children[i]);
				row = this.getRow(index);
				Ext.fly(row).setStyle('display', 'none'); 
				this.collapseRow(index);
			}
		}
	},
	
	/**
	 * @access private
	 */
	initData : function(ds, cm)
	{
		Ext.ux.maximgb.treegrid.GridView.superclass.initData.call(this, ds, cm);
		if (this.ds) {
			this.ds.un('activenodechange', this.onStoreActiveNodeChange, this);
			this.ds.un('expandnode', this.onStoreExpandNode, this);
			this.ds.un('collapsenode', this.onStoreCollapseNode, this);
		}
		if (ds) {
			ds.on('activenodechange', this.onStoreActiveNodeChange, this);
			ds.on('expandnode', this.onStoreExpandNode, this);
			ds.on('collapsenode', this.onStoreCollapseNode, this);
		}
	},
	
	onStoreActiveNodeChange : function(store, old_rc, new_rc)
	{
		var parents, i, len, rec, items = [],
				ts = this.templates;
				
		if (new_rc) {
			parents = this.ds.getNodeAncestors(new_rc),
			parents.reverse();
			parents.push(new_rc);
		
			for (i = 0, len = parents.length; i < len; i++) {
				rec = parents[i];
				items.push(
					ts.brd_item.apply({
						id : rec.id,
						title : this.grid.i18n.breadcrumbs_tip,
						caption : rec.get(
							this.cm.getDataIndex(
								this.cm.getIndexById(this.grid.master_column_id)
							)
						)
					})
				);
			}
			
			this.breadcrumbs_el.update(
				this.grid.i18n.path_separator +
				ts.brd_item.apply({
					id: '',
					title : this.grid.i18n.breadcrumbs_root_tip,
					caption : this.grid.root_title
				}) +
				this.grid.i18n.path_separator +
				items.join(this.grid.i18n.path_separator)
			);
		}
		else {
			this.setRootBreadcrumbs();
		}
	},
	
	setRootBreadcrumbs : function()
	{
		var ts = this.templates;
		this.breadcrumbs_el.update(
			this.grid.i18n.path_separator +
			ts.brd_item.apply({
					id: '',
					title : this.grid.i18n.breadcrumbs_root_tip,
					caption : this.grid.root_title
			})
		);
	},
	
	onLoad : function(store, records, options)
	{
		var id = store.getLoadedNodeIdFromOptions(options);
		if (id === null) {
			Ext.ux.maximgb.treegrid.GridView.superclass.onLoad.call(this, store, records, options);
		}
	},
	
	onStoreExpandNode : function(store, rc)
	{
		this.expandRow(rc);
	},
	
	onStoreCollapseNode : function(store, rc)
	{
		this.collapseRow(rc);
	}
});