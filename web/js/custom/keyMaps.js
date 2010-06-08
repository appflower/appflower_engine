/**
* Keymap for tabPortalPanel inside a portal page
*
* @author radu
*/

var map = new Ext.KeyMap(document, {
	shift:true,
	key: Ext.EventObject.TAB,
	handler: function (key,e)
	{
		e.stopEvent();
		
		var viewport=App.getViewport();
		var tabPortalPanel=viewport.layout.center.panel.items.items[0].items.items[0];
		
		if(tabPortalPanel.getXType()=='tabpanel')
		{		
			var items = tabPortalPanel.items.items;
			var active_tab = tabPortalPanel.getActiveTab();
			var total_tabs = items.length;
			
			// loop the tabs
			for(i = 0 ; i < items.length; i++)
			{
				// find the active tab based on the id property.
				if (active_tab.id == items[i].id) {
					// do we want to move left?
					if (key == Ext.EventObject.LEFT)
					{
						// move left
						var next = (i - 1)
						if (next < 0) {
							// we're at -1, set to last tab
							next = (total_tabs - 1);
						}
					}
					else
					{
						// move right
						var next = (i + 1);
						if (next >= total_tabs)
						{
							// we've gone 1 too many set to start position.
							next = 0;
						}
					}
					// set the tab and return there's no need to carry on
					tabPortalPanel.setActiveTab(items[next].id);
					return;
				}
			}
		}
	}
});