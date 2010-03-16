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
  }
});

Ext.ux.DDBulkMultiselect = function(config) {
  config.treeConfig = config.treeConfig || {};
  Ext.applyIf(config.treeConfig, {
    animate: true,
    autoScroll: true,
    enableDD:true,
    containerScroll:true,
    loader: new Ext.tree.TreeLoader({dataUrl:'https://192.168.198.129/interface/getNodes'}),
  });

  var multiselect = this;
  Ext.applyIf(config, {
    //required: store, dataIndex, name
    displayField: 'text',
    valueField: 'id',
    srcText:'source',
    dstText:'target',
    layout: 'column',
    border: false,
    defaults: {
      style: "margin:10px",
      columnWidth: 0.5,
      height: config.height
    },
    items: [
      this.srcTree = new Ext.tree.TreePanel(config.treeConfig),
      this.dstTree = new Ext.tree.TreePanel(config.treeConfig),
      this.hiddenField = new Ext.form.Hidden({
        name: config.name,
        dataIndex: config.dataIndex,
        setValue: function(v) {
          if(typeof v !== 'object') {
            v = [];
          }
          this.value = v;
          this.setDomValue();

          multiselect.rebuildTrees();
        },
        getValue: function() {
          return this.value;
        },
        removeItem: function(v) {
          this.value.remove(v);

          this.setDomValue();
        },
        addItem: function(v) {
          this.value.push(v);

          this.setDomValue();
        },
        setDomValue: function() {
          if(this.rendered){
            var v = this.value.join(',');
            this.el.dom.value = (v === null || v === undefined ? '' : v);
            this.validate();
          }
        }
      })
    ]
  });
  delete config.height;

  Ext.ux.DDBulkMultiselect.superclass.constructor.call(this, config);
};
Ext.extend(Ext.ux.DDBulkMultiselect, Ext.Panel, {
  initComponent: function() {
    Ext.ux.DDBulkMultiselect.superclass.initComponent.call(this);

    this.setupTrees();

    //this.store.load();
    console.log();
    //this.dstTree.render();
    
    this.dstTree.on('remove', this.onRemove, this);
    this.dstTree.on('append', this.onAddItem, this);
    this.dstTree.on('insert', this.onAddItem, this);
    //this.store.on('datachanged', this.rebuildTrees, this);
   
    //this.rebuildTrees();
  },
  onRemove: function(tree, parent, node, index) {
    this.hiddenField.removeItem(node.attributes.value || node.id);
  },
  onAddItem: function(tree, parent, node) {
    this.hiddenField.addItem(node.attributes.value || node.id);
  },
  setupTrees: function() {
    var srcSorter = new Ext.tree.TreeSorter(this.srcTree);
    var dstSorter = new Ext.tree.TreeSorter(this.dstTree);

    this.srcTree.setRootNode(new Ext.ux.JsonTreeNode({
      text: this.srcText,
      expanded: true,
      leaf: false
    }));
    this.dstTree.setRootNode(new Ext.ux.JsonTreeNode({
      text: this.dstText,
      expanded: true,
      leaf: false
    }));
  },
  rebuildTrees: function() {
    var nodeValueArray = this.hiddenField.value || [];
    if(this.hiddenField) {
      this.hiddenField.value = [];
    }

    var srcRoot = this.srcTree.getRootNode();
    srcRoot.destroyChildren();

    var dstRoot = this.dstTree.getRootNode();
    dstRoot.destroyChildren();

    // TODO use this to create a proper tree in the source
    var missingParentFilter = function(r) {
      return (!r.data.parent_id || r.data.parent_id === "");
    };

    // I'm not sure what to query, if this were used without
    // our persistent filters
    var records = this.store.data.filterBy(function(r) { return true; });

    records.each(function(r) {
      var pos = nodeValueArray.indexOf(r.data[this.valueField]);
      if(pos == -1) {
        this.appendNode(srcRoot, r);
      } else {
        this.appendNode(dstRoot, r);
      }
    }, this);

    if(this.rendered) {
      dstRoot.expand();
      srcRoot.expand();
    }
  },
  appendNode: function(node, r) {
    node.appendChild(new Ext.tree.TreeNode({
      text: r.data[this.displayField],
      value: r.data[this.valueField],
      record: r,
      leaf: true
    }));
  }
});

Ext.reg("treeitemselector", Ext.ux.DDBulkMultiselect);