/**
 * Paging toolbar for work this AbstractTreeStore.
 */
Ext.ux.maximgb.treegrid.PagingToolbar = Ext.extend(Ext.PagingToolbar,
{
	onRender : function(ct, position)
	{
		Ext.ux.maximgb.treegrid.PagingToolbar.superclass.onRender.call(this, ct, position);
		this.updateUI();
	},

  getPageData : function()
  {
		var total = 0, cursor = 0;
		if (this.store) {
			cursor = this.store.getActiveNodePageOffset();
			total = this.store.getActiveNodeTotalCount();
		}
    return {
        total : total,
        activePage : Math.ceil((cursor + this.pageSize) / this.pageSize),
        pages :  total < this.pageSize ? 1 : Math.ceil(total / this.pageSize)
    };
	},
	
	updateInfo : function()
	{
		var count = 0, cursor = 0, total = 0, msg;
		if (this.displayEl) {
			if (this.store) {
				cursor = this.store.getActiveNodePageOffset();
				count = this.store.getActiveNodeCount();
				total = this.store.getActiveNodeTotalCount();
			}
			msg = count == 0 ?
				this.emptyMsg 
					:
        String.format(
            this.displayMsg,
            cursor + 1, cursor + count, total
        );
			this.displayEl.update(msg);
		}
	},
	
	updateUI : function()
	{
		var d = this.getPageData(), ap = d.activePage, ps = d.pages;

    this.afterTextEl.el.innerHTML = String.format(this.afterPageText, d.pages);
    this.field.dom.value = ap;
    this.first.setDisabled(ap == 1);
    this.prev.setDisabled(ap == 1);
    this.next.setDisabled(ap == ps);
    this.last.setDisabled(ap == ps);
    this.loading.enable();
    this.updateInfo();
	},

	unbind : function(store)
	{
		Ext.ux.maximgb.treegrid.PagingToolbar.superclass.unbind.call(this, store);
		store.un('activenodechange', this.onStoreActiveNodeChange, this);
	},

	bind : function(store)
	{
		Ext.ux.maximgb.treegrid.PagingToolbar.superclass.bind.call(this, store);
		store.on('activenodechange', this.onStoreActiveNodeChange, this);
	},
	
	beforeLoad : function(store, options)
	{
		Ext.ux.maximgb.treegrid.PagingToolbar.superclass.beforeLoad.call(this, store, options);
		if (options && options.params) {
			if(options.params[this.paramNames.start] === undefined) {
				options.params[this.paramNames.start] = 0;
			}
			if(options.params[this.paramNames.limit] === undefined) {
				options.params[this.paramNames.limit] = this.pageSize;
			}
		}
	},
	
	onClick : function(which)
	{
		var store = this.store,
				cursor = store ? store.getActiveNodePageOffset() : 0,
				total = store ? store.getActiveNodeTotalCount() : 0;
				
		switch(which){
			case "first":
				this.doLoad(0);
				break;
			case "prev":
				this.doLoad(Math.max(0, cursor - this.pageSize));
				break;
			case "next":
				this.doLoad(cursor + this.pageSize);
				break;
			case "last":
        var extra = total % this.pageSize;
        var lastStart = extra ? (total - extra) : total - this.pageSize;
        this.doLoad(lastStart);
				break;
			case "refresh":
				this.doLoad(cursor);
				break;
		}
	},
	
	onStoreActiveNodeChange : function(store, old_rec, new_rec)
	{
		if (this.rendered) {
			this.updateUI();
		}
	}
});