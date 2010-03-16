Ext.ns("Ext.ux");

/**
 * @class Ext.ux.VerticalTabPanel
 * @extends Ext.TabPanel
 * @author <a href="mailto:me@carina.net">Carina Stumpf</a>
 * @version 0.2
 *
 * An extension for Ext.TabPanel that enables tabs to be positioned on the left or right side of a TabPanel.
 */


/**
 * @constructor
 * @param {Object} cfg A config object
 *  @cfg {String} tabPosition 'top' (the ext default behaviour), 'bottom' (also ext default), 'left' (vertical tabs on the left side) or right (vertical tabs on the right side)
 *  @cfg {Number} tabWidth (only applies if tabPosition is set to 'left' or 'right') the width of the tab strip in pixel; defaults to 150
 *  @cfg {String} textAlign 'left' or 'right', defaults to 'left' (only applies if tabPosition is set to 'left' or 'right')
 */
Ext.ux.VerticalTabPanel = function(cfg) {
  if (cfg.tabPosition == 'left' || cfg.tabPosition == 'right') {
    cfg.cls = cfg.cls || '';
    cfg.cls = 'ux-vertical-tabs ' + cfg.cls;
    if (cfg.textAlign && cfg.textAlign == 'right') {
      cfg.cls = 'ux-vertical-tabs-alignright ' + cfg.cls;
    }
    cfg.cls = (cfg.tabPosition == 'left' ? 'ux-vertical-tabs-left ' : 'ux-vertical-tabs-right ') + cfg.cls;
    this.intendedTabPosition = cfg.tabPosition;
    this.verticalTabs = true;
    cfg.tabPosition = 'top';
  }

  Ext.ux.VerticalTabPanel.superclass.constructor.call(this, cfg);

};



Ext.extend(Ext.ux.VerticalTabPanel, Ext.TabPanel, {
  tabWidth : 150,


  afterRender : function() {
    Ext.ux.VerticalTabPanel.superclass.afterRender.call(this);
    if (this.verticalTabs) {
      this.header.setWidth(this.tabWidth);
      this.header.setHeight(this.height || this.container.getHeight());
    }
  },


/**
 * Adjust header and footer size.
 * @param {Number} w width of the container
 * @return {Number} the body will be resized to this width
 */

  adjustBodyWidth : function(w) {
    if (this.verticalTabs) {
      if (Ext.isIE6) {
        //I got the value "3" through trial and error; it seems to be related with the x-panel-header border; if the border
        //is set to "none", then this substraction is not necessary - but it does not seem related to the border width, margin or padding of any
        //of the panels so I dont know how to calculate it; please let me know if you have any idea what's going on here
        this.bwrap.setWidth(w );
      }
      return w;
    }
    else {
      return Ext.ux.VerticalTabPanel.superclass.adjustBodyWidth.call(this, w);
    }
  },

/**
 * Get the new body height and adjust the height of the tab strip if it is vertical.
 * @param h {Number}
 */
  adjustBodyHeight : function(h) {
    if (this.verticalTabs) {
      this.header.setHeight(h + (this.tbar ? this.tbar.getHeight() : 0)+500);
    }
    return Ext.ux.VerticalTabPanel.superclass.adjustBodyHeight.call(this, h);
  },

/**
 * If the tab strip is vertical, we need to substract the "header" width.
 * @return {Number} The frame width
 */
  getFrameWidth : function() {
    return Ext.ux.VerticalTabPanel.superclass.getFrameWidth.call(this) + this.verticalTabs ? this.tabWidth : 0;
  },

/**
 * If the tab strip is vertical, we don't need to substract it's height
 * @return {Number} The frame height
 */
  getFrameHeight : function() {
    return Ext.ux.VerticalTabPanel.superclass.getFrameHeight.call(this) - (this.verticalTabs ? this.header.getHeight() : 0);
  }
});
