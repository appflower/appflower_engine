/**
* @author: radu
*/

Ext.ns('ux');

Ext.ux.GroupingColorView = function(config){
    Ext.apply(this, config);
    Ext.ux.GroupingColorView.superclass.constructor.call(this);
};

Ext.ux.GroupingColorView = Ext.extend(Ext.ux.GridColorView, {
    /**
     * @cfg {String} groupByText Text displayed in the grid header menu for grouping by a column
     * (defaults to 'Group By This Field').
     */
    groupByText : 'Group By This Field',
    /**
     * @cfg {String} showGroupsText Text displayed in the grid header for enabling/disabling grouping
     * (defaults to 'Show in Groups').
     */
    showGroupsText : 'Show in Groups',
    /**
     * @cfg {Boolean} hideGroupedColumn <tt>true</tt> to hide the column that is currently grouped (defaults to <tt>false</tt>)
     */
    hideGroupedColumn : false,
    /**
     * @cfg {Boolean} showGroupName If <tt>true</tt> will display a prefix plus a ': ' before the group field value
     * in the group header line.  The prefix will consist of the <tt><b>{@link Ext.grid.Column#groupName groupName}</b></tt>
     * (or the configured <tt><b>{@link Ext.grid.Column#header header}</b></tt> if not provided) configured in the
     * {@link Ext.grid.Column} for each set of grouped rows (defaults to <tt>true</tt>).
     */
    showGroupName : true,
    /**
     * @cfg {Boolean} startCollapsed <tt>true</tt> to start all groups collapsed (defaults to <tt>false</tt>)
     */
    startCollapsed : false,
    /**
     * @cfg {Boolean} enableGrouping <tt>false</tt> to disable grouping functionality (defaults to <tt>true</tt>)
     */
    enableGrouping : true,
    /**
     * @cfg {Boolean} enableGroupingMenu <tt>true</tt> to enable the grouping control in the column menu (defaults to <tt>true</tt>)
     */
    enableGroupingMenu : true,
    /**
     * @cfg {Boolean} enableNoGroups <tt>true</tt> to allow the user to turn off grouping (defaults to <tt>true</tt>)
     */
    enableNoGroups : true,
    /**
     * @cfg {String} emptyGroupText The text to display when there is an empty group value (defaults to <tt>'(None)'</tt>).
     * May also be specified per column, see {@link Ext.grid.Column}.{@link Ext.grid.Column#emptyGroupText emptyGroupText}.
     */
    emptyGroupText : '(None)',
    /**
     * @cfg {Boolean} ignoreAdd <tt>true</tt> to skip refreshing the view when new rows are added (defaults to <tt>false</tt>)
     */
    ignoreAdd : false,
    /**
     * @cfg {String} groupTextTpl The template used to render the group header (defaults to <tt>'{text}'</tt>).
     * This is used to format an object which contains the following properties:
     * <div class="mdetail-params"><ul>
     * <li><b>group</b> : String<p class="sub-desc">The <i>rendered</i> value of the group field.
     * By default this is the unchanged value of the group field. If a <tt><b>{@link Ext.grid.Column#groupRenderer groupRenderer}</b></tt>
     * is specified, it is the result of a call to that function.</p></li>
     * <li><b>gvalue</b> : Object<p class="sub-desc">The <i>raw</i> value of the group field.</p></li>
     * <li><b>text</b> : String<p class="sub-desc">The configured header (as described in <tt>{@link #showGroupName})</tt>
     * if <tt>{@link #showGroupName}</tt> is <tt>true</tt>) plus the <i>rendered</i> group field value.</p></li>
     * <li><b>groupId</b> : String<p class="sub-desc">A unique, generated ID which is applied to the
     * View Element which contains the group.</p></li>
     * <li><b>startRow</b> : Number<p class="sub-desc">The row index of the Record which caused group change.</p></li>
     * <li><b>rs</b> : Array<p class="sub-desc">Contains a single element: The Record providing the data
     * for the row which caused group change.</p></li>
     * <li><b>cls</b> : String<p class="sub-desc">The generated class name string to apply to the group header Element.</p></li>
     * <li><b>style</b> : String<p class="sub-desc">The inline style rules to apply to the group header Element.</p></li>
     * </ul></div></p>
     * See {@link Ext.XTemplate} for information on how to format data using a template. Possible usage:<pre><code>
var grid = new Ext.grid.GridPanel({
    ...
    view: new Ext.ux.GroupingColorView({
        groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})'
    }),
});
     * </code></pre>
     */
    groupTextTpl : '{text}',
    /**
     * @cfg {Function} groupRenderer This property must be configured in the {@link Ext.grid.Column} for
     * each column.
     */

    // private
    gidSeed : 1000,

    // private
    initTemplates : function(){
        Ext.ux.GroupingColorView.superclass.initTemplates.call(this);
        this.state = {};

        var sm = this.grid.getSelectionModel();
        sm.on(sm.selectRow ? 'beforerowselect' : 'beforecellselect',
                this.onBeforeRowSelect, this);

        if(!this.startGroup){
            this.startGroup = new Ext.XTemplate(
                '<div id="{groupId}" class="x-grid-group {cls}">',
                    '<div id="{groupId}-hd" class="x-grid-group-hd" style="{style}"><div class="x-grid-group-title">', this.groupTextTpl ,'</div></div>',
                    '<div id="{groupId}-bd" class="x-grid-group-body">'
            );
        }
        this.startGroup.compile();
        this.endGroup = '</div></div>';
    },

    // private
    findGroup : function(el){
        return Ext.fly(el).up('.x-grid-group', this.mainBody.dom);
    },

    // private
    getGroups : function(){
        return this.hasRows() ? this.mainBody.dom.childNodes : [];
    },

    // private
    onAdd : function(){
        if(this.enableGrouping && !this.ignoreAdd){
            var ss = this.getScrollState();
            this.refresh();
            this.restoreScroll(ss);
        }else if(!this.enableGrouping){
            Ext.ux.GroupingColorView.superclass.onAdd.apply(this, arguments);
        }
    },

    // private
    onRemove : function(ds, record, index, isUpdate){
        Ext.ux.GroupingColorView.superclass.onRemove.apply(this, arguments);
        var g = document.getElementById(record._groupId);
        if(g && g.childNodes[1].childNodes.length < 1){
            Ext.removeNode(g);
        }
        this.applyEmptyText();
    },

    // private
    refreshRow : function(record){
        if(this.ds.getCount()==1){
            this.refresh();
        }else{
            this.isUpdating = true;
            Ext.ux.GroupingColorView.superclass.refreshRow.apply(this, arguments);
            this.isUpdating = false;
        }
    },

    // private
    beforeMenuShow : function(){
        var item, items = this.hmenu.items, disabled = this.cm.config[this.hdCtxIndex].groupable === false;
        if((item = items.get('groupBy'))){
            item.setDisabled(disabled);
        }
        if((item = items.get('showGroups'))){
            item.setDisabled(disabled);
		    item.setChecked(!!this.getGroupField(), true);
        }
    },

    // private
    renderUI : function(){
       /* Ext.ux.GroupingColorView.superclass.renderUI.call(this);
        this.mainBody.on('mousedown', this.interceptMouse, this);

        if(this.enableGroupingMenu && this.hmenu){
            this.hmenu.add('-',{
                itemId:'groupBy',
                text: this.groupByText,
                handler: this.onGroupByClick,
                scope: this,
                iconCls:'x-group-by-icon'
            });
            if(this.enableNoGroups){
                this.hmenu.add({
                    itemId:'showGroups',
                    text: this.showGroupsText,
                    checked: true,
                    checkHandler: this.onShowGroupsClick,
                    scope: this
                });
            }
            this.hmenu.on('beforeshow', this.beforeMenuShow, this);
        }*/
        var markup = Ext.grid.GroupingView.superclass.renderUI.call(this);

        if(this.enableGroupingMenu && this.hmenu){
            this.hmenu.add('-',{
                itemId:'groupBy',
                text: this.groupByText,
                handler: this.onGroupByClick,
                scope: this,
                iconCls:'x-group-by-icon'
            });
            if(this.enableNoGroups){
                this.hmenu.add({
                    itemId:'showGroups',
                    text: this.showGroupsText,
                    checked: true,
                    checkHandler: this.onShowGroupsClick,
                    scope: this
                });
            }
            this.hmenu.on('beforeshow', this.beforeMenuShow, this);
        }
        return markup;
    },

    // private
    onGroupByClick : function(){
        this.grid.store.groupBy(this.cm.getDataIndex(this.hdCtxIndex));
        this.beforeMenuShow(); // Make sure the checkboxes get properly set when changing groups
    },

    // private
    onShowGroupsClick : function(mi, checked){
        if(checked){
            this.onGroupByClick();
        }else{
            this.grid.store.clearGrouping();
        }
    },

    /**
     * Toggles the specified group if no value is passed, otherwise sets the expanded state of the group to the value passed.
     * @param {String} groupId The groupId assigned to the group (see getGroupId)
     * @param {Boolean} expanded (optional)
     */
    toggleGroup : function(group, expanded){
        this.grid.stopEditing(true);
        group = Ext.getDom(group);
        var gel = Ext.fly(group);
        expanded = expanded !== undefined ?
                expanded : gel.hasClass('x-grid-group-collapsed');

        this.state[gel.dom.id] = expanded;
        gel[expanded ? 'removeClass' : 'addClass']('x-grid-group-collapsed');
    },

    /**
     * Toggles all groups if no value is passed, otherwise sets the expanded state of all groups to the value passed.
     * @param {Boolean} expanded (optional)
     */
    toggleAllGroups : function(expanded){
        var groups = this.getGroups();
        for(var i = 0, len = groups.length; i < len; i++){
            this.toggleGroup(groups[i], expanded);
        }
    },

    /**
     * Expands all grouped rows.
     */
    expandAllGroups : function(){
        this.toggleAllGroups(true);
    },

    /**
     * Collapses all grouped rows.
     */
    collapseAllGroups : function(){
        this.toggleAllGroups(false);
    },

    // private
    interceptMouse : function(e){
        var hd = e.getTarget('.x-grid-group-hd', this.mainBody);
        if(hd){
            e.stopEvent();
            this.toggleGroup(hd.parentNode);
        }
    },

    // private
    getGroup : function(v, r, groupRenderer, rowIndex, colIndex, ds){
        var g = groupRenderer ? groupRenderer(v, {}, r, rowIndex, colIndex, ds) : String(v);
        if(g === ''){
            g = this.cm.config[colIndex].emptyGroupText || this.emptyGroupText;
        }
        return g;
    },

    // private
    getGroupField : function(){
        return this.grid.store.getGroupState();
    },
    
    // private
    afterRender : function(){
        Ext.ux.GroupingColorView.superclass.afterRender.call(this);
        if(this.grid.deferRowRender){
            this.updateGroupWidths();
        }
    },

    // private
    renderRows : function(){
        var groupField = this.getGroupField();
        var eg = !!groupField;
        // if they turned off grouping and the last grouped field is hidden
        if(this.hideGroupedColumn) {
            var colIndex = this.cm.findColumnIndex(groupField);
            if(!eg && this.lastGroupField !== undefined) {
                this.mainBody.update('');
                this.cm.setHidden(this.cm.findColumnIndex(this.lastGroupField), false);
                delete this.lastGroupField;
            }else if (eg && this.lastGroupField === undefined) {
                this.lastGroupField = groupField;
                this.cm.setHidden(colIndex, true);
            }else if (eg && this.lastGroupField !== undefined && groupField !== this.lastGroupField) {
                this.mainBody.update('');
                var oldIndex = this.cm.findColumnIndex(this.lastGroupField);
                this.cm.setHidden(oldIndex, false);
                this.lastGroupField = groupField;
                this.cm.setHidden(colIndex, true);
            }
        }
        return Ext.ux.GroupingColorView.superclass.renderRows.apply(
                    this, arguments);
    },

    // private
    doRender : function(cs, rs, ds, startRow, colCount, stripe){
        if(rs.length < 1){
            return '';
        }
        var groupField = this.getGroupField(),
            colIndex = this.cm.findColumnIndex(groupField),
            g;

        this.enableGrouping = !!groupField;

        if(!this.enableGrouping || this.isUpdating){
            return Ext.ux.GroupingColorView.superclass.doRender.apply(
                    this, arguments);
        }
        var gstyle = 'width:'+this.getTotalWidth()+';';

        var gidPrefix = this.grid.getGridEl().id;
        var cfg = this.cm.config[colIndex];
        var groupRenderer = cfg.groupRenderer || cfg.renderer;
        var prefix = this.showGroupName ?
                     (cfg.groupName || cfg.header)+': ' : '';

        var groups = [], curGroup, i, len, gid;
        for(i = 0, len = rs.length; i < len; i++){
            var rowIndex = startRow + i,
                r = rs[i],
                gvalue = r.data[groupField];
                
                g = this.getGroup(gvalue, r, groupRenderer, rowIndex, colIndex, ds);
            if(!curGroup || curGroup.group != g){
                gid = gidPrefix + '-gp-' + groupField + '-' + Ext.util.Format.htmlEncode(g);
               	// if state is defined use it, however state is in terms of expanded
				// so negate it, otherwise use the default.
				var isCollapsed  = typeof this.state[gid] !== 'undefined' ? !this.state[gid] : this.startCollapsed;
				var gcls = isCollapsed ? 'x-grid-group-collapsed' : '';	
                curGroup = {
                    group: g,
                    gvalue: gvalue,
                    text: prefix + g,
                    groupId: gid,
                    startRow: rowIndex,
                    rs: [r],
                    cls: gcls,
                    style: gstyle
                };
                groups.push(curGroup);
            }else{
                curGroup.rs.push(r);
            }
            r._groupId = gid;
        }

        var buf = [];
        for(i = 0, len = groups.length; i < len; i++){
            g = groups[i];
            this.doGroupStart(buf, g, cs, ds, colCount);
            buf[buf.length] = Ext.ux.GroupingColorView.superclass.doRender.call(
                    this, cs, g.rs, ds, g.startRow, colCount, stripe);

            this.doGroupEnd(buf, g, cs, ds, colCount);
        }
        return buf.join('');
    },

    /**
     * Dynamically tries to determine the groupId of a specific value
     * @param {String} value
     * @return {String} The group id
     */
    getGroupId : function(value){
        var gidPrefix = this.grid.getGridEl().id;
        var groupField = this.getGroupField();
        var colIndex = this.cm.findColumnIndex(groupField);
        var cfg = this.cm.config[colIndex];
        var groupRenderer = cfg.groupRenderer || cfg.renderer;
        var gtext = this.getGroup(value, {data:{}}, groupRenderer, 0, colIndex, this.ds);
        return gidPrefix + '-gp-' + groupField + '-' + Ext.util.Format.htmlEncode(value);
    },

    // private
    doGroupStart : function(buf, g, cs, ds, colCount){
        buf[buf.length] = this.startGroup.apply(g);
    },

    // private
    doGroupEnd : function(buf, g, cs, ds, colCount){
        buf[buf.length] = this.endGroup;
    },

    // private
    getRows : function(){
        if(!this.enableGrouping){
            return Ext.ux.GroupingColorView.superclass.getRows.call(this);
        }
        var r = [];
        var g, gs = this.getGroups();
        for(var i = 0, len = gs.length; i < len; i++){
            g = gs[i].childNodes[1].childNodes;
            for(var j = 0, jlen = g.length; j < jlen; j++){
                r[r.length] = g[j];
            }
        }
        return r;
    },

    // private
    updateGroupWidths : function(){
        if(!this.enableGrouping || !this.hasRows()){
            return;
        }
        var tw = Math.max(this.cm.getTotalWidth(), this.el.dom.offsetWidth-this.scrollOffset) +'px';
        var gs = this.getGroups();
        for(var i = 0, len = gs.length; i < len; i++){
            gs[i].firstChild.style.width = tw;
        }
    },

    // private
    onColumnWidthUpdated : function(col, w, tw){
        Ext.ux.GroupingColorView.superclass.onColumnWidthUpdated.call(this, col, w, tw);
        this.updateGroupWidths();
    },

    // private
    onAllColumnWidthsUpdated : function(ws, tw){
        Ext.ux.GroupingColorView.superclass.onAllColumnWidthsUpdated.call(this, ws, tw);
        this.updateGroupWidths();
    },

    // private
    onColumnHiddenUpdated : function(col, hidden, tw){
        Ext.ux.GroupingColorView.superclass.onColumnHiddenUpdated.call(this, col, hidden, tw);
        this.updateGroupWidths();
    },

    // private
    onLayout : function(){
        this.updateGroupWidths();
    },

    // private
    onBeforeRowSelect : function(sm, rowIndex){
        if(!this.enableGrouping){
            return;
        }
        var row = this.getRow(rowIndex);
        if(row && !row.offsetParent){
            var g = this.findGroup(row);
            this.toggleGroup(g, true);
        }
    }
});
// private
Ext.ux.GroupingColorView.GROUP_ID = 1000;