Ext.override(Ext.Container, {
	render : function(){
		Ext.Container.superclass.render.apply(this, arguments);
		if(this.layout){
			if(typeof this.layout == 'string'){
				this.layout = new Ext.Container.LAYOUTS[this.layout.toLowerCase()](this.layoutConfig);
			}
			this.setLayout(this.layout);
			if(this.activeItem !== undefined){
				var item = this.activeItem;
				delete this.activeItem;
				this.layout.setActiveItem(item);
				//return;
			}
		}
		if(!this.ownerCt){
			this.doLayout();
		}
		if(this.monitorResize === true){
			Ext.EventManager.onWindowResize(this.doLayout, this, [false]);
		}
	}
});
Ext.override(Ext.layout.Accordion, {
	setActiveItem: function(c) {
		c = this.container.getComponent(c);
		if(this.activeItem != c){
			if(c.rendered && c.collapsed){
				c.expand();
			}else{
				this.activeItem = c;
			}
		}
	},
	renderItem : function(c){
		if(this.animate === false){
			c.animCollapse = false;
		}
		c.collapsible = true;
		if(this.autoWidth){
			c.autoWidth = true;
		}
		if(this.titleCollapse){
			c.titleCollapse = true;
		}
		if(this.hideCollapseTool){
			c.hideCollapseTool = true;
		}
		if(this.collapseFirst !== undefined){
			c.collapseFirst = this.collapseFirst;
		}
		if(!this.activeItem && !c.collapsed){
			this.activeItem = c;
		}else if(this.activeItem){
			c.collapsed = this.activeItem != c;
		}
		Ext.layout.Accordion.superclass.renderItem.apply(this, arguments);
		c.header.addClass('x-accordion-hd');
		c.on('beforeexpand', this.beforeExpand, this);
	}
});