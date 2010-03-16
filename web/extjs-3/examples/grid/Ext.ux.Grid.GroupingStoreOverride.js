/*
 * For the grids with no initial sort, the grouping view attempt shows a error
 * 
 * @author: prakash paudel
 */
Ext.override(Ext.data.GroupingStore, {
    applySort : function(){
        Ext.data.GroupingStore.superclass.applySort.call(this);
        if(!this.groupOnSort && !this.remoteGroup){
            var gs = this.getGroupState();
            var si = this.sortInfo || {};
            if(gs && gs != si.field){
            	this.sortData(this.groupField);
            }
        }
    }
}); 
