/*!
 * Ext JS Library 3.3.1
 * Copyright(c) 2006-2010 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */
Ext.Desktop = function(app) {
    this.taskbar = new Ext.ux.TaskBar(app);
    this.xTickSize = this.yTickSize = 1;
    var taskbar = this.taskbar;
    
    var desktopEl = Ext.get('x-desktop');
    var taskbarEl = Ext.get('ux-taskbar');
    
    this.cascade = function() {
        var x = 0,
        y = 0;
        afApp.windows.each(function(win) {
            if (win.isVisible() && !win.maximized) {
                win.setPosition(x, y);
                x += 20;
                y += 20;
            }
        },
        this);
    };

    this.tile = function() {
        var availWidth = desktopEl.getWidth(true);
        
        var x = this.xTickSize;
        var y = this.yTickSize;
        var nextY = y;
        afApp.windows.each(function(win) {
            if (win.isVisible() && !win.maximized) {
                var w = win.el.getWidth();

                //              Wrap to next row if we are not at the line start and this Window will go off the end
                if ((x > this.xTickSize) && (x + w > availWidth)) {
                    x = this.xTickSize;
                    y = nextY;
                }

                win.setPosition(x, y);
                x += w + this.xTickSize;
                nextY = Math.max(nextY, y + win.el.getHeight() + this.yTickSize);
            }
        },
        this);
    };

    this.contextMenu = new Ext.menu.Menu({
        items: [{
            text: 'Tile',
            handler: this.tile,
            scope: this
        },
        {
            text: 'Cascade',
            handler: this.cascade,
            scope: this
        }]
    });
    
    desktopEl.on('contextmenu',
        function(e) {
            e.stopEvent();
            this.contextMenu.showAt(e.getXY());
        },
        this);

    afApp.layout();
};
