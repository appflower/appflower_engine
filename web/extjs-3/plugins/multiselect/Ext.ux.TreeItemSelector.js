Ext.override(Ext.layout.TableLayout, {
	getNextCell : function(c){
		var cell = this.getNextNonSpan(this.currentColumn, this.currentRow);
		var curCol = this.currentColumn = cell[0], curRow = this.currentRow = cell[1];
		for(var rowIndex = curRow; rowIndex < curRow + (c.rowspan || 1); rowIndex++){
			if(!this.cells[rowIndex]){
				this.cells[rowIndex] = [];
			}
			for(var colIndex = curCol; colIndex < curCol + (c.colspan || 1); colIndex++){
				this.cells[rowIndex][colIndex] = true;
			}
		}
		var td = document.createElement('td');
		if(c.cellId){
			td.id = c.cellId;
		}
		var cls = 'x-table-layout-cell';
		if(c.cellCls){
			cls += ' ' + c.cellCls;
		}
		td.className = cls;
		if(c.cellStyle){
			Ext.DomHelper.applyStyles(td, c.cellStyle);
		}
		if(c.colspan){
			td.colSpan = c.colspan;
		}
		if(c.rowspan){
			td.rowSpan = c.rowspan;
		}
		this.getRow(curRow).appendChild(td);
		return td;
	}
});

Ext.ux.JsonTreeNode = function(config) {
  config = config || {};
  Ext.applyIf(config, {leaf: (config.children === null)});
  Ext.ux.JsonTreeNode.superclass.constructor.call(this, config);
  if (config.children) {
    this.createChildren(config.children);
  }
};
Ext.extend(Ext.ux.JsonTreeNode, Ext.tree.TreeNode, {
  createChildren: function(children) {
    this.on('click', function() {
      this.toggle();
    }, this);
    for (var i=0; i<children.length; i++) {
      this.appendChild(new Ext.ux.JsonTreeNode(children[i]));
    }
  },
  //Only called on root, destroy() does the rest
  destroyChildren: function() {
    while(this.firstChild) {
      var node = this.firstChild;
      this.removeChild(node);
      if (node.destroy) {
        node.destroy();
      }
    }
  },
  appendChild : function(n){
  	//Ext.util.Observable.capture(n, function(e){console.info(e)}); 

  	if(!n.render && !Ext.isArray(n)){
        n = this.getLoader().createNode(n);
    }
    var node = Ext.ux.JsonTreeNode.superclass.appendChild.call(this, n);
    if(node && this.childrenRendered){
        node.render();
    }
    this.ui.updateExpandIcon();
    return node;
   },
   renderChildren : function(suppressEvent){
        if(suppressEvent !== false){
            this.fireEvent("beforechildrenrendered", this);
        }
        var cs = this.childNodes;
        //console.log(cs);
        for(var i = 0, len = cs.length; i < len; i++){
            cs[i].render(true);
        }
        this.childrenRendered = true;
    }
});

/**
* @author radu
*/
 
/** 
 * @class Ext.ux.TreeItemSelector
 * @extends Ext.form.Field
 */
Ext.ux.TreeItemSelector = Ext.extend(Ext.form.Field,  {
	fromRootText:'Options',
	toRootText:'Selected',
    width:400,
    height:'auto',
    hideNavIcons:false,
    imagePath:"",
    iconLeft:"left2.gif",
    iconRight:"right2.gif",
    drawLeftIcon:true,
    drawRightIcon:true,
    switchToFrom:false,
    readOnly:false,
    bodyStyle:null,
    border:true,
    defaultAutoCreate:{tag: "div"},
    fromChildren:[/*{"text":"Group 1","value":"G1","leaf":false,"iconCls":"folder","children":[{"text":"Item1","value":"item 1","leaf":true,"iconCls":"file"},{"text":"Item2","value":"item 2","leaf":true,"iconCls":"file"}]},{"text":"Group 2","value":"G2","leaf":false,"iconCls":"folder","children":[{"text":"Item3","value":"item 3","leaf":true,"iconCls":"file"},{"text":"Item4","value":"item 4","leaf":true,"iconCls":"file"}]}*/],
    toChildren:[/*{"text":"Group 1","value":"G1","leaf":false,"iconCls":"folder","children":[{"text":"Item10","value":"item 10","leaf":true,"iconCls":"file"}]}*/],
    
    initComponent: function(){
        Ext.ux.TreeItemSelector.superclass.initComponent.call(this);
        this.addEvents({
            'change' : true
        });         
    },

    onRender: function(ct, position){
        Ext.ux.TreeItemSelector.superclass.onRender.call(this, ct, position);

        this.fromTreeConfig = this.fromTreeConfig || {};
        Ext.applyIf(this.fromTreeConfig, {
			animate: true,
			autoScroll: true,
			enableDD:true,
			containerScroll:true,
			cellStyle:'vertical-align:top;'
		});
		
		this.toTreeConfig = this.toTreeConfig || {};
		 Ext.applyIf(this.toTreeConfig, {
		 	animate: true,
			autoScroll: true,
			enableDD:true,
			containerScroll:true,
			dropConfig: {appendOnly:true},
			cellStyle:'vertical-align:top;'
		});
        
        this.fromTree = new Ext.tree.TreePanel(this.fromTreeConfig);     
        this.fromTree.setRootNode(new Ext.ux.JsonTreeNode({text: this.fromRootText, 
											         draggable:false, // disable root node dragging
											         id:'source',
											         expanded: true,
      												 leaf: false,
      												 children:this.fromChildren     												 										        }));
           
      	this.toTree = new Ext.tree.TreePanel(this.toTreeConfig);
		this.toTree.setRootNode(new Ext.ux.JsonTreeNode({text: this.toRootText, 
											         draggable:false, // disable root node dragging
											         id:'target',
											         expanded: true,
      												 leaf: false,
      												 children:this.toChildren 
											        }));
											        
		// install event handlers
		this.toTree.on({
			 beforenodedrop:{scope:this, fn:this.onBeforeNodeDrop},
			 click:{scope:this, fn:this.onClick}
		});
		
		this.fromTree.on({
			 beforenodedrop:{scope:this, fn:this.onBeforeNodeDrop},
			 click:{scope:this, fn:this.onClick}
		});
											        
		new Ext.tree.TreeSorter(this.fromTree, {folderSort:true});
    	new Ext.tree.TreeSorter(this.toTree, {folderSort:true});
		                       
        var p = new Ext.Panel({
            bodyStyle:this.bodyStyle,
            border:this.border,
            layout:"table",
            layoutConfig:{columns:3}
        });
        p.add(this.switchToFrom ? this.toTree : this.fromTree);
        var icons = new Ext.Panel({header:false,cellStyle:'vertical-align:middle;'});
        p.add(icons);
        p.add(this.switchToFrom ? this.fromTree : this.toTree);
        p.render(this.el);
        icons.el.down('.'+icons.bwrapCls).remove();

        if (this.imagePath!="" && this.imagePath.charAt(this.imagePath.length-1)!="/")
            this.imagePath+="/";
        
        this.iconLeft = this.imagePath + (this.iconLeft || 'left2.gif');
        this.iconRight = this.imagePath + (this.iconRight || 'right2.gif');
       
        var el=icons.getEl();
        
        this.addIcon = el.createChild({tag:'img', src:this.switchToFrom?this.iconLeft:this.iconRight, style:{cursor:'pointer', margin:'2px'}});
        el.createChild({tag: 'br'});
        this.removeIcon = el.createChild({tag:'img', src:this.switchToFrom?this.iconRight:this.iconLeft, style:{cursor:'pointer', margin:'2px'}});
        el.createChild({tag: 'br'});
        
        if (!this.readOnly) {
            
            this.addIcon.on('click', this.fromTo, this);
            this.removeIcon.on('click', this.toFrom, this);
        }
        if (!this.drawLeftIcon || this.hideNavIcons) { this.addIcon.dom.style.display='none'; }
        if (!this.drawRightIcon || this.hideNavIcons) { this.removeIcon.dom.style.display='none'; }
        
        var tb = p.body.first();
        this.el.setWidth(p.body.getWidth());
        p.body.removeClass();
        
        this.hiddenName = this.name;
        var hiddenTag={tag: "input", type: "hidden", value: "", name:this.name};
        this.hiddenField = this.el.createChild(hiddenTag);        
    },
    
    initValue:Ext.emptyFn,
        
    fromTo : function() {
    	if(this.selectedNode&&this.selectedNode!=null)
    	{
	       	var dropEvent = {
	            target: this.toTree.root,
	            dropNode: this.selectedNode
	        };
	        
	        this.selectedNode=null;
	        
	        this.onBeforeNodeDrop(dropEvent);
    	}
    },
    
    toFrom : function() {
    	if(this.selectedNode&&this.selectedNode!=null)
    	{
	       	var dropEvent = {
	            target: this.fromTree.root,
	            dropNode: this.selectedNode
	        };
	        
	        this.selectedNode=null;
	        
	        this.onBeforeNodeDrop(dropEvent);
    	}
    },
    
    /**
	 * runs before node is dropped
	 * @private
	 * @param {Object} e dropEvent object
	 */
	onBeforeNodeDrop:function(e) {
		
		if(this.disabled){
			return false;
		}
		
		// source node, node being dragged
		var s = e.dropNode;
		
		// destination node (dropping on this node)
		var d = e.target.leaf ? e.target.parentNode : e.target;

		// node has been dropped within the same parent
		if(s.parentNode === d) {
			return false;
		}
		
		//if destination is root
		if (d.isRoot)
		{
			//if dragged node is a folder
			if(s.parentNode.isRoot)
			{
				//if folder exist in destination copy childs
				if(this.hasChild(d,s.attributes.value))
				{
					s.expand();
					
					var dtemp=this.getChild(d,s.attributes.value);
					
					for(var i = 0, len = s.childNodes.length; i < len; i++) {
					    if(!this.hasChild(dtemp,s.childNodes[i].attributes.value))
						dtemp.appendChild(this.createNode(s.childNodes[i]));
					}
					
					d.expand();
					dtemp.expand();
					
					//remove the folder from source
					s.parentNode.removeChild(s);
					//if(s.parentNode)
					//s.parentNode.destroyChildren();
				}
				else
				{
					var dtemp=d.appendChild(this.createNode(s));
					
					for(var i = 0, len = s.childNodes.length; i < len; i++) {
					    dtemp.appendChild(this.createNode(s.childNodes[i]));
					}
					
					d.expand();
					dtemp.expand();
					
					//remove the folder from source
					s.parentNode.removeChild(s);
				}
			}
			//if dragged node is a leaf
			else
			{
				//if parent folder exist in destination copy leaf
				if(this.hasChild(d,s.parentNode.attributes.value))
				{
					var dtemp=this.getChild(d,s.parentNode.attributes.value);
					d.expand();
										
					dtemp.appendChild(this.createNode(s));
					dtemp.expand();
										
					//remove the leaf from source
					s.parentNode.removeChild(s);
				}
				//create parent folder cause it does not exist
				else
				{
					var dtemp=d.appendChild(this.createNode(s.parentNode));
					
					dtemp.appendChild(this.createNode(s));
					dtemp.expand();
					
					//remove the leaf from source
					s.parentNode.removeChild(s);
				}
			}
		}
		//if destination is a folder
		else if(d.parentNode.isRoot)
		{
			//if dragged node is a folder && destination has same value as dragged node
			if(s.parentNode.isRoot&&s.attributes.value==d.attributes.value)
			{
				for(var i = 0, len = s.childNodes.length; i < len; i++) {
				    if(!this.hasChild(d,s.childNodes[i].attributes.value))
					d.appendChild(this.createNode(s.childNodes[i]));
				}
				
				//remove the folder from source
				s.parentNode.removeChild(s);
			}
			//dragged is a leaf
			else if(!s.parentNode.isRoot/*&&s.parentNode.attributes.value==d.attributes.value*/)
			{
				d.appendChild(this.createNode(s));
				d.expand();
									
				//remove the leaf from source
				s.parentNode.removeChild(s);
			}
		}
		
		this.valueChanged();
		
		e.target.ui.endDrop();
		
		return false;		
	},
    
	onClick: function(n){
		this.selectedNode=n;
	},
	
	createNode: function(oldNode)
	{
		return new Ext.ux.JsonTreeNode({text: oldNode.text, value: oldNode.attributes.value, leaf: oldNode.isLeaf(), iconCls: oldNode.attributes.iconCls })
	},
	
	hasChild: function(node, childValue) {
		return (node.isLeaf() ? node.parentNode : node).findChild('value', childValue) !== null;
	},
	
	getChild: function(node, childValue) {
		return (node.isLeaf() ? node.parentNode : node).findChild('value', childValue);
	},
	
    valueChanged: function() {
        var json=new Ext.tree.JsonTreeSerializer(this.toTree);
        this.hiddenField.dom.value = json.toString();
        this.fireEvent('change', this, this.getValue(), this.hiddenField.dom.value);
    },
    
    getValue : function() {
        return this.hiddenField.dom.value;
    }
});

Ext.reg("treeitemselector", Ext.ux.TreeItemSelector);