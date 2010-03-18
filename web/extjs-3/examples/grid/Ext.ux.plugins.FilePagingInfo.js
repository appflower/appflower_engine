/**
Extended Grid
Ability to update on realtime
@author: Prakash Paudel
*/
Ext.namespace('Ext.ux.plugins');

Ext.ux.plugins.FilePagingInfo = {
	init: function(container){    	
		Ext.apply(container, {
			onRender: container.onRender.createSequence(function(ct, position){				
				pager = this;
				this.on('change',function(pager,data){					
					var currentPage = data.activePage;
					var to = this.formatData((pager.pageSize * currentPage) > data.total ? data.total:pager.pageSize * currentPage);					
					var from = this.formatData(currentPage == 1?1:(currentPage-1)*pager.pageSize);
					//var msg = "Displaying "+pager.store.getCount()+" messages within "+from+" to "+to+" of "+ this.formatData(data.total);
					var msg = "Displaying "+pager.store.getCount()+" Messages";				
					this.displayEl = this.el.select('.x-toolbar-right').select('xtb-text');					
					pager.displayEl.update(msg);
				
				});			    		   
			}),
			formatData: function(data){
				var size = 1024;
				if(data < size) return data + ((data < 2) ?" Byte":" Bytes");
				if(data >= size && data < size*1024){
					data = (data/size)
					return Math.abs(data).toFixed(2)+" Kb"
				}
				size = size*1024;
				if(data >= size && data < size*1024){
					data = (data/size)
					return Math.abs(data).toFixed(2)+" Mb"
				}
				size = size*1024;
				if(data >= size && data < size*1024){
					data = (data/size)
					return Math.abs(data).toFixed(2)+" Gb"
				}
				size = size*1024;
				if(data >= size && data < size*1024){
					data = (data/size)
					return Math.abs(data).toFixed(2)+" Tb"
				}
				
			}
		});        
	}
};
Ext.ux.plugins.FilePagingInfo = Ext.extend(Ext.ux.plugins.FilePagingInfo, Ext.ux.GridColorView);