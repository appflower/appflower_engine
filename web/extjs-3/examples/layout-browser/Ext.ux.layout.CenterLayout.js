Ext.ns('Ext.ux.layout');

Ext.ux.layout.CenterLayout = Ext.extend(Ext.layout.FitLayout, {
	// private
    setItemSize : function(item, size){
        this.container.addClass('ux-layout-center');
        item.addClass('ux-layout-center-item');
        if(item && size.height > 0){
            if(item.width){
                size.width = item.width;
                size.height = 'auto';
            }
            item.setSize(size);
        }
    }
});
Ext.Container.LAYOUTS['ux.center'] = Ext.ux.layout.CenterLayout;
if(afApp.saveBookmarkBeforeLogin) afApp.saveBookmarkBeforeLogin();