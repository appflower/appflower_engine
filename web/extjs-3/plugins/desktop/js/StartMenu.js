/*!
 * Ext JS Library 3.4.0
 * Copyright(c) 2006-2011 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */
/**
 * @class Ext.ux.StartMenu
 * @extends Ext.menu.Menu
 * A start menu object.
 * @constructor
 * Creates a new StartMenu
 * @param {Object} config Configuration options
 *
 * SAMPLE USAGE:
 *
 * this.startMenu = new Ext.ux.StartMenu({
 *      iconCls: 'user',
 *      height: 300,
 *      shadow: true,
 *      title: get_cookie('memberName'),
 *      width: 300
 *  });
 *
 * this.startMenu.add({
 *      text: 'Grid Window',
 *      iconCls:'icon-grid',
 *      handler : this.createWindow,
 *      scope: this
 *  });
 *
 * this.startMenu.addTool({
 *      text:'Logout',
 *      iconCls:'logout',
 *      handler:function(){ window.location = "logout.php"; },
 *      scope:this
 *  });
 */

Ext.namespace("Ext.ux");

Ext.ux.StartMenu = Ext.extend(Ext.menu.Menu, {
    
    /**
     * @cfg {String} iconCls The menu icon class
     */
    /**
     * @cfg {String} icon The menu icon image, size should be 48x48
     */
    
    /**
     * Ext template method
     * @override
     * @private
     */
    initComponent : function(config) {
        Ext.ux.StartMenu.superclass.initComponent.call(this, config);

        var tools = this.toolItems;
        this.toolItems = new Ext.util.MixedCollection();
        if (tools) {
            this.addTool.apply(this, tools);
        }
    },

    // private
    onRender : function(ct, position){
        Ext.ux.StartMenu.superclass.onRender.call(this, ct, position);
        var el = this.el.addClass('ux-start-menu');

        var header = el.createChild({
            tag: "div",
            cls: "x-window-header x-unselectable x-panel-icon"
        });

        this.header = header;

        this.headerIcon = header.createChild({
            tag: "image",
            cls: 'x-window-header-icon',
            src: this.icon ? this.icon : Ext.BLANK_IMAGE_URL
        });
        
        var headerText = header.createChild({
            tag: "span",
            cls: "x-window-header-text"
        });
        var tl = header.wrap({
            cls: "ux-start-menu-tl"
        });
        var tr = header.wrap({
            cls: "ux-start-menu-tr"
        });
        var tc = header.wrap({
            cls: "ux-start-menu-tc"
        });

        this.menuBWrap = el.createChild({
            tag: "div",
            cls: "x-window-body x-border-layout-ct ux-start-menu-body"
        });
        var ml = this.menuBWrap.wrap({
            cls: "ux-start-menu-ml"
        });
        var mc = this.menuBWrap.wrap({
            cls: "x-window-mc ux-start-menu-bwrap"
        });

        this.menuPanel = this.menuBWrap.createChild({
            tag: "div",
            cls: "x-panel x-border-panel ux-start-menu-apps-panel"
        });
        this.toolsPanel = this.menuBWrap.createChild({
            tag: "div",
            cls: "x-panel x-border-panel ux-start-menu-tools-panel"
        });

        var bwrap = ml.wrap({cls: "x-window-bwrap"});
        var bc = bwrap.createChild({
            tag: "div",
            cls: "ux-start-menu-bc"
        });
        this.bl = bc.wrap({
            cls: "ux-start-menu-bl x-panel-nofooter"
        });
        var br = bc.wrap({
            cls: "ux-start-menu-br"
        });

        this.ul.appendTo(this.menuPanel);

        var toolsUl = this.toolsPanel.createChild({
            tag: "ul",
            cls: "x-menu-list"
        });

        this.mon(toolsUl, 'click', this.onClick, this);
        this.mon(toolsUl, 'mouseover', this.onMouseOver, this);
        this.mon(toolsUl, 'mouseout', this.onMouseOut, this);

        this.items.each(function(item){
            item.parentMenu = this;
        }, this);

        this.toolItems.each(
            function(item){
                var li = document.createElement("li");
                li.className = "x-menu-list-item";
                toolsUl.dom.appendChild(li);
                item.render(li);
                item.parentMenu = this;
            }, this);

        this.toolsUl = toolsUl;

        this.menuBWrap.setStyle('position', 'relative');
        
        this.menuPanel.setStyle({
            padding: '2px',
            position: 'absolute',
            overflow: 'auto'
        });

        this.toolsPanel.setStyle({
            padding: '2px 4px 2px 2px',
            position: 'absolute',
            overflow: 'auto'
        });

        this.setTitle(this.title, this.iconCls);
        
        this.menuBWrap.setHeight(this.height - (this.header.getHeight() + this.bl.getHeight()));
    },

    // private
    findTargetItem : function(e){
        var t = e.getTarget(".x-menu-list-item", this.ul,  true);
        if(t && t.menuItemId){
            if(this.items.get(t.menuItemId)){
                return this.items.get(t.menuItemId);
            }else{
                return this.toolItems.get(t.menuItemId);
            }
        }
    },

    /**
     * Displays this menu relative to another element
     * @param {Mixed} element The element to align to
     * @param {String} position (optional) The {@link Ext.Element#alignTo} anchor position to use in aligning to
     * the element (defaults to this.defaultAlign)
     * @param {Ext.ux.StartMenu} parentMenu (optional) This menu's parent menu, if applicable (defaults to undefined)
     */
    show : function(el, pos, parentMenu){
        this.parentMenu = parentMenu;
        if(!this.el){
            this.render();
        }

        this.fireEvent("beforeshow", this);
        this.showAt(this.el.getAlignToXY(el, pos || this.defaultAlign), parentMenu, false);
        var tPanelWidth = 100;
        var box = this.menuBWrap.getBox();
        this.menuPanel.setWidth(box.width-tPanelWidth);
        this.menuPanel.setHeight(box.height);

        this.toolsPanel.setWidth(tPanelWidth);
        this.toolsPanel.setX(box.x+box.width-tPanelWidth);
        this.toolsPanel.setHeight(box.height);
    },

    addTool : function(){
        var a = arguments, l = a.length, item;
        for(var i = 0; i < l; i++){
            var el = a[i];
            if(el.render){ // some kind of Item
                item = this.addToolItem(el);
            }else if(typeof el == "string"){ // string
                if(el == "separator" || el == "-"){
                    item = this.addToolSeparator();
                }else{
                    item = this.addText(el);
                }
            }else if(el.tagName || el.el){ // element
                item = this.addElement(el);
            }else if(typeof el == "object"){ // must be menu item config?
                item = this.addToolMenuItem(el);
            }
        }
        return item;
    },

    /**
     * Adds a separator bar to the Tools
     * @return {Ext.menu.Item} The menu item that was added
     */
    addToolSeparator : function(){
        return this.addToolItem(new Ext.menu.Separator({itemCls: 'ux-toolmenu-sep'}));
    },

    addToolMenuItem : function(config){
        if(!(config instanceof Ext.menu.Item)){
            if(typeof config.checked == "boolean"){ // must be check menu item config?
                config = new Ext.menu.CheckItem(config);
            }else{
                config = new Ext.menu.Item(config);
            }
        }
        return this.addToolItem(config);
    },
    
    /**
     * Adds tools menu item.
     * 
     * @param {Ext.menu.Item} item The menu item being added
     * @return {Ext.menu.Item} tool item
     * @author Nikolai Babinski
     */
    addToolItem : function(item) {
        this.toolItems.add(item);
        
        if (this.toolsUl) {
            var li = this.toolsUl.createChild({tag: 'li', cls: 'x-menu-list-item'});
            item.render(li);
            item.parentMenu = this;
        }
        
        return item;
    },

    /**
     * Inserts tools menu item at specified position.
     * 
     * @param {Number} index The insertion position inside tools menu
     * @param {Ext.menu.Item} item The menu item being inserted
     * @return {Ext.menu.Item} tool item
     * @author Nikolai Babinski
     */
    insertToolItem : function(index, item) {
        this.toolItems.insert(index, item);
        
        if (this.toolsUl) {
            var beforeLi = this.toolsUl.down('li:nth-child(' + index + ')'),
                li = this.toolsUl.createChild({tag: 'li', cls: 'x-menu-list-item'}, beforeLi ? beforeLi : null);
            item.render(li);
            item.parentMenu = this;
        }
        
        return item;
    },

    /**
     * Adjusts menu height.
     * @protected
     * @author Nikolai Babinski
     */
    adjustMenuHeight : function() {
        this.menuBWrap.setHeight(this.height - (this.header.getHeight() + this.bl.getHeight()));
        var box = this.menuBWrap.getBox();
        this.menuPanel.setHeight(box.height);
        this.toolsPanel.setHeight(box.height);
    },
    
    /**
     * Sets menu's title {@link #title}.
     * @param {String} title The menu's title being set
     * @param {String} (optional) iconCls The icon class to be set
     * @return {Ext.ux.StartMenu} this menu
     */
    setTitle : function(title, iconCls) {
        title = title ? Ext.util.Format.trim(title) : '';
        
        this.header.child('span').update(title);
        //uncomment this if this.headerIcon is not used
        //if (this.title == '' || title == '') { this.adjustMenuHeight(); }
        this.title = title;
        
        if (Ext.isDefined(iconCls)) {
            this.setTitleIconCls(iconCls);
        }
        
        return this;
    },
    
    /**
     * Sets title's {@link #header} icon class {@link #iconCls}.
     * @param {String} iconCls The icon class being set
     * @return {Ext.ux.StartMenu} this menu
     * @author Nikolai Babinski
     */
    setTitleIconCls : function(iconCls) {
        this.header.replaceClass(this.iconCls, iconCls);
        this.iconCls = iconCls;
        
        return this;
    },
    
    /**
     * Sets title's {@link #header} icon image {@link #icon}.
     * @param {String} icon The title's icon image
     * @author Nikolai Babinski
     */
    setTitleIcon : function(icon) {
        this.icon = icon ? icon : Ext.BLANK_IMAGE_URL; 
        this.headerIcon.set({src: this.icon});
    }
});