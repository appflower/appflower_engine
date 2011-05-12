/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

var TreeTest = function(){
    // shorthand
    var Tree = Ext.tree;
    
    return {
        init : function(){
            // yui-ext tree
            var tree = new Tree.TreePanel({
                el:'tree',
                animate:true, 
                autoScroll:true,
                loader: new Tree.TreeLoader({dataUrl:'https://192.168.198.129/interface/getNodes'}),
                enableDD:true,
                //ddGroup:'t2t',
                containerScroll: true,
                dropConfig: {appendOnly:true}
            });
            
            // add a tree sorter in folder mode
            new Tree.TreeSorter(tree, {folderSort:true});
            
            // set the root node
            var root = new Tree.AsyncTreeNode({
                text: 'Ext JS', 
                draggable:false, // disable root node dragging
                id:'source'
            });
            tree.setRootNode(root);
            
            // render the tree
            tree.render();
            
            root.expand(false, /*no anim*/ false);
            
            //-------------------------------------------------------------
            
            //var
            
            // YUI tree            
            var tree2 = new Tree.TreePanel({
                el:'tree2',
                animate:true,
                autoScroll:true,
                //rootVisible: false,
                loader: new Ext.tree.TreeLoader({
                    dataUrl:'https://192.168.198.129/interface/getNodes',
                    baseParams: {lib:'yui'} // custom http params
                }),
                containerScroll: true,
                enableDD:true,
                //ddGroup:'t2t',
                dropConfig: {appendOnly:true}
                ,listeners:{
                	'beforenodedrop':function(de)
                	{
                		var n = de.dropNode, p = de.point, t = de.target, s = de.source;
			         	if(n.isLeaf())
			            {
			            	this.createParentDirNodes(n,p,t);
			            	//console.log(n.parentNode);
			            }
			            
			            /*if(p == "above"){
			                t.parentNode.insertBefore(n, t);
			            }else if(p == "below"){
			                t.parentNode.insertBefore(n, t.nextSibling);
			            }else{
			                t.appendChild(n);
			            }
			            
				        n.ui.focus();*/
				        /*if(Ext.enableFx && this.tree.hlDrop){
				            n.ui.highlight();
				        }*/
				        t.ui.endDrop();
				        //this.tree.fireEvent("nodedrop", de);
				        
				        return true;
				   }
                },
                createParentDirNodes:function(n,p,t)
                {
                	var parentNode=null;
                	
                	parentNode=n.parentNode;
                	
                	if(parentNode!=null)
                	{              		
                		if(p == "above"){
			                t.parentNode.insertBefore(new Ext.tree.AsyncTreeNode({text:parentNode.text, iconCls:'folder'}), t);
			            }else if(p == "below"){
			                t.parentNode.insertBefore(new Ext.tree.AsyncTreeNode({text:parentNode.text, iconCls:'folder'}), t.nextSibling);
			            }else{
			                newNode = t.appendChild(new Ext.tree.AsyncTreeNode({text:parentNode.text, iconCls:'folder'}));
			            }
			            
			            console.log(parentNode.parentNode);
			            console.log(parentNode.parentNode.parentNode);
			            //this.createParentDirNodes(parentNode,p,t);
                	}
                }
            });
            
            // add a tree sorter in folder mode
            new Tree.TreeSorter(tree2, {folderSort:true});
            
            // add the root node
            var root2 = new Tree.AsyncTreeNode({
                text: 'My Files', 
                draggable:false, 
                id:'yui'
            });
            tree2.setRootNode(root2);
            tree2.render();
            
            root2.expand(false, /*no anim*/ false);
        }
    };
}();

Ext.EventManager.onDocumentReady(TreeTest.init, TreeTest, true);