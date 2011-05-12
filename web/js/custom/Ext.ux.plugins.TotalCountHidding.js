/*
* Redefines the pagingToolbar displayMsg.
*/
Ext.namespace('Ext.ux.plugins');

Ext.ux.plugins.TotalCountHidding = function() {};
Ext.ux.plugins.TotalCountHidding.prototype = {
    init: function(grid) {
        var bbar = grid.getBottomToolbar();
        if (bbar) {
            bbar.updateInfo = function() {
                if(this.displayItem){
                    var count = this.store.getCount();
                    var msg = count == 0 ?
                        this.emptyMsg :
                        String.format('Displaying {0} messages', count);
                    this.displayItem.setText(msg);
                }
            };
        }
    }
};

