/**
 * @class Ext.tree.TreeSerializer
 * A base class for implementations which provide serialization of an
 * {@link Ext.tree.TreePanel}.
 * <p>
 * Implementations must provide a toString method which returns the serialized
 * representation of the tree.
 * 
 * @constructor
 * @param {TreePanel} tree
 * @param {Object} config
 */
Ext.tree.TreeSerializer = function(tree, config){
    if (typeof this.toString !== 'function') {
        throw 'Ext.tree.TreeSerializer implementation does not implement toString()';
    }
    this.tree = tree;
    if (this.attributeFilter) {
        this.attributeFilter = this.attributeFilter.createInterceptor(this.defaultAttributeFilter);
    } else {
        this.attributeFilter = this.defaultAttributeFilter;
    }
    if (this.nodeFilter) {
        this.nodeFilter = this.nodeFilter.createInterceptor(this.defaultNodeFilter);
    } else {
        this.nodeFilter = this.defaultNodeFilter;
    }
    Ext.apply(this, config);
};

Ext.tree.TreeSerializer.prototype = {
    
	includeRootNode:false,
	/*
     * @cfg nodeFilter {Function} (optional) A function, which when passed the node, returns true or false to include
     * or exclude the node.
     */
    /*
     * @cfg attributeFilter {Function} (optional) A function, which when passed an attribute name, and an attribute value,
     * returns true or false to include or exclude the attribute.
     */
    /*
     * @cfg attributeMap {Array} (Optional) An associative array mapping Node attribute names to XML attribute names.
     */

    /* @private
     * Array of node attributes to ignore.
     */
    standardAttributes: ["expanded", "allowDrag", "allowDrop", "disabled", "icon", "cls", "iconCls", "href", "hrefTarget", "qtip", "singleClickExpand", "uiProvider", "id", "draggable"],
    
    /** @private
     * Default attribute filter.
     * Rejects functions and standard attributes.
     */
    defaultAttributeFilter: function(attName, attValue) {
        return    (typeof attValue != 'function') && (this.standardAttributes.indexOf(attName) == -1);
    },

    /** @private
     * Default node filter.
     * Accepts all nodes.
     */
    defaultNodeFilter: function(node) {
        return true;
    }
};

/**
 * @class Ext.tree.XmlTreeSerializer
 * An implementation of Ext.tree.TreeSerializer which serializes an
 * {@link Ext.tree.TreePanel} to an XML string.
 */
Ext.tree.XmlTreeSerializer = function(tree, config){
    Ext.tree.XmlTreeSerializer.superclass.constructor.apply(this, arguments);
};

Ext.extend(Ext.tree.XmlTreeSerializer, Ext.tree.TreeSerializer, {
    /**
     * Returns a string of XML that represents the tree
     * @return {String}
     */
    toString: function(nodeFilter, attributeFilter){
        return '\u003C?xml version="1.0"?>\u003Ctree>' + nodeToString(this.tree.getRootNode()) + '\u003C/tree>';
    },

    /**
     * Returns a string of XML that represents the node
     * @param {Object} node The node to serialize
     * @return {String}
     */
    nodeToString: function(node){
        if (!this.nodeFilter(node)) {
            return '';
        }
        var result = '\u003Cnode';
        if (this.attributeFilter("id", node.id)) {
            result += ' id="' + node.id + '"';
        }

        // Add all user-added attributes unless rejected by the attributeFilter.
        for(var key in node.attributes) {
            if (this.attributeFilter(key, node.attributes[key])) {
                result += ' ' + (this.attributeMap ? (this.attributeMap[key] || key) : key) + '="' + node.attributes[key] + '"';
            }
        }

        // Add child nodes if any
        var children = node.childNodes;
        var clen = children.length;
        if(clen == 0){
            result += '/>';
        }else{
            result += '>';
            for(var i = 0; i < clen; i++){
                result += this.nodeToString(children[i]);
            }
            result += '\u003C/node>';
        }
        return result;
    }

});

/**
 * @class Ext.tree.JsonTreeSerializer
 * An implementation of Ext.tree.TreeSerializer which serializes an
 * {@link Ext.tree.TreePanel} to a Json string.
 */
Ext.tree.JsonTreeSerializer = function(tree, config){
    Ext.tree.JsonTreeSerializer.superclass.constructor.apply(this, arguments);
};

Ext.extend(Ext.tree.JsonTreeSerializer, Ext.tree.TreeSerializer, {
    /**
     * Returns a string of Json that represents the tree
     * @return {String}
     */
    toString: function(){
          return this.nodeToString(this.tree.getRootNode());
    },

    /**
     * Returns a string of Json that represents the node
     * @param {Object} node The node to serialize
     */
    nodeToString: function(node){
        // Exclude nodes based on caller-supplied filtering function
        if (!this.nodeFilter(node)) {
            return '';
        }
        
        var root=this.tree.getRootNode();
        
        if(node==root&&this.includeRootNode==false)
        {
        	var result = "[";
        	
        	var children = node.childNodes;
	        var clen = children.length;
	        if(clen != 0){
	           
	        	for(var i = 0; i < clen; i++){
	              result += ((i>0)?",":'')+this.nodeToString(children[i]);
	            }
	        }
        	
        	return result + "]";
        }
        else
        {        
	        var c = false, result = "{";
	        if (this.attributeFilter("id", node.id)) {
	            result += '"id":"' + node.id + '"';
	            c = true;
	        }
	
	        // Add all user-added attributes unless rejected by the attributeFilter.
	        for(var key in node.attributes) {
	            if (this.attributeFilter(key, node.attributes[key])) {
	                if (c) result += ',';
	                result += '"' + (this.attributeMap ? (this.attributeMap[key] || key) : key) + '":"' + node.attributes[key] + '"';
	                c = true;
	            }
	        }
	    
	        // Add child nodes if any
	        var children = node.childNodes;
	        var clen = children.length;
	        if(clen != 0){
	            if (c) result += ',';
	            result += '"children":[';
	            for(var i = 0; i < clen; i++){
	                if (i > 0) result += ',';
	                result += this.nodeToString(children[i]);
	            }
	            result += ']';
	        }
	        return result + "}";
        }
    }
}); 
