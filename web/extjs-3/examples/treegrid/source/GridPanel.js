Ext.ux.maximgb.treegrid.GridPanel = Ext.extend(Ext.grid.GridPanel, 
{
	/**
	 * @cfg {String|Integer} master_column_id Master column id. Master column cells are nested.
	 * Master column cell values are used to build breadcrumbs.
	 */
	master_column_id : 0,
	
	/**
	 * @cfg {String} Root node title.
	 */
	root_title : null,
	
	/**
	 * @cfg {Object} i18n I18N text strings.
	 */
	i18n : null,

	// Private
	initComponent : function()
	{
		Ext.ux.maximgb.treegrid.GridPanel.superclass.initComponent.call(this);
		
		Ext.applyIf(this.i18n, Ext.ux.maximgb.treegrid.GridPanel.prototype.i18n);
		
		if (!this.root_title) {
			this.root_title = this.title || this.i18n.root_title;
		}
		
		this.getSelectionModel().on(
			'selectionchange',
			this.onTreeGridSelectionChange,
			this
		);
	},

	/**
	 * Returns view instance.
	 *
	 * @access private
	 * @return {GridView}
	 */
	getView : function()
	{
		if (!this.view) {
			this.view = new Ext.ux.maximgb.treegrid.GridView(this.viewConfig);
		}
		return this.view;
	},
	
	/**
	 * @access private
	 */
	onClick : function(e)
	{
		var target = e.getTarget(),
				view = this.getView(),
				row = view.findRowIndex(target),
				store = this.getStore(),
				sm = this.getSelectionModel(), 
				record, record_id, do_default = true;
		
		// Row click
		if (row !== false) {
			if (Ext.fly(target).hasClass('ux-maximgb-treegrid-elbow-active')) {
				record = store.getAt(row);
				if (store.isExpandedNode(record)) {
					store.collapseNode(record);
				}
				else {
					store.expandNode(record);
				}
				do_default = false;
			}
		}
		// Breadcrumb click
		else if (Ext.fly(target).hasClass('ux-maximgb-treegrid-brditem')) {
			record_id = Ext.id(target);
			record_id = record_id.substr(record_id.lastIndexOf('-') + 1);
			if (record_id != '') {
				record = store.getById(record_id);
				row = store.indexOf(record);
				
				if (e.hasModifier()) {
					if (store.isExpandedNode(record)) {
						store.collapseNode(record);
					}
					else {
						store.expandNode(record);
					}
				}
				else if (sm.isSelected && !sm.isSelected(row)) {
					sm.selectRow(row);
				}
			}
			else {
				sm.clearSelections();
			}
			e.preventDefault();
		}

		if (do_default) {
			Ext.ux.maximgb.treegrid.GridPanel.superclass.onClick.call(this, e);
		}
	},

	/**
   * @access private
   */
	onMouseDown : function(e)
	{
		var target = e.getTarget();

		if (!Ext.fly(target).hasClass('ux-maximgb-treegrid-elbow-active')) {
			Ext.ux.maximgb.treegrid.GridPanel.superclass.onMouseDown.call(this, e);
		}
	},
	
	/**
	 * @access private
	 */
	onDblClick : function(e)
	{
		var target = e.getTarget(),
				view = this.getView(),
				row = view.findRowIndex(target),
				store = this.getStore(),
				sm = this.getSelectionModel(), 
				record, record_id;
			
		// Breadcrumbs select + expand/collapse	
		if (!row && Ext.fly(target).hasClass('ux-maximgb-treegrid-brditem')) {
			record_id = Ext.id(target);
			record_id = record_id.substr(record_id.lastIndexOf('-') + 1);
			if (record_id != '') {
				record = store.getById(record_id);
				row = store.indexOf(record);
				
				if (store.isExpandedNode(record)) {
					store.collapseNode(record);
				}
				else {
					store.expandNode(record);
				}
				
				if (sm.isSelected && !sm.isSelected(row)) {
					sm.selectRow(row);
				}
			}
			else {
				sm.clearSelections();
			}
		}
		
		Ext.ux.maximgb.treegrid.GridPanel.superclass.onDblClick.call(this, e);
	},
	
	/**
	 * @access private
	 */
	onTreeGridSelectionChange : function(sm, selection)
	{
		var record;
		// Row selection model
		if (sm.getSelected) {
			record = sm.getSelected();
			this.getStore().setActiveNode(record);
		}
		// Cell selection model
		else if (Ext.type(selection) == 'array' && selection.length > 0) {
			record = store.getAt(selection[0])
			this.getStore().setActiveNode(record);
		}
		else {
			throw "Unknown selection model applyed to the grid.";
		}
	}
});

Ext.ux.maximgb.treegrid.GridPanel.prototype.i18n = {
	path_separator : ' / ',
	root_title : '[root]',
	breadcrumbs_tip : 'Click to select node, CTRL+Click to expand or collapse node, Double click to select and expand or collapse node.',
	breadcrumbs_root_tip : 'Click to select the top level node.'
}