// vim: ts=4:sw=4:nu:fdc=4:nospell
/**
 * Ext.ux.FileTreePanel
 *
 * @author  Ing. Jozef Sakáloš
 * @version $Id: Ext.ux.FileTreePanel.js 266 2008-05-18 23:24:47Z jozo $
 * @date    13. March 2008
 *
 * @license Ext.ux.FileTreePanel is licensed under the terms of
 * the Open Source LGPL 3.0 license.  Commercial use is permitted to the extent
 * that the code/component(s) do NOT become part of another Open Source or Commercially
 * licensed development library or toolkit without explicit permission.
 * 
 * License details: http://www.gnu.org/licenses/lgpl.html
 */

/*global Ext, window, document, setTimeout */

/**
 * @class Ext.ux.FileTreePanel
 * @extends Ext.tree.TreePanel
 */

Ext.ux.FileTreePanel = Ext.extend(Ext.tree.TreePanel, {
	
	tabPanelId:'center_panel'

	// config variables overridable from outside
	// {{{
	/**
	 * @cfg {Object} baseParams This object is not used directly by FileTreePanel but it is
	 * propagated to lower level objects instead. Included here for convenience.
	 */

	/**
	 * @cfg {String} confirmText Text to display as title of confirmation message box
	 */
	 ,confirmText:'Confirm'

	/**
	 * @cfg {Boolean} containerScroll true to register 
	 * this container with ScrollManager (defaults to true)
	 */
	,containerScroll:true

	/**
	 * @cfg {String} deleteText Delete text (for message box title or other displayed texts)
	 */
	,deleteText:'Delete'

	/**
	 * @cfg {String} deleteUrl URL to use when deleting; this.url is used if not set (defaults to undefined)
	 */

	/**
	 * @cfg {String} downloadUrl URL to use when downloading; this.url is used if not set (defaults to undefined)
	 */

	/**
	 * @cfg {Boolean} enableDD true to enable drag & drop of files and folders (defaults to true)
	 */
	,enableDD:true

	/**
	 * @cfg {Boolean) enableDelete true to enable to delete files and directories. 
	 * If false context menu item is not shown (defaults to true)
	 */
	,enableDelete:true

	/**
	 * @cfg {Boolean) enableNewDir true to enable to create new directory. 
	 * If false context menu item is not shown (defaults to true)
	 */
	,enableNewDir:true

	/**
	 * @cfg {Boolean) enableOpen true to enable open submenu
	 * If false context menu item is not shown (defaults to true)
	 */
	,enableOpen:true

	/**
	 * @cfg {Boolean} enableProgress true to enable querying server for progress information
	 * Passed to underlying uploader. Included here for convenience.
	 */
	,enableProgress:true

	/**
	 * @cfg {Boolean) enableRename true to enable to rename files and directories. 
	 * If false context menu item is not shown (defaults to true)
	 */
	,enableRename:true

	/**
	 * @cfg {Boolean} enableSort true to enable sorting of tree. See also folderSort (defaults to true)
	 */
	,enableSort:true

	/**
	 * @cfg {Boolean) enableUpload true to enable to upload files. 
	 * If false context menu item is not shown (defaults to true)
	 */
	,enableUpload:true

	/**
	 * @cfg {String} errorText Text to display for an error
	 */
	,errorText:'Error'

	/**
	 * @cfg {String} existsText Text to display in message box if file exists
	 */
	,existsText:'File <b>{0}</b> already exists'

	/**
	 * @cfg {Boolean} true to expand root node on FileTreePanel render (defaults to true)
	 */
	,expandOnRender:true

	/**
	 * @cfg {String} fileCls class prefix to add to nodes. "-extension" is appended to
	 * this prefix to form filetype class, for example: file-odt, file-pdf. These classes
	 * are used to display correct filetype icons in the tree. css file and icons must
	 * exist of course.
	 */
	,fileCls:'file'

	/**
	 * @cfg {String} fileText
	 */
	,fileText:'File'

	/**
	 * @cfg {Boolean} focusPopup true to focus new browser popup window for 'popup' openMode
	 * (defaults to true)
	 */
	,focusPopup:true

	/**
	 * @cfg {Boolean} folderSort true to place directories at the top of the tree (defaults to true)
	 */
	,folderSort:true

	/**
	 * @cfg {String} hrefPrefix Text to prepend before file href for file open command. 
	 * (defaults to '')
	 */
	,hrefPrefix:''

	/**
	 * @cfg {String} hrefSuffix Text to append to file href for file open command. 
	 * (defaults to '')
	 */
	,hrefSuffix:''

	/**
	 * @cfg {String} layout Layout to use for this panel (defaults to 'fit')
	 */
	,layout:'fit'

	/**
	 * @cfg {String} loadingText Text to use for load mask msg
	 */
	,loadingText:'Loading'

	/**
	 * @cfg {Boolean} loadMask True to mask tree panel while loading
	 */
	,loadMask:false

	/**
	 * @cfg {Number} maxFileSize Maximum upload file size in bytes
	 * This config property is propagated down to uploader for convenience
	 */
	,maxFileSize:524288

	/**
	 * @cfg {Number} maxMsgLen Maximum message length for message box (defaults to 2000).
	 * If message is longer Ext.util.Format.ellipsis is used to truncate it and append ...
	 */
	,maxMsgLen:2000

	/**
	 * @cfg {String} method Method to use when posting to server. Other valid value is 'get'
	 * (defaults to 'post')
	 */
	,method:'post'

	/**
	 * @cfg {String} newdirText Default name for new directories (defaults to 'New Folder')
	 */
	,newdirText:'New Folder'
	
	/**
	 * @cfg {String} newdirText Default name for new files (defaults to 'file.php')
	 */
	,newfileText:'file.php'

	/**
	 * @cfg {String} newdirUrl URL to use when creating new directory; 
	 * this.url is used if not set (defaults to undefined)
	 */

	/**
	 * @cfg {String} openMode Default file open mode. This mode is used when user dblclicks 
	 * a file. Other valid values are '_self', '_blank' and 'download' (defaults to 'popup')
	 */
	,openMode:'_self'

	/**
	 * @cfg {String} overwriteText Text to use in overwrite confirmation message box
	 */
	,overwriteText:'Do you want to overwrite it?'

	/**
	 * @cfg {String} popupFeatures Features for new browser window opened by popup open mode
	 */
	,popupFeatures:'width=800,height=600,dependent=1,scrollbars=1,resizable=1,toolbar=1'

	/**
	 * @cfg {Boolean} readOnly true to disable write operations. treeEditor and context menu
	 * are not created if true (defaults to false)
	 */
	,readOnly:false

	/**
	 * @cfg {String} reallyWantText Text to display for that question
	 */
	,reallyWantText:'Do you really want to'

	/**
	 * @cfg {String} renameUrl URL to use when renaming; this.url is used if not set (defaults to undefined)
	 */

	/**
	 * @cfg {String} rootPath Relative path pointing to the directory that is root of this tree (defaults to 'root')
	 */
	,rootPath:'root'

	/**
	 * @cfg {String} rootText Text to display for root node (defaults to 'Tree Root')
	 */
	,rootText:'Tree Root'

	/**
	 * @cfg {Boolean} rootVisible true = root node visible, false = hidden (defaults to true)
	 */
	,rootVisible:true

	/**
	 * @cfg {Boolean} selectOnEdit true to select the edited text on edit start (defaults to true)
	 */
	,selectOnEdit:true

	/**
	 * @cfg {Boolean} singleUpload true to upload files in one form, false to upload one by one
	 * This config property is propagated down to uploader for convenience
	 */
	,singleUpload:false

	/**
	 * @cfg {Boolean} topMenu true to create top toolbar with menu in addition to contextmenu
	 */
	,topMenu:false

	/**
	 * @cfg {String} url URL to use when communicating with server
	 */
	,url:'filetree.php'
	// }}}

	// overrides
	// {{{
	/**
	 * called by Ext when instantiating
	 * @private
	 * @param {Object} config Configuration object
	 */
	,initComponent:function() {

		// {{{
		Ext.apply(this, {

			// create root node
			 root:new Ext.tree.AsyncTreeNode({
				 text:this.rootText
				,path:this.rootPath
				,rootVisible:this.rootVisible
				,allowDrag:false
			})

			// create treeEditor
			,treeEditor:!this.readOnly ? new Ext.tree.TreeEditor(this, {
				 allowBlank:false
				,cancelOnEsc:true
				,completeOnEnter:true
				,ignoreNoChange:true
				,selectOnFocus:this.selectOnEdit
			}) : undefined

			// drop config
			,dropConfig:this.dropConfig ? this.dropConfig : {
				 ddGroup:this.ddGroup || 'TreeDD'
				,appendOnly:this.enableSort
				,expandDelay:3600000 // do not expand on drag over node
			}

			// create treeSorter
			,treeSorter:this.enableSort ? new Ext.tree.TreeSorter(this, {folderSort:this.folderSort}) : undefined

			// {{{
			,keys:[{
				// Enter = open
				 key:Ext.EventObject.ENTER, scope:this
				,fn:function(key, e) {
					var sm = this.getSelectionModel();
					var node = sm.getSelectedNode();
					if(node && 0 !== node.getDepth() && node.isLeaf()) {
						this.openNode(node);
					}
			}},{
				// F2 = edit
				 key:113, scope:this
				,fn:function(key, e) {
					var sm = this.getSelectionModel();
					var node = sm.getSelectedNode();
					if(node && 0 !== node.getDepth() && this.enableRename && this.readOnly !== true) {
						this.treeEditor.triggerEdit(node);
					}
			}},{
				// Delete Key = Delete
				 key:46, stopEvent:true, scope:this
				,fn:function(key, e) {
					var sm = this.getSelectionModel();
					var node = sm.getSelectedNode();
					if(node && 0 !== node.getDepth() && this.enableDelete && this.readOnly !== true) {
						this.deleteNode(node);
					}
			}},{
				// Ctrl + E = reload
				 key:69, ctrl:true, stopEvent:true, scope:this
				,fn:function(key, e) {
					var sm = this.getSelectionModel();
					var node = sm.getSelectedNode();
					if(node) {
						node = node.isLeaf() ? node.parentNode : node;
						sm.select(node);
						node.reload();
					}
			}},{
				// Ctrl + -> = expand deep
				 key:39, ctrl:true, stopEvent:true, scope:this
				,fn:function(key, e) {
					var sm = this.getSelectionModel();
					var node = sm.getSelectedNode();
					if(node && !node.isLeaf()) {
						sm.select(node);
						node.expand.defer(1, node, [true]);
					}
				}},{
				// Ctrl + <- = collapse deep
				 key:37, ctrl:true, scope:this, stopEvent:true
				,fn:function(key, e) {
					var sm = this.getSelectionModel();
					var node = sm.getSelectedNode();
					if(node && !node.isLeaf()) {
						sm.select(node);
						node.collapse.defer(1, node, [true]);
					}
				}},{
				// Ctrl + N = New Directory
				 key:78, ctrl:true, scope:this, stopEvent:true
				,fn:function(key, e) {
					var sm, node;
					sm = this.getSelectionModel();
					node = sm.getSelectedNode();
					if(node && this.enableNewDir && this.readOnly !== true) {
						node = node.isLeaf() ? node.parentNode : node;
						this.createNewDir(node);
					}
			}}]
			// }}}

		}); // eo apply
		// }}}
		// {{{
		// create loader
		if(!this.loader) {
			this.loader = new Ext.tree.TreeLoader({
				 url:this.url
				,baseParams:{cmd:'get'}
				,listeners:{
					beforeload:{scope:this, fn:function(loader, node) {
						loader.baseParams.path = this.getPath(node);
					}}
				}
			});
		}
		// }}}
		// {{{
		// install top menu if configured
		if(true === this.topMenu) {
			this.tbar = [{
				 text:this.fileText
				,disabled:true
				,scope:this
				,menu:this.getContextMenu()
			}];
		}
		// }}}

		// call parent
		Ext.ux.FileTreePanel.superclass.initComponent.apply(this, arguments);

		// {{{
		// install treeEditor event handlers 
		if(this.treeEditor) {
			// do not enter edit mode on selected node click
			this.treeEditor.beforeNodeClick = function(node,e){return true;};

			// treeEditor event handlers
			this.treeEditor.on({
				 complete:{scope:this, fn:this.onEditComplete}
				,beforecomplete:{scope:this, fn:this.onBeforeEditComplete}
			});
		}
		// }}}
		// {{{
		// install event handlers
		this.on({
			 contextmenu:{scope:this, fn:this.onContextMenu, stopEvent:true}
			,dblclick:{scope:this, fn:this.onDblClick}
			,beforenodedrop:{scope:this, fn:this.onBeforeNodeDrop}
			,nodedrop:{scope:this, fn:this.onNodeDrop}
			,nodedragover:{scope:this, fn:this.onNodeDragOver}
			,newfile:{scope:this, fn:this.onNewFileSuccess}
			,'delete':{scope:this, fn:this.onDeleteSuccess}
		});

		// }}}
		// {{{
		// add events
		this.addEvents(
			/**
			 * @event beforeopen
			 * Fires before file open. Return false to cancel the event
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {String} fileName name of the file being opened
			 * @param {String} url url of the file being opened
			 * @param {String} mode open mode
			 */
			 'beforeopen'
			/**
			 * @event open
			 * Fires after file open has been initiated
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {String} fileName name of the file being opened
			 * @param {String} url url of the file being opened
			 * @param {String} mode open mode
			 */
			,'open'
			/**
			 * @event beforerename
			 * Fires after the user completes file name editing 
			 * but before the file is renamed. Return false to cancel the event
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {Ext.tree.AsyncTreeNode} node being renamed
			 * @param {String} newPath including file name 
			 * @param {String} oldPath including file name 
			 */
			,'beforerename'
			/**
			 * @event rename
			 * Fires after the file has been successfully renamed
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {Ext.tree.AsyncTreeNode} node that has been renamed
			 * @param {String} newPath including file name 
			 * @param {String} oldPath including file name 
			 */
			,'rename'
			/**
			 * @event renamefailure
			 * Fires after a failure when renaming file
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {Ext.tree.AsyncTreeNode} node rename of which failed
			 * @param {String} newPath including file name 
			 * @param {String} oldPath including file name 
			 */
			,'renamefailure'
			/**
			 * @event beforedelete
			 * Fires before a file or directory is deleted. Return false to cancel the event.
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {Ext.tree.AsyncTreeNode} node being deleted
			 */
			,'beforedelete'
			/**
			 * @event delete
			 * Fires after a file or directory has been deleted
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {String} path including file name that has been deleted
			 */
			,'delete'
			/**
			 * @event deletefailure
			 * Fires if node delete failed
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {Ext.tree.AsyncTreeNode} node delete of which failed
			 */
			,'deletefailure'
			/**
			 * @event beforenewdir
			 * Fires before new directory is created. Return false to cancel the event
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {Ext.tree.AsyncTreeNode} node under which the new directory is being created
			 */
			,'beforenewdir'
			/**
			 * @event newdir
			 * Fires after the new directory has been successfully created
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {Ext.tree.AsyncTreeNode} new node/directory that has been created
			 */
			,'newdir'
			/**
			 * @event newdirfailure
			 * Fires if creation of new directory failed
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {String} path creation of which failed
			 */
			,'newdirfailure'
			/**
			 * @event beforenewfile
			 * Fires before new file is created. Return false to cancel the event
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {Ext.tree.AsyncTreeNode} node under which the new file is being created
			 */
			,'beforenewfile'
			/**
			 * @event newfile
			 * Fires after the new file has been successfully created
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {Ext.tree.AsyncTreeNode} new node/file that has been created
			 */
			,'newfile'
			/**
			 * @event newfilefailure
			 * Fires if creation of new file failed
			 * @param {Ext.ux.FileTreePanel} this
			 * @param {String} path creation of which failed
			 */
			,'newfilefailure'
		); // eo addEvents
		// }}}

	} // eo function initComponent
	// }}}
	// {{{
	/**
	 * onRender override - just expands root node if configured
	 * @private
	 */
	,onRender:function() {
		// call parent
		Ext.ux.FileTreePanel.superclass.onRender.apply(this, arguments);

		if(true === this.topMenu) {
			this.topMenu = Ext.getCmp(this.getTopToolbar().items.itemAt(0).id);
			this.getSelectionModel().on({
				 scope:this
				,selectionchange:function(sm, node) {
					var disable = node ? false : true;
					disable = disable || this.readOnly;
					this.topMenu.setDisabled(disable);
				}
			});
			Ext.apply(this.topMenu, {
				 showMenu:function() {
					this.showContextMenu(false);
				}.createDelegate(this)
//				,menu:this.getContextMenu()
			});
		}

		// expand root node if so configured
		if(this.expandOnRender) {
			this.root.expand();
		}

		// prevent default browser context menu to appear 
		this.el.on({
			contextmenu:{fn:function(){return false;},stopEvent:true}
		});

		// setup loading mask if configured
		if(true === this.loadMask) {
			this.loader.on({
				 scope:this.el
				,beforeload:this.el.mask.createDelegate(this.el, [this.loadingText + '...'])
				,load:this.el.unmask
				,loadexception:this.el.unmask
			});
		}

	} // eo function onRender
	// }}}

	// new methods
	// {{{
	/**
	 * runs after an Ajax requested command has completed/failed
	 * @private
	 * @param {Object} options Options used for the request
	 * @param {Boolean} success true if ajax call was successful (cmd may have failed)
	 * @param {Object} response ajax call response object
	 */
	,cmdCallback:function(options, success, response) {
		var i, o, node;
		var showMsg = true;

		// process Ajax success
		if(true === success) {

			// try to decode JSON response
			try {
				o = Ext.decode(response.responseText);
			}
			catch(ex) {
				this.showError(response.responseText);
			}

			// process command success
			if(true === o.success) {
				switch(options.params.cmd) {
					case 'delete':
						if(true !== this.eventsSuspended) {
							this.fireEvent('delete', this, options.node);
						}
						options.node.parentNode.removeChild(options.node);
					break;

					case 'newdir':
						if(true !== this.eventsSuspended) {
							this.fireEvent('newdir', this, options.node);
						}
					break;
					
					case 'newfile':
						if(true !== this.eventsSuspended) {
							this.fireEvent('newfile', this, options.node);
						}
					break;

					case 'rename':
						this.updateCls(options.node, options.params.oldname);
						if(true !== this.eventsSuspended) {
							this.fireEvent('rename', this, options.node, options.params.newname, options.params.oldname);
						}
					break;
				}
			} // eo process command success
			// process command failure
			else {
				switch(options.params.cmd) {

					case 'rename':
						// handle drag & drop rename error
						if(options.oldParent) {
							options.oldParent.appendChild(options.node);
						}
						// handle simple rename error
						else {
							options.node.setText(options.oldName);
						}
						// signal failure to onNodeDrop
						if(options.e) {
							options.e.failure = true;
						}
						if(true !== this.eventsSuspended) {
							this.fireEvent('renamefailure', this, options.node, options.params.newname, options.params.oldname);
						}
					break;

					case 'newdir':
						if(false !== this.eventsSuspended) {
							this.fireEvent('newdirfailure', this, options.params.dir);
						}
						options.node.parentNode.removeChild(options.node);
					break;
					
					case 'newfile':
						if(false !== this.eventsSuspended) {
							this.fireEvent('newfilefailure', this, options.params.file);
						}
						options.node.parentNode.removeChild(options.node);
					break;

					case 'delete':
						if(true !== this.eventsSuspended) {
							this.fireEvent('deletefailure', this, options.node);
						}
						options.node.parentNode.reload.defer(1, options.node.parentNode);
					break;

					default:
						this.root.reload();
					break;
				}

				// show default message box with server error
				this.showError(o.error || response.responseText);
			} // eo process command failure
		} // eo process Ajax success

		// process Ajax failure
		else {
			this.showError(response.responseText);
		}
	} // eo function cmdCallback
	// }}}
	// {{{
	/**
	 * displays overwrite confirm msg box and runs passed callback if response is yes
	 * @private
	 * @param {String} filename File to overwrite
	 * @param {Function} callback Function to call on yes response
	 * @param {Object} scope Scope for callback (defaults to this)
	 */
	,confirmOverwrite:function(filename, callback, scope) {
		Ext.Msg.show({
			 title:this.confirmText
			,msg:String.format(this.existsText, filename) + '. ' + this.overwriteText
			,icon:Ext.Msg.QUESTION
			,buttons:Ext.Msg.YESNO
			,fn:callback.createDelegate(scope || this)
		});
	}
	// }}}
	// {{{
	/**
	 * creates new directory (node)
	 * @private
	 * @param {Ext.tree.AsyncTreeNode} node
	 */
	,createNewDir:function(node) {

		// fire beforenewdir event
		if(true !== this.eventsSuspended && false === this.fireEvent('beforenewdir', this, node)) {
			return;
		}

		var treeEditor = this.treeEditor;
		var newNode;

		// get node to append the new directory to
		var appendNode = node.isLeaf() ? node.parentNode : node;

		// create new folder after the appendNode is expanded
		appendNode.expand(false, false, function(n) {
			// create new node
			newNode = n.appendChild(new Ext.tree.AsyncTreeNode({text:this.newdirText, iconCls:'folder'}));

			// setup one-shot event handler for editing completed
			treeEditor.on({
				complete:{
					 scope:this
					,single:true
					,fn:this.onNewDir
				}}
			);

			// creating new directory flag
			treeEditor.creatingNewDir = true;

			// start editing after short delay
			(function(){treeEditor.triggerEdit(newNode);}.defer(10));
		// expand callback needs to run in this context
		}.createDelegate(this));

	} // eo function creatingNewDir
	/**
	 * creates new file (node)
	 * @private
	 * @param {Ext.tree.AsyncTreeNode} node
	 */
	,createNewFile:function(node) {

		// fire beforenewfile event
		if(true !== this.eventsSuspended && false === this.fireEvent('beforenewfile', this, node)) {
			return;
		}

		var treeEditor = this.treeEditor;
		var newNode;

		// get node to append the new file to
		var appendNode = node.isLeaf() ? node.parentNode : node;

		// create new file after the appendNode is expanded
		appendNode.expand(false, false, function(n) {
			// create new node
			newNode = n.appendChild(new Ext.tree.AsyncTreeNode({text:this.newfileText, leaf:true}));

			// setup one-shot event handler for editing completed
			treeEditor.on({
				complete:{
					 scope:this
					,single:true
					,fn:this.onNewFile
				}}
			);

			// creating new file flag
			treeEditor.creatingNewFile = true;

			// start editing after short delay
			(function(){treeEditor.triggerEdit(newNode);}.defer(10));
		// expand callback needs to run in this context
		}.createDelegate(this));

	} // eo function creatingNewFile
	// }}}
	// {{{
	/**
	 * deletes the passed node
	 * @private
	 * @param {Ext.tree.AsyncTreeNode} node
	 */
	,deleteNode:function(node) {
		// fire beforedelete event
		if(true !== this.eventsSuspended && false === this.fireEvent('beforedelete', this, node)) {
			return;
		}

		Ext.Msg.show({
			 title:this.deleteText
			,msg:this.reallyWantText + ' ' + this.deleteText.toLowerCase()  + ' <b>' + node.text + '</b>?'
			,icon:Ext.Msg.WARNING
			,buttons:Ext.Msg.YESNO
			,scope:this
			,fn:function(response) {
				// do nothing if answer is not yes
				if('yes' !== response) {
					this.getEl().dom.focus();
					return;
				}
				// setup request options
				var options = {
					 url:this.deleteUrl || this.url
					,method:this.method
					,scope:this
					,callback:this.cmdCallback
					,node:node
					,params:{
						 cmd:'delete'
						,file:this.getPath(node)
					}
				};
				Ext.Ajax.request(options);
			}
		});
	} // eo function deleteNode
	// }}}
	// {{{
	/**
	 * requests file download from server
	 * @private
	 * @param {String} path Full path including file name but relative to server root path
	 */
	,downloadFile:function(path) {

		// create hidden target iframe
		var id = Ext.id();
		var frame = document.createElement('iframe');
		frame.id = id;
		frame.name = id;
		frame.className = 'x-hidden';
		if(Ext.isIE) {
			frame.src = Ext.SSL_SECURE_URL;
		}

		document.body.appendChild(frame);

		if(Ext.isIE) {
			document.frames[id].name = id;
		}

		var form = Ext.DomHelper.append(document.body, {
			 tag:'form'
			,method:'post'
			,action:this.downloadUrl || this.url
			,target:id
		});

		document.body.appendChild(form);

		var hidden;

		// append cmd to form
		hidden = document.createElement('input');
		hidden.type = 'hidden';
		hidden.name = 'cmd';
		hidden.value = 'download';
		form.appendChild(hidden);

		// append path to form
		hidden = document.createElement('input');
		hidden.type = 'hidden';
		hidden.name = 'path';
		hidden.value = path;
		form.appendChild(hidden);

		var callback = function() {
			Ext.EventManager.removeListener(frame, 'load', callback, this);
			setTimeout(function() {document.body.removeChild(form);}, 100);
			setTimeout(function() {document.body.removeChild(frame);}, 110);
		};
		
		Ext.EventManager.on(frame, 'load', callback, this);

		form.submit();
	}
	/**
	* open the file into a tab from the center tabpanel
	*/
	,openFile:function(node,path) {
		
		var id = Ext.id();
		
		var tabPanel=Ext.getCmp(this.tabPanelId);
		var currentTabs=tabPanel.items.items;
		var fileName=this.getFileName(path);
		
		//find if the current path is opened
		var opened_tabs=tabPanel.find('path',path);
		
		if(opened_tabs.length>0)
		{
			var opened_tab=opened_tabs[0];
			opened_tab.show();
		}
		else{
		
			//find if No file tab is activated
			var no_file_tabs=tabPanel.find('title','No file');
			
			var new_tab=tabPanel.add(new Ext.ux.CodePress({title:fileName,
															closable:true,
															path:path,
															tabTip:path,
															file:path,
															tabPanel:tabPanel})).show();
			
			for(var tab in no_file_tabs)
			{
				tabPanel.remove(tab);
			}		
		}
	}
	// }}}
	// {{{
	/**
	 * returns (and lazy create) the context menu
	 * @private
	 */
	,getContextMenu:function() {
		// lazy create context menu
		if(!this.contextmenu) {
			var config = {
				 singleUpload:this.singleUpload
				,maxFileSize:this.maxFileSize
				,enableProgress:this.enableProgress
			};
			if(this.baseParams) {
				config.baseParams = this.baseParams;
			}
			this.contextmenu = new Ext.ux.FileTreeMenu(config);
			this.contextmenu.on({click:{scope:this, fn:this.onContextClick}});

			this.uploadPanel = this.contextmenu.getItemByCmd('upload-panel').component;
			this.uploadPanel.on({
				 beforeupload:{scope:this, fn:this.onBeforeUpload}
				,allfinished:{scope:this, fn:this.onAllFinished}
			});
			this.uploadPanel.setUrl(this.uploadUrl || this.url);
		}
		return this.contextmenu;
	} // eo function getContextMenu
	// }}}
	// {{{
	/**
	 * returns file class based on name extension
	 * @private
	 * @param {String} name File name to get class of
	 */
	,getFileCls:function(name) {
		var atmp = name.split('.');
		if(1 === atmp.length) {
			return this.fileCls;
		}
		else {
			return this.fileCls + '-' + atmp.pop().toLowerCase();
		}
	}
	// }}}
	// {{{
	,getFileName:function(path) {
		var atmp=path.split('/');
		
		return atmp[atmp.length-1];
	}
	/**
	 * returns path of node (file/directory)
	 * @private
	 */
	,getPath:function(node) {
		var path, p, a;

		// get path for non-root node
		if(node !== this.root) {
			p = node.parentNode;
			a = [node.text];
			while(p && p !== this.root) {
				a.unshift(p.text);
				p = p.parentNode;
			}
			a.unshift(this.root.attributes.path || '');
			path = a.join(this.pathSeparator);
		}

		// path for root node is it's path attribute
		else {
			path = node.attributes.path || '';
		}

		// a little bit of security: strip leading / or .
		// full path security checking has to be implemented on server
		path = path.replace(/^[\/\.]*/, '');
		return path;
	} // eo function getPath
	// }}}
	// {{{
	/**
	 * returns true if node has child with the specified name (text)
	 * @private
	 * @param {Ext.data.Node} node
	 * @param {String} childName
	 */
	,hasChild:function(node, childName) {
		return (node.isLeaf() ? node.parentNode : node).findChild('text', childName) !== null;
	}
	// }}}
	// {{{
	/**
	 * Hides context menu
	 * @return {Ext.ux.FileTreeMenu} this
	 */
	,hideContextMenu:function() {
		if(this.contextmenu && this.contextmenu.isVisible()) {
			this.contextmenu.hide();
		}
		return this;
	} // eo function hideContextMenu
	// }}}
	// {{{
	/**
	 * called before editing is completed - allows edit cancellation
	 * @private
	 * @param {TreeEditor} editor
	 * @param {String} newName
	 * @param {String} oldName
	 */
	,onBeforeEditComplete:function(editor, newName, oldName) {
		if(editor.cancellingEdit) {
			editor.cancellingEdit = false;
			return;
		}
		var oldPath = this.getPath(editor.editNode);
		var newPath = oldPath.replace(/\/[^\\]+$/, '/' + newName);

		if(false === this.fireEvent('beforerename', this, editor.editNode, newPath, oldPath)) {
			editor.cancellingEdit = true;
			editor.cancelEdit();
			return false;
		}
	}
	// }}}
	// {{{
	/**
	 * runs before node is dropped
	 * @private
	 * @param {Object} e dropEvent object
	 */
	,onBeforeNodeDrop:function(e) {

		// source node, node being dragged
		var s = e.dropNode;

		// destination node (dropping on this node)
		var d = e.target.leaf ? e.target.parentNode : e.target;

		// node has been dropped within the same parent
		if(s.parentNode === d) {
			return false;
		}

		// check if same name exists in the destination
		// this works only if destination node is loaded
		if(this.hasChild(d, s.text) && undefined === e.confirmed) {
			this.confirmOverwrite(s.text, function(response) {
				e.confirmed = 'yes' === response;
				this.onBeforeNodeDrop(e);
			});
			return false;
		}
		if(false === e.confirmed) {
			return false;
		}

		e.confirmed = undefined;
		e.oldParent = s.parentNode;

		var oldName = this.getPath(s);
		var newName = this.getPath(d) + '/' + s.text;

		// fire beforerename event
		if(true !== this.eventsSuspended && false === this.fireEvent('beforerename', this, s, newName, oldName)) {
			return false;
		}

		var options = {
			 url:this.renameUrl || this.url
			,method:this.method
			,scope:this
			,callback:this.cmdCallback
			,node:s
			,oldParent:s.parentNode
			,e:e
			,params:{
				 cmd:'rename'
				,oldname:oldName
				,newname:newName
			}
		};
		Ext.Ajax.request(options);
		return true;
	}
	// }}}
	// {{{
	/**
	 * sets uploadPanel's destination path
	 * @private
	 */
	,onBeforeUpload:function(uploadPanel) {

		var menu = this.getContextMenu();
		var path = this.getPath(menu.node);
		if(menu.node.isLeaf()) {
			path = path.replace(/\/[^\/]+$/, '', path);
		}
		uploadPanel.setPath(path);

	} // eo function onBeforeUpload
	// }}}
	// {{{
	/**
	 * reloads tree node on upload finish
	 * @private
	 */
	,onAllFinished:function(uploader) {
		var menu = this.getContextMenu();
		(menu.node.isLeaf() ? menu.node.parentNode : menu.node).reload();
	} // eo function onAllFinished
	// }}}
	// {{{
	/**
	 * @private
	 * context menu click handler
	 * @param {Ext.menu.Menu} context menu
	 * @param {Ext.menu.Item} item clicked
	 * @param {Ext.EventObject} raw event
	 */
	,onContextClick:function(menu, item, e) {
		if(item.disabled) {
			return;
		}
		var node = menu.node;
		if(!node) {
			node = menu.parentMenu.node;
		}
		switch(item.cmd) {
			case 'reload':
				node.reload();
			break;

			case 'expand':
				node.expand(true);
			break;

			case 'collapse':
				node.collapse(true);
			break;

			case 'open':
				this.openNode(node);
			break;

			case 'open-self':
				this.openNode(node, '_self');
			break;

			case 'open-popup':
				this.openNode(node, 'popup');
			break;

			case 'open-blank':
				this.openNode(node, '_blank');
			break;

			case 'open-dwnld':
				this.openNode(node, 'download');
			break;

			case 'rename':
				this.treeEditor.triggerEdit(node);
			break;

			case 'delete':
				this.deleteNode(node);
			break;

			case 'newdir':
				this.createNewDir(node);
			break;
			
			case 'newfile':
				this.createNewFile(node);
			break;

			default:
			break;
		}
	} // eo function onContextClick
	// }}}
	// {{{
	/**
	 * contextmenu event handler
	 * @private
	 */
	,onContextMenu:function(node, e) {
		if(this.readOnly) {
			return false;
		}
		this.showContextMenu(node);

		return false;
	} // eo function onContextMenu
	// }}}
	// {{{
	/**
	 * dblclick handlers
	 * @private
	 */
	,onDblClick:function(node, e) {
		this.openNode(node);
	} // eo function onDblClick
	// }}}
	// {{{
	/**
	 * Destroys the FileTreePanel and sub-components
	 * @private
	 */
	,onDestroy:function() {

		// destroy contextmenu
		if(this.contextmenu) {
			this.contextmenu.purgeListeners();
			this.contextmenu.destroy();
			this.contextmenu = null;
		}

		// destroy treeEditor
		if(this.treeEditor) {
			this.treeEditor.purgeListeners();
			this.treeEditor.destroy();
			this.treeEditor = null;
		}

		// remover reference to treeSorter
		if(this.treeSorter) {
			this.treeSorter = null;
		}

		// call parent
		Ext.ux.FileTreePanel.superclass.onDestroy.call(this);

	} // eo function onDestroy
	// }}}
	// {{{
	/**
	 * runs when editing of a node (rename) is completed
	 * @private
	 * @param {Ext.Editor} editor
	 * @param {String} newName
	 * @param {String} oldName
	 */
	,onEditComplete:function(editor, newName, oldName) {

		var node = editor.editNode;

		if(newName === oldName || editor.creatingNewDir || editor.creatingNewFile) {
			editor.creatingNewDir = false;
			editor.creatingNewFile = false;
			return;
		}
		var path = this.getPath(node.parentNode);
		var options = {
			 url:this.renameUrl || this.url
			,method:this.method
			,scope:this
			,callback:this.cmdCallback
			,node:node
			,oldName:oldName
			,params:{
				 cmd:'rename'
				,oldname:path + '/' + oldName
				,newname:path + '/' + newName
			}
		};
		Ext.Ajax.request(options);
	}
	// }}}
	// {{{
	/**
	 * create new directory handler
	 * @private
	 * runs after editing of new directory name is completed
	 * @param {Ext.Editor} editor
	 */
	,onNewDir:function(editor) {
		var path = this.getPath(editor.editNode);
		var options = {
			 url:this.newdirUrl || this.url
			,method:this.method
			,scope:this
			,node:editor.editNode
			,callback:this.cmdCallback
			,params:{
				 cmd:'newdir'
				,dir:path
			}
		};
		Ext.Ajax.request(options);
	}
	/**
	 * create new file handler
	 * @private
	 * runs after editing of new file name is completed
	 * @param {Ext.Editor} editor
	 */
	,onNewFile:function(editor) {
		var path = this.getPath(editor.editNode);
		var options = {
			 url:this.newfileUrl || this.url
			,method:this.method
			,scope:this
			,node:editor.editNode
			,callback:this.cmdCallback
			,params:{
				 cmd:'newfile'
				,file:path
			}
		};
		Ext.Ajax.request(options);
	}
	/**
	 * create new file handler after the file is saved
	 * @param node
	 */
	,onNewFileSuccess:function(tree,node) {
	    this.openNode(node,'_self');
	}
	/**
	 * create delete handler after the file is deleted from tree
	 * @param node
	 */
	,onDeleteSuccess:function(tree,node) {
	    var tabPanel=Ext.getCmp(this.tabPanelId);
		var path=this.getPath(node);
		
		//find if the current path is opened
		var opened_tabs=tabPanel.find('path',path);
		
		if(opened_tabs.length>0)
		{
			for(var tab in opened_tabs)
			{
				tabPanel.remove(tab);
			}
		}
	}
	// }}}
	// {{{
	/**
	 * called while dragging over, decides if drop is allowed
	 * @private
	 * @param {Object} dd event
	 */
	,onNodeDragOver:function(e) {
		e.cancel = e.target.disabled || e.dropNode.parentNode === e.target.parentNode && e.target.isLeaf();
	} // eo function onNodeDragOver
	// }}}
	// {{{
	/**
	 * called when node is dropped
	 * @private
	 * @param {Object} dd event
	 */
	,onNodeDrop:function(e) {

		// failure can be signalled by cmdCallback
		// put drop node to the original parent in that case
		if(true === e.failure) {
			e.oldParent.appendChild(e.dropNode);
			return;
		}

		// if we already have node with the same text, remove the duplicate
		var sameNode = e.dropNode.parentNode.findChild('text', e.dropNode.text);
		if(sameNode && sameNode !== e.dropNode) {
			sameNode.parentNode.removeChild(sameNode);
		}
	}
	// }}}
	// {{{
	/**
	 * Opens node
	 * @param {Ext.tree.AsyncTreeNode} node
	 * @param {String} mode Can be "_self", "_blank", or "popup". Defaults to (this.openMode)
	 */
	,openNode:function(node, mode) {

		if(!this.enableOpen) {
			return;
		}

		mode = mode || this.openMode;

		var url;
		var path;
		if(node.isLeaf()) {
			path = this.getPath(node);
						
			url = this.hrefPrefix + path + this.hrefSuffix;

			// fire beforeopen event
			if(true !== this.eventsSuspended && false === this.fireEvent('beforeopen', this, node.text, url, mode)) {
				return;
			}

			switch(mode) {
				case 'popup':
					if(!this.popup || this.popup.closed) {
						this.popup = window.open(url, this.hrefTarget, this.popupFeatures);
					}
					this.popup.location = url;
					if(this.focusPopup) {
						this.popup.focus();
					}
				break;

				case '_self':
					this.openFile(node,path);
				break;

				case '_blank':
					window.open(url);
				break;

				case 'download':
					this.downloadFile(path);
				break;
			}

			// fire open event
			if(true !== this.eventsSuspended) {
				this.fireEvent('open', this, node.text, url, mode);
			}
		}

	}
	// }}}
	// {{{
	/**
	 * Sets/Unsets delete of files/directories disabled/enabled
	 * @param {Boolean} disabled
	 * @return {Ext.ux.FileTreePanel} this
	 */
	,setDeleteDisabled:function(disabled) {
		disabled = !(!disabled);
		if(!this.enableDelete === disabled) {
			return this;
		}
		this.hideContextMenu();
		this.enableDelete = !disabled;
	} // eo function setDeleteDisabled
	// }}}
	// {{{
	/**
	 * Sets/Unsets creation of new directory disabled/enabled
	 * @param {Boolean} disabled
	 * @return {Ext.ux.FileTreePanel} this
	 */
	,setNewdirDisabled:function(disabled) {
		disabled = !(!disabled);
		if(!this.enableNewDir === disabled) {
			return this;
		}
		this.hideContextMenu();
		this.enableNewDir = !disabled;

	} // eo function setNewdirDisabled
	// }}}
	// {{{
	/**
	 * Sets/Unsets open files disabled/enabled
	 * @param {Boolean} disabled
	 * @return {Ext.ux.FileTreePanel} this
	 */
	,setOpenDisabled:function(disabled) {
		disabled = !(!disabled);
		if(!this.enableOpen === disabled) {
			return this;
		}
		this.hideContextMenu();
		this.enableOpen = !disabled;

		return this;
	} // eo function setOpenDisabled
	// }}}
	// {{{
	/**
	 * Sets/Unsets this tree to/from readOnly state
	 * @param {Boolean} readOnly
	 * @return {Ext.ux.FileTreePanel} this
	 */
	,setReadOnly:function(readOnly) {
		readOnly = !(!readOnly);
		if(this.readOnly === readOnly) {
			return this;
		}
		this.hideContextMenu();
		if(this.dragZone) {
			this.dragZone.locked = readOnly;
		}
		this.readOnly = readOnly;

		return this;

	} // eo function setReadOnly
	// }}}
	// {{{
	/**
	 * Sets/Unsets rename of files/directories disabled/enabled
	 * @param {Boolean} disabled
	 * @return {Ext.ux.FileTreePanel} this
	 */
	,setRenameDisabled:function(disabled) {
		disabled = !(!disabled);
		if(!this.enableRename === disabled) {
			return this;
		}
		this.hideContextMenu();
		if(this.dragZone) {
			this.dragZone.locked = disabled;
		}
		this.enableRename = !disabled;

		return this;
	} // eo function setRenameDisabled
	// }}}
	// {{{
	/**
	 * Sets/Unsets uploading of files disabled/enabled
	 * @param {Boolean} disabled
	 * @return {Ext.ux.FileTreePanel} this
	 */
	,setUploadDisabled:function(disabled) {
		disabled = !(!disabled);
		if(!this.enableUpload === disabled) {
			return this;
		}
		this.hideContextMenu();
		this.enableUpload = !disabled;

		return this;
	} // of function setUploadDisabled
	// }}}
	// {{{
	/**
	 * adjusts context menu depending on many things and shows it
	 * @private
	 * @param {Ext.tree.AsyncTreeNode} node Node on which was right-clicked
	 */
	,showContextMenu:function(node) {

		// setup node alignment
		var topAlign = false;
		var alignEl = this.topMenu ? this.topMenu.getEl() : this.body;

		if(!node) {
			node = this.getSelectionModel().getSelectedNode();
			topAlign = true;
		}
		else {
			alignEl = node.getUI().getEl();
		}
		if(!node) {
			return;
		}

		var menu = this.getContextMenu();
		menu.node = node;

		// set node name
		menu.getItemByCmd('nodename').setText(Ext.util.Format.ellipsis(node.text, 22));

		// enable/disable items depending on node clicked
		menu.setItemDisabled('open', !node.isLeaf());
		menu.setItemDisabled('reload', node.isLeaf());
		menu.setItemDisabled('expand', node.isLeaf());
		menu.setItemDisabled('collapse', node.isLeaf());
		menu.setItemDisabled('delete', node === this.root || node.disabled);
		menu.setItemDisabled('rename', this.readOnly || node === this.root || node.disabled);
		menu.setItemDisabled('newdir', this.readOnly || (node.isLeaf() ? node.parentNode.disabled : node.disabled));
		menu.setItemDisabled('newfile', this.readOnly || (node.isLeaf() ? node.parentNode.disabled : node.disabled));
		menu.setItemDisabled('upload', node.isLeaf() ? node.parentNode.disabled : node.disabled);
		menu.setItemDisabled('upload-panel', node.isLeaf() ? node.parentNode.disabled : node.disabled);
		
		// show/hide logic
		menu.getItemByCmd('open').setVisible(this.enableOpen);
		menu.getItemByCmd('delete').setVisible(this.enableDelete);
		menu.getItemByCmd('newdir').setVisible(this.enableNewDir);
		menu.getItemByCmd('rename').setVisible(this.enableRename);
		menu.getItemByCmd('upload').setVisible(this.enableUpload);
		menu.getItemByCmd('upload-panel').setVisible(this.enableUpload);
		menu.getItemByCmd('sep-upload').setVisible(this.enableUpload);
		menu.getItemByCmd('sep-collapse').setVisible(this.enableNewDir || this.enableDelete || this.enableRename);

		// select node
		node.select();

		// show menu
		if(topAlign) {
			menu.showAt(menu.getEl().getAlignToXY(alignEl, 'tl-bl?'));
		}
		else {
			menu.showAt(menu.getEl().getAlignToXY(alignEl, 'tl-tl?', [0, 18]));
		}
	} // eo function 
	// }}}
	// {{{
	/**
	 * universal show error function
	 * @private
	 * @param {String} msg message
	 * @param {String} title title
	 */
	,showError:function(msg, title) {
		Ext.Msg.show({
			 title:title || this.errorText
			,msg:Ext.util.Format.ellipsis(msg, this.maxMsgLen)
			,fixCursor:true
			,icon:Ext.Msg.ERROR
			,buttons:Ext.Msg.OK
			,minWidth:1200 > String(msg).length ? 360 : 600
		});
	} // eo function showError
	// }}}
	// {{{
	/**
	 * updates class of leaf after rename
	 * @private
	 * @param {Ext.tree.AsyncTreeNode} node Node to update class of
	 * @param {String} oldName Name the node had before
	 */
	,updateCls:function(node, oldName) {
		if(node.isLeaf()) {
			Ext.fly(node.getUI().iconNode).removeClass(this.getFileCls(oldName));
			Ext.fly(node.getUI().iconNode).addClass(this.getFileCls(node.text));
		}
	}
	// }}}

}); // eo extend

// register xtype
Ext.reg('filetreepanel', Ext.ux.FileTreePanel);

// eof

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/filetree/js/Ext.ux.FileTreePanel.js  ]*/

// vim: ts=4:sw=4:nu:fdc=4:nospell
/**
 * Ext.ux.FileTreeMenu
 *
 * @author  Ing. Jozef Sakáloš
 * @version $Id: Ext.ux.FileTreeMenu.js 520 2009-01-31 02:59:29Z jozo $
 * @date    13. March 2008
 *
 * @license Ext.ux.FileTreeMenu is licensed under the terms of
 * the Open Source LGPL 3.0 license.  Commercial use is permitted to the extent
 * that the code/component(s) do NOT become part of another Open Source or Commercially
 * licensed development library or toolkit without explicit permission.
 * 
 * License details: http://www.gnu.org/licenses/lgpl.html
 */

/*global Ext */

/**
 * @class Ext.ux.FileTreeMenu
 * @extends Ext.menu.Menu
 * @constructor
 * Creates new FileTreeMenu object
 * @param {Object} config A configuration object
 */
Ext.ux.FileTreeMenu = function(config) {
	config = config || {};

	var uploadPanelConfig = {
		 contextmenu:this
		,buttonsAt:config.buttonsAt || 'tbar'
		,singleUpload:config.singleUpload || false
		,maxFileSize:config.maxFileSize
		,enableProgress:config.enableProgress
	};
	if(config.baseParams) {
		config.baseParams.cmd = config.baseParams.cmd || 'upload';
		config.baseParams.dir = config.baseParams.dir || '.';
		uploadPanelConfig.baseParams = config.baseParams;
	}

	// {{{
	Ext.apply(config, {
		items:[{
			 text:'&#160'
			,cls:'ux-ftm-nodename'
			,disabledClass:''
			,disabled:true
			,cmd:'nodename'
		},{
			 text:this.openText + ' (Enter)'
			,iconCls:this.openIconCls
			,cmd:'open'
			,menu:{
				items:[{
					 text:this.openSelfText
					,iconCls:this.openSelfIconCls
					,cmd:'open-self'
				}/*,{
					 text:this.openPopupText
					,iconCls:this.openPopupIconCls
					,cmd:'open-popup'
				},{
					 text:this.openBlankText
					,iconCls:this.openBlankIconCls
					,cmd:'open-blank'
				},{
					 text:this.openDwnldText
					,iconCls:this.openDwnldIconCls
					,cmd:'open-dwnld'
				}*/]
			}
		}
		,new Ext.menu.Separator({cmd:'sep-open'})
		,{
			 text:this.reloadText + ' (Ctrl+E)'
			,iconCls:this.reloadIconCls
			,cmd:'reload'
		},{
			 text:this.expandText + ' (Ctrl+&nbsp;&rarr;)'
			,iconCls:this.expandIconCls
			,cmd:'expand'
		},{
			 text:this.collapseText + ' (Ctrl+&nbsp;&larr;)'
			,iconCls:this.collapseIconCls
			,cmd:'collapse'
		}
		,new Ext.menu.Separator({cmd:'sep-collapse'})
		,{
			 text:this.renameText + ' (F2)'
			,iconCls:this.renameIconCls
			,cmd:'rename'
		},{	
			 text:this.deleteText + ' (' + this.deleteKeyName + ')'
			,iconCls:this.deleteIconCls
			,cmd:'delete'
		},{	
			 text:this.newdirText + '... (Ctrl+N)'
			,iconCls:this.newdirIconCls
			,cmd:'newdir'
		},{	
			 text:this.newfileText
			,iconCls:this.newfileIconCls
			,cmd:'newfile'
		}
		,new Ext.menu.Separator({cmd:'sep-upload'})
		,{
			 text:this.uploadFileText + ' (Ctrl+U)'
			,iconCls:this.uploadIconCls
			,hideOnClick:false
			,cmd:'upload'
		}
			,new Ext.menu.Adapter(new Ext.ux.UploadPanel(uploadPanelConfig), {
				 hideOnClick:false
				,cmd:'upload-panel'
			})
		]
	}); // eo apply
	// }}}

	// call parent
	Ext.ux.FileTreeMenu.superclass.constructor.call(this, config);

	// relay event from submenu
	this.relayEvents(this.getItemByCmd('open').menu, ['click', 'itemclick']);

}; // eo constructor

Ext.extend(Ext.ux.FileTreeMenu, Ext.menu.Menu, {
	// configuration options overridable from outside
	/**
	 * @cfg {String} collapseIconCls icon class for collapse all item
	 */
	 collapseIconCls:'icon-collapse-all'

	 /**
	  * @cfg {String} collapseText text for collapse all item
	  */
	,collapseText: 'Collapse all'

	/**
	 * @cfg {String} deleteIconCls icon class for delete item
	 */
	,deleteIconCls:'icon-cross'

	/**
	 * @cfg {String} deleteKeyName text for delete item shortcut
	 */
	,deleteKeyName:'Delete Key'

	/**
	 * @cfg {String} deleteText text for delete item
	 */
	,deleteText:'Delete'

	/**
	 * @cfg {String} expandIconCls icon class for expand all item
	 */
	,expandIconCls:'icon-expand-all'

	/**
	 * @cfg {String} expandText text for expand all item
	 */
	,expandText: 'Expand all'

	/**
	 * @cfg {String} newdirIconCls icon class for new directory item
	 */
	,newdirIconCls:'icon-folder-add'

	/**
	 * @cfg {String} newdirText text for new directory item
	 */
	,newdirText:'New folder'
	
	/**
	 * @cfg {String} newfileIconCls icon class for new file item
	 */
	,newfileIconCls:'icon-plus'

	/**
	 * @cfg {String} newfileText text for new file item
	 */
	,newfileText:'New file'

	/**
	 * @cfg {String} openBlankIconCls icon class for open in new window item
	 */
	,openBlankIconCls:'icon-open-blank'

	/**
	 * @cfg {String} openBlankText text for open in new window item
	 */
	,openBlankText:'Open in new window'

	/**
	 * @cfg {String} openDwnldIconCls icon class for download item
	 */
	,openDwnldIconCls:'icon-open-download'

	/**
	 * @cfg {String} openDwnldText text for download item
	 */
	,openDwnldText:'Download'

	/**
	 * @cfg {String} openIconCls icon class for open submenu
	 */
	,openIconCls:'icon-open'

	/**
	 * @cfg {String} openPopupIconCls icon class for open in popup item
	 */
	,openPopupIconCls:'icon-open-popup'

	/**
	 * @cfg {String} text for open in poput item
	 */
	,openPopupText:'Open in popup'

	/**
	 * @cfg {String} openSelfIconCls icon class for open in this window item
	 */
	,openSelfIconCls:'icon-open-self'

	/**
	 * @cfg {String} openSelfText text for open in this window item
	 */
	,openSelfText:'Open in this window'

	/**
	 * @cfg {String} openText text for open submenu
	 */
	,openText:'Open'

	/**
	 * @cfg {String} reloadIconCls icon class for reload item
	 */
	,reloadIconCls:'icon-refresh'

	/**
	 * @cfg {String} reloadText text for reload item
	 */
	,reloadText:'R<span style="text-decoration:underline">e</span>load'

	/**
	 * @cfg {String} icon class for rename item
	 */
	,renameIconCls:'icon-pencil'

	/**
	 * @cfg {String} renameText text for rename item
	 */
	,renameText: 'Rename'
	
	/**
	 * @cfg {String} uploadFileText text for upload file item
	 */
	,uploadFileText:'<span style="text-decoration:underline">U</span>pload file'

	/**
	 * @cfg {String} uploadIconCls icon class for upload file item
	 */
	,uploadIconCls:'icon-upload'

	/**
	 * @cfg {String} uploadText text for word 'Upload'
	 */
	,uploadText:'Upload'

	/**
	 * @cfg {Number} width Width of the menu. 
	 * Cannot be empty as we have upload panel inside.
	 */
	,width:190

	// {{{
	/**
	 * Returns menu item identified by cmd. Unique cmd is used to identify menu items.
	 * I cannot use ids as they are applied to underlying DOM elements that would prevent
	 * to have more than one menu on the page.
	 * @param {String} cmd
	 * Valid cmds are:
	 *		- nodename
	 *		- open
	 *		- open-self
	 *		- open-popup
	 *		- open-blank
	 *		- open-dwnld
	 *		- sep-open (for separator after open submenu)
	 *		- reload
	 *		- expand
	 *		- collapse
	 *		- sep-collapse (for separator after collapse item)
	 *		- rename
	 *		- delete
	 *		- newdir
	 *		- sep-upload (for separator before upload panel)
	 *		- upload (for upload file item that does nothing)
	 *		- upload-panel (for upload panel)
	 * @return {Ext.menu.Item} menu item
	 */
	,getItemByCmd:function(cmd) {
		var open;
		var item = this.items.find(function(i) {
			return cmd === i.cmd;
		});
		if(!item) {
			open = this.items.find(function(i) {
				return 'open' === i.cmd;
			});
			if(!open) {
				return null;
			}
			item = open.menu.items.find(function(i) {
				return cmd === i.cmd;
			});
		}
		return item;
	} // eo function getItemByCmd
	// }}}
	// {{{
	/**
	 * Sets/Unsets item identified by cmd to disabled/enabled state
	 * @param {String} cmd Item indentifier, see getItemByCmd for explanation
	 * @param {Boolean} disabled true to disable the item
	 */
	,setItemDisabled:function(cmd, disabled) {
		var item = this.getItemByCmd(cmd);
		if(item) {
			item.setDisabled(disabled);
		}
	} // eo function setItemDisabled
	// }}}
	// {{{
	/**
	 * destroys uploadPanel if we have one
	 * @private
	 */
	,beforeDestroy:function() {
		var uploadPanel = this.getItemByCmd('upload-panel');
		if(uploadPanel && uploadPanel.component) {
			uploadPanel.component.purgeListeners();
			uploadPanel.component.destroy();
			uploadPanel.component = null;
		}
	} // eo function beforeDestroy
	// }}}

}); // eo extend

// register xtype
Ext.reg('filetreemenu', Ext.ux.FileTreeMenu);

// eof

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/filetree/js/Ext.ux.FileTreeMenu.js  ]*/

Ext.namespace('Ext.ux.form');

/**
 * @class Ext.ux.form.BrowseButton
 * @extends Ext.Button
 * Ext.Button that provides a customizable file browse button.
 * Clicking this button, pops up a file dialog box for a user to select the file to upload.
 * This is accomplished by having a transparent <input type="file"> box above the Ext.Button.
 * When a user thinks he or she is clicking the Ext.Button, they're actually clicking the hidden input "Browse..." box.
 * Note: this class can be instantiated explicitly or with xtypes anywhere a regular Ext.Button can be except in 2 scenarios:
 * - Panel.addButton method both as an instantiated object or as an xtype config object.
 * - Panel.buttons config object as an xtype config object.
 * These scenarios fail because Ext explicitly creates an Ext.Button in these cases.
 * Browser compatibility:
 * Internet Explorer 6:
 * - no issues
 * Internet Explorer 7:
 * - no issues
 * Firefox 2 - Windows:
 * - pointer cursor doesn't display when hovering over the button.
 * Safari 3 - Windows:
 * - no issues.
 * @author loeppky - based on the work done by MaximGB in Ext.ux.UploadDialog (http://extjs.com/forum/showthread.php?t=21558)
 * The follow the curosr float div idea also came from MaximGB.
 * @see http://extjs.com/forum/showthread.php?t=29032
 * @constructor
 * Create a new BrowseButton.
 * @param {Object} config Configuration options
 */
Ext.ux.form.BrowseButton = Ext.extend(Ext.Button, {
	/*
	 * Config options:
	 */
	/**
	 * @cfg {String} inputFileName
	 * Name to use for the hidden input file DOM element.  Deaults to "file".
	 */
	inputFileName: 'file',
	/**
	 * @cfg {Boolean} debug
	 * Toggle for turning on debug mode.
	 * Debug mode doesn't make clipEl transparent so that one can see how effectively it covers the Ext.Button.
	 * In addition, clipEl is given a green background and floatEl a red background to see how well they are positioned.
	 */
	debug: false,
	
	
	/*
	 * Private constants:
	 */
	/**
	 * @property FLOAT_EL_WIDTH
	 * @type Number
	 * The width (in pixels) of floatEl.
	 * It should be less than the width of the IE "Browse" button's width (65 pixels), since IE doesn't let you resize it.
	 * We define this width so we can quickly center floatEl at the mouse cursor without having to make any function calls.
	 * @private
	 */
	FLOAT_EL_WIDTH: 60,
	
	/**
	 * @property FLOAT_EL_HEIGHT
	 * @type Number
	 * The heigh (in pixels) of floatEl.
	 * It should be less than the height of the "Browse" button's height.
	 * We define this height so we can quickly center floatEl at the mouse cursor without having to make any function calls.
	 * @private
	 */
	FLOAT_EL_HEIGHT: 18,
	
	
	/*
	 * Private properties:
	 */
	/**
	 * @property buttonCt
	 * @type Ext.Element
	 * Element that contains the actual Button DOM element.
	 * We store a reference to it, so we can easily grab its size for sizing the clipEl.
	 * @private
	 */
	buttonCt: null,
	/**
	 * @property clipEl
	 * @type Ext.Element
	 * Element that contains the floatEl.
	 * This element is positioned to fill the area of Ext.Button and has overflow turned off.
	 * This keeps floadEl tight to the Ext.Button, and prevents it from masking surrounding elements.
	 * @private
	 */
	clipEl: null,
	/**
	 * @property floatEl
	 * @type Ext.Element
	 * Element that contains the inputFileEl.
	 * This element is size to be less than or equal to the size of the input file "Browse" button.
	 * It is then positioned wherever the user moves the cursor, so that their click always clicks the input file "Browse" button.
	 * Overflow is turned off to preven inputFileEl from masking surrounding elements.
	 * @private
	 */
	floatEl: null,
	/**
	 * @property inputFileEl
	 * @type Ext.Element
	 * Element for the hiden file input.
	 * @private
	 */
	inputFileEl: null,
	/**
	 * @property originalHandler
	 * @type Function
	 * The handler originally defined for the Ext.Button during construction using the "handler" config option.
	 * We need to null out the "handler" property so that it is only called when a file is selected.
	 * @private
	 */
	originalHandler: null,
	/**
	 * @property originalScope
	 * @type Object
	 * The scope originally defined for the Ext.Button during construction using the "scope" config option.
	 * While the "scope" property doesn't need to be nulled, to be consistent with originalHandler, we do.
	 * @private
	 */
	originalScope: null,
	
	
	/*
	 * Protected Ext.Button overrides
	 */
	/**
	 * @see Ext.Button.initComponent
	 */
	initComponent: function(){
		Ext.ux.form.BrowseButton.superclass.initComponent.call(this);
		// Store references to the original handler and scope before nulling them.
		// This is done so that this class can control when the handler is called.
		// There are some cases where the hidden file input browse button doesn't completely cover the Ext.Button.
		// The handler shouldn't be called in these cases.  It should only be called if a new file is selected on the file system.  
		this.originalHandler = this.handler;
		this.originalScope = this.scope;
		this.handler = null;
		this.scope = null;
	},
	
	/**
	 * @see Ext.Button.onRender
	 */
	onRender: function(ct, position){
		Ext.ux.form.BrowseButton.superclass.onRender.call(this, ct, position); // render the Ext.Button
		this.buttonCt = this.el.child('.x-btn-center em');
		this.buttonCt.position('relative'); // this is important!
		var styleCfg = {
			position: 'absolute',
			overflow: 'hidden',
			top: '0px', // default
			left: '0px' // default
		};
		// browser specifics for better overlay tightness
		if (Ext.isIE) {
			Ext.apply(styleCfg, {
				left: '-3px',
				top: '-3px'
			});
		} else if (Ext.isGecko) {
			Ext.apply(styleCfg, {
				left: '-3px',
				top: '-3px'
			});
		} else if (Ext.isSafari) {
			Ext.apply(styleCfg, {
				left: '-4px',
				top: '-2px'
			});
		}
		this.clipEl = this.buttonCt.createChild({
			tag: 'div',
			style: styleCfg
		});
		this.setClipSize();
		this.clipEl.on({
			'mousemove': this.onButtonMouseMove,
			'mouseover': this.onButtonMouseMove,
			scope: this
		});
		
		this.floatEl = this.clipEl.createChild({
			tag: 'div',
			style: {
				position: 'absolute',
				width: this.FLOAT_EL_WIDTH + 'px',
				height: this.FLOAT_EL_HEIGHT + 'px',
				overflow: 'hidden'
			}
		});
		
		
		if (this.debug) {
			this.clipEl.applyStyles({
				'background-color': 'green'
			});
			this.floatEl.applyStyles({
				'background-color': 'red'
			});
		} else {
			this.clipEl.setOpacity(0.0);
		}
		
		// Cover cases where someone tabs to the button:
		// Listen to focus of the button so we can translate the focus to the input file el.
		var buttonEl = this.el.child(this.buttonSelector);
		buttonEl.on('focus', this.onButtonFocus, this);
		// In IE, it's possible to tab to the text portion of the input file el.  
		// We want to listen to keyevents so that if a space is pressed, we "click" the input file el.
		if (Ext.isIE) {
			this.el.on('keydown', this.onButtonKeyDown, this);
		}
		
		this.createInputFile();
	},
	
	
	/*
	 * Private helper methods:
	 */
	/**
	 * Sets the size of clipEl so that is covering as much of the button as possible.
	 * @private
	 */
	setClipSize: function(){
		if (this.clipEl) {
			var width = this.buttonCt.getWidth();
			var height = this.buttonCt.getHeight();
			// The button container can have a width and height of zero when it's rendered in a hidden panel.
			// This is most noticable when using a card layout, as the items are all rendered but hidden,
			// (unless deferredRender is set to true). 
			// In this case, the clip size can't be determined, so we attempt to set it later.
			// This check repeats until the button container has a size. 
			if (width === 0 || height === 0) {
				this.setClipSize.defer(100, this);
			} else {
				if (Ext.isIE) {
					width = width + 5;
					height = height + 5;
				} else if (Ext.isGecko) {
					width = width + 6;
					height = height + 6;
				} else if (Ext.isSafari) {
					width = width + 6;
					height = height + 6;
				}
				this.clipEl.setSize(width, height);
			}
		}
	},
	
	/**
	 * Creates the input file element and adds it to inputFileCt.
	 * The created input file elementis sized, positioned, and styled appropriately.
	 * Event handlers for the element are set up, and a tooltip is applied if defined in the original config.
	 * @private
	 */
	createInputFile: function(){
		// When an input file gets detached and set as the child of a different DOM element,
		// straggling <em> elements get left behind.  
		// I don't know why this happens but we delete any <em> elements we can find under the floatEl to prevent a memory leak.
		this.floatEl.select('em').each(function(el){
			el.remove();
		});
		this.inputFileEl = this.floatEl.createChild({
			tag: 'input',
			type: 'file',
			size: 1, // must be > 0. It's value doesn't really matter due to our masking div (inputFileCt).  
			name: this.inputFileName || Ext.id(this.el),
			tabindex: this.tabIndex,
			// Use the same pointer as an Ext.Button would use.  This doesn't work in Firefox.
			// This positioning right-aligns the input file to ensure that the "Browse" button is visible.
			style: {
				position: 'absolute',
				cursor: 'pointer',
				right: '0px',
				top: '0px'
			}
		});
		this.inputFileEl = this.inputFileEl.child('input') || this.inputFileEl;
		
		// setup events
		this.inputFileEl.on({
			'click': this.onInputFileClick,
			'change': this.onInputFileChange,
			'focus': this.onInputFileFocus,
			'select': this.onInputFileFocus,
			'blur': this.onInputFileBlur,
			scope: this
		});
		
		// add a tooltip
		if (this.tooltip) {
			if (typeof this.tooltip == 'object') {
				Ext.QuickTips.register(Ext.apply({
					target: this.inputFileEl
				}, this.tooltip));
			} else {
				this.inputFileEl.dom[this.tooltipType] = this.tooltip;
			}
		}
	},
	
	/**
	 * Redirecting focus to the input file element so the user can press space and select files.
	 * @param {Event} e focus event.
	 * @private
	 */
	onButtonFocus: function(e){
		if (this.inputFileEl) {
			this.inputFileEl.focus();
			e.stopEvent();
		}
	},
	
	/**
	 * Handler for the IE case where once can tab to the text box of an input file el.
	 * If the key is a space, we simply "click" the inputFileEl.
	 * @param {Event} e key event.
	 * @private
	 */
	onButtonKeyDown: function(e){
		if (this.inputFileEl && e.getKey() == Ext.EventObject.SPACE) {
			this.inputFileEl.dom.click();
			e.stopEvent();
		}
	},
	
	/**
	 * Handler when the cursor moves over the clipEl.
	 * The floatEl gets centered to the cursor location.
	 * @param {Event} e mouse event.
	 * @private
	 */
	onButtonMouseMove: function(e){
		var xy = e.getXY();
		xy[0] -= this.FLOAT_EL_WIDTH / 2;
		xy[1] -= this.FLOAT_EL_HEIGHT / 2;
		this.floatEl.setXY(xy);
	},
	
	/**
	 * Add the visual enhancement to the button when the input file recieves focus. 
	 * This is the tip for the user that now he/she can press space to select the file.
	 * @private
	 */
	onInputFileFocus: function(e){
		if (!this.isDisabled) {
			this.el.addClass("x-btn-over");
		}
	},
	
	/**
	 * Removes the visual enhancement from the button.
	 * @private
	 */
	onInputFileBlur: function(e){
		this.el.removeClass("x-btn-over");
	},
	
	/**
	 * Handler when inputFileEl's "Browse..." button is clicked.
	 * @param {Event} e click event.
	 * @private
	 */
	onInputFileClick: function(e){
		e.stopPropagation();
	},
	
	/**
	 * Handler when inputFileEl changes value (i.e. a new file is selected).
	 * @private
	 */
	onInputFileChange: function(){
		if (this.originalHandler) {
			this.originalHandler.call(this.originalScope, this);
		}
	},
	
	
	/*
	 * Public methods:
	 */
	/**
	 * Detaches the input file associated with this BrowseButton so that it can be used for other purposed (e.g. uplaoding).
	 * The returned input file has all listeners and tooltips applied to it by this class removed.
	 * @param {Boolean} whether to create a new input file element for this BrowseButton after detaching.
	 * True will prevent creation.  Defaults to false.
	 * @return {Ext.Element} the detached input file element.
	 */
	detachInputFile: function(noCreate){
		var result = this.inputFileEl;
		
		if (typeof this.tooltip == 'object') {
			Ext.QuickTips.unregister(this.inputFileEl);
		} else {
			this.inputFileEl.dom[this.tooltipType] = null;
		}
		this.inputFileEl.removeAllListeners();
		this.inputFileEl = null;
		
		if (!noCreate) {
			this.createInputFile();
		}
		return result;
	},
	
	/**
	 * @return {Ext.Element} the input file element attached to this BrowseButton.
	 */
	getInputFile: function(){
		return this.inputFileEl;
	},
	
	/**
	 * @see Ext.Button.disable
	 */
	disable: function(){
		Ext.ux.form.BrowseButton.superclass.disable.call(this);
		this.inputFileEl.dom.disabled = true;
	},
	
	/**
	 * @see Ext.Button.enable
	 */
	enable: function(){
		Ext.ux.form.BrowseButton.superclass.enable.call(this);
		this.inputFileEl.dom.disabled = false;
	}
});

Ext.reg('browsebutton', Ext.ux.form.BrowseButton);


/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/filetree/js/Ext.ux.form.BrowseButton.js  ]*/

// vim: ts=4:sw=4:nu:fdc=4:nospell
/**
 * Ext.ux.FileUploader
 *
 * @author  Ing. Jozef Sakáloš
 * @version $Id: Ext.ux.FileUploader.js 302 2008-08-03 20:57:33Z jozo $
 * @date    15. March 2008
 *
 * @license Ext.ux.FileUploader is licensed under the terms of
 * the Open Source LGPL 3.0 license.  Commercial use is permitted to the extent
 * that the code/component(s) do NOT become part of another Open Source or Commercially
 * licensed development library or toolkit without explicit permission.
 * 
 * License details: http://www.gnu.org/licenses/lgpl.html
 */

/*global Ext */

/**
 * @class Ext.ux.FileUploader
 * @extends Ext.util.Observable
 * @constructor
 */
Ext.ux.FileUploader = function(config) {
	Ext.apply(this, config);

	// call parent
	Ext.ux.FileUploader.superclass.constructor.apply(this, arguments);

	// add events
	// {{{
	this.addEvents(
		/**
		 * @event beforeallstart
		 * Fires before an upload (of all files) is started. Return false to cancel the event.
		 * @param {Ext.ux.FileUploader} this
		 */
		 'beforeallstart'
		/**
		 * @event allfinished
		 * Fires after upload (of all files) is finished
		 * @param {Ext.ux.FileUploader} this
		 */
		,'allfinished'
		/**
		 * @event beforefilestart
		 * Fires before the file upload is started. Return false to cancel the event.
		 * Fires only when singleUpload = false
		 * @param {Ext.ux.FileUploader} this
		 * @param {Ext.data.Record} record upload of which is being started
		 */
		,'beforefilestart'
		/**
		 * @event filefinished
		 * Fires when file finished uploading.
		 * Fires only when singleUpload = false
		 * @param {Ext.ux.FileUploader} this
		 * @param {Ext.data.Record} record upload of which has finished
		 */
		,'filefinished'
		/**
		 * @event progress
		 * Fires when progress has been updated
		 * @param {Ext.ux.FileUploader} this
		 * @param {Object} data Progress data object
		 * @param {Ext.data.Record} record Only if singleUpload = false
		 */
		,'progress'
	);
	// }}}

}; // eo constructor

Ext.extend(Ext.ux.FileUploader, Ext.util.Observable, {
	
	// configuration options
	// {{{
	/**
	 * @cfg {Object} baseParams baseParams are sent to server in each request.
	 */
	 baseParams:{cmd:'upload',dir:'.'}

	/**
	 * @cfg {Boolean} concurrent true to start all requests upon upload start, false to start
	 * the next request only if previous one has been completed (or failed). Applicable only if
	 * singleUpload = false
	 */
	,concurrent:true

	/**
	 * @cfg {Boolean} enableProgress true to enable querying server for progress information
	 */
	,enableProgress:true

	/**
	 * @cfg {String} jsonErrorText Text to use for json error
	 */
	,jsonErrorText:'Cannot decode JSON object'

	/**
	 * @cfg {Number} Maximum client file size in bytes
	 */
	,maxFileSize:524288

	/**
	 * @cfg {String} progressIdName Name to give hidden field for upload progress identificator
	 */
	,progressIdName:'UPLOAD_IDENTIFIER'

	/**
	 * @cfg {Number} progressInterval How often (in ms) is progress requested from server
	 */
	,progressInterval:2000

	/**
	 * @cfg {String} progressUrl URL to request upload progress from
	 */
	,progressUrl:'progress.php'

	/**
	 * @cfg {Object} progressMap Mapping of received progress fields to store progress fields
	 */
	,progressMap:{
		 bytes_total:'bytesTotal'
		,bytes_uploaded:'bytesUploaded'
		,est_sec:'estSec'
		,files_uploaded:'filesUploaded'
		,speed_average:'speedAverage'
		,speed_last:'speedLast'
		,time_last:'timeLast'
		,time_start:'timeStart'
	}
	/**
	 * @cfg {Boolean} singleUpload true to upload files in one form, false to upload one by one
	 */
	,singleUpload:false
	
	/**
	 * @cfg {Ext.data.Store} store Mandatory. Store that holds files to upload
	 */

	/**
	 * @cfg {String} unknownErrorText Text to use for unknow error
	 */
	,unknownErrorText:'Unknown error'

	/**
	 * @cfg {String} url Mandatory. URL to upload to
	 */

	// }}}

	// private
	// {{{
	/**
	 * uploads in progress count
	 * @private
	 */
	,upCount:0
	// }}}

	// methods
	// {{{
	/**
	 * creates form to use for upload.
	 * @private
	 * @return {Ext.Element} form
	 */
	,createForm:function(record) {
		var progressId = parseInt(Math.random() * 1e10, 10);
		var form = Ext.getBody().createChild({
			 tag:'form'
			,action:this.url
			,method:'post'
			,cls:'x-hidden'
			,id:Ext.id()
			,cn:[{
				 tag:'input'
				,type:'hidden'
				,name:'APC_UPLOAD_PROGRESS'
				,value:progressId
			},{
				 tag:'input'
				,type:'hidden'
				,name:this.progressIdName
				,value:progressId
			},{
				 tag:'input'
				,type:'hidden'
				,name:'MAX_FILE_SIZE'
				,value:this.maxFileSize
			}]
		});
		if(record) {
			record.set('form', form);
			record.set('progressId', progressId);
		}
		else {
			this.progressId = progressId;
		}
		return form;

	} // eo function createForm
	// }}}
	// {{{
	,deleteForm:function(form, record) {
		form.remove();
		if(record) {
			record.set('form', null);
		}
	} // eo function deleteForm
	// }}}
	// {{{
	/**
	 * Fires event(s) on upload finish/error
	 * @private
	 */
	,fireFinishEvents:function(options) {
		if(true !== this.eventsSuspended && !this.singleUpload) {
			this.fireEvent('filefinished', this, options && options.record);
		}
		if(true !== this.eventsSuspended && 0 === this.upCount) {
			this.stopProgress();
			this.fireEvent('allfinished', this);
		}
	} // eo function fireFinishEvents
	// }}}
	// {{{
	/**
	 * Geg the iframe identified by record
	 * @private
	 * @param {Ext.data.Record} record
	 * @return {Ext.Element} iframe or null if not found
	 */
	,getIframe:function(record) {
		var iframe = null;
		var form = record.get('form');
		if(form && form.dom && form.dom.target) {
			iframe = Ext.get(form.dom.target);
		}
		return iframe;
	} // eo function getIframe
	// }}}
	// {{{
	/**
	 * returns options for Ajax upload request
	 * @private
	 * @param {Ext.data.Record} record
	 * @param {Object} params params to add
	 */
	,getOptions:function(record, params) {
		var o = {
			 url:this.url
			,method:'post'
			,isUpload:true
			,scope:this
			,callback:this.uploadCallback
			,record:record
			,params:this.getParams(record, params)
		};
		return o;
	} // eo function getOptions
	// }}}
	// {{{
	/**
	 * get params to use for request
	 * @private
	 * @return {Object} params
	 */
	,getParams:function(record, params) {
		var p = {path:this.path};
		Ext.apply(p, this.baseParams || {}, params || {});
		return p;
	}
	// }}}
	// {{{
	/**
	 * processes success response
	 * @private
	 * @param {Object} options options the request was called with
	 * @param {Object} response request response object
	 * @param {Object} o decoded response.responseText
	 */
	,processSuccess:function(options, response, o) {
		var record = false;

		// all files uploadded ok
		if(this.singleUpload) {
			this.store.each(function(r) {
				r.set('state', 'done');
				r.set('error', '');
				r.commit();
			});
		}
		else {
			record = options.record;
			record.set('state', 'done');
			record.set('error', '');
			record.commit();
		}

		this.deleteForm(options.form, record);

	} // eo processSuccess
	// }}}
	// {{{
	/**
	 * processes failure response
	 * @private
	 * @param {Object} options options the request was called with
	 * @param {Object} response request response object
	 * @param {String/Object} error Error text or JSON decoded object. Optional.
	 */
	,processFailure:function(options, response, error) {
		var record = options.record;
		var records;

		// singleUpload - all files uploaded in one form
		if(this.singleUpload) {
			// some files may have been successful
			records = this.store.queryBy(function(r){
				var state = r.get('state');
				return 'done' !== state && 'uploading' !== state;
			});
			records.each(function(record) {
				var e = error.errors ? error.errors[record.id] : this.unknownErrorText;
				if(e) {
					record.set('state', 'failed');
					record.set('error', e);
					Ext.getBody().appendChild(record.get('input'));
				}
				else {
					record.set('state', 'done');
					record.set('error', '');
				}
				record.commit();
			}, this);

			this.deleteForm(options.form);
		}
		// multipleUpload - each file uploaded in it's own form
		else {
			if(error && 'object' === Ext.type(error)) {
				record.set('error', error.errors && error.errors[record.id] ? error.errors[record.id] : this.unknownErrorText);
			}
			else if(error) {
				record.set('error', error);
			}
			else if(response && response.responseText) {
				record.set('error', response.responseText);
			}
			else {
				record.set('error', this.unknownErrorText);
			}
			record.set('state', 'failed');
			record.commit();
		}
	} // eof processFailure
	// }}}
	// {{{
	/**
	 * Delayed task callback
	 */
	,requestProgress:function() {
		var records, p;
		var o = {
			 url:this.progressUrl
			,method:'post'
			,params:{}
			,scope:this
			,callback:function(options, success, response) {
				var o;
				if(true !== success) {
					return;
				}
				try {
					o = Ext.decode(response.responseText);
				}
				catch(e) {
					return;
				}
				if('object' !== Ext.type(o) || true !== o.success) {
					return;
				}

				if(this.singleUpload) {
					this.progress = {};
					for(p in o) {
						if(this.progressMap[p]) {
							this.progress[this.progressMap[p]] = parseInt(o[p], 10);
						}
					}
					if(true !== this.eventsSuspended) {
						this.fireEvent('progress', this, this.progress);
					}

				}
				else {
					for(p in o) {
						if(this.progressMap[p] && options.record) {
							options.record.set(this.progressMap[p], parseInt(o[p], 10));
						}
					}
					if(options.record) {
						options.record.commit();
						if(true !== this.eventsSuspended) {
							this.fireEvent('progress', this, options.record.data, options.record);
						}
					}
				}
				this.progressTask.delay(this.progressInterval);
			}
		};
		if(this.singleUpload) {
			o.params[this.progressIdName] = this.progressId;
			o.params.APC_UPLOAD_PROGRESS = this.progressId;
			Ext.Ajax.request(o);
		}
		else {
			records = this.store.query('state', 'uploading');
			records.each(function(r) {
				o.params[this.progressIdName] = r.get('progressId');
				o.params.APC_UPLOAD_PROGRESS = o.params[this.progressIdName];
				o.record = r;
				(function() {
					Ext.Ajax.request(o);
				}).defer(250);
			}, this);
		}
	} // eo function requestProgress
	// }}}
	// {{{
	/**
	 * path setter
	 * @private
	 */
	,setPath:function(path) {
		this.path = path;
	} // eo setPath
	// }}}
	// {{{
	/**
	 * url setter
	 * @private
	 */
	,setUrl:function(url) {
		this.url = url;
	} // eo setUrl
	// }}}
	// {{{
	/**
	 * Starts progress fetching from server
	 * @private
	 */
	,startProgress:function() {
		if(!this.progressTask) {
			this.progressTask = new Ext.util.DelayedTask(this.requestProgress, this);
		}
		this.progressTask.delay.defer(this.progressInterval / 2, this.progressTask, [this.progressInterval]);
	} // eo function startProgress
	// }}}
	// {{{
	/**
	 * Stops progress fetching from server
	 * @private
	 */
	,stopProgress:function() {
		if(this.progressTask) {
			this.progressTask.cancel();
		}
	} // eo function stopProgress
	// }}}
	// {{{
	/**
	 * Stops all currently running uploads
	 */
	,stopAll:function() {
		var records = this.store.query('state', 'uploading');
		records.each(this.stopUpload, this);
	} // eo function stopAll
	// }}}
	// {{{
	/**
	 * Stops currently running upload
	 * @param {Ext.data.Record} record Optional, if not set singleUpload = true is assumed
	 * and the global stop is initiated
	 */
	,stopUpload:function(record) {
		// single abord
		var iframe = false;
		if(record) {
			iframe = this.getIframe(record);
			this.stopIframe(iframe);
			this.upCount--;
			this.upCount = 0 > this.upCount ? 0 : this.upCount;
			record.set('state', 'stopped');
			this.fireFinishEvents({record:record});
		}
		// all abort
		else if(this.form) {
			iframe = Ext.fly(this.form.dom.target);
			this.stopIframe(iframe);
			this.upCount = 0;
			this.fireFinishEvents();
		}

	} // eo function abortUpload
	// }}}
	// {{{
	/**
	 * Stops uploading in hidden iframe
	 * @private
	 * @param {Ext.Element} iframe
	 */
	,stopIframe:function(iframe) {
		if(iframe) {
			try {
				iframe.dom.contentWindow.stop();
				iframe.remove.defer(250, iframe);
			}
			catch(e){}
		}
	} // eo function stopIframe
	// }}}
	// {{{
	/**
	 * Main public interface function. Preforms the upload
	 */
	,upload:function() {
		
		var records = this.store.queryBy(function(r){return 'done' !== r.get('state');});
		if(!records.getCount()) {
			return;
		}

		// fire beforeallstart event
		if(true !== this.eventsSuspended && false === this.fireEvent('beforeallstart', this)) {
			return;
		}
		if(this.singleUpload) {
			this.uploadSingle();
		}
		else {
			records.each(this.uploadFile, this);
		}
		
		if(true === this.enableProgress) {
			this.startProgress();
		}

	} // eo function upload
	// }}}
	// {{{
	/**
	 * called for both success and failure. Does nearly nothing
	 * @private
	 * but dispatches processing to processSuccess and processFailure functions
	 */
	,uploadCallback:function(options, success, response) {

		var o;
		this.upCount--;
		this.form = false;

		// process ajax success
		if(true === success) {
			try {
				o = Ext.decode(response.responseText);
			}
			catch(e) {
				this.processFailure(options, response, this.jsonErrorText);
				this.fireFinishEvents(options);
				return;
			}
			// process command success
			if(true === o.success) {
				this.processSuccess(options, response, o);
			}
			// process command failure
			else {
				this.processFailure(options, response, o);
			}
		}
		// process ajax failure
		else {
			this.processFailure(options, response);
		}

		this.fireFinishEvents(options);

	} // eo function uploadCallback
	// }}}
	// {{{
	/**
	 * Uploads one file
	 * @param {Ext.data.Record} record
	 * @param {Object} params Optional. Additional params to use in request.
	 */
	,uploadFile:function(record, params) {
		// fire beforestart event
		if(true !== this.eventsSuspended && false === this.fireEvent('beforefilestart', this, record)) {
			return;
		}

		// create form for upload
		var form = this.createForm(record);

		// append input to the form
		var inp = record.get('input');
		inp.set({name:inp.id});
		form.appendChild(inp);

		// get params for request
		var o = this.getOptions(record, params);
		o.form = form;

		// set state 
		record.set('state', 'uploading');
		record.set('pctComplete', 0);

		// increment active uploads count
		this.upCount++;

		// request upload
		Ext.Ajax.request(o);

		// todo:delete after devel
		this.getIframe.defer(100, this, [record]);

	} // eo function uploadFile
	// }}}
	// {{{
	/**
	 * Uploads all files in single request
	 */
	,uploadSingle:function() {

		// get records to upload
		var records = this.store.queryBy(function(r){return 'done' !== r.get('state');});
		if(!records.getCount()) {
			return;
		}

		// create form and append inputs to it
		var form = this.createForm();
		records.each(function(record) {
			var inp = record.get('input');
			inp.set({name:inp.id});
			form.appendChild(inp);
			record.set('state', 'uploading');
		}, this);

		// create options for request
		var o = this.getOptions();
		o.form = form;

		// save form for stop
		this.form = form;

		// increment active uploads counter
		this.upCount++;

		// request upload
		Ext.Ajax.request(o);
	
	} // eo function uploadSingle
	// }}}

}); // eo extend

// register xtype
Ext.reg('fileuploader', Ext.ux.FileUploader);

 // eof

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/filetree/js/Ext.ux.FileUploader.js  ]*/

// vim: ts=4:sw=4:nu:fdc=4:nospell
/**
 * Ext.ux.form.UploadPanel
 *
 * @author  Ing. Jozef Sakáloš
 * @version $Id: Ext.ux.UploadPanel.js 310 2008-08-14 17:23:48Z jozo $
 * @date    13. March 2008
 *
 * @license Ext.ux.form.UploadPanel is licensed under the terms of
 * the Open Source LGPL 3.0 license.  Commercial use is permitted to the extent
 * that the code/component(s) do NOT become part of another Open Source or Commercially
 * licensed development library or toolkit without explicit permission.
 * 
 * License details: http://www.gnu.org/licenses/lgpl.html
 */

/*global Ext */

/**
 * @class Ext.ux.UploadPanel
 * @extends Ext.Panel
 */
Ext.ux.UploadPanel = Ext.extend(Ext.Panel, {

	// configuration options overridable from outside
	// {{{
	/**
	 * @cfg {String} addIconCls icon class for add (file browse) button
	 */
	 addIconCls:'icon-plus'

	/**
	 * @cfg {String} addText Text on Add button
	 */
	,addText:'Add'

	/**
	 * @cfg {Object} baseParams This object is not used directly by FileTreePanel but it is
	 * propagated to lower level objects instead. Included here for convenience.
	 */

	/**
	 * @cfg {String} bodyStyle style to use for panel body
	 */
	,bodyStyle:'padding:2px'

	/**
	 * @cfg {String} buttonsAt Where buttons are placed. Valid values are tbar, bbar, body (defaults to 'tbar')
	 */
	,buttonsAt:'tbar'

	/**
	 * @cfg {String} clickRemoveText
	 */
	,clickRemoveText:'Click to remove'

	/**
	 * @cfg {String} clickStopText
	 */
	,clickStopText:'Click to stop'

	/**
	 * @cfg {String} emptyText empty text for dataview
	 */
	,emptyText:'No files'

	/**
	 * @cfg {Boolean} enableProgress true to enable querying server for progress information
	 * Passed to underlying uploader. Included here for convenience.
	 */
	,enableProgress:true

	/**
	 * @cfg {String} errorText
	 */
	,errorText:'Error'

	/**
	 * @cfg {String} fileCls class prefix to use for file type classes
	 */
	,fileCls:'file'

	/**
	 * @cfg {String} fileQueuedText File upload status text
	 */
	,fileQueuedText:'File <b>{0}</b> is queued for upload' 

	/**
	 * @cfg {String} fileDoneText File upload status text
	 */
	,fileDoneText:'File <b>{0}</b> has been successfully uploaded'

	/**
	 * @cfg {String} fileFailedText File upload status text
	 */
	,fileFailedText:'File <b>{0}</b> failed to upload'

	/**
	 * @cfg {String} fileStoppedText File upload status text
	 */
	,fileStoppedText:'File <b>{0}</b> stopped by user'

	/**
	 * @cfg {String} fileUploadingText File upload status text
	 */
	,fileUploadingText:'Uploading file <b>{0}</b>'

	/**
	 * @cfg {Number} maxFileSize Maximum upload file size in bytes
	 * This config property is propagated down to uploader for convenience
	 */
	,maxFileSize:524288

	/**
	 * @cfg {Number} Maximum file name length for short file names
	 */
	,maxLength:18

	/**
	 * @cfg {String} removeAllIconCls iconClass to use for Remove All button (defaults to 'icon-cross'
	 */
	,removeAllIconCls:'icon-cross'

	/**
	 * @cfg {String} removeAllText text to use for Remove All button tooltip
	 */
	,removeAllText:'Remove All'

	/**
	 * @cfg {String} removeIconCls icon class to use for remove file icon
	 */
	,removeIconCls:'icon-minus'

	/**
	 * @cfg {String} removeText Remove text
	 */
	,removeText:'Remove'

	/**
	 * @cfg {String} selectedClass class for selected item of DataView
	 */
	,selectedClass:'ux-up-item-selected'

	/**
	 * @cfg {Boolean} singleUpload true to upload files in one form, false to upload one by one
	 * This config property is propagated down to uploader for convenience
	 */
	,singleUpload:false

	/**
	 * @cfg {String} stopAllText
	 */
	,stopAllText:'Stop All'

	/** 
	 * @cfg {String} stopIconCls icon class to use for stop
	 */
	,stopIconCls:'icon-stop'

	/**
	 * @cfg {String/Ext.XTemplate} tpl Template for DataView.
	 */

	/**
	 * @cfg {String} uploadText Upload text
	 */
	,uploadText:'Upload'

	/**
	 * @cfg {String} uploadIconCls icon class to use for upload button
	 */
	,uploadIconCls:'icon-upload'

	/**
	 * @cfg {String} workingIconCls iconClass to use for busy indicator
	 */
	,workingIconCls:'icon-working'

	// }}}

	// overrides
	// {{{
	,initComponent:function() {

		// {{{
		// create buttons
		// add (file browse button) configuration
		var addCfg = {
			 xtype:'browsebutton'
			,text:this.addText + '...'
			,iconCls:this.addIconCls
			,scope:this
			,handler:this.onAddFile
		};

		// upload button configuration
		var upCfg = {
			 xtype:'button'
			,iconCls:this.uploadIconCls
			,text:this.uploadText
			,scope:this
			,handler:this.onUpload
			,disabled:true
		};

		// remove all button configuration
		var removeAllCfg = {
			 xtype:'button'
			,iconCls:this.removeAllIconCls
			,tooltip:this.removeAllText
			,scope:this
			,handler:this.onRemoveAllClick
			,disabled:true
		};

		// todo: either to cancel buttons in body or implement it
		if('body' !== this.buttonsAt) {
			this[this.buttonsAt] = [addCfg, upCfg, '->', removeAllCfg];
		}
		// }}}
		// {{{
		// create store
		// fields for record
		var fields = [
			 {name:'id', type:'text', system:true}
			,{name:'shortName', type:'text', system:true}
			,{name:'fileName', type:'text', system:true}
			,{name:'filePath', type:'text', system:true}
			,{name:'fileCls', type:'text', system:true}
			,{name:'input', system:true}
			,{name:'form', system:true}
			,{name:'state', type:'text', system:true}
			,{name:'error', type:'text', system:true}
			,{name:'progressId', type:'int', system:true}
			,{name:'bytesTotal', type:'int', system:true}
			,{name:'bytesUploaded', type:'int', system:true}
			,{name:'estSec', type:'int', system:true}
			,{name:'filesUploaded', type:'int', system:true}
			,{name:'speedAverage', type:'int', system:true}
			,{name:'speedLast', type:'int', system:true}
			,{name:'timeLast', type:'int', system:true}
			,{name:'timeStart', type:'int', system:true}
			,{name:'pctComplete', type:'int', system:true}
		];

		// add custom fields if passed
		if(Ext.isArray(this.customFields)) {
			fields.push(this.customFields);
		}

		// create store
		this.store = new Ext.data.SimpleStore({
			 id:0
			,fields:fields
			,data:[]
		});
		// }}}
		// {{{
		// create view
		Ext.apply(this, {
			items:[{
				 xtype:'dataview'
				,itemSelector:'div.ux-up-item'
				,store:this.store
				,selectedClass:this.selectedClass
				,singleSelect:true
				,emptyText:this.emptyText
				,tpl: this.tpl || new Ext.XTemplate(
					  '<tpl for=".">'
					+ '<div class="ux-up-item">'
//					+ '<div class="ux-up-indicator">&#160;</div>'
					+ '<div class="ux-up-icon-file {fileCls}">&#160;</div>'
					+ '<div class="ux-up-text x-unselectable" qtip="{fileName}">{shortName}</div>'
					+ '<div id="remove-{[values.input.id]}" class="ux-up-icon-state ux-up-icon-{state}"'
					+ 'qtip="{[this.scope.getQtip(values)]}">&#160;</div>'
					+ '</div>'
					+ '</tpl>'
					, {scope:this}
				)
				,listeners:{click:{scope:this, fn:this.onViewClick}}

			}]
		});
		// }}}

		// call parent
		Ext.ux.UploadPanel.superclass.initComponent.apply(this, arguments);

		// save useful references
		this.view = this.items.itemAt(0);

		// {{{
		// add events
		this.addEvents(
			/**
			 * Fires before the file is added to store. Return false to cancel the add
			 * @event beforefileadd
			 * @param {Ext.ux.UploadPanel} this
			 * @param {Ext.Element} input (type=file) being added
			 */
			'beforefileadd'
			/**
			 * Fires after the file is added to the store
			 * @event fileadd
			 * @param {Ext.ux.UploadPanel} this
			 * @param {Ext.data.Store} store
			 * @param {Ext.data.Record} Record (containing the input) that has been added to the store
			 */
			,'fileadd'
			/**
			 * Fires before the file is removed from the store. Return false to cancel the remove
			 * @event beforefileremove
			 * @param {Ext.ux.UploadPanel} this
			 * @param {Ext.data.Store} store
			 * @param {Ext.data.Record} Record (containing the input) that is being removed from the store
			 */
			,'beforefileremove'
			/**
			 * Fires after the record (file) has been removed from the store
			 * @event fileremove
			 * @param {Ext.ux.UploadPanel} this
			 * @param {Ext.data.Store} store
			 */
			,'fileremove'
			/**
			 * Fires before all files are removed from the store (queue). Return false to cancel the clear.
			 * Events for individual files being removed are suspended while clearing the queue.
			 * @event beforequeueclear
			 * @param {Ext.ux.UploadPanel} this
			 * @param {Ext.data.Store} store
			 */
			,'beforequeueclear'
			/**
			 * Fires after the store (queue) has been cleared
			 * Events for individual files being removed are suspended while clearing the queue.
			 * @event queueclear
			 * @param {Ext.ux.UploadPanel} this
			 * @param {Ext.data.Store} store
			 */
			,'queueclear'
			/**
			 * Fires after the upload button is clicked but before any upload is started
			 * Return false to cancel the event
			 * @param {Ext.ux.UploadPanel} this
			 */
			,'beforeupload'
		);
		// }}}
		// {{{
		// relay view events
		this.relayEvents(this.view, [
			 'beforeclick'
			,'beforeselect'
			,'click'
			,'containerclick'
			,'contextmenu'
			,'dblclick'
			,'selectionchange'
		]);
		// }}}

		// create uploader
		var config = {
			 store:this.store
			,singleUpload:this.singleUpload
			,maxFileSize:this.maxFileSize
			,enableProgress:this.enableProgress
			,url:this.url
			,path:this.path
		};
		if(this.baseParams) {
			config.baseParams = this.baseParams;
		}
		this.uploader = new Ext.ux.FileUploader(config);

		// relay uploader events
		this.relayEvents(this.uploader, [
			 'beforeallstart'
			,'allfinished'
			,'progress'
		]);

		// install event handlers
		this.on({
			 beforeallstart:{scope:this, fn:function() {
			 	this.uploading = true;
				this.updateButtons();
			}}
			,allfinished:{scope:this, fn:function() {
				this.uploading = false;
				this.updateButtons();
			}}
			,progress:{fn:this.onProgress.createDelegate(this)}
		});
	} // eo function initComponent
	// }}}
	// {{{
	/**
	 * onRender override, saves references to buttons
	 * @private
	 */
	,onRender:function() {
		// call parent
		Ext.ux.UploadPanel.superclass.onRender.apply(this, arguments);

		// save useful references
		var tb = 'tbar' === this.buttonsAt ? this.getTopToolbar() : this.getBottomToolbar();
		this.addBtn = Ext.getCmp(tb.items.first().id);
		this.uploadBtn = Ext.getCmp(tb.items.itemAt(1).id);
		this.removeAllBtn = Ext.getCmp(tb.items.last().id);
	} // eo function onRender
	// }}}

	// added methods
	// {{{
	/**
	 * called by XTemplate to get qtip depending on state
	 * @private
	 * @param {Object} values XTemplate values
	 */
	,getQtip:function(values) {
		var qtip = '';
		switch(values.state) {
			case 'queued':
				qtip = String.format(this.fileQueuedText, values.fileName);
				qtip += '<br>' + this.clickRemoveText;
			break;

			case 'uploading':
				qtip = String.format(this.fileUploadingText, values.fileName);
				qtip += '<br>' + values.pctComplete + '% done';
				qtip += '<br>' + this.clickStopText;
			break;

			case 'done':
				qtip = String.format(this.fileDoneText, values.fileName);
				qtip += '<br>' + this.clickRemoveText;
			break;

			case 'failed':
				qtip = String.format(this.fileFailedText, values.fileName);
				qtip += '<br>' + this.errorText + ':' + values.error;
				qtip += '<br>' + this.clickRemoveText;
			break;

			case 'stopped':
				qtip = String.format(this.fileStoppedText, values.fileName);
				qtip += '<br>' + this.clickRemoveText;
			break;
		}
		return qtip;
	} // eo function getQtip
	// }}}
	// {{{
	/**
	 * get file name
	 * @private
	 * @param {Ext.Element} inp Input element containing the full file path
	 * @return {String}
	 */
	,getFileName:function(inp) {
		return inp.getValue().split(/[\/\\]/).pop();
	} // eo function getFileName
	// }}}
	// {{{
	/**
	 * get file path (excluding the file name)
	 * @private
	 * @param {Ext.Element} inp Input element containing the full file path
	 * @return {String}
	 */
	,getFilePath:function(inp) {
		return inp.getValue().replace(/[^\/\\]+$/,'');
	} // eo function getFilePath
	// }}}
	// {{{
	/**
	 * returns file class based on name extension
	 * @private
	 * @param {String} name File name to get class of
	 * @return {String} class to use for file type icon
	 */
	,getFileCls: function(name) {
		var atmp = name.split('.');
		if(1 === atmp.length) {
			return this.fileCls;
		}
		else {
			return this.fileCls + '-' + atmp.pop().toLowerCase();
		}
	}
	// }}}
	// {{{
	/**
	 * called when file is added - adds file to store
	 * @private
	 * @param {Ext.ux.BrowseButton}
	 */
	,onAddFile:function(bb) {
		if(true !== this.eventsSuspended && false === this.fireEvent('beforefileadd', this, bb.getInputFile())) {
			return;
		}
		var inp = bb.detachInputFile();
		inp.addClass('x-hidden');
		var fileName = this.getFileName(inp);

		// create new record and add it to store
		var rec = new this.store.recordType({
			 input:inp
			,fileName:fileName
			,filePath:this.getFilePath(inp)
			,shortName: Ext.util.Format.ellipsis(fileName, this.maxLength)
			,fileCls:this.getFileCls(fileName)
			,state:'queued'
		}, inp.id);
		rec.commit();
		this.store.add(rec);

		this.syncShadow();

		this.uploadBtn.enable();
		this.removeAllBtn.enable();

		if(true !== this.eventsSuspended) {
			this.fireEvent('fileadd', this, this.store, rec);
		}

	} // eo onAddFile
	// }}}
	// {{{
	/**
	 * destroys child components
	 * @private
	 */
	,onDestroy:function() {

		// destroy uploader
		if(this.uploader) {
			this.uploader.stopAll();
			this.uploader.purgeListeners();
			this.uploader = null;
		}

		// destroy view
		if(this.view) {
			this.view.purgeListeners();
			this.view.destroy();
			this.view = null;
		}

		// destroy store
		if(this.store) {
			this.store.purgeListeners();
			this.store.destroy();
			this.store = null;
		}

	} // eo function onDestroy
	// }}}
	// {{{
	/**
	 * progress event handler
	 * @private
	 * @param {Ext.ux.FileUploader} uploader
	 * @param {Object} data progress data
	 * @param {Ext.data.Record} record
	 */
	,onProgress:function(uploader, data, record) {
		var bytesTotal, bytesUploaded, pctComplete, state, idx, item, width, pgWidth;
		if(record) {
			state = record.get('state');
			bytesTotal = record.get('bytesTotal') || 1;
			bytesUploaded = record.get('bytesUploaded') || 0;
			if('uploading' === state) {
				pctComplete = Math.round(1000 * bytesUploaded/bytesTotal) / 10;
			}
			else if('done' === state) {
				pctComplete = 100;
			}
			else {
				pctComplete = 0;
			}
			record.set('pctComplete', pctComplete);

			idx = this.store.indexOf(record);
			item = Ext.get(this.view.getNode(idx));
			if(item) {
				width = item.getWidth();
				item.applyStyles({'background-position':width * pctComplete / 100 + 'px'});
			}
		}
	} // eo function onProgress
	// }}}
	// {{{
	/**
	 * called when file remove icon is clicked - performs the remove
	 * @private
	 * @param {Ext.data.Record}
	 */
	,onRemoveFile:function(record) {
		if(true !== this.eventsSuspended && false === this.fireEvent('beforefileremove', this, this.store, record)) {
			return;
		}

		// remove DOM elements
		var inp = record.get('input');
		var wrap = inp.up('em');
		inp.remove();
		if(wrap) {
			wrap.remove();
		}

		// remove record from store
		this.store.remove(record);

		var count = this.store.getCount();
		this.uploadBtn.setDisabled(!count);
		this.removeAllBtn.setDisabled(!count);

		if(true !== this.eventsSuspended) {
			this.fireEvent('fileremove', this, this.store);
			this.syncShadow();
		}
	} // eo function onRemoveFile
	// }}}
	// {{{
	/**
	 * Remove All/Stop All button click handler
	 * @private
	 */
	,onRemoveAllClick:function(btn) {
		if(true === this.uploading) {
			this.stopAll();
		}
		else {
			this.removeAll();
		}
	} // eo function onRemoveAllClick

	,stopAll:function() {
		this.uploader.stopAll();
	} // eo function stopAll
	// }}}
	// {{{
	/**
	 * DataView click handler
	 * @private
	 */
	,onViewClick:function(view, index, node, e) {
		var t = e.getTarget('div:any(.ux-up-icon-queued|.ux-up-icon-failed|.ux-up-icon-done|.ux-up-icon-stopped)');
		if(t) {
			this.onRemoveFile(this.store.getAt(index));
		}
		t = e.getTarget('div.ux-up-icon-uploading');
		if(t) {
			this.uploader.stopUpload(this.store.getAt(index));
		}
	} // eo function onViewClick
	// }}}
	// {{{
	/**
	 * tells uploader to upload
	 * @private
	 */
	,onUpload:function() {
		if(true !== this.eventsSuspended && false === this.fireEvent('beforeupload', this)) {
			return false;
		}
		this.uploader.upload();
	} // eo function onUpload
	// }}}
	// {{{
	/**
	 * url setter
	 */
	,setUrl:function(url) {
		this.url = url;
		this.uploader.setUrl(url);
	} // eo function setUrl
	// }}}
	// {{{
	/**
	 * path setter
	 */
	,setPath:function(path) {
		this.uploader.setPath(path);
	} // eo function setPath
	// }}}
	// {{{
	/**
	 * Updates buttons states depending on uploading state
	 * @private
	 */
	,updateButtons:function() {
		if(true === this.uploading) {
			this.addBtn.disable();
			this.uploadBtn.disable();
			this.removeAllBtn.setIconClass(this.stopIconCls);
			this.removeAllBtn.getEl().child(this.removeAllBtn.buttonSelector).dom[this.removeAllBtn.tooltipType] = this.stopAllText;
		}
		else {
			this.addBtn.enable();
			this.uploadBtn.enable();
			this.removeAllBtn.setIconClass(this.removeAllIconCls);
			this.removeAllBtn.getEl().child(this.removeAllBtn.buttonSelector).dom[this.removeAllBtn.tooltipType] = this.removeAllText;
		}
	} // eo function updateButtons
	// }}}
	// {{{
	/**
	 * Removes all files from store and destroys file inputs
	 */
	,removeAll:function() {
		var suspendState = this.eventsSuspended;
		if(false !== this.eventsSuspended && false === this.fireEvent('beforequeueclear', this, this.store)) {
			return false;
		}
		this.suspendEvents();

		this.store.each(this.onRemoveFile, this);

		this.eventsSuspended = suspendState;
		if(true !== this.eventsSuspended) {
			this.fireEvent('queueclear', this, this.store);
		}
		this.syncShadow();
	} // eo function removeAll
	// }}}
	// {{{
	/**
	 * synchronize context menu shadow if we're in contextmenu
	 * @private
	 */
	,syncShadow:function() {
		if(this.contextmenu && this.contextmenu.shadow) {
			this.contextmenu.getEl().shadow.show(this.contextmenu.getEl());
		}
	} // eo function syncShadow
	// }}}

}); // eo extend

// register xtype
Ext.reg('uploadpanel', Ext.ux.UploadPanel);

// eof

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/filetree/js/Ext.ux.UploadPanel.js  ]*/

/*
* Extended Panel
* Ability to update the data of widget on realtime
* @author: Prakash Paudel
*/
Ext.namespace('Ext.ux.plugins');
/*
 * Realtime widgets update.
 * Config variables:
 *     startInitial ... whether to start the autoreload automaticaly
 *     reloadToolVisible
 *     rate ... reload delay in milliseconds
 *
 * @author: prakash paudel
 */
Ext.ns("Ext.ux.plugins");
Ext.ux.plugins.RealtimeWidgetUpdate = function(config){
	Ext.apply(this,config);
}
Ext.extend(Ext.ux.plugins.RealtimeWidgetUpdate, Ext.util.Observable,{		
	init: function(widget){
		var me = this;
		var numRequests = 0;  // The number of requests in the air.
		var autoReload;
		var stopTool = {
			id: "stop-reload",
			hidden:this.startInitial?false:true,
			handler: function(){
				autoReload.stop();
				widget.tools['stop-reload'].setVisible(false);
				widget.tools['start-reload'].setVisible(true);
			},
			scope:widget,
			qtip:"Pause auto widget reload."
		};
		var startTool = {
				id: "start-reload",
				hidden:this.startInitial?true:false,
				handler: function(){
					autoReload.started = true;
					widget.tools['start-reload'].setVisible(false);
					widget.tools['stop-reload'].setVisible(true);
					this.reloadNow();
				},
				scope:widget,
				qtip:"Start auto widget reload."
		};
		widget.tools = widget.tools || [];
		if(this.reloadToolVisible){
			for(var i=0, len=widget.tools.length;i<len;i++) {
	            if (widget.tools[i].id=='start-reload') return;
	        }
			widget.tools.reverse();			
			widget.tools[widget.tools.length] = stopTool;
			widget.tools[widget.tools.length] = startTool;
			widget.tools.reverse();
		}		

		autoReload = {
			started: me.startInitial,
			ajaxId: undefined,
			stop: function(){
				autoReload.started = false;
				autoReload._abortAjax();
			},
			_abortAjax: function(){
				if(autoReload.ajaxId) {
					numRequests -= 1;
					Ext.Ajax.abort(autoReload.ajaxId);
					autoReload.ajaxId = undefined;
				}
			},
			isEnabled: function(){
				return autoReload.started;
			},
			task: new Ext.util.DelayedTask(function(){
				if(autoReload.isEnabled()) {
					widget.reloadNow();
				}
			}),
			beforeLoad: function() {
				// The numRequests counts also active paging requests.
				numRequests += 1;
				autoReload._abortAjax();
			},
			afterLoad: function() {
				autoReload.ajaxId = undefined;
				numRequests -= 1;
				if(autoReload.isEnabled()) {
					autoReload.task.delay(me.rate);
				}
			},
			afterFailure: function() {
				// Wait a bit before hammering the server again.
				window.setTimeout(autoReload.afterLoad, 10000);
			}
		};
		if(widget.getStore) {
			widget.getStore().on('beforeload', autoReload.beforeLoad);
			widget.getStore().on('load', autoReload.afterLoad);
			widget.getStore().on('exception', autoReload.afterFailure);
		}

		Ext.apply(widget,{
			gridReload: function(){
				if(numRequests <= 0){
					numRequests = 0;
					widget.disableLoadMask = true;
					if(widget.loadMask && widget.loadMask.disable) {
						widget.loadMask.disable();
					}

					var store = widget.getStore();
					if(store.proxy.conn.disableCaching === false) {
						store.proxy.conn.disableCaching = true;
					}
					if(store.proxy.setUrl) {
						// Gives a signal that waiting for new news is OK.
						var url = store.proxy.url;
						if(url.indexOf('?') > 0){
							url = url + '&_wait=1';
						} else {
							url = url + '?_wait=1';
						}
						store.proxy.setUrl(url);
					}
					store.reload();
					autoReload.ajaxId = undefined;
					if(store.proxy.activeRequest) {
						autoReload.ajaxId = store.proxy.activeRequest.read;
					}
				}
			},
			htmlReload: function(){
				var owner = this;
				if(!autoReload.ajaxId){
			       autoReload.ajaxId = Ext.Ajax.request({
			    	   url:me.url,
			    	   method:'POST',
			    	   params:Ext.apply(me.requestParams,{
			    	   		reload:'true'
			       	   }),
			       	   success: owner.onSuccess,
			       	   failure: function(){
							autoReload.afterFailure();
			       	   }
			       });
    		   }
			},
			reloadNow: function(){
				var owner = this;
				if(widget.getStore){
					owner.gridReload();
    		   }else {
    			    owner.htmlReload();
    		   }
			},
			onHide: function(){
				this.reloadNow();
			},
			onSuccess: function(r){
			   autoReload.afterLoad();
			   var rc = null;
			   try{rc=new RegExp('^("(\\\\.|[^"\\\\\\n\\r])*?"|[,:{}\\[\\]0-9.\\-+Eaeflnr-u \\n\\r\\t])+?$')}
			   catch(z){rc=/^(true|false|null|\[.*\]|\{.*\}|".*"|\d+|\d+\.\d+)$/}
			   if(!rc.test(r.responseText)) { return;}
			   var json = Ext.util.JSON.decode(r.responseText);				       		  
			   if(json.html && json.js){
				   var html = json.html;
				   var js = json.js;					       		  
				   if(widget && widget.items && widget.items.items[0] && widget.items.items[0].getEl() && widget.items.items[0].getEl().dom){
					   widget.items.items[0].getEl().dom.innerHTML = html;
					 if(js) eval(js);
				   } 					       		
			   }else if(json.html){
				   var html = json.html;					       		   				       		   
				   if(widget && widget.items && widget.items.items[0] && widget.items.items[0].getEl() && widget.items.items[0].getEl().dom){
					   // Restoring cheated height for the div if any
					   var div = document.createElement("div");
					   div.innerHTML = html;					   
					   if(div.firstChild.onmouseover){
						   if(widget.items.items[0].getEl().dom.firstChild){
							   div.firstChild.style.height = widget.items.items[0].getEl().dom.firstChild.style.height;
							   html = div.innerHTML;
						   }
					   }
					   //....................................
					   widget.items.items[0].getEl().dom.innerHTML = html;					       			
				   }else if(widget && widget.getEl() && widget.getEl()){
					   // For the html texts display like in diagnostics.......
					   // Compatible with cheats used in html panel............
					   // Divisions hirarchy must match..........
					   var el = widget.getEl().dom;				       			   
					   if(el){
						   var p = el.firstChild.childNodes[0];
						   if(p){
							   p.innerHTML = html;
						   }
					   }
				   }
			   }
			}
		});
	}
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/plugins/Ext.ux.plugins.RealtimeWidgetUpdate.js  ]*/

// create namespace for plugins
/**
 * Different from Extjs-2 Version
 */

Ext.namespace('Ext.ux.plugins');
Ext.ux.plugins.HelpText = {
    init: function(container){
        Ext.apply(container, {
            onRender: container.onRender.createSequence(function(ct, position){
                // if there is helpText create a div and display the text below the field
                if (typeof this.helpText == 'string') {														
	                switch (this.helpType)
	                {
	                	case "comment":                
			                this.wrap = this.wrap || this.el.wrap();
		                	var ins = this.el.dom;
		                	ins = this.wrap.dom;
		                    Ext.DomHelper.append(ins.parentNode,{
		                        tag: 'div',
		                        cls: typeof this.helpTextClass != 'undefined' ? this.helpTextClass : '',
		                        style: typeof this.helpTextStyle != 'undefined' ? this.helpTextStyle : 'clear: right; font-size: 11px; color: #888888;',
		                        html: this.helpText
		                    });
			            break;						
			            
			            case "inline":
							var el = this.getEl();
							var enc = el.findParent('.x-form-item');
							if(enc){
								var els = Ext.select('label',true,enc);								
								label = els.elements[0];
							}							
							var style="padding-left:2px;";
							if(!label) break;
			            	switch(this.xtype)
			            	{
			            		case "radio":			            			
			            			label = els.elements[1];
			            			break;
			            		default:			            			
			            			var style="padding-right:2px;float:left";
			            			break;
			            	}			            	
			            	this.helpText = (this.helpText+'').replace(/[\\"']/g, '\'');
			            	Ext.DomHelper.insertFirst(label,{
		                        tag: 'span',
		                        style: style,
		                        html: '<img src="/appFlowerPlugin/images/help.png" qtip="'+this.helpText+'">'
		                    });
			            break;
	                }
                }
                
            }),// end of function onRender
            findByClassName: function(div,className){
        		var curDiv;
        		while(curDiv = div.parentNode){
        			if(curDiv.className.match(className)){
        				return curDiv
        			}
        			div = curDiv;
        		}
        		return false;
        	}
        });
        
    } // end of function init
};
Ext.ux.plugins.HelpText = Ext.extend(Ext.ux.plugins.HelpText, Ext.util.Observable);
// end of file

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/form/Ext.ux.plugins.HelpText.js  ]*/

Ext.ns("Ext.ux.plugins");
Ext.ux.plugins.DayTimeSelect = Ext.extend(Ext.form.Field, {
    border:true,
    width:250,
    dayTimeFieldWidth:60,
    fromFieldWidth:60,
    toFieldWidth:60,
    buttonWidth:50,
    listWidth:200,
    layout:'form',
    hidden:true,
  
    prepareValue:function(dataType,dataArray){
    	da = Array();
    	j = 0;
    	for(i=0;i<dataArray.length;i++){
    		if(dataArray[i] != '' && dataArray[i] != null){
    			da[j] = dataArray[i];
    			j++;
    		}
    	}
    	if(this.returnType == 'json'){
    		var returnJSON = Array();
    		returnJSON[0] = dataType;
    		returnJSON[1] = da;
    		json = Ext.util.JSON.encode(returnJSON); 
    		this.setValue(json);
    	}else{
    		commaS = '';
    		
    		for(i=0;i<dataArray.length;i++){
	    		if(dataArray[i] != '' && dataArray[i] != null){
	    			commaS += dataArray[i];
	    			if(i != (dataArray.length-1)) commaS += ",";
	    		}	    		
    		}
    		if(commaS != "") commaS = dataType+","+commaS;
    		this.setValue(commaS);
    	}
    },
    resizeComponents:function(type){    	
    	if(type == 'auto'){    		
    		var fw = this.width - 30;
    		//Upper row widths
    		this.fromFieldWidth = Math.floor((25/100)*fw);
    		this.toFieldWidth = Math.floor((25/100)*fw);
    		this.buttonWidth = Math.floor((20/100)*fw);
    		var t = this.fromFieldWidth + this.toFieldWidth + this.buttonWidth + 30;
    		this.dayTimeFieldWidth = this.width - t;    		
    		//Lower row widths
    		this.listWidth = this.width - this.buttonWidth - 5;     			
    	}
    }    
    ,initComponent:function() {
        var config = {
  	
        };
        Ext.apply(this, config);

        Ext.ux.plugins.DayTimeSelect.superclass.initComponent.apply(this, arguments);
    }     
    ,onRender:function(ct,position){
    	//this.el.style.display = "none";
    	this.resizeComponents('auto');
    	
        Ext.ux.plugins.DayTimeSelect.superclass.onRender.apply(this, arguments);
        var randomN = Math.floor(Math.random()*10000000);
        var dataType = 'day';
        var dataArray = Array();
        mainDiv = Ext.DomHelper.insertFirst(ct, {tag: 'div', style:'width:'+this.width+'px;float:left'});
		topRowDiv = Ext.DomHelper.append(mainDiv,{tag:'div',style:'width:inherit;float:left;'});
		dayTimeDiv = Ext.DomHelper.append(topRowDiv,{tag:'div',style:'width:'+this.dayTimeFieldWidth+'px;float:left; margin-right:15px;'});
		fromDiv = Ext.DomHelper.append(topRowDiv,{tag:'div',style:'float:left;width:'+this.fromFieldWidth+'px;'});
		hyphenDiv = Ext.DomHelper.append(topRowDiv,{tag:'div',style:'text-align:center;float:left;width:10px;',html:'-'});						
		toDiv = Ext.DomHelper.append(topRowDiv,{tag:'div',style:'float:left;margin-right:5px;width:'+this.toFieldWidth+'px;'});
		okButtonDiv = Ext.DomHelper.append(topRowDiv,{tag:'div',style:'float:right;width:'+this.buttonWidth+'px;'});
		clearDiv = Ext.DomHelper.append(mainDiv,{tag:'div',style:'clear:both'});
	    clearDiv.id = 'daytimeselect_'+randomN+'_cleardiv0_';
		
		dayStore = new Ext.data.SimpleStore({				        
	        fields: [
	            'valueField',
	            'displayField'
	        ],
	        data: [
			        ['1', '1'], ['2', '2'],['3', '3'], ['4', '4'],['5', '5'], ['6', '6'],['7', '7'], ['8', '8'], ['9', '9'],['10', '10'],
			        ['11', '11'], ['12', '12'],['13', '13'], ['14', '14'],['15', '15'], ['16', '16'],['17', '17'], ['18', '18'], ['19', '19'],['20', '20'],
			        ['21', '21'], ['22', '22'],['23', '23'], ['24', '24'],['25', '25'], ['26', '26'],['27', '27'], ['28', '28'], ['29', '29'],['30', '30'],['31', '31']
			      ]
	    });
	    timeStore = new Ext.data.SimpleStore({				        
	        fields: [
	            'valueField',
	            'displayField'
	        ],
	        data: [
			        ['1', '1'], ['2', '2'],['3', '3'], ['4', '4'],['5', '5'], ['6', '6'],['7', '7'], ['8', '8'], ['9', '9'],['10', '10'],
			        ['11', '11'], ['12', '12'],['13', '13'], ['14', '14'],['15', '15'], ['16', '16'],['17', '17'], ['18', '18'], ['19', '19'],['20', '20'],
			        ['21', '21'], ['22', '22'],['23', '23'], ['24', '24']
			      ]
	    });
		dayTimeCombo = new Ext.form.ComboBox({
			typeAhead: true,
			id:'daytimeselect_daytimecombo_'+randomN,
		    triggerAction: 'all',
		    width:this.dayTimeFieldWidth,
		    lazyRender:true,
		    mode: 'local',
		    store: new Ext.data.SimpleStore({				        
		        fields: [
		            'valueField',
		            'displayField'
		        ],
		        data: [['day', 'Daily'], ['time', 'Hourly']]
		    }),
		    listeners:{
		    	select:function(){				    		
		    		if(this.getValue()=="time"){				    		
		    			Ext.getCmp('daytimeselect_fromcombo_'+randomN).bindStore(timeStore);
		    			Ext.getCmp('daytimeselect_tocombo_'+randomN).bindStore(timeStore);
		    		}
		    		if(this.getValue()=="day"){				    		
		    			Ext.getCmp('daytimeselect_fromcombo_'+randomN).bindStore(dayStore);
		    			Ext.getCmp('daytimeselect_tocombo_'+randomN).bindStore(dayStore);
		    		}
		    		if(!dataArray.length)
		    		dataType = this.getValue();
		    	}
		    },
		    valueField: 'valueField',
		    displayField: 'displayField'
		});
		
		dayTimeCombo.setValue('day');
		dayTimeCombo.render(dayTimeDiv);
		
		fromCombo = new Ext.form.ComboBox({
			typeAhead: true,
			id:'daytimeselect_fromcombo_'+randomN,
		    triggerAction: 'all',
		    width:this.fromFieldWidth,
		    lazyRender:true,
		    mode: 'local',
		    store: dayStore,
		    valueField: 'valueField',
		    displayField: 'displayField',
		    emptyText:'From',
		   
		});
		
		//fromCombo.setValue('day');
		fromCombo.render(fromDiv);
		
		toCombo = new Ext.form.ComboBox({
			typeAhead: true,
			id:'daytimeselect_tocombo_'+randomN,
		    triggerAction: 'all',
		    width:this.toFieldWidth,
		    lazyRender:true,
		    mode: 'local',
		    store: dayStore,
		    valueField: 'valueField',
		    displayField: 'displayField',
		    emptyText:'To',
		   
		});
		
		//toCombo.setValue('day');
		toCombo.render(toDiv);
		
		okButton = new Ext.Button({
			text:"Add",
			parent:this,
			width:this.buttonWidth,
			count:0,
			t_item:0,
			listeners:{
				click:function(){
					if(Ext.getCmp('daytimeselect_daytimecombo_'+randomN).getValue() == "" || Ext.getCmp('daytimeselect_fromcombo_'+randomN).getValue() == "" || Ext.getCmp('daytimeselect_tocombo_'+randomN).getValue() == ""){
						Ext.Msg.show({
						   title:'Error !',
						   msg: 'Invalid Selections',
						   buttons: Ext.Msg.OK,
						   //fn: processResult,
						   icon: Ext.MessageBox.ERROR
						});
						return;
					}
					//Uncomment if from should be less than to value
					/*if(Ext.getCmp('daytimeselect_fromcombo_'+randomN).getValue() > Ext.getCmp('daytimeselect_tocombo_'+randomN).getValue()){
						Ext.Msg.show({
						   title:'Error !',
						   msg: 'Invalid range !<br>From value is greater than To value',
						   buttons: Ext.Msg.OK,
						   //fn: processResult,
						   icon: Ext.MessageBox.ERROR
						});
						return;
					}*/
					if(this.t_item && dataType != Ext.getCmp('daytimeselect_daytimecombo_'+randomN).getValue()){
						Ext.Msg.show({
						   title:'Error !',
						   msg: 'Invalid data type for this series',
						   buttons: Ext.Msg.OK,
						   //fn: processResult,
						   icon: Ext.MessageBox.ERROR
						});
						return;
					}							
					text = Ext.getCmp('daytimeselect_fromcombo_'+randomN).getValue()+"-"+Ext.getCmp('daytimeselect_tocombo_'+randomN).getValue();
					var found = false;
					for(var i = 0; i<dataArray.length; i++) {
						if(dataArray[i] == text) {
							found = true;
						}
					}
					if(found){
						Ext.Msg.show({
						   title:'Error !',
						   msg: 'Data already in list',
						   buttons: Ext.Msg.OK,
						   //fn: processResult,
						   icon: Ext.MessageBox.ERROR
						});
						return;
					}
					dataArray[this.count] = Ext.getCmp('daytimeselect_fromcombo_'+randomN).getValue()+"-"+Ext.getCmp('daytimeselect_tocombo_'+randomN).getValue();
					this.t_item++;
					this.parent.prepareValue(dataType,dataArray);
					
					//alert(dataArray.length);
					//alert(dataType)
					
					
	        		clearDiv = Ext.DomHelper.append(mainDiv,{tag:'div',style:'width:inherit;clear:both;font-size:0px;height:3px'});
	        		clearDiv.id = 'daytimeselect_'+randomN+'_cleardiv_'+this.count;
	        		
					listRowDiv = Ext.DomHelper.append(mainDiv,{tag:'div',style:'width:inherit;float:left;'});
					listRowDiv.id = 'daytimeselect_'+randomN+'_listrowdiv_'+this.count;
					
					listDiv = Ext.DomHelper.append(listRowDiv,{tag:'div',style:'width:'+this.parent.listWidth+'px; margin-right:5px;float:left;'});
					listDiv.id = 'daytimeselect_'+randomN+'_listdiv_'+this.count;
					
					removeButtonDiv = Ext.DomHelper.append(listRowDiv,{tag:'div',style:'width:inherit;float:right; width:'+this.parent.buttonWidth+'px;'});
					removeButtonDiv.id = 'daytimeselect_'+randomN+'_removebuttondiv_'+this.count;
				    
				    clearDiv = Ext.DomHelper.append(mainDiv,{tag:'div',style:'width:inherit;clear:both;font-size:0px;height:3px'});
	        		clearDiv.id = 'daytimeselect_'+randomN+'_cleardiv_'+this.count;
	        		
	        		    	
					list = new Ext.form.TextField({
						bodyStyle:'background-color:#f5f5f5',
						height:20,
						width:this.parent.listWidth,
						readOnly:true
					});
					list.setValue(text);
					list.render(listDiv);
					removeButton = new Ext.Button({
						text:"DEL",
						id:'daytimeselect_'+randomN+'_removebutton_'+this.count,
						c:this.count,
						t_item:this.t_item,
						parent:this,
						
						listeners:{
							click:function(){
								dataArray[this.c] = '';
								Ext.DomHelper.applyStyles(Ext.get('daytimeselect_'+randomN+'_listrowdiv_'+this.c),'display:none');
								Ext.DomHelper.applyStyles(Ext.get('daytimeselect_'+randomN+'_cleardiv_'+this.c),'display:none');
								this.parent.t_item--;
								if(this.parent.t_item == 0) dataArray = new Array();
								this.parent.parent.prepareValue(dataType,dataArray);
							}
						}
					});				
					removeButton.render(removeButtonDiv);
					this.count++;
				}
			}
		});				
		okButton.render(okButtonDiv);	
		data = this.value;
    	dataArr = data.split(',');
    	dataType = dataArr[0];
    	dayTimeCombo.setValue(dataType);
    	for(i=1;i<dataArr.length;i++){
    		valArr = dataArr[i].split("-");
    		fromCombo.setValue(valArr[0]);
    		toCombo.setValue(valArr[1]);
    		okButton.fireEvent('click');
    		//dataArray[i-1] = dataArr[i];
    		//okButton.count = i-1;
    	}
    }

});
Ext.reg('daytimeselect', Ext.ux.plugins.DayTimeSelect); 

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/datetime/Ext.ux.plugins.DayTimeSelect.js  ]*/

Ext.ux.ListField = Ext.extend(Ext.form.Field,  {
    dataFields:[],
    data:[],
    width:100,
    height:200,
    displayField:'value',
    valueField:'key',
    allowBlank:true,
    minLength:0,
    maxLength:Number.MAX_VALUE,
    blankText:Ext.form.TextField.prototype.blankText,
    minLengthText:'Minimum {0} item(s) required',
    maxLengthText:'Maximum {0} item(s) allowed',
    delimiter:',',
    backupStore:null,
    // private
    defaultAutoCreate : {tag: "div"},
    
    // private
    initComponent: function(){
        Ext.ux.ListField.superclass.initComponent.call(this);
        this.addEvents({
            'dblclick' : true,
            'click' : true,
            'change' : true,
            'drop' : true
        });     
    },
    
    // private
    onRender: function(ct, position){
        Ext.ux.ListField.superclass.onRender.call(this, ct, position);
        var cls = 'ux-mselect';
        this.inputField = new Ext.form.TextField({
        	hideLabel:true,
        	width:120        	
        });
        this.inputField.on('specialkey',this.onAdd,this);
        var addButton = new Ext.Button({
        	iconCls: 'listfield-add-button'
        });
        addButton.on('click',this.onAdd,this);
        var clearButton = new Ext.Button({
        	text: "Clear"        	
        });
        clearButton.on('click', this.onClear, this);
        
        var resetButton = new Ext.Button({
        	text: "Reset"
        });
        resetButton.on('click',this.onReset,this);
        
        var fs = new Ext.form.FieldSet({
            renderTo:this.el,
            title:this.legend,
            height:this.height,
            width:this.width,
            style:"padding:0;",
            tbar:[this.inputField,addButton,'->',clearButton]
        });
        
        //if(!this.legend)fs.el.down('.'+fs.headerCls).remove();
        fs.body.addClass(cls);

        var tpl = '<tpl for="."><div style="border-bottom:1px dashed #ddd" class="' + cls + '-item';
        if(Ext.isIE || Ext.isIE7){
            tpl+='" unselectable=on';
        }else{
            tpl+=' x-unselectable"';
        }
        tpl+='><span class="listfield-remove-icon"><a class="listfield-remove-icon-a" href="#">&nbsp;</a></span>{' + this.displayField + '}</div></tpl>';

        if(!this.store){
            this.store = new Ext.data.SimpleStore({
                fields: this.dataFields,
                data : this.data
            });
        }
        
       
        this.view = new Ext.ux.DDView({
            //multiSelect: true, 
            store: this.store, 
            selectedClass: cls+"-selected", 
            tpl:tpl,
            allowDup:this.allowDup, 
            copy: this.copy, 
            allowTrash: this.allowTrash, 
            dragGroup: this.dragGroup, 
            dropGroup: this.dropGroup, 
            itemSelector:"."+cls+"-item",
            isFormField:true, 
            applyTo:fs.body,
            appendOnly:this.appendOnly,
            sortField:this.sortField, 
            sortDir:this.sortDir
            
        });  
        fs.add(this.view);
        
        this.view.on('click', this.onClick, this);        
        this.hiddenName = this.name;
        var hiddenTag={tag: "input", type: "hidden", value: "", name:this.name};
        if (this.isFormField) { 
            this.hiddenField = this.el.createChild(hiddenTag);
        } else {
            this.hiddenField = Ext.get(document.body).createChild(hiddenTag);
        }        
        this.hiddenField.dom.value = this.getList();
        fs.doLayout();        
    },    
    /*
     * Internal components events
     */
    onAdd: function(ct,e){    	
    	var val = null;
    	if(ct instanceof Ext.Button){
    		val = this.inputField.getValue();    	
    	}else{
    		if(!e) return;
	    	if(e.getKey()==e.ENTER && ct.getXType()=='textfield'){
	    		val = ct.getValue();    		
	    	}else{
	    		return;	
	    	}
    	}
    	if(!val) return;
    	if(this.ifExists(val)){
    		Ext.Msg.alert("Error","Item already exists in list"); return;
    	}
    	//this.view.store.add(new Ext.data.Record.create([val,val]))
    	var data = Ext.data.Record.create(['key','value']);    	
    	this.view.store.add(new data({key:val,value:val}))
    	this.setList(this.getList());
    	this.inputField.focus()
    },
    ifExists: function(val){
    	for(var i=0;i<this.view.store.getCount();i++){
    		if(this.view.store.getAt(i).data.value == val) return true;
    	}
    	return false;
    },
    onClear: function(){
    	this.view.store.removeAll();
    	this.setList(this.getList());
    	this.inputField.focus();
    },
    onReset: function(){
    	this.view.bindStore = this.backupStore;
    	this.inputField.focus();
    },
    /*************************************************/
    getList: function(){
        var returnArray = [];
        var c = this.view.store.getCount();
        
        for (var i=0; i<c; i++) {
            returnArray.push(this.store.getAt(i).get(this.valueField));
        }
        return returnArray.join(this.delimiter);
    },
    // private
    onClick: function(vw, index, node, e) {
    	if(e.target.className == "listfield-remove-icon-a"){
    		this.view.store.remove(this.view.store.getAt(index));
    	}
        this.setList(this.getList())
    },
    setList: function(){
    	this.hiddenField.dom.value = this.getList();
    }
});

Ext.reg("listfield", Ext.ux.ListField);

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/multiselect/ListField.js  ]*/

/**
 * @class Ext.ux.ColorField
 * @extends Ext.form.TriggerField
 * Provides a color input field with a {@link Ext.ColorPalette} dropdown.
* @constructor
* Create a new ColorField
 * <br />Example:
 * <pre><code>
var color_field = new Ext.ux.ColorField({
	fieldLabel: 'Color',
	id: 'color',
	width: 175,
	allowBlank: false
});
</code></pre>
* @param {Object} config
 */

Ext.ux.ColorField = Ext.extend(Ext.form.TriggerField,  {
	valueVisible : true,
	
    /**
     * @cfg {String} invalidText
     * The error to display when the color in the field is invalid (defaults to
     * '{value} is not a valid color - it must be in the format {format}').
     */
    invalidText : "'{0}' is not a valid color - it must be in a the hex format (# followed by 3 or 6 letters/numbers 0-9 A-F)",
    /**
     * @cfg {String} triggerClass
     * An additional CSS class used to style the trigger button.  The trigger will always get the
     * class 'x-form-trigger' and triggerClass will be <b>appended</b> if specified (defaults to 'x-form-color-trigger'
     * which displays a color wheel icon).
     */
    triggerClass : 'x-form-color-trigger',
    /**
     * @cfg {String/Object} autoCreate
     * A DomHelper element spec, or true for a default element spec (defaults to
     * {tag: "input", type: "text", size: "10", autocomplete: "off"})
     */

    // private
    defaultAutoCreate : {tag: "input", type: "text", size: "10", maxlength: "7", autocomplete: "off"},

    // Limit input to hex values
	maskRe: /[#a-f0-9]/i,
	
	initComponent: function(){
    	if(Ext.getBody().first(".x-color-picker")) return;
    	if(!this.picker){
    		elem = new Ext.Element(document.createElement('div'));
    		elem.setXY([-500,-500]);
    		Ext.getBody().appendChild(elem);
			this.picker = new Ext.ux.color.ColorPickerPanel({
                applyTo: elem,
                hex:"ffffff",
                mode:'hue'                
            });	
			Ext.getBody().first(".x-color-picker").setStyle({
				display:"none"
			})
            this.picker = null;
		}
    },
    // private
    validateValue : function(value){    	
        if(!Ext.ux.ColorField.superclass.validateValue.call(this, value)){
            return false;
        }
        if(value.length < 1){ // if it's blank and textfield didn't flag it then it's valid
        	 this.setColor('');
        	 return true;
        }

        var parseOK = this.parseColor(value);

        if(!value || (parseOK == false)){
            this.markInvalid(String.format(this.invalidText,value));
            return false;
        }
		this.setColor(value);
        return true;
    },

	/**
	 * Sets the current color and changes the background.
	 * Does *not* change the value of the field.
	 * @param {String} hex The color value.
	 */
	setColor : function(color) {    	
		if (color=='' || color==undefined)
		{
			if (this.emptyText!='' && this.parseColor(this.emptyText))
				color=this.emptyText;
			else
				color='transparent';
		}
		if (this.trigger){
			if(this.valueVisible){
				this.trigger.setStyle( {
					'background-color': color
				});
			}else{
				this.getEl().dom.style.backgroundColor=color;				
			}
			this.getEl().setStyle({
				'background-color':color
			})			
		}
		else
		{
			this.on('render',function(){
				this.setColor(color);				
			},this);
		}
	},
	
    // private
    // Provides logic to override the default TriggerField.validateBlur which just returns true
    validateBlur : function(){
       // return !this.menu || !this.menu.isVisible();
    },

    /**
     * Returns the current value of the color field
     * @return {String} value The color value
     */
    getValue : function(){
        return Ext.ux.ColorField.superclass.getValue.call(this) || "";
    },

    /**
     * Sets the value of the color field.  You can pass a string that can be parsed into a valid HTML color
     * <br />Usage:
     * <pre><code>
		colorField.setValue('#FFFFFF');
       </code></pre>
     * @param {String} color The color string
     */
    setValue : function(color){
    	
    	//this.getEl().dom.style.fontSize = this.valueVisible?"":"font-size:0px"
    	
        Ext.ux.ColorField.superclass.setValue.call(this, this.formatColor(color));
		this.setColor( this.formatColor(color));
    },

    // private
    parseColor : function(value){
		return (!value || (value.substring(0,1) != '#')) ?
			false : (value.length==4 || value.length==7 );
    },

    // private
    formatColor : function(value){
		if (!value || this.parseColor(value))
			return value;
		if (value.length==3 || value.length==6) {
			return '#' + value;
		}
        return '';
    },

    // private
    menuListeners : {
        select: function(e, c){
            this.setValue(c);
        },
        show : function(){ // retain focus styling
            this.onFocus();
        },
        hide : function(){
            this.focus.defer(10, this);
            var ml = this.menuListeners;
            this.menu.un("select", ml.select,  this);
            this.menu.un("show", ml.show,  this);
            this.menu.un("hide", ml.hide,  this);
        }
    },

    // private
    // Implements the default empty TriggerField.onTriggerClick function to display the ColorPalette
    onTriggerClick : function(e){ 
    	e.stopEvent();
    	var Pheight = e.getPageY(),Pwidth=e.getPageX();
        Pheight = this.getBox().y;
        Pwidth = this.getBox().x;
        if(this.disabled){
            return;
        }
        var el = this.wrap.dom.id
       
        if(this.picker == null)
        { 
            var w = document.body.clientWidth;
            var h = document.body.clientHeight;           
            var boxHeight = this.getBox().height;            
            if(Pheight > h-310){
               Pheight = Pheight-285;
               boxHeight = -boxHeight;
            }           
            if(Pwidth > w-400){
                Pwidth = Pwidth-400+this.getBox().width;
            }
            Pheight+=boxHeight;
           
            var val = this.getValue();           
            this.pickerdiv = Ext.DomHelper.append(Ext.getBody(),{tag:"div",style:{position:'absolute',top:Pheight+"px",left:Pwidth+"px",width:'400px',height:'310px'}});
            this.pickerdiv.style.zIndex = "10000"
            this.picker = new Ext.ux.color.ColorPickerPanel({
                applyTo: this.pickerdiv,
                hex:val.replace("#",""),
                mode:'hue'
            });
            this.picker.okButton.on('click',function(){
                this.setValue(this.picker.hex.getValue());
                this.pickerdiv.style.display="none";
                this.picker = null;
            },this) 
        }
        else
        {
            this.pickerdiv.style.display="none";
            this.picker=null;
          
        }
        Ext.getBody().un("mousedown");
        Ext.getBody().on("mousedown",function(){
        	if(e.getPageY() >= Pheight && e.getPageY() <= Pheight+310 && e.getPageX() >= Pwidth && e.getPageX() <= Pwidth+400){
        		return;
        	}
        	if(this.picker){
        		this.pickerdiv.style.display="none";
        		this.picker=null;
        	}
        	
        },this)
    }
});

Ext.reg('colorfield',Ext.ux.ColorField);

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/form/colorfield/color-field.js  ]*/

/*
 	This project began as a port of a Color Picker written by:
  	Copyright (c) 2007 John Dyer (http://johndyer.name)

	Permission is hereby granted, free of charge, to any person
	obtaining a copy of this software and associated documentation
	files (the "Software"), to deal in the Software without
	restriction, including without limitation the rights to use,
	copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the
	Software is furnished to do so, subject to the following
	conditions:
	
	The above copyright notice and this permission notice shall be
	included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
	EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
	OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
	NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
	HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
	WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
	FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
	OTHER DEALINGS IN THE SOFTWARE.
*/

Ext.namespace("Ext.ux.color");

/*
	todo:
		* CYMK conversion
		* research other color spaces
		* clean up "setter" code
*/

/**
 * @class Ext.ux.Color
 * Experimental Color class. Supports and converts between RGB, HSV and HEX
 * @cfg {Number} red Red value, between 0 and 255
 * @cfg {Number} green Green value, between 0 and 255
 * @cfg {Number} blue Red value, between 0 and 255
 * @cfg {Number} hue Hue value, between 0 and 360 degrees
 * @cfg {Number} saturation Saturation value, between 0 and 100 percent
 * @cfg {Number} brightness Brightness value, between 0 and 100 percent
 * @constructor
 * Create a new Color, ready to convert between RGB, HSV and HEX.
 * @param {Object} config The config object
 */

Ext.ux.color.Color = function(config) {
	Ext.apply(this, this.defaultValues)
	Ext.apply(this, config);
}

Ext.ux.color.Color.prototype = {
	
	/**	
     * Sets red, green or blue values to your liking. After this method completes,
     * values will be used to update HSV and HEX
     * @param {Object} rgb The red:, green: or blue: values to set with
     */	
	setRgb: function(rgb) {
		this.set('red',   rgb);
		this.set('green', rgb);
		this.set('blue',  rgb);
		this.rgbToHsv();
		this.produceHex();
	},

	
	/**	
     * Sets hue, saturation or brughtness values to your liking. After this method completes,
     * values will be used to update RGB and HEX
     * @param {Object} hsv The hue:, saturation: or brightness: values to set with
     */	
	setHsv: function(hsv) {
		this.set('hue',        hsv);
		this.set('saturation', hsv);
		this.set('brightness', hsv);
		this.hsvToRgb();
		this.produceHex();
	},
	
	/**	
     * Sets hex for color you your liking. After this method completes,
     * values will be used to update RGB and HSV
     * @param {Object} hex The hex value to set with
     */	
	setHex: function(hex) {
		var part, splitHex;
		hex = this.validateHex(hex);
		splitHex = this.splitHex(hex);
		for (part in splitHex) { hex = splitHex[part];
			switch(part) {
				case 'red':
					this.setRed(parseInt(hex, 16));
					break;
					
				case 'green':
					this.setGreen(parseInt(hex, 16));
					break;
					
				case 'blue':
					this.setBlue(parseInt(hex, 16));
					break;
			}
		}
		this.rgbToHsv();
		this.produceHex();
	},
	
	// The numbered version for this Class
	version: {
		major: 0,
		minor: 1,
		tiny: 0,
		toString: function() {
			return [this.major, this.minor, this.tiny].join('.');
		}
	},
	
	//private
	defaultValues: {
		red:        0,
		green:      0,
		blue:       0,
		hue:        0,
		saturation: 0,
		brightness: 0,
		hex:  "000000"
	},
	
	produceHex: function() {
		this.hex = this.intToHex(this.red) + this.intToHex(this.green) + this.intToHex(this.blue);
	},
	
	//private
	intToHex: function (dec){
		var result = (parseInt(dec, 10).toString(16));
		if (result.length == 1)
			result = ("0" + result);
		return result.toUpperCase();
	},
	
	//private
	constrainWithWarnings: function(value, key, min, max) {		
		if (isNaN(value)) {
			//console.warn(key+" value must be between numerical. You supplied "+value+" for "+key+". Using "+min+" instead.");
			value = min;
		}
		else if (value < min) {
			//console.warn(key+" value must be between "+min+" and "+max+". You supplied "+value+" for "+key+". Using "+min+" instead.");
			value = min;
		}
		else if (value > max) {
			//console.warn(key+" value must be between "+min+" and "+max+". You supplied "+value+" for "+key+". Using "+max+" instead.");
			value = max;
		}
		return value		
	},
	
	//private
	constrainRgb: function(value, color) {
		value = this.constrainWithWarnings(value, color, 0, 255);
		
		if(color.match(/^(red|blue|green)$/) !== null) {
			this[color] = value;
		}
	},
	
	//private
	// http://www.cs.rit.edu/~ncs/color/t_convert.html
	// The Hue/Saturation/Value model was created by A. R. Smith in 1978.
	rgbToHsv: function() {
		var min, max, delta, hue, 
			saturation, brightness, 
			red, green, blue;
		
		red   = this.red   / 255;
		green = this.green / 255;
		blue  = this.blue  / 255
				
		max = Math.max(red, green, blue);
		min = Math.min(red, green, blue);
		
		delta = max - min;
				
		brightness = max;
		saturation = (max) ? ((max - min) / max) : 0;

		if(!saturation) {
			hue = 0;
		}
		else {
			if (red === max) {
				hue = (green - blue) / delta;
			}
			else if (green === max) {
				hue = 2 + (blue - red) / delta;
			}
			else {
				hue = 4 + (red - green) / delta;
			}
			
			hue = hue * 60;
			if (hue < 0) { hue += 360; }
		}
		this.setHue(parseInt(hue, 10));
		this.setSaturation(parseInt(saturation * 100));
		this.setBrightness(parseInt(brightness * 100));
	},
	
	//private
	hsvToRgb: function() {
		var i, hue, f, p, q, t, rgbSetter,
			brightness = this.brightness/100,
			saturation = this.saturation / 100;
					
		
		if (this.saturation === 0) {
			var monotone = brightness * 255;
			this.setRed(monotone);
			this.setGreen(monotone);
			this.setBlue(monotone);
		}
		
		hue = this.hue / 60;
		i   = parseInt(hue, 10);
		
		f = hue - i;
		p = brightness * (1 - saturation);
		q = brightness * (1 - saturation * f);
		t = brightness * (1 - saturation * (1 - f));

		rgbSetter = [
			[brightness,t,p],
			[q,brightness,p],
			[p,brightness,t],
			[p,q,brightness],
			[t,p,brightness],
			[brightness,p,q]
		][i];

		this.setRed(rgbSetter[0] * 255);
		this.setGreen(rgbSetter[1] * 255);
		this.setBlue(rgbSetter[2] * 255);
	},

	//private
	validateHex: function(value) {
		var hex = value || this.hex;
		hex = hex.toUpperCase();
		if (hex.charAt[0] === '#') { hex = hex.slice(1); }
		hex = hex.replace(/[^A-F0-9]/g, '0');
		if (hex.length > 6) { hex = hex.substring(0, 6); }
		return hex;
	},
	
	//private
	capitalize: function (string) { 
		return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase(); 
	},
	
	//private
	set: function(property, source) {
		if(source[property]) {
			this['set'+this.capitalize(property)](source[property]);
		}	
	},
	
	//private
	splitHex: function(hex) {
		return {
			red:   hex.slice(0, 2),
			green: hex.slice(2, 4),
			blue:  hex.slice(4, 6)
		}
	},
	
	//private
	setRed: function(value) {
		this.constrainRgb(value, 'red');
	},
	
	
	//private
	setGreen: function(value) {
		this.constrainRgb(value, 'green');
	},
	
	
	//private
	setBlue: function(value) {
		this.constrainRgb(value, 'blue');
	},
	
	
	//private
	setHue: function(value) {
		value = this.constrainWithWarnings(value, 'hue', 0, 359);
		this.hue = value;
	},
	
	//private
	setSaturation: function(value) {
		value = this.constrainWithWarnings(value, 'saturation', 0, 100);
		this.saturation = value;
	},
	
	
	//private
	setBrightness: function(value) {
		value = this.constrainWithWarnings(value, 'brightness', 0, 100);
		this.brightness = value;
	}
};

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/form/adv-color-picker/code/Color.js  ]*/

Ext.ux.Radio = {
	groups: {},
	callbacks: {},
	
	registerGroup: function(group) 
	{
		if(!group.radioGroupId) {
			group.radioGroupId = Ext.id();
		}
		if(!this.groups[group.radioGroupId]) {
			this.groups[group.radioGroupId] = {
				dialed: null,
				members: []
			};
		}
	},
	
	registerMember: function(group, member, callback) 
	{
		this.registerGroup(group);
		if(!member.radioGroupMemberId) {
			member.radioGroupMemberId = Ext.id();
		}
		if (this.groups[group.radioGroupId].members.indexOf(member.radioGroupMemberId) === -1) {
			this.groups[group.radioGroupId].members.push(member.radioGroupMemberId);
			this.callbacks[member.radioGroupMemberId] = callback;
		}
	},
	
	unregisterMember: function(group, member) 
	{
		var members = this.groups[group.radioGroupId].members;
		Ext.each(members, function(member, i)
		{
			if(member === member.radioGroupMember.id) {
				members = members.splice(i, 1);
			}
		});
	},
	
	dial: function(group, member) 
	{
		this.groups[group.radioGroupId].dialed = member.radioGroupMemberId;
		Ext.each(this.groups[group.radioGroupId].members, function(member) 
		{
			this.callbacks[member].un()
		}, this);
		this.callbacks[member.radioGroupMemberId].dial();
	},
	
	dialed: function(group, member)
	{
		return (this.groups[group.radioGroupId].dialed === member.radioGroupMemberId);
	}
};

/*
 	This project began as a port of a Color Picker written by:
  	Copyright (c) 2007 John Dyer (http://johndyer.name)

	Permission is hereby granted, free of charge, to any person
	obtaining a copy of this software and associated documentation
	files (the "Software"), to deal in the Software without
	restriction, including without limitation the rights to use,
	copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the
	Software is furnished to do so, subject to the following
	conditions:
	
	The above copyright notice and this permission notice shall be
	included in all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
	EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
	OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
	NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
	HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
	WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
	FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
	OTHER DEALINGS IN THE SOFTWARE.
*/

/**
 * @class Ext.ux.color.ColorPickerPanel
 * Experimental Color class. Supports and converts between RGB, HSV and HEX
 * @cfg {Object} hex A hexidecimal value to start the colorpicker with.
 * @constructor
 * Create a new ColorPanel.
 * @param {Object} config The config object
 */

Ext.ux.color.ColorPickerPanel = function() 
{
	this.modeFields = {};
	this.preloads = [];
	Ext.ux.color.ColorPickerPanel.superclass.constructor.apply(this, arguments);
};

Ext.extend(Ext.ux.color.ColorPickerPanel, Ext.Panel, 
{
	width: 400,
	height: 305,
	hex: "FF0000",
	
	mode: 'saturation',
	
	baseCls: 'x-color-picker x-panel',
	iconCls: 'x-color-wheel',
	
	
	images: 
	[
		'bar-blue-bl',
		'bar-blue-br',
		'bar-blue-tl',
		'bar-blue-tr',
		'bar-brightness',
		'bar-green-bl',
		'bar-green-br',
		'bar-green-tl',
		'bar-green-tr',
		'bar-hue',
		'bar-red-bl',
		'bar-red-br',
		'bar-red-tl',
		'bar-red-tr',
		'bar-saturation',
		'map-blue-max',
		'map-blue-min',
		'map-brightness',
		'map-green-max',
		'map-green-min',
		'map-hue',
		'map-red-max',
		'map-saturation',
		'map-saturation-overlay'
	],
	
	modes: {
		red: {
			name: 'red',
			abbr: 'R',
			min: 0, 
			max: 255
		},
		green: {
			name: 'green',
			abbr: 'G',
			min: 0, 
			max: 255
		},
		blue: {
			name: 'blue',
			abbr: 'B',
			min: 0, 
			max: 255
		},
		hue: {
			name: 'hue',
			abbr: 'H',
			min: 0, 
			max: 359,
			unit: '�'
		},
		saturation: {
			name: 'saturation',
			abbr: 'S',
			min: 0, 
			max: 100,
			unit: '%'
		},
		brightness: {
			name: 'brightness',
			abbr: 'B',
			min: 0, max: 100,
			unit: '%'
		}
	},
	
	write: function(mode, value) 
	{
		var field = this.modeFields[mode.name],
			val = parseInt(value, 10);
			
		val = val.constrain(mode.min, mode.max)
		field.setRawValue(val.toString().replace(new RegExp(mode.unit||''), '') + (mode.unit||''));
	},
	 
	isRgb:function(mode) 
	{
		 return !('red green blue rgb'.indexOf(mode.name) === -1);
	},
	isHsv: function(mode)
	{
		return !('hue saturation brightness hsv'.indexOf(mode.name) === -1)
	},
	
	setFromConsole: function(mode) {
		if (this.isRgb({name:mode})) {
			this.color.setRgb({
				red: this.modeFields.red.getValue(),
				green: this.modeFields.green.getValue(),
				blue: this.modeFields.blue.getValue()
			});
			this.writeToConsole('Hsv');
		}
		else {
			this.color.setHsv({
				hue: this.modeFields.hue.getValue(),
				saturation: this.modeFields.saturation.getValue(),
				brightness: this.modeFields.brightness.getValue()
			});
			this.writeToConsole('Rgb');
		}
	},
	
	setFromTrack: function(y) {
		switch(this.mode) {
			case 'hue':
				this.write(this.modes.hue, 360 - (y/255) * 360);
				break;
			case 'saturation':
				this.write(this.modes.saturation, 100 - (y/255) * 100);
				break;
			case 'brightness':
				this.write(this.modes.brightness, 100 - (y / 255) * 100);
				break;
				
			case 'red':
				this.write(this.modes.red, 255 - (y/255) * 255);
				break;
			case 'green':
				this.write(this.modes.green, 255 - (y/255) * 255);
				break;
			case 'blue':
				this.write(this.modes.blue, 255 - (y/255) * 255);
				break;				
		}
		
		switch(this.mode) {
			case 'hue':
			case 'saturation':
			case 'brightness':
				this.setFromConsole('hsv');
				break;
				
			case 'red':
			case 'green':
			case 'blue':
				this.setFromConsole('rgb');
				break;				
		}
		
		this.paint();
	},
	
	setFromMap: function(x, y) {
		switch(this.mode) {
			case 'hue':
				this.write(this.modes.saturation, (x/255)*100);
				this.write(this.modes.brightness, 100 - ((y/255)*100));
				break;
				
			case 'saturation':
				this.write(this.modes.hue, (x/255)*360);
				this.write(this.modes.brightness, 100 - ((y/255)*100));
				break;
				
			case 'brightness':
				this.write(this.modes.hue, (x/255)*360);
				this.write(this.modes.saturation, 100 - ((y/255)*100));
				break;
								
			case 'red':
				this.write(this.modes.blue, x);
				this.write(this.modes.green, 255 - y);
				break;
				
			case 'green':
				this.write(this.modes.blue, x);
				this.write(this.modes.red, 255 - y);
				break;
				
			case 'blue':
				this.write(this.modes.red, x);
				this.write(this.modes.green, 255 - y);
				break;				
		}
		
		switch(this.mode) {
			case 'hue':
			case 'saturation':
			case 'brightness':
				this.setFromConsole('hsv');
				break;
				
			case 'red':
			case 'green':
			case 'blue':
				this.setFromConsole('rgb');
				break;				
		}
		
		this.paint();	
	},
	
	writeToConsole: function(group) {
		var slot, mode;
		for(slot in this.modes) {
			mode = this.modes[slot];
			if ((group && this['is' + group](mode)) || !group) {
				this.write(mode, this.color[mode.name]);
			}
		}
		
		this.hex.setValue(this.color.hex);
	},
	
	setMode: function(name) 
	{
		this.mode = name;
		Ext.each(this.layers.map.concat(this.layers.track), function(layer) 
		{
			this.setClass(layer, 'x-layer');
			this.setAlpha(layer, 100);
			this.setBackground(layer, null);
		}, this);
		this[this.mode+"Mode"]()
		this.paint();
		this.paintSliders();
	},
	
	hueMode: function() {
		this.setBackground(this.layers.map[0], this.color.hex);				

		// add a hue map on the top
		this.setClass(this.layers.map[1], "map-hue");

		// simple hue bar
		this.setClass(this.layers.track[3], 'bar-hue');	
	},
	
	saturationMode: function() {
		// bottom has saturation map
		this.setClass(this.layers.map[0], 'map-saturation');

		// top has overlay
		this.setClass(this.layers.map[1], 'map-saturation-overlay');

		// bottom: color
		this.setBackground(this.layers.track[2], this.color.hex);
		
		// top: graduated overlay
		this.setClass(this.layers.track[3], 'bar-saturation');
	},
	
	brightnessMode: function() {
		// MAP
		// bottom: nothing
		
		// top
		this.setBackground(this.layers.map[0], '000000');	
		this.setClass(this.layers.map[1], 'map-brightness');				
		
		// SLIDER
		// bottom
		this.setBackground(this.layers.track[2], this.color.hex);
		
		// top				
		this.setClass(this.layers.track[3], 'bar-brightness');
	},
	
	redMode: function() { this.colorMode('red'); },
	greenMode: function() { this.colorMode('green'); },
	blueMode: function() { this.colorMode('blue'); },
	
	colorMode: function(color) {
		this.setClass(this.layers.map[1], 'map-'+color+'-max');
		this.setClass(this.layers.map[0], 'map-'+color+'-min');
		
		this.setClass(this.layers.track[3], 'bar-'+color+'-tl');
		this.setClass(this.layers.track[2], 'bar-'+color+'-tr');
		this.setClass(this.layers.track[1], 'bar-'+color+'-br');
		this.setClass(this.layers.track[0], 'bar-'+color+'-bl');	
	},
	
	onRender: function() 
	{
		if (this.el) 
		{
			Ext.ux.color.ColorPickerPanel.superclass.onRender.apply(this, arguments);
			this.initMarkup();
			this.initLayers();
			this.initConsole();
			this.initMap();
			this.initSlider();
			this.writeToConsole();
			this.setMode(this.mode);
		}
	},
	
	initComponent: function() 
	{
		if (this.frame) {
			this.width = 410;
			this.height = 310;
		}
		this.preloadImages();
		Ext.ux.color.ColorPickerPanel.superclass.initComponent.apply(this, arguments);
		this.color = new Ext.ux.color.Color();
		this.color.setHex(this.hex);
	},
	
	initMarkup: function() 
	{
			this.body.dom.innerHTML += [
				'<div class="x-map">',
					'<div class="x-layer"></div>',
					'<div class="x-layer"></div>',
					'<div class="x-pointer"></div>',
				'</div>',
				'<div class="x-track">',
					'<div class="x-layer"></div>',
					'<div class="x-layer"></div>',
					'<div class="x-layer"></div>',
					'<div class="x-layer"></div>',
					'<div class="x-slider"></div>',
				'</div>',
				'<ul class="x-console">',
					'<li class="preview"></li>',
					'<li class="hue"></li>',
					'<li class="saturation"></li>',
					'<li class="brightness"></li>',
					'<li class="red"></li>',
					'<li class="green"></li>',
					'<li class="blue"></li>',
					'<li class="hex"></li>',
                    '<li class="okButton" style="float:right"></li>',
				'</ul>'
			].join('');
	},
	
	initConsole: function() 
	{
		var _console = this.body.first('.x-console'),
			radio,
			group = Ext.id(),
			slot, mode,
			that = this;
		
		this.preview = _console.first('.preview');
		
		for (slot in this.modes) {
			(function(mode){
				el = _console.first('.' + mode.name)
				
				radio = new Ext.Element(document.createElement('div'));
				radio.addClass('x-radio');
				
				Ext.ux.Radio.registerMember(that, radio.dom, {
					dial: function(){
						Ext.fly(this).addClass('x-dialed');
					}.createDelegate(radio),
					un: function(){
						Ext.fly(this).removeClass('x-dialed');
					}.createDelegate(radio)
				});
				
				mode.radio = radio;
				radio.dom.mode = mode.name;
				el.appendChild(radio);
				
				l = document.createElement('div');
				l.innerHTML = mode.abbr+": ";				
				label = new Ext.Element(l);				
				label.addClass('x-label');
				el.appendChild(label);
				
				if (that.mode === mode.name) {
					Ext.ux.Radio.dial(that, radio.dom);
				}
				
				radio.on({
					'click': function(event, radio){
						that.setMode(radio.mode);
						Ext.ux.Radio.dial(that, radio);
					}
				});
				
				that.modeFields[mode.name] = new Ext.form.NumberField({
					allowBlank: false,
					allowNegative: false,
					allowDecimals: false,
					fieldLabel: mode.unit,
					minValue: mode.min,
					maxValue: mode.max,
					renderTo: el,
					validator: function(value){
						that.write(mode, value);
						that.setFromConsole(mode);
						that.paint();
						that.paintSliders();
						return true;
					}
				});
				
				that.modeFields[mode.name].el.on({
					'click': function(event){
//						console.warn("STUB");
					}
				});
			})(that.modes[slot])
		};
		
		this.hex = new Ext.form.TextField({
			renderTo: _console.first('.hex'),
			allowBlank: false,
			fieldLabel: 'hex',
			label: true,
			validator: function(value)
			{
				this.setRawValue("#"+Ext.ux.color.Color.prototype.validateHex(value.replace(/^#/, '')));
				return true;
			}
		});
		l = document.createElement('div');
		l.innerHTML = "Hex: ";				
		label = new Ext.Element(l);				
		label.addClass('x-hex-label');
		_console.first('.hex').appendChild(label);
		
        this.okButton = new Ext.Button({
			renderTo: _console.first('.okButton'),			
			text: 'Ok'						
		});
	},
	
	initMap: function() 
	{
		var that = this;
		var map = this.body.first('.x-map');
		map.dom.id = Ext.id();
		
		var pointer = map.first('.x-pointer');
		pointer.dom.id = Ext.id();

		this.map = new Ext.dd.DragDrop({
			id: map.dom.id
		});
		
		Ext.apply(this.map, {
			onMouseDown: function(event) {
				this.onDrag(event)
			},
			
			onDrag: function(event, dontSet) {
				var width = pointer.getWidth() - 2,
					height = pointer.getHeight() - 2,
					halfWidth = (width / 2),
					halfHeight = (height / 2),
					x = event.xy[0] - map.getLeft() - halfWidth,
					y = event.xy[1] - map.getTop() - halfHeight;
					
				
				x = x.constrain(0 - halfWidth, map.getWidth() - halfWidth);
				pointer.setLeft(x - 1);
				
				y = y.constrain(0 - halfHeight, map.getHeight() - halfHeight);
				pointer.setTop(y - 1);
				
				if (!dontSet) {
					that.setFromMap(event.xy[0] - map.getLeft(), event.xy[1] - map.getTop());
				}
			},
			
			endDrag: function() {
				document.documentElement.style.cursor = 'default';
			}
		});
	},
	
	initSlider: function() 
	{
		var that = this;
		
		var track = this.body.first('.x-track');
		track.dom.id = Ext.id();
		
		var slider = track.first('.x-slider');
		slider.dom.id = Ext.id();
		
		this.track = new Ext.dd.DragDrop({
			id: track.dom.id
		});
		
		Ext.apply(this.track, {
			onMouseDown: function(event) {
				this.onDrag(event)
			},
			
			onDrag: function(event, dontSet) {
				var height = slider.getHeight() -2,
					halfHeight = height / 2,
					y = event.xy[1] - track.getTop() - halfHeight;
				
				y = y.constrain(0 - halfHeight, track.getHeight() - halfHeight);
				slider.setTop(y - 1);
				
				if (!dontSet) {
					that.setFromTrack(event.xy[1] - track.getTop());
				}
			},
			
			endDrag: function() {
				document.documentElement.style.cursor = 'default';
			}
		});
	},
	
	setAlpha: function(layer, value) 
	{
		Ext.fly(layer).setOpacity(value/100);
	},
	
	setClass: function(layer, name) 
	{
		layer.className = 'x-layer ' + name;
	},
	setBackground: function(layer, hex) 
	{
		Ext.fly(layer).setStyle({
			backgroundColor: (hex==null ? 'transparent' : "#" + hex)
		});
	},
	
	initLayers: function() 
	{
		this.layers = {
			track: this.body.query('.x-track > .x-layer'),
			map: this.body.query('.x-map > .x-layer')
		};
	},
	
	preloadImages: function() 
	{
		var img;
		Ext.each(this.images, function(image) 
		{
			img = new Image();
			img.src = "/images/" + image + ".png";
			this.preloads.push(img);
		}, this);
	},
	
	paint: function() 
	{
		this.paintMap();
		this.paintTrack();
		this.paintPreview();
//		this.paintSliders();
	},
	
	paintMap: function() 
	{
		switch(this.mode) {
			case 'hue':
				// fake color with only hue
				var color = new Ext.ux.color.Color();
				color.setHsv({hue:this.color.hue, saturation:100, brightness:100});					
				this.setBackground(this.layers.map[0], color.hex);
				break;
				
			case 'saturation':
				this.setAlpha(this.layers.map[1], 100 - this.color.saturation);
				break;
				
			case 'brightness':
				this.setAlpha(this.layers.map[1], this.color.brightness);
				break;
				
			case 'red':								
				this.setAlpha(this.layers.map[1], (this.color.red/255)*100);
				break;
				
			case 'green':
				this.setAlpha(this.layers.map[1], (this.color.green/255)*100);
				break;
				
			case 'blue':
				this.setAlpha(this.layers.map[1], (this.color.blue/255)*100);
				break;				
		}
	},
	
	paintTrack: function() 
	{
		switch(this.mode) {
			case 'hue':
				break;
				
			case 'saturation':
				var saturatedColor = new Ext.ux.color.Color();
				saturatedColor.setHsv({hue:this.color.hue, saturation:100, brightness:this.color.brightness});
				this.setBackground(this.layers.track[2], saturatedColor.hex);
				break;
				
			case 'brightness':
				var brightnessColor = new Ext.ux.color.Color();
				brightnessColor.setHsv({hue:this.color.hue, saturation:this.color.saturation, brightness:100});
				this.setBackground(this.layers.track[2], brightnessColor.hex);
				break;
			case 'red':
			case 'green':				
			case 'blue':
			
				var hValue = 0;
				var vValue = 0;
				
				switch(this.mode) {
					case 'red':
						hValue = this.modeFields.blue.getValue();
						vValue = this.modeFields.green.getValue();
						break;
					case 'green':
						hValue = this.modeFields.blue.getValue();
						vValue = this.modeFields.red.getValue();
						break;
					case 'blue':
						hValue = this.modeFields.red.getValue();
						vValue = this.modeFields.green.getValue();
				}
			
				var horzPer = (hValue/255)*100;
				var vertPer = (vValue/255)*100;
				
				var horzPerRev = ((255-hValue)/255)*100;
				var vertPerRev = ((255-vValue)/255)*100;
										
				this.setAlpha(this.layers.track[3], (vertPer>horzPerRev) ? horzPerRev : vertPer);
				this.setAlpha(this.layers.track[2], (vertPer>horzPer) ? horzPer : vertPer); 
				this.setAlpha(this.layers.track[1], (vertPerRev>horzPer) ? horzPer : vertPerRev);
				this.setAlpha(this.layers.track[0], (vertPerRev>horzPerRev) ? horzPerRev : vertPerRev);
			
				break;
		}	
	},
	
	paintPreview: function() 
	{
		this.setBackground(this.preview, this.color.hex);
	},
	
	paintSliders: function()
	{
		var sliderValue = 0,
			sliderValues = {
				hue: 360, 
				saturation: 100, 
				brightness: 100, 
				red: 255, 
				green: 255, 
				blue: 255
			},
			modeValue = sliderValues[this.mode];
		sliderValue = modeValue - this.color[this.mode];
		
		this.track.onDrag({
			xy:[0, (255 * (sliderValue / modeValue)) + Ext.fly(this.track.getEl()).getTop()]
		}, true);
		
		var mapTop = 0, mapLeft = 0;
		
		switch(this.mode) {
			case 'hue':
				mapLeft = this.color.saturation /100 *255;
				mapTop = (100 - this.color.brightness) /100 *255;
				break;
				
			case 'saturation':
				mapLeft = this.color.hue /360 *255;
				mapTop = (100 - this.color.brightness) /100 *255;
				break;
				
			case 'brightness':
				mapLeft = this.color.hue /360 *255;
				mapTop = (100 - this.color.saturation) /100 *255;
				break;
				
			case 'red':
				mapLeft = this.color.blue;
				mapTop = 255 - this.color.green;
				break;
				
			case 'green':
				mapLeft = this.color.blue;
				mapTop = 255 - this.color.red;
				break;
				
			case 'blue':
				mapLeft = this.color.red;
				mapTop = 255 - this.color.green;
				break;				
		}
		
		this.map.onDrag({
			xy: [
				mapLeft + Ext.fly(this.map.getEl()).getLeft(), 
				mapTop + Ext.fly(this.map.getEl()).getTop()
			]
		}, true);
//			mapPointValues = {
//				hue:        ['saturation', [100, 'brightness']],
//				saturation: ['hue',        [100, 'brightness']],
//				brightness: ['hue',        [100, 'saturation']],
//				red:        ['blue',       [255, 'green']],
//				green:      ['blue',       [255, 'red']],
//				blue:       ['red',        [255, 'green']]
//			},
//			modeValue = mapPointValues[this.pickerMode][1][0];
//		mapLeft = this.color[mapPointValues[this.pickerMode][0]];
//		mapTop = modeValue - this.color[mapPointValues[this.pickerMode][1][1]];
//		
//		console.log(mapTop, mapLeft);
//		
//		mapTop = (mapTop / modeValue) * 255;
//		mapLeft = (mapLeft / modeValue) * 255;
		
//		console.log(mapTop, mapLeft);
//		console.log(mapLeft, mapTop)
		
	}
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/form/adv-color-picker/code/ColorPickerPanel.js  ]*/

Ext.ns("Ext.ux");
Ext.ux.ColorPickerWin = function(config){
	confing = config || {}
	var Cwin = new Ext.Window(Ext.apply({
		title:"Color Picker",
		width:500,
		height:500,
		closeAction:'hide',
		layout:'fit',
		items: new Ext.Panel({
			items: new Ext.ux.color.ColorPickerPanel({
				width:300,
				height:400
			})
		})
	},config));	
	return Cwin;
}

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/form/adv-color-picker/code/ColorPickerWin.js  ]*/

/**
 * @class Ext.ux.form.StaticTextField
 * @extends Ext.BoxComponent
 * Base class to easily display static text in a form layout.
 * @constructor
 * Creates a new StaticTextField Field
 * @param {Object} config Configuration options
 * @author Based on MiscField by Nullity with modifications by Aparajita Fishman and Jonas Skoogh
 */
Ext.ns('Ext.ux.form');

Ext.ux.form.StaticTextField = function(config){
    this.name = config.name || config.id;
    Ext.ux.form.StaticTextField.superclass.constructor.call(this, config);
};

Ext.extend(Ext.ux.form.StaticTextField, Ext.BoxComponent,  {
    /**
     * @cfg {String/Object} autoCreate A DomHelper element spec, or true for a default element spec (defaults to
     * {tag: "div"})
     */
    defaultAutoCreate : {tag: "div", style:"padding-top:3px;"},

    /**
     * @cfg {String} fieldClass The default CSS class for the field (defaults to "x-form-field")
     */
    //fieldClass : "x-form-text",

    // private
    isFormField : true,

    /**
     * @cfg {Boolean} postValue True to create a hidden field that will post the field's value during a submit
     */
    submitValue : false,

    /**
     * @cfg {Mixed} value A value to initialize this field with.
     */
    value : undefined,

    /**
     * @cfg {DateFormat} format of date if value is date.
     */
    dateFormat : undefined,
    
    /**
     * @cfg {Boolean} disableReset True to prevent this field from being reset when calling Ext.form.Form.reset()
     */
    disableReset: true,

    // private
    field: null,
    
    /**
     * Returns the name attribute of the field if available
     * @return {String} name The field name
     */
    getName: function(){
         return this.name;
    },

    // private
    onRender : function(ct, position){
        Ext.ux.form.StaticTextField.superclass.onRender.call(this, ct, position);
        if(!this.el){
            var cfg = this.getAutoCreate();
            this.el = ct.createChild(cfg, position);
        
            if (this.submitValue) {
                this.field = ct.createChild({tag:'input', type:'hidden', name: this.getName(), id: ''}, position);
            }
        }

        this.el.addClass([this.fieldClass, this.cls, 'ux-form-statictextfield']);
        this.initValue();
    },

    // private
    afterRender : function(ct, position){
        Ext.ux.form.StaticTextField.superclass.afterRender.call(this);
        this.initEvents();
    },

    // private
    initValue : function(){
        if(this.value !== undefined){
            this.setRawValue(this.value);
        }else if(this.el.dom.innerHTML.length > 0){
            this.setRawValue(this.el.dom.innerHTML);
        }
    },

    /**
     * Returns true if this field has been changed since it was originally loaded.
     */
    isDirty : function() {
        return false;
    },

    /**
     * Resets the current field value to the originally-loaded value
     * @param {Boolean} force Force a reset even if the option disableReset is true
     */
    reset : function(force){
        if(!this.disableReset || force === true){
            this.setValue(this.originalValue);
        }
    },

    // private
    initEvents : function(){
        // reference to original value for reset
        this.originalValue = this.getRawValue();
    },

    /**
     * Returns whether or not the field value is currently valid
     * Always returns true, not used in StaticTextField.
     * @return {Boolean} True
     */
    isValid : function(){
        return true;
    },

    /**
     * Validates the field value
     * Always returns true, not used in StaticTextField.  Required for Ext.form.Form.isValid()
     * @return {Boolean} True
     */
    validate : function(){
        return true;
    },

    processValue : function(value){
        return value;
    },

    // private
    // Subclasses should provide the validation implementation by overriding this
    validateValue : function(value){
        return true;
    },

    /**
     * Mark this field as invalid
     * Not used in StaticTextField.   Required for Ext.form.Form.markInvalid()
     */
    markInvalid : function(){
        return;
    },

    /**
     * Clear any invalid styles/messages for this field
     * Not used in StaticTextField.   Required for Ext.form.Form.clearInvalid()
     */
    clearInvalid : function(){
        return;
    },

    /**
     * Returns the raw field value.
     * @return {Mixed} value The field value
     */
    getRawValue : function(){
       return (this.rendered) ? this.value : null;
    },

    /**
     * Returns the clean field value.
     * @return {String} value The field value
     */
    getValue : function(){
        return this.getRawValue();
    },

    /**
     * Sets the raw field value. The display text is <strong>not</strong> HTML encoded.
     * @param {Mixed} value The value to set
     */
    setRawValue : function(v){
        this.value = v;
        if(this.rendered){
                if(Ext.isDate(v) && this.dateFormat){
                    this.el.dom.innerHTML = v.format(this.dateFormat);
                }
                        else {                
                this.el.dom.innerHTML = v;
                        }
                        if(this.field){
                this.field.dom.value = v;
            }
        }
    },

    /**
     * Sets the field value. The display text is HTML encoded.
     * @param {Mixed} value The value to set
     */
    setValue : function(v){
        this.value = v;
        if(this.rendered){
            if(Ext.isDate(v) && this.dateFormat){
                this.el.dom.innerHTML = Ext.util.Format.htmlEncode(v.format(this.dateFormat));
            }
            else {
            this.el.dom.innerHTML = Ext.util.Format.htmlEncode(v);
            }
            if(this.field){
              this.field.dom.value = v;
          }
        }
    }
});

Ext.reg('statictextfield', Ext.ux.form.StaticTextField); 

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/form/Ext.ux.form.StaticTextField.js  ]*/

/*

 * CodePress - Real Time Syntax Highlighting Editor written in JavaScript - http://codepress.org/

 * 

 * Copyright (C) 2006 Fernando M.A.d.S. <fermads@gmail.com>

 *

 * This program is free software; you can redistribute it and/or modify it under the terms of the 

 * GNU Lesser General Public License as published by the Free Software Foundation.

 * 

 * Read the full licence: http://www.opensource.org/licenses/lgpl-license.php

 *

 * This file is not the original but has been modified to be more compliant with

 * ExtJs. Changes are made by S.J.Hoeksma

 */

Ext.namespace('Ext.ux');



/**

 * Component which wraps the <a href="http://codepress.org">CodePress</a> library to make

 * it available for ExtJs. CodePress gives syntax highlighting for different programming

 * languages.

 * @type component

 */

Ext.ux.CodePress = Ext.extend(Ext.form.Field, {

   /**
   * Iframe engine code loader url
   */
	engineUrl:'/appFlower/codepress',
	/**
	* file content url, from where to get the file content
	*/
	fileContentUrl:'/appFlower/filecontent',
	/**
	* file
	*/
	file:false,
	
    /**

     * The id of the element to pull code from

     * @type {String} 

     @cfg */

    sourceEl : false,

  

    /**

     * The code to use in the editor

     * @type {String} 

     @cfg */

     code : false,

  

    /**

     * The language to render the code with (defaults none)

     * @type {String} 

     @cfg */

    language : false,

    /**

     * Height of the editor (defaults false)

     * @type {Int}

     @cfg  */

    height : false,

  

    /** 

     * Width of the editor (defaults false)

     * @type {Int}

     @cfg */

    width : false,

  

    /**

     * AutoResize window on change container (defaults false)

     * @type {Boolean}

     @cfg */

    autoResize : false,

  

    /**

     * Trim the code of trailing spaces and empty lines (defaults true)

     * @type {Boolean}

     @cfg */

    trim  : true,

  

    /**

     * Is autoComplete for keywords turned on or off (defaults true)

     * @type {Boolean}

     @cfg */

    autoComplete : true,

  

    /**

     * Is the editor readonly (defaults false)

     * @type {Boolean}

     @cfg */

    readOnly  : false,

  

    /**

     * Are lineNumbers visible (defaults true)

     * @type {Boolean}

     @cfg */

    lineNumbers : true,



    //@private Has the editor been initialized

    initialized : false,

  

    /**

     * @private Init the codepress component for ExtJs

     */

    initComponent : function(){

      Ext.ux.CodePress.superclass.initComponent.call(this);



      // Hide the sourceEl if provided

      if(this.sourceEl) Ext.get(this.sourceEl).hide();

    

      this.addEvents({

          /**

           * Fires when the editor is fully initialized (including the iframe)

           * @event initialize 

           * @param {Object} editor The editor

           */

          initialize: true,



          /**

           * Fires when the editor is first receives the focus. Any insertion must wait

           * until after this event.

           * @event activate 

           * @param {Object} editor The editor when activated

           */

          activate: true



      });

    },

  

   /**

    * @private (for BoxComponent)

    */

   adjustSize : Ext.BoxComponent.prototype.adjustSize,

   listeners : {
        resize:{
            fn: function(){ this.resize(); }
        }
    },  

    /**

     * Resize the the editor depending, behavior depends on height,width and autoResize

     */

   resize : function(){
       if(this.initialized){
            var h = (this.height || this.ownerCt.body.dom.clientHeight) +'px';
            var w = (this.width || this.ownerCt.body.dom.clientWidth) +'px';
            this.editor.body.style.width = w;
            this.iframe.setStyle('height', h);
            this.iframe.setStyle('width', w);
            this.textarea.setStyle('height', h);
            this.textarea.setStyle('width', w);
        }
    },



    /**

     * @private During render we create textarea of code press

     * @param {Component} ct The component to render

     * @param {Object} position A object containing the position of the component

     */

    onRender : function(ct, position){

        Ext.ux.CodePress.superclass.onRender.call(this, ct, position);

    

        //Taken from Ext.form.HtmlEditor

        this.el.dom.style.border = '0 none';

        this.el.dom.setAttribute('tabIndex', -1);

        this.el.addClass('x-hidden');

    

    

        if(Ext.isIE){ // fix IE 1px bogus margin

            this.el.applyStyles('margin-top:-1px;margin-bottom:-1px;')

        }

        this.wrap = this.el.wrap({});

    

        // Create the iframe

        this.iframe = Ext.get(document.createElement('iframe'));

        this.iframe.src = (Ext.SSL_SECURE_URL || 'javascript:false');

    

        // Create the textarea element if not created

        if(!this.sourceEl){

            this.textarea = Ext.get(document.createElement('textarea'));

          }else{

            this.textarea = Ext.get(this.sourceEl);

          }

        this.textarea.dom.disabled = true;

        this.textarea.dom.style.overflow = 'hidden';
        
        this.textarea.dom.style.display = 'none';

        this.textarea.dom.style.overflow = 'auto';

        this.iframe.dom.frameBorder = 0; // remove IE internal iframe border

        this.iframe.setStyle('visibility', 'hidden');

        this.iframe.setStyle('position', 'absolute');

        this.options = this.textarea.dom.className;    

        this.wrap.dom.appendChild(this.textarea.dom);

        this.textarea.dom.parentNode.insertBefore(this.iframe.dom, this.textarea.dom);    

        this.setLanguage();
        
        this.edit();

        this.height = (this.height || this.ownerCt.body.dom.clientHeight);

        this.width= (this.width || this.ownerCt.body.dom.clientWidth);

    },

   

   /**

    * @private We don't support focus of editor

    */

    focus : function(){},

  

   /**

    * @private Initialize the editor

    */

   initialize : function() {

      if(Ext.isIE){

        this.doc = this.iframe.dom.contentWindow.document;

        this.win = this.iframe.dom.contentWindow;

      } else {

        this.doc = this.iframe.dom.contentDocument;

        this.win = this.iframe.dom.contentWindow;

      }

      this.editor = this.win.CodePress;

      this.editor.body = this.doc.getElementsByTagName('body')[0];

      if(this.fileContentUrl){

        Ext.Ajax.request({

          url: this.fileContentUrl+'/?file='+this.file

          , method:'get'

          , success:function(response, options){

          	var r = Ext.decode(response.responseText);
          	
          	var code = r.response;
            
            this.code = code;

            this.editor.setCode(this.code);
            
            this.editor.syntaxHighlight('init');
            
          }.createDelegate(this)
          
          ,	failure: function() {
			    Ext.Msg.alert("","The server can't read '"+this.file+"' !");
			    
			    //this.tabPanel.remove(this);
			    
		  }.createDelegate(this)

        });

      }else{

        this.editor.setCode(this.code || this.textarea.dom.value);

      }

      this.setOptions();

      this.editor.syntaxHighlight('init');

      this.textarea.dom.style.display = 'none';

      this.iframe.dom.style.position = 'static';

      this.iframe.dom.style.visibility = 'visible';

      this.iframe.dom.style.display = 'inline';

      this.initialized = true;

      this.fireEvent('initialize', this);
      
      this.resize();

   },

  

   /**

    * Initailize the editor with a element and set the langauge

    * @param {Object} obj Can by a textarea id or a string

    * @param {String} language The langauge to use

    */

   edit : function(obj,language) {

    if(obj) this.textarea.dom.value = document.getElementById(obj) ? document.getElementById(obj).value : obj;

    if(!this.textarea.dom.disabled) return;

    this.language = language ? language : this.getLanguage();

    this.iframe.dom.src = this.engineUrl+'/language/'+this.language+'/ts/'+(new Date).getTime();

    this.iframe.removeListener('load', this.initialize);

    this.iframe.on('load', this.initialize, this);

  },

  /**
  
  *	set the language from the file
  
  */
  
  setLanguage:function() {
  	
  	if(this.file)
  	{
	  	var atmp=this.file.split('.');
	  	var possible_extension='';
	  	if(atmp[atmp.length-1])
	  	possible_extension=atmp[atmp.length-1];
			
	  	switch(possible_extension)
	  	{
	  		case 'php':
	  		case 'inc':
	  		case 'phtml':
	  			this.language='php';
	  			break;
	  			
	  		case 'js':
	  			this.language='javascript';
	  			break;
	  			
	  		case 'css':
	  			this.language='css';
	  			break;
	  			
	  		case 'sql':
	  			this.language='sql';
	  			break;
	  			
	  		case 'html':
	  		case 'htm':
	  			this.language='html';
	  			break;
	  		
	  		default:
	  			this.language='generic';
	  			break;
	  	}
  	}
  },

  /**

   * Get the current langauge used by the editor

   * @return {String} The language used by editor

   */

  getLanguage : function() {

    if(this.language) return this.language;

    for (language in Ext.ux.CodePress.languages) 

      if(this.options.match('\\b'+language+'\\b')) 

        return Ext.ux.CodePress.languages[language] ? language : 'generic';

  },

  

  /**

   * Set the options of editor

   * See config items autoComplete, readOnly, lineNumbers

   */

  setOptions : function() {

    if(this.autoComplete===false || this.options.match('autocomplete-off')) this.toggleAutoComplete();

    if(this.readOnly===true || this.options.match('readonly-on')) this.toggleReadOnly();

    if(this.lineNumbers===false || this.options.match('linenumbers-off')) this.toggleLineNumbers();

  },

  

  /**

   * Original CodePress function to get the code from the editor. For compatibility reasons

   * with ExtJs TextArea whe implemented getValue

   * @return {String} The code from editor

   */

  getCode : function() {

    var code;

    if (this.textarea && this.editor) 

     code = this.textarea.dom.disabled ? this.editor.getCode() : this.textarea.dom.value;

    else

     code = this.code || "";  

    code =  this.trim ? code.replace(/^s+|(s+$|n$|r$)/g,"") : code;

    return code;

  },



  /**

   * Original CodePress function to set the code of the editor.For compatibility reasons

   * with ExtJs TextArea whe implemented setValue

   * @param {String} code The code to be display in editor

   */

  setCode : function(code) {

    if (this.textarea && this.editor) {

      this.textarea.dom.disabled ? this.editor.setCode(code) : this.textarea.dom.value = code;

      this.editor.syntaxHighlight('init');

    } else {

     this.code = code;

   }

  },

  

  /**

   * Set the value to be used by the editor

   * @param {String} text The code to be display in editor

   */

  setValue : function(text) {

    this.setCode(text);

  },

  

  /**

   * Get the value of the code within the editor

   * @return {String} The code within the editor

   */

  getValue : function() {

    return this.getCode();

  },

  /**

   * Toggle autocomplreate on or off

   */

  toggleAutoComplete : function() {

    if (this.editor)

      this.editor.autocomplete = (this.editor.autocomplete) ? false : true;

  },

  

  /**

   * Toggle readonly on or off

   */

  toggleReadOnly : function() {

    this.textarea.dom.readOnly = (this.textarea.dom.readOnly) ? false : true;

    if(this.iframe.dom.style.display != 'none' && this.editor) // prevent exception on FF + iframe with display:none

      this.editor.readOnly(this.textarea.dom.readOnly ? true : false);

  },

  

  /**

   * Toggle line numbers on or off

   */

  toggleLineNumbers : function() {

    if (!this.editor) return;

    var cn = this.editor.body.className;

    this.editor.body.className = (cn==''||cn=='show-line-numbers') ? 'hide-line-numbers' : 'show-line-numbers';

  },

  

  /**

   * Toggle between codepress and textarea

   */

  toggleEditor : function() {

    if(this.textarea.dom.disabled) {

      this.textarea.dom.value = this.getCode();

      this.textarea.dom.disabled = false;

      this.iframe.dom.style.display = 'none';

      this.textarea.dom.style.display = 'inline';

    }

    else {

      this.textarea.dom.disabled = true;

      this.setCode(this.textarea.dom.value);

      if (this.editor) this.editor.syntaxHighlight('init');

      this.iframe.dom.style.display = 'inline';

      this.textarea.dom.style.display = 'none';

    }
  },

    
   toggleIframe : function() {
    	if(this.iframe.dom.style.visibility=='hidden')
    	{
    		this.iframe.dom.style.visibility='visible';
    		this.iframe.dom.style.position = 'static';
    		this.iframe.dom.style.display = 'inline';
    		
    		this.resize();
    		
			this.iframe.dom.style.width=this.width + 'px';
			this.iframe.dom.style.height=this.height + 'px';
    	}
    	else
    	{
    		this.iframe.dom.style.visibility='hidden';
			this.iframe.dom.style.width='0px';
			this.iframe.dom.style.height='0px';
    	}
   }

});



Ext.reg('codepress', Ext.ux.CodePress);

  

Ext.ux.CodePress.languages = {  

  csharp : 'C#', 

  css : 'CSS', 

  generic : 'Generic',

  html : 'HTML',

  java : 'Java', 

  javascript : 'JavaScript', 

  perl : 'Perl', 

  ruby : 'Ruby',  

  php : 'PHP', 

  text : 'Text', 

  sql : 'SQL',

  vbscript : 'VBScript'

} 

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/codepress/Ext.ux.CodePress.js  ]*/

/**
* @author radu
*/
Ext.ns('Ext.ux.form');
/** 
 * @class Ext.ux.form.ComboBox
 * @extends Ext.form.ComboBox
 */
Ext.ux.form.ComboBox = Ext.extend(Ext.form.ComboBox,  {
    /**
     * @cfg {Mixed} transform The id, DOM node or element of an existing HTML SELECT to convert to a ComboBox.
     * Note that if you specify this and the combo is going to be in a {@link Ext.form.BasicForm} or
     * {@link Ext.form.FormPanel}, you must also set {@link #lazyRender} = true.
     */
    /**
     * @cfg {Boolean} lazyRender True to prevent the ComboBox from rendering until requested (should always be used when
     * rendering into an Ext.Editor, defaults to false).
     */
    /**
     * @cfg {Boolean/Object} autoCreate A DomHelper element spec, or true for a default element spec (defaults to:
     * {tag: "input", type: "text", size: "24", autocomplete: "off"})
     */
    /**
     * @cfg {Ext.data.Store/Array} store The data source to which this combo is bound (defaults to undefined).  This can be
     * any {@link Ext.data.Store} subclass, a 1-dimensional array (e.g., ['Foo','Bar']) or a 2-dimensional array (e.g.,
     * [['f','Foo'],['b','Bar']]).  Arrays will be converted to a {@link Ext.data.SimpleStore} internally.
     * 1-dimensional arrays will automatically be expanded (each array item will be the combo value and text) and
     * for multi-dimensional arrays, the value in index 0 of each item will be assumed to be the combo value, while
     * the value at index 1 is assumed to be the combo text.
     */
    /**
     * @cfg {String} title If supplied, a header element is created containing this text and added into the top of
     * the dropdown list (defaults to undefined, with no header element)
     */

    // private
    defaultAutoCreate : {tag: "input", type: "text", size: "24", autocomplete: "off"},
    /**
     * @cfg {Number} listWidth The width in pixels of the dropdown list (defaults to the width of the ComboBox field)
     */
    /**
     * @cfg {String} displayField The underlying data field name to bind to this ComboBox (defaults to undefined if
     * mode = 'remote' or 'text' if transforming a select)
     */
    /**
     * @cfg {String} valueField The underlying data value name to bind to this ComboBox (defaults to undefined if
     * mode = 'remote' or 'value' if transforming a select) Note: use of a valueField requires the user to make a selection
     * in order for a value to be mapped.
     */
    /**
     * @cfg {String} hiddenName If specified, a hidden form field with this name is dynamically generated to store the
     * field's data value (defaults to the underlying DOM element's name). Required for the combo's value to automatically
     * post during a form submission.  Note that the hidden field's id will also default to this name if {@link #hiddenId}
     * is not specified.  The combo's id and the hidden field's ids should be different, since no two DOM nodes should
     * share the same id, so if the combo and hidden names are the same, you should specify a unique hiddenId.
     */
    /**
     * @cfg {String} hiddenId If {@link #hiddenName} is specified, hiddenId can also be provided to give the hidden field
     * a unique id (defaults to the hiddenName).  The hiddenId and combo {@link #id} should be different, since no two DOM
     * nodes should share the same id.
     */
    /**
     * @cfg {String} listClass CSS class to apply to the dropdown list element (defaults to '')
     */
    listClass: '',
    /**
     * @cfg {String} selectedClass CSS class to apply to the selected item in the dropdown list (defaults to 'x-combo-selected')
     */
    selectedClass: 'x-combo-selected',
    /**
     * @cfg {String} triggerClass An additional CSS class used to style the trigger button.  The trigger will always get the
     * class 'x-form-trigger' and triggerClass will be <b>appended</b> if specified (defaults to 'x-form-arrow-trigger'
     * which displays a downward arrow icon).
     */
    triggerClass : 'x-form-arrow-trigger',
    /**
     * @cfg {Boolean/String} shadow True or "sides" for the default effect, "frame" for 4-way shadow, and "drop" for bottom-right
     */
    shadow:'sides',
    /**
     * @cfg {String} listAlign A valid anchor position value. See {@link Ext.Element#alignTo} for details on supported
     * anchor positions (defaults to 'tl-bl')
     */
    listAlign: 'tl-bl?',
    /**
     * @cfg {Number} maxHeight The maximum height in pixels of the dropdown list before scrollbars are shown (defaults to 300)
     */
    maxHeight: 300,
    /**
     * @cfg {Number} minHeight The minimum height in pixels of the dropdown list when the list is constrained by its
     * distance to the viewport edges (defaults to 90)
     */
    minHeight: 90,
    /**
     * @cfg {String} triggerAction The action to execute when the trigger field is activated.  Use 'all' to run the
     * query specified by the allQuery config option (defaults to 'query')
     */
    triggerAction: 'query',
    /**
     * @cfg {Number} minChars The minimum number of characters the user must type before autocomplete and typeahead activate
     * (defaults to 4 if remote or 0 if local, does not apply if editable = false)
     */
    minChars : 4,
    /**
     * @cfg {Boolean} typeAhead True to populate and autoselect the remainder of the text being typed after a configurable
     * delay ({@link #typeAheadDelay}) if it matches a known value (defaults to false)
     */
    typeAhead: false,
    /**
     * @cfg {Number} queryDelay The length of time in milliseconds to delay between the start of typing and sending the
     * query to filter the dropdown list (defaults to 500 if mode = 'remote' or 10 if mode = 'local')
     */
    queryDelay: 500,
    /**
     * @cfg {Number} pageSize If greater than 0, a paging toolbar is displayed in the footer of the dropdown list and the
     * filter queries will execute with page start and limit parameters.  Only applies when mode = 'remote' (defaults to 0)
     */
    pageSize: 0,
    /**
     * @cfg {Boolean} selectOnFocus True to select any existing text in the field immediately on focus.  Only applies
     * when editable = true (defaults to false)
     */
    selectOnFocus:false,
    /**
     * @cfg {String} queryParam Name of the query as it will be passed on the querystring (defaults to 'query')
     */
    queryParam: 'query',
    /**
     * @cfg {String} loadingText The text to display in the dropdown list while data is loading.  Only applies
     * when mode = 'remote' (defaults to 'Loading...')
     */
    loadingText: 'Loading...',
    /**
     * @cfg {Boolean} resizable True to add a resize handle to the bottom of the dropdown list (defaults to false)
     */
    resizable: false,
    /**
     * @cfg {Number} handleHeight The height in pixels of the dropdown list resize handle if resizable = true (defaults to 8)
     */
    handleHeight : 8,
    /**
     * @cfg {Boolean} editable False to prevent the user from typing text directly into the field, just like a
     * traditional select (defaults to true)
     */
    editable: true,
    /**
     * @cfg {String} allQuery The text query to send to the server to return all records for the list with no filtering (defaults to '')
     */
    allQuery: '',
    /**
     * @cfg {String} mode Set to 'local' if the ComboBox loads local data (defaults to 'remote' which loads from the server)
     */
    mode: 'remote',
    /**
     * @cfg {Number} minListWidth The minimum width of the dropdown list in pixels (defaults to 70, will be ignored if
     * listWidth has a higher value)
     */
    minListWidth : 70,
    /**
     * @cfg {Boolean} forceSelection True to restrict the selected value to one of the values in the list, false to
     * allow the user to set arbitrary text into the field (defaults to false)
     */
    forceSelection:false,
    /**
     * @cfg {Number} typeAheadDelay The length of time in milliseconds to wait until the typeahead text is displayed
     * if typeAhead = true (defaults to 250)
     */
    typeAheadDelay : 250,
    /**
     * @cfg {String} valueNotFoundText When using a name/value combo, if the value passed to setValue is not found in
     * the store, valueNotFoundText will be displayed as the field text if defined (defaults to undefined). If this
     * defaut text is used, it means there is no value set and no validation will occur on this field.
     */

    /**
     * @cfg {Boolean} lazyInit True to not initialize the list for this combo until the field is focused (defaults to true)
     */
    lazyInit : true,

    // private
    initComponent : function(){
        Ext.form.ComboBox.superclass.initComponent.call(this);
        this.addEvents(
            /**
             * @event expand
             * Fires when the dropdown list is expanded
             * @param {Ext.form.ComboBox} combo This combo box
             */
            'expand',
            /**
             * @event collapse
             * Fires when the dropdown list is collapsed
             * @param {Ext.form.ComboBox} combo This combo box
             */
            'collapse',
            /**
             * @event beforeselect
             * Fires before a list item is selected. Return false to cancel the selection.
             * @param {Ext.form.ComboBox} combo This combo box
             * @param {Ext.data.Record} record The data record returned from the underlying store
             * @param {Number} index The index of the selected item in the dropdown list
             */
            'beforeselect',
            /**
             * @event select
             * Fires when a list item is selected
             * @param {Ext.form.ComboBox} combo This combo box
             * @param {Ext.data.Record} record The data record returned from the underlying store
             * @param {Number} index The index of the selected item in the dropdown list
             */
            'select',
            /**
             * @event beforequery
             * Fires before all queries are processed. Return false to cancel the query or set the queryEvent's
             * cancel property to true.
             * @param {Object} queryEvent An object that has these properties:<ul>
             * <li><code>combo</code> : Ext.form.ComboBox <div class="sub-desc">This combo box</div></li>
             * <li><code>query</code> : String <div class="sub-desc">The query</div></li>
             * <li><code>forceAll</code> : Boolean <div class="sub-desc">True to force "all" query</div></li>
             * <li><code>cancel</code> : Boolean <div class="sub-desc">Set to true to cancel the query</div></li>
             * </ul>
             */
            'beforequery'
        );
        if(this.transform){
            this.allowDomMove = false;
            var s = Ext.getDom(this.transform);
            if(!this.hiddenName){
                this.hiddenName = s.name;
            }
            if(!this.store){
                this.mode = 'local';
                var d = [], opts = s.options;
                for(var i = 0, len = opts.length;i < len; i++){
                    var o = opts[i];
                    var value = (Ext.isIE ? o.getAttributeNode('value').specified : o.hasAttribute('value')) ? o.value : o.text;
                    if(o.selected) {
                        this.value = value;
                    }
                    d.push([value, o.text]);
                }
                this.store = new Ext.data.SimpleStore({
                    'id': 0,
                    fields: ['value', 'text'],
                    data : d
                });
                this.valueField = 'value';
                this.displayField = 'text';
            }
            s.name = Ext.id(); // wipe out the name in case somewhere else they have a reference
            if(!this.lazyRender){
                this.target = true;
                this.el = Ext.DomHelper.insertBefore(s, this.autoCreate || this.defaultAutoCreate);
                Ext.removeNode(s); // remove it
                this.render(this.el.parentNode);
            }else{
                Ext.removeNode(s); // remove it
            }
        }
        //auto-configure store from local array data
        else if(Ext.isArray(this.store)){
        	//radu - start
        	if (Ext.isArray(this.store[0])){
				this.store = new Ext.data.SimpleStore({
				    fields: ['value','text','color'],
				    data: this.store
				});
			    this.valueField = 'value';
			}else{
				this.store = new Ext.data.SimpleStore({
				    fields: ['text'],
				    data: this.store,
				    expandData: true
				});
		        this.valueField = 'text';
			}
			this.displayField = 'text';
			this.mode = 'local';			
			//radu - end
		}

        this.selectedIndex = -1;
        if(this.mode == 'local'){
            if(this.initialConfig.queryDelay === undefined){
                this.queryDelay = 10;
            }
            if(this.initialConfig.minChars === undefined){
                this.minChars = 0;
            }
        }
    },

    // private
    onRender : function(ct, position){
        Ext.form.ComboBox.superclass.onRender.call(this, ct, position);
        if(this.hiddenName){
            this.hiddenField = this.el.insertSibling({tag:'input', type:'hidden', name: this.hiddenName,
                    id: (this.hiddenId||this.hiddenName)}, 'before', true);

            // prevent input submission
            this.el.dom.removeAttribute('name');
        }
        if(Ext.isGecko){
            this.el.dom.setAttribute('autocomplete', 'off');
        }

        if(!this.lazyInit){
            this.initList();
        }else{
            this.on('focus', this.initList, this, {single: true});
        }

        if(!this.editable){
            this.editable = true;
            this.setEditable(false);
        }
    },

    // private
    initValue : function(){
        Ext.form.ComboBox.superclass.initValue.call(this);
        if(this.hiddenField){
		    this.hiddenField.value =
		        this.hiddenValue !== undefined ? this.hiddenValue :
		        this.value !== undefined ? this.value : '';
        }
    },

    // private
    initList : function(){
        if(!this.list){
            var cls = 'x-combo-list';

            this.list = new Ext.Layer({
                shadow: this.shadow, cls: [cls, this.listClass].join(' '), constrain:false
            });

            var lw = this.listWidth || Math.max(this.wrap.getWidth(), this.minListWidth);
            this.list.setWidth(lw);
            this.list.swallowEvent('mousewheel');
            this.assetHeight = 0;

            if(this.title){
                this.header = this.list.createChild({cls:cls+'-hd', html: this.title});
                this.assetHeight += this.header.getHeight();
            }

            this.innerList = this.list.createChild({cls:cls+'-inner'});
            this.innerList.on('mouseover', this.onViewOver, this);
            this.innerList.on('mousemove', this.onViewMove, this);
            this.innerList.setWidth(lw - this.list.getFrameWidth('lr'));

            if(this.pageSize){
                this.footer = this.list.createChild({cls:cls+'-ft'});
                this.pageTb = new Ext.PagingToolbar({
                    store:this.store,
                    pageSize: this.pageSize,
                    renderTo:this.footer
                });
                this.assetHeight += this.footer.getHeight();
            }

            if(!this.tpl){
                /**
                * @cfg {String/Ext.XTemplate} tpl The template string, or {@link Ext.XTemplate}
                * instance to use to display each item in the dropdown list. Use
                * this to create custom UI layouts for items in the list.
                * <p>
                * If you wish to preserve the default visual look of list items, add the CSS
                * class name <pre>x-combo-list-item</pre> to the template's container element.
                * <p>
                * <b>The template must contain one or more substitution parameters using field
                * names from the Combo's</b> {@link #store Store}. An example of a custom template
                * would be adding an <pre>ext:qtip</pre> attribute which might display other fields
                * from the Store.
                * <p>
                * The dropdown list is displayed in a DataView. See {@link Ext.DataView} for details.
                */
            	this.tpl = '<tpl for="."><div class="'+cls+'-item" style="background-color:{color};" qtip="{'+this.displayField+'}">{' + this.displayField + '}</div></tpl>';
                /**
                 * @cfg {String} itemSelector
                 * <b>This setting is required if a custom XTemplate has been specified in {@link #tpl}
                 * which assigns a class other than <pre>'x-combo-list-item'</pre> to dropdown list items</b>.
                 * A simple CSS selector (e.g. div.some-class or span:first-child) that will be
                 * used to determine what nodes the DataView which handles the dropdown display will
                 * be working with.
                 */
            }

            /**
            * The {@link Ext.DataView DataView} used to display the ComboBox's options.
            * @type Ext.DataView
            */
            this.view = new Ext.DataView({
                applyTo: this.innerList,
                tpl: this.tpl,
                singleSelect: true,
                selectedClass: this.selectedClass,
                itemSelector: this.itemSelector || '.' + cls + '-item'
            });

            this.view.on('click', this.onViewClick, this);

            this.bindStore(this.store, true);

            if(this.resizable){
                this.resizer = new Ext.Resizable(this.list,  {
                   pinned:true, handles:'se'
                });
                this.resizer.on('resize', function(r, w, h){
                    this.maxHeight = h-this.handleHeight-this.list.getFrameWidth('tb')-this.assetHeight;
                    this.listWidth = w;
                    this.innerList.setWidth(w - this.list.getFrameWidth('lr'));
                    this.restrictHeight();
                }, this);
                this[this.pageSize?'footer':'innerList'].setStyle('margin-bottom', this.handleHeight+'px');
            }
        }
    },

    // private
    bindStore : function(store, initial){
        if(this.store && !initial){
            this.store.un('beforeload', this.onBeforeLoad, this);
            this.store.un('load', this.onLoad, this);
            this.store.un('loadexception', this.collapse, this);
            if(!store){
                this.store = null;
                if(this.view){
                    this.view.setStore(null);
                }
            }
        }
        if(store){
            this.store = Ext.StoreMgr.lookup(store);

            this.store.on('beforeload', this.onBeforeLoad, this);
            this.store.on('load', this.onLoad, this);
            this.store.on('loadexception', this.collapse, this);

            if(this.view){
                this.view.setStore(store);
            }
        }
    },

    // private
    initEvents : function(){
        Ext.form.ComboBox.superclass.initEvents.call(this);

        this.keyNav = new Ext.KeyNav(this.el, {
            "up" : function(e){
                this.inKeyMode = true;
                this.selectPrev();
            },

            "down" : function(e){
                if(!this.isExpanded()){
                    this.onTriggerClick();
                }else{
                    this.inKeyMode = true;
                    this.selectNext();
                }
            },

            "enter" : function(e){
                this.onViewClick();
                this.delayedCheck = true;
                this.unsetDelayCheck.defer(10, this);
            },

            "esc" : function(e){
                this.collapse();
            },

            "tab" : function(e){
                this.onViewClick(false);
                return true;
            },

            scope : this,

            doRelay : function(foo, bar, hname){
                if(hname == 'down' || this.scope.isExpanded()){
                   return Ext.KeyNav.prototype.doRelay.apply(this, arguments);
                }
                return true;
            },

            forceKeyDown : true
        });
        this.queryDelay = Math.max(this.queryDelay || 10,
                this.mode == 'local' ? 10 : 250);
        this.dqTask = new Ext.util.DelayedTask(this.initQuery, this);
        if(this.typeAhead){
            this.taTask = new Ext.util.DelayedTask(this.onTypeAhead, this);
        }
        //enableKeyUpEvent used for custom code
        //added by radu
        if(this.enableKeyUpEvent!==false&&this.editable !== false){
            this.el.on("keyup", this.onKeyUp, this);
        }
        if(this.forceSelection){
            this.on('blur', this.doForce, this);
        }
    },

    // private
    onDestroy : function(){
        if(this.view){
            this.view.el.removeAllListeners();
            this.view.el.remove();
            this.view.purgeListeners();
        }
        if(this.list){
            this.list.destroy();
        }
        this.bindStore(null);
        Ext.form.ComboBox.superclass.onDestroy.call(this);
    },

    // private
    unsetDelayCheck : function(){
        delete this.delayedCheck;
    },

    // private
    fireKey : function(e){
        if(e.isNavKeyPress() && !this.isExpanded() && !this.delayedCheck){
            this.fireEvent("specialkey", this, e);
        }
    },

    // private
    onResize: function(w, h){
        Ext.form.ComboBox.superclass.onResize.apply(this, arguments);
        if(this.list && this.listWidth === undefined){
            var lw = Math.max(w, this.minListWidth);
            this.list.setWidth(lw);
            this.innerList.setWidth(lw - this.list.getFrameWidth('lr'));
        }
    },

    // private
    onEnable: function(){
        Ext.form.ComboBox.superclass.onEnable.apply(this, arguments);
        if(this.hiddenField){
            this.hiddenField.disabled = false;
        }
    },

    // private
    onDisable: function(){
        Ext.form.ComboBox.superclass.onDisable.apply(this, arguments);
        if(this.hiddenField){
            this.hiddenField.disabled = true;
        }
    },

    /**
     * Allow or prevent the user from directly editing the field text.  If false is passed,
     * the user will only be able to select from the items defined in the dropdown list.  This method
     * is the runtime equivalent of setting the 'editable' config option at config time.
     * @param {Boolean} value True to allow the user to directly edit the field text
     */
    setEditable : function(value){
        if(value == this.editable){
            return;
        }
        this.editable = value;
        if(!value){
            this.el.dom.setAttribute('readOnly', true);
            this.el.on('mousedown', this.onTriggerClick,  this);
            this.el.addClass('x-combo-noedit');
        }else{
            this.el.dom.setAttribute('readOnly', false);
            this.el.un('mousedown', this.onTriggerClick,  this);
            this.el.removeClass('x-combo-noedit');
        }
    },

    // private
    onBeforeLoad : function(){
        if(!this.hasFocus){
            return;
        }
        this.innerList.update(this.loadingText ?
               '<div class="loading-indicator">'+this.loadingText+'</div>' : '');
        this.restrictHeight();
        this.selectedIndex = -1;
    },

    // private
    onLoad : function(){
        if(!this.hasFocus){
            return;
        }
        if(this.store.getCount() > 0){
            this.expand();
            this.restrictHeight();
            if(this.lastQuery == this.allQuery){
                if(this.editable){
                    this.el.dom.select();
                }
                if(!this.selectByValue(this.value, true)){
                    this.select(0, true);
                }
            }else{
                this.selectNext();
                if(this.typeAhead && this.lastKey != Ext.EventObject.BACKSPACE && this.lastKey != Ext.EventObject.DELETE){
                    this.taTask.delay(this.typeAheadDelay);
                }
            }
        }else{
            this.onEmptyResults();
        }
        //this.el.focus();
    },

    // private
    onTypeAhead : function(){
        if(this.store.getCount() > 0){
            var r = this.store.getAt(0);
            var newValue = r.data[this.displayField];
            var len = newValue.length;
            var selStart = this.getRawValue().length;
            if(selStart != len){
                this.setRawValue(newValue);
                this.selectText(selStart, newValue.length);
            }
        }
    },

    // private
    onSelect : function(record, index){
        if(this.fireEvent('beforeselect', this, record, index) !== false){
            this.setValue(record.data[this.valueField || this.displayField]);
            this.collapse();
            this.fireEvent('select', this, record, index);
        }
    },

    /**
     * Returns the currently selected field value or empty string if no value is set.
     * @return {String} value The selected value
     */
    getValue : function(){
        if(this.valueField){
            return typeof this.value != 'undefined' ? this.value : '';
        }else{
            return Ext.form.ComboBox.superclass.getValue.call(this);
        }
    },

    /**
     * Clears any text/value currently set in the field
     */
    clearValue : function(){
        if(this.hiddenField){
            this.hiddenField.value = '';
        }
        this.setRawValue('');
        this.lastSelectionText = '';
        this.applyEmptyText();
        this.value = '';
    },

    /**
     * Sets the specified value into the field.  If the value finds a match, the corresponding record text
     * will be displayed in the field.  If the value does not match the data value of an existing item,
     * and the valueNotFoundText config option is defined, it will be displayed as the default field text.
     * Otherwise the field will be blank (although the value will still be set).
     * @param {String} value The value to match
     */
    setValue : function(v){
        var text = v;
        if(this.valueField){
            var r = this.findRecord(this.valueField, v);
            if(r){
                text = r.data[this.displayField];
            }else if(this.valueNotFoundText !== undefined){
                text = this.valueNotFoundText;
            }
        }
        this.lastSelectionText = text;
        if(this.hiddenField){
            this.hiddenField.value = v;
        }
        Ext.form.ComboBox.superclass.setValue.call(this, text);
        this.value = v;
    },

    // private
    findRecord : function(prop, value){
        var record;
        if(this.store.getCount() > 0){
            this.store.each(function(r){
                if(r.data[prop] == value){
                    record = r;
                    return false;
                }
            });
        }
        return record;
    },

    // private
    onViewMove : function(e, t){
        this.inKeyMode = false;
    },

    // private
    onViewOver : function(e, t){
        if(this.inKeyMode){ // prevent key nav and mouse over conflicts
            return;
        }
        var item = this.view.findItemFromChild(t);
        if(item){
            var index = this.view.indexOf(item);
            this.select(index, false);
        }
    },

    // private
    onViewClick : function(doFocus){
        var index = this.view.getSelectedIndexes()[0];
        var r = this.store.getAt(index);
        if(r){
            this.onSelect(r, index);
        }
        if(doFocus !== false){
            this.el.focus();
        }
    },

    // private
    restrictHeight : function(){
        this.innerList.dom.style.height = '';
        var inner = this.innerList.dom;
        var pad = this.list.getFrameWidth('tb')+(this.resizable?this.handleHeight:0)+this.assetHeight;
        var h = Math.max(inner.clientHeight, inner.offsetHeight, inner.scrollHeight);
        var ha = this.getPosition()[1]-Ext.getBody().getScroll().top;
        var hb = Ext.lib.Dom.getViewHeight()-ha-this.getSize().height;
        var space = Math.max(ha, hb, this.minHeight || 0)-this.list.shadowOffset-pad-5;
        h = Math.min(h, space, this.maxHeight);

        this.innerList.setHeight(h);
        this.list.beginUpdate();
        this.list.setHeight(h+pad);
        this.list.alignTo(this.wrap, this.listAlign);
        this.list.endUpdate();
    },

    // private
    onEmptyResults : function(){
        this.collapse();
    },

    /**
     * Returns true if the dropdown list is expanded, else false.
     */
    isExpanded : function(){
        return this.list && this.list.isVisible();
    },

    /**
     * Select an item in the dropdown list by its data value. This function does NOT cause the select event to fire.
     * The store must be loaded and the list expanded for this function to work, otherwise use setValue.
     * @param {String} value The data value of the item to select
     * @param {Boolean} scrollIntoView False to prevent the dropdown list from autoscrolling to display the
     * selected item if it is not currently in view (defaults to true)
     * @return {Boolean} True if the value matched an item in the list, else false
     */
    selectByValue : function(v, scrollIntoView){
        if(v !== undefined && v !== null){
            var r = this.findRecord(this.valueField || this.displayField, v);
            if(r){
                this.select(this.store.indexOf(r), scrollIntoView);
                return true;
            }
        }
        return false;
    },

    /**
     * Select an item in the dropdown list by its numeric index in the list. This function does NOT cause the select event to fire.
     * The store must be loaded and the list expanded for this function to work, otherwise use setValue.
     * @param {Number} index The zero-based index of the list item to select
     * @param {Boolean} scrollIntoView False to prevent the dropdown list from autoscrolling to display the
     * selected item if it is not currently in view (defaults to true)
     */
    select : function(index, scrollIntoView){
        this.selectedIndex = index;
        this.view.select(index);
        if(scrollIntoView !== false){
            var el = this.view.getNode(index);
            if(el){
                this.innerList.scrollChildIntoView(el, false);
            }
        }
    },

    // private
    selectNext : function(){
        var ct = this.store.getCount();
        if(ct > 0){
            if(this.selectedIndex == -1){
                this.select(0);
            }else if(this.selectedIndex < ct-1){
                this.select(this.selectedIndex+1);
            }
        }
    },

    // private
    selectPrev : function(){
        var ct = this.store.getCount();
        if(ct > 0){
            if(this.selectedIndex == -1){
                this.select(0);
            }else if(this.selectedIndex != 0){
                this.select(this.selectedIndex-1);
            }
        }
    },

    // private
    onKeyUp : function(e){
        if(this.editable !== false && !e.isSpecialKey()){
            this.lastKey = e.getKey();
            this.dqTask.delay(this.queryDelay);
        }
    },

    // private
    validateBlur : function(){
        return !this.list || !this.list.isVisible();
    },

    // private
    initQuery : function(){
        this.doQuery(this.getRawValue());
    },

    // private
    doForce : function(){
        if(this.el.dom.value.length > 0){
            this.el.dom.value =
                this.lastSelectionText === undefined ? '' : this.lastSelectionText;
            this.applyEmptyText();
        }
    },

    /**
     * Execute a query to filter the dropdown list.  Fires the {@link #beforequery} event prior to performing the
     * query allowing the query action to be canceled if needed.
     * @param {String} query The SQL query to execute
     * @param {Boolean} forceAll True to force the query to execute even if there are currently fewer characters
     * in the field than the minimum specified by the minChars config option.  It also clears any filter previously
     * saved in the current store (defaults to false)
     */
    doQuery : function(q, forceAll){
        if(q === undefined || q === null){
            q = '';
        }
        var qe = {
            query: q,
            forceAll: forceAll,
            combo: this,
            cancel:false
        };
        if(this.fireEvent('beforequery', qe)===false || qe.cancel){
            return false;
        }
        q = qe.query;
        forceAll = qe.forceAll;
        if(forceAll === true || (q.length >= this.minChars)){
            if(this.lastQuery !== q){
                this.lastQuery = q;
                if(this.mode == 'local'){
                    this.selectedIndex = -1;
                    if(forceAll){
                        this.store.clearFilter();
                    }else{
                        this.store.filter(this.displayField, q);
                    }
                    this.onLoad();
                }else{
                    this.store.baseParams[this.queryParam] = q;
                    this.store.load({
                        params: this.getParams(q)
                    });
                    this.expand();
                }
            }else{
                this.selectedIndex = -1;
                this.onLoad();
            }
        }
    },

    // private
    getParams : function(q){
        var p = {};
        //p[this.queryParam] = q;
        if(this.pageSize){
            p.start = 0;
            p.limit = this.pageSize;
        }
        return p;
    },

    /**
     * Hides the dropdown list if it is currently expanded. Fires the {@link #collapse} event on completion.
     */
    collapse : function(){
        if(!this.isExpanded()){
            return;
        }
        this.list.hide();
        Ext.getDoc().un('mousewheel', this.collapseIf, this);
        Ext.getDoc().un('mousedown', this.collapseIf, this);
        this.fireEvent('collapse', this);
    },

    // private
    collapseIf : function(e){
        if(!e.within(this.wrap) && !e.within(this.list)){
            this.collapse();
        }
    },

    /**
     * Expands the dropdown list if it is currently hidden. Fires the {@link #expand} event on completion.
     */
    expand : function(){
        if(this.isExpanded() || !this.hasFocus){
            return;
        }
        
        this.list.alignTo(this.wrap, this.listAlign);
        this.list.show();
        this.innerList.setOverflow('auto'); // necessary for FF 2.0/Mac
        Ext.getDoc().on('mousewheel', this.collapseIf, this);
        Ext.getDoc().on('mousedown', this.collapseIf, this);
        this.fireEvent('expand', this);
    },

    /**
     * @method onTriggerClick
     * @hide
     */
    // private
    // Implements the default empty TriggerField.onTriggerClick function
    onTriggerClick : function(){
        if(this.disabled){
            return;
        }
        if(this.isExpanded()){
            this.collapse();
            this.el.focus();
        }else {
            this.onFocus({});
            if(this.triggerAction == 'all') {
                this.doQuery(this.allQuery, true);
            } else {
                this.doQuery(this.getRawValue());
            }
            this.el.focus();
        }
    }

    /**
     * @hide
     * @method autoSize
     */
    /**
     * @cfg {Boolean} grow @hide
     */
    /**
     * @cfg {Number} growMin @hide
     */
    /**
     * @cfg {Number} growMax @hide
     */

});
Ext.reg('combowcolors', Ext.ux.form.ComboBox);

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/form/Ext.ux.form.Combo.js  ]*/

Ext.namespace('Ext.ux.plugins');
Ext.ux.plugins.ExtendedComboBox = {
    init: function(container){
        Ext.apply(container, {
            onRender: container.onRender.createSequence(function(ct, position){
                // adjust styles
            	var combo = this;
                this.wrap.applyStyles({position:'relative'});
                this.el.applyStyles({border:'0px',backgroundColor:'transparent', textDecoration:'underline'});
                
                this.el.removeClass('x-form-text'); 
                this.el.removeClass('x-form-field'); 
                this.el.on("click",function(){combo.expand()})
                this.trigger.removeClass('x-form-trigger');
                this.trigger.removeClass('x-form-arrow-trigger');                    
                this.trigger.applyStyles({backgroundImage:'none',border:'0px',width:'0px'});
                //this.emptyText = "Select One";
                this.on('focus', function(boundEl,value) {
                  	combo.expand();// some code goes in here
                });
            }) // end of function onRender
        });
     
        
    }
};

Ext.ux.plugins.ExtendedComboBox = Ext.extend(Ext.ux.plugins.ExtendedComboBox, Ext.util.Observable);
// end of file

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/form/Ext.ux.plugins.ExtendedComboBox.js  ]*/

/**
Extended Item Selector
Ability to auto suggest
@author: Prakash Paudel
*/
Ext.ux.RemoteComboAutoSuggest = Ext.extend(Ext.form.ComboBox,  {
    
    initComponent: function(){
        Ext.ux.RemoteComboAutoSuggest.superclass.initComponent.call(this);
         this.addEvents({
            'keyup' : true,
            'change' : true
        }); 
        this.preValue = this.getValue(); 
        this.firstTime = true;
    },	
    onRender: function(ct, position){
        Ext.ux.RemoteComboAutoSuggest.superclass.onRender.call(this, ct, position);
        combo = this;
        combo.displayField = "value";
        combo.valueField = "key";       
        store = new Ext.data.JsonStore({
			url: this.url,
			fields: [
				'key','value'
			]
		});
		store.load({params:{id:this.preValue}});
		store.on("load",function(){
			combo.bindStore(store);
			if(this.firstTime){
				combo.setValue(this.preValue);
				this.firstTime = false;
			}			
		},this)
		var delay = new Ext.util.DelayedTask(function(){
			keyword = combo.el.getValue();
			if(keyword != combo.preValue){
				combo.store.load({params:{like:keyword}});
				combo.preValue = keyword;
			}
		});
		onkeyup = function(){
			delay.delay(1000);				
		}
		
		
    }
});

Ext.reg("remotecomboautosuggest", Ext.ux.RemoteComboAutoSuggest);

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/form/Ext.ux.plugins.RemoteComboAutoSuggest.js  ]*/

// vim: ts=4:sw=4:nu:fdc=4:nospell
/**
 * Ext.ux.form.LovCombo, List of Values Combo
 *
 * @author    Ing. Jozef Sak�lo�
 * @copyright (c) 2008, by Ing. Jozef Sak�lo�
 * @date      16. April 2008
 * @version   $Id: Ext.ux.form.LovCombo.js 285 2008-06-06 09:22:20Z jozo $
 *
 * @license Ext.ux.form.LovCombo.js is licensed under the terms of the Open Source
 * LGPL 3.0 license. Commercial use is permitted to the extent that the 
 * code/component(s) do NOT become part of another Open Source or Commercially
 * licensed development library or toolkit without explicit permission.
 * 
 * License details: http://www.gnu.org/licenses/lgpl.html
 */
 
/*global Ext */

// add RegExp.escape if it has not been already added
if('function' !== typeof RegExp.escape) {
    RegExp.escape = function(s) {
        if('string' !== typeof s) {
            return s;
        }
        // Note: if pasting from forum, precede ]/\ with backslash manually
        return s.replace(/([.*+?^=!:${}()|[\]\/\\])/g, '\\$1');
    }; // eo function escape
}

// create namespace
Ext.ns('Ext.ux.form');
 
/**
 *
 * @class Ext.ux.form.LovCombo
 * @extends Ext.form.ComboBox
 */
Ext.ux.form.LovCombo = Ext.extend(Ext.form.ComboBox, {

    // {{{
    // configuration options
    /**
     * @cfg {String} selectAllOn the value of the option used as 
     * the select-all / deselect-all trigger
     */
    selectAllOn: 'select-deselect-all',
    /**
     * @cfg {String} checkField name of field used to store checked state.
     * It is automatically added to existing fields.
     * Change it only if it collides with your normal field.
     */
     checkField:'checked'

    /**
     * @cfg {String} separator separator to use between values and texts for getValue and submission
     */
    ,separator:','

    /**
     * @cfg {String} displaySeparator displaySeparator to use between values and texts for display
     */
    ,displaySeparator:','

    /**
     * @cfg {String/Array} tpl Template for items. 
     * Change it only if you know what you are doing.
     */
    // }}}
    // {{{
    ,initComponent:function() {
		// call parent
	    Ext.ux.form.LovCombo.superclass.initComponent.apply(this, arguments);
    
        // template with checkbox
        if(!this.tpl) {
            this.tpl = 
                 '<tpl for=".">'
                +'<div class="x-combo-list-item">'
                +'<img src="' + Ext.BLANK_IMAGE_URL + '" '
                +'class="ux-lovcombo-icon ux-lovcombo-icon-'
                //+'{[values.' + this.checkField + '?"checked":values.' + this.checkField + '===null?"mixed":"unchecked"' + ']}">'
                +'{[values.' + this.checkField + '?"checked":"unchecked"' + ']}">'
                +'<div class="ux-lovcombo-item-text">{' + (this.displayField || 'text' )+ '}</div>'
                +'</div>'
                +'</tpl>'
            ;
        }
 
       

        // install internal event handlers
        this.on({
             scope:this
            ,beforequery:this.onBeforeQuery
            //,blur:this.onRealBlur
        });

        this.onRender = this.onRender.createSequence(function(){
        	if(!this.getStore())return;
        	var MyRecordType = Ext.data.Record.create([this.valueField, this.displayField]);        	
        	var json = '({"'+this.valueField+'":"select-deselect-all","'+this.displayField+'":"<span style=\'display:block\'><b>Select/Deselect All</b></span>"})';        	
        	myrec = new MyRecordType(eval(json));        	
        	this.getStore().insert(0,myrec);
        	if(this.selectAllCheck){        		
        		var record = this.getStore().getAt(0);
        		record.set(this.checkField, true);
                this.doQuery(this.allQuery);
        	}
        	
        })
        // remove selection from input field
        this.onLoad = this.onLoad.createSequence(function() {        	
            if(this.el) {
                var v = this.el.dom.value;
                this.el.dom.value = '';
                this.el.dom.value = v;
            }
        });
		this.hideTask = new Ext.util.DelayedTask(this.hideList, this);
 
    } // e/o function initComponent
    // }}}
    // {{{
    /**
     * Disables default tab key bahavior
     * @private
     */
    ,initEvents:function() {
        Ext.ux.form.LovCombo.superclass.initEvents.apply(this, arguments);

        // disable default tab handling - does no good
        this.keyNav.tab = false;

    } // eo function initEvents
    // }}}
    // {{{
    /**
     * clears value
     */
    ,clearValue:function() {
        this.value = '';
        this.setRawValue(this.value);
        this.store.clearFilter();
        this.store.each(function(r) {
            r.set(this.checkField, false);
        }, this);
        if(this.hiddenField) {
            this.hiddenField.value = '';
        }
        this.applyEmptyText();
    } // eo function clearValue
    // }}}
    // {{{
    /**
     * @return {String} separator (plus space) separated list of selected displayFields
     * @private
     */
    ,getCheckedDisplay:function() {
        var re = new RegExp(RegExp.escape(this.separator), "g");
        return this.getCheckedValue(this.displayField).replace(re, RegExp.escape(this.displaySeparator) + ' ');
    } // eo function getCheckedDisplay
    // }}}
    // {{{
    /**
     * @return {String} separator separated list of selected valueFields
     * @private
     */
    ,getCheckedValue:function(field) {
        field = field || this.valueField;
        var c = [];

        // store may be filtered so get all records
        var snapshot = this.store.snapshot || this.store.data;
        snapshot.each(function(r) {
            if (r.get(this.checkField) && r.data[this.valueField] !== this.selectAllOn)
                c.push(r.get(field));
        }, this);

        return c.join(this.separator);
    } // eo function getCheckedValue

    ,selectAllCheck:function() {
        var snapshot = this.store.snapshot || this.store.data;        
        var selectAll = true;
        snapshot.each(function(r) {
            if (r.data[this.valueField] !== this.selectAllOn && !r.get(this.checkField)) {
                selectAll = false;
                return;
            }
        }, this);        
        return selectAll;
    }

    // }}}
    // {{{
    /**
     * beforequery event handler - handles multiple selections
     * @param {Object} qe query event
     * @private
     */
    ,onBeforeQuery:function(qe) {
        qe.query = qe.query.replace(new RegExp(this.getCheckedDisplay() + '[ ' + RegExp.escape(this.separator) + ']*'), '');
    } // eo function onBeforeQuery
    // }}}
    // {{{
    /**
     * blur event handler - runs only when real blur event is fired
     */
    ,beforeBlur:function() {
        this.list.hide();
        var rv = this.getRawValue();
        var rva = rv.split(new RegExp(RegExp.escape(this.displaySeparator) + ' *'));
        var va = [];
        var snapshot = this.store.snapshot || this.store.data;

        // iterate through raw values and records and check/uncheck items
        Ext.each(rva, function(v) {
            snapshot.each(function(r) {
                if(v === r.get(this.displayField)) {                	
                    va.push(r.get(this.valueField));
                }
            }, this);
        }, this);
        //this.setValue(va.join(this.separator));
        this.store.clearFilter();
    } // eo function onRealBlur
    // }}}
    // {{{
    /**
     * Combo's onSelect override
     * @private
     * @param {Ext.data.Record} record record that has been selected in the list
     * @param {Number} index index of selected (clicked) record
     */
    ,onSelect:function(record, index) {
        if(this.fireEvent('beforeselect', this, record, index) !== false){

            // toggle checked field
            record.set(this.checkField, !record.get(this.checkField));

            // display full list
            if(this.store.isFiltered()) {
                this.doQuery(this.allQuery);
            }

            // set (update) value and fire event
            if(record.data[this.valueField] === this.selectAllOn){
                if(record.get(this.checkField)){
                    this.selectAll();
                }else{
                    this.deselectAll();
                }                
            }else{
                this.setValue(this.getCheckedValue());
            }            
            this.fireEvent('select', this, record, index);
        }
		this.hideTask.delay(3000);
    } // eo function onSelect
    // }}}
	
	,hideList: function(){
		this.list.hide();
	}
    // {{{
    /**
     * Sets the value of the LovCombo
     * @param {Mixed} v value
     */
    ,setValue:function(v) { 
        if(v) {
            v = '' + v;
            if(this.valueField) {            	
                this.store.clearFilter();
                this.store.each(function(r) {
                    if (r.data[this.valueField] === this.selectAllOn && this.selectAllCheck())
                        r.set(this.checkField, true);
                    else if (r.data[this.valueField] === this.selectAllOn)
                        r.set(this.checkField, null);
                    else {
                        var checked = !(!v.match(
                             '(^|' + RegExp.escape(this.separator) + ')' + RegExp.escape(r.get(this.valueField))
                            +'(' + RegExp.escape(this.separator) + '|$)'))
                        ;
                        r.set(this.checkField, checked);
                    }
                }, this);
                
                this.value = this.getCheckedValue();
                this.setRawValue(this.getCheckedDisplay());
                if(this.hiddenField) {
                    this.hiddenField.value = this.value;
                }
            }
            else {
                this.value = v;
                this.setRawValue(v);
                if(this.hiddenField) {
                    this.hiddenField.value = v;
                }
            }
            if(this.el) {
                this.el.removeClass(this.emptyClass);
            }
        }
        else {
            this.clearValue();
        }    	
        
    } // eo function setValue
    // }}}
    // {{{
    /**
     * Selects all items
     */
    ,selectAll:function() {
        this.store.each(function(record){
            // toggle checked field
            record.set(this.checkField, true);
        }, this);

        //display full list
        this.doQuery(this.allQuery);
        this.setValue(this.getCheckedValue());
    } // eo full selectAll
    // }}}
    // {{{
    /**
     * Deselects all items. Synonym for clearValue
     */
    ,deselectAll:function() {
        this.clearValue();
    } // eo full deselectAll 
    // }}}

}); // eo extend
 
// register xtype
Ext.reg('lovcombo', Ext.ux.form.LovCombo); 
 
// eof

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/form/lovcombo-1.0/js/Ext.ux.form.LovCombo.js  ]*/

/**
Captcha input field
Verifying human involvament in form processing
@author: Prakash Paudel
*/
Ext.ux.Captcha = Ext.extend(Ext.form.TextField,  {
    
    initComponent: function(){
        Ext.ux.Captcha.superclass.initComponent.call(this);          
    },
	
    onRender: function(ct, position){
        Ext.ux.Captcha.superclass.onRender.call(this, ct, position);
        randId = 'captcha_image_'+(Math.floor(Math.random()*10000000));
        src = this.src;
        captchaDiv = Ext.DomHelper.insertFirst(ct, {tag: 'div', style:'width:'+this.width+'px; margin:0px; margin-bottom:2px;float:left; padding:0px;clear:both;'});
        captchaImgDiv = Ext.DomHelper.insertFirst(captchaDiv, {tag: 'div', style:'margin:0px;float:left; padding:0px'});
        reloadImgDiv = Ext.DomHelper.insertFirst(captchaDiv, {tag: 'div', html:'<div style="color:#888; padding-bottom:2px;">Can\'t see image? <a href="#" onclick="document.getElementById(randId).src=document.getElementById(randId).src+\'#\'" style="color:#0000ff">Reload</a></div>',  style:'margin:0px;float:right; padding:0px'});
        captchaImg = Ext.DomHelper.append(captchaImgDiv, {tag: 'img', id:randId, src:this.src, width:this.width,style:this.imgStyle});  
        if(!this.input){
        	this.el.applyStyles({display:'none'});
        }
    }
});

Ext.reg("captcha", Ext.ux.Captcha);

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/form/Ext.ux.plugins.Captcha.js  ]*/

/**
Extended Item Selector
Ability to auto suggest
@author: Prakash Paudel
*/
Ext.ux.ItemSelectorAutoSuggest = Ext.extend(Ext.form.Field,  {
    msWidth:200,
    msHeight:300,
    hideNavIcons:false,
    imagePath:"",
    iconUp:"up2.gif",
    iconDown:"down2.gif",
    iconLeft:"left2.gif",
    iconRight:"right2.gif",
    iconTop:"top2.gif",
    iconBottom:"bottom2.gif",
    drawUpIcon:true,
    drawDownIcon:true,
    drawLeftIcon:true,
    drawRightIcon:true,
    drawTopIcon:true,
    drawBotIcon:true,
    url:null,
    fromStore:null,
    toStore:null,
    fromData:null, 
    toData:null,
    displayField:0,
    valueField:1,
    switchToFrom:false,
    allowDup:false,
    focusClass:undefined,
    delimiter:',',
    readOnly:false,
    toLegend:null,
    fromLegend:null,
    toSortField:null,
    fromSortField:null,
    toSortDir:'ASC',
    fromSortDir:'ASC',
    toTBar:null,
    fromTBar:null,
    bodyStyle:null,
    border:false,
    loadOnChange:true,
    defaultAutoCreate:{tag: "div"},
    keyword:null,
    randN: Math.floor(Math.random()*10000000),
    fromMultiselect:null,
    
    initComponent: function(){
        Ext.ux.ItemSelectorAutoSuggest.superclass.initComponent.call(this);
        this.addEvents({
            'rowdblclick' : true,
            'change' : true
        });         
    },
	onOkButtonClick: function(keyword,option){
		this.fromMultiselect.el.mask("Loading...");	
		this.fromStore.load({params:{like:keyword,option:option}});
		ms = this.fromMultiselect;
		this.fromStore.on('load',function(){ms.el.unmask();});	
	},
	onDataLoad:function(){
		ms = this.fromMultiselect;
		this.fromMultiselect.el.mask("Loading...");		
		this.fromMultiselect.store.load();
		this.fromMultiselect.store.on('load',function(){ms.el.unmask();});		
	},
    onRender: function(ct, position){
        Ext.ux.ItemSelectorAutoSuggest.superclass.onRender.call(this, ct, position);
              
		var AS_optioncombo = new Ext.form.ComboBox({	
			parent:this,		
			name: this.id+"_optioncombo_"+this.randN,
			store:new Ext.data.SimpleStore({
				fields:[
					'value','display'
				],
				data:[
					['start_with','Starts with'],['any','Match Any']
				]
			}),	
			listeners:{
				change: function(){
					if(AS_keyword.getValue() != AS_keyword.originalValue && AS_keyword.getValue() != "")
					this.parent.onOkButtonClick(AS_keyword.getValue(),AS_optioncombo.getValue())
				}
			},
			width:80,
			displayField:'display',
			valueField:'value',
			triggerAction:'all',
			mode:'local',
			lazyRender:true
		});
		AS_optioncombo.setValue('any')
		this.fromStore = new Ext.data.JsonStore({
			url: this.url,
			fields: [
				this.valueField,this.displayField
			]
		});	
		var AS_okbutton = new Ext.Button({
			name: this.id+"_okbutton_"+this.randN,
			text:"Go",
			parent:this,
			listeners:{
				click:function(){this.parent.onOkButtonClick(AS_keyword.getValue(),AS_optioncombo.getValue())}
			}			
		});
		var AS_listallbutton = new Ext.Button({
			name: this.id+"_listallbutton_"+this.randN,
			text:"List All",
			parent:this,
			listeners:{
				click:function(){this.parent.onOkButtonClick('%%','')}
			}			
		});

		if(this.loadOnChange){				
			var AS_keyword = new Ext.form.TextField({
				name: this.id+"_keyword_"+this.randN,
				id: this.id+"_keyword_"+this.randN,
				emptyText:"Filter...",
				parent:this,
				width:100,
				enableKeyEvents: true,
				listeners:{
					keyup: function(){this.parent.onOkButtonClick(AS_keyword.getValue(),AS_optioncombo.getValue())}
				}
				
			});			
			var AS_fromTBar = new Ext.Toolbar({
				items:[
					AS_optioncombo,AS_keyword,AS_listallbutton
				]
			});
		}else{			
			var AS_keyword = new Ext.form.TextField({
				name: this.id+"_keyword_"+this.randN,
				id: this.id+"_keyword_"+this.randN,
				emptyText:"Filter...",
				width:100
				
			});
			var AS_fromTBar = new Ext.Toolbar({
				items:[
					AS_optioncombo,AS_keyword,AS_okbutton,AS_listallbutton
				]
			});	
		}	
		
		this.fromTBar = AS_fromTBar;		
		
        this.fromMultiselect = new Ext.ux.Multiselect({
            legend: this.fromLegend,
            delimiter: this.delimiter,
            allowDup: this.allowDup,
            copy: this.allowDup,
            allowTrash: this.allowDup,
            dragGroup: this.readOnly ? null : "drop2-"+this.el.dom.id,
            dropGroup: this.readOnly ? null : "drop1-"+this.el.dom.id,
            width: this.msWidth,
            height: this.msHeight,
            dataFields: this.dataFields,
            data: this.fromData,
            displayField: this.displayField,
            valueField: this.valueField,
            store: this.fromStore,
            isFormField: false,
            tbar: this.fromTBar,
            appendOnly: true,
            sortField: this.fromSortField,
            sortDir: this.fromSortDir,
            id: this.id+"_from_multi_select_"+this.randN
        });
        this.fromMultiselect.on('dblclick', this.onRowDblClick, this);

        if (!this.toStore) {
            this.toStore = new Ext.data.SimpleStore({
                fields: this.dataFields,
                data : this.toData
            });
        }
        this.toStore.on('add', this.valueChanged, this);
        this.toStore.on('remove', this.valueChanged, this);
        this.toStore.on('load', this.valueChanged, this);

        this.toMultiselect = new Ext.ux.Multiselect({
            legend: this.toLegend,
            delimiter: this.delimiter,
            allowDup: this.allowDup,
            dragGroup: this.readOnly ? null : "drop1-"+this.el.dom.id,
            //dropGroup: this.readOnly ? null : "drop2-"+this.el.dom.id+(this.toSortField ? "" : ",drop1-"+this.el.dom.id),
            dropGroup: this.readOnly ? null : "drop2-"+this.el.dom.id+",drop1-"+this.el.dom.id,
            width: this.msWidth,
            height: this.msHeight,
            displayField: this.displayField,
            valueField: this.valueField,
            store: this.toStore,
            isFormField: false,
            tbar: this.toTBar,
            sortField: this.toSortField,
            sortDir: this.toSortDir
        });
        this.toMultiselect.on('dblclick', this.onRowDblClick, this);
                
        var p = new Ext.Panel({
            bodyStyle:this.bodyStyle,
            border:this.border,
            layout:"table",
            layoutConfig:{columns:3},
            width:530,

        });
        p.add(this.switchToFrom ? this.toMultiselect : this.fromMultiselect);
        var icons = new Ext.Panel({header:false});
        p.add(icons);
        p.add(this.switchToFrom ? this.fromMultiselect : this.toMultiselect);
        p.render(this.el);
        icons.el.down('.'+icons.bwrapCls).remove();

        if (this.imagePath!="" && this.imagePath.charAt(this.imagePath.length-1)!="/")
            this.imagePath+="/";
        this.iconUp = this.imagePath + (this.iconUp || 'up2.gif');
        this.iconDown = this.imagePath + (this.iconDown || 'down2.gif');
        this.iconLeft = this.imagePath + (this.iconLeft || 'left2.gif');
        this.iconRight = this.imagePath + (this.iconRight || 'right2.gif');
        this.iconTop = this.imagePath + (this.iconTop || 'top2.gif');
        this.iconBottom = this.imagePath + (this.iconBottom || 'bottom2.gif');
        var el=icons.getEl();
        if (!this.toSortField) {
            this.toTopIcon = el.createChild({tag:'img', src:this.iconTop, style:{cursor:'pointer', margin:'2px'}});
            el.createChild({tag: 'br'});
            this.upIcon = el.createChild({tag:'img', src:this.iconUp, style:{cursor:'pointer', margin:'2px'}});
            el.createChild({tag: 'br'});
        }
        this.addIcon = el.createChild({tag:'img', src:this.switchToFrom?this.iconLeft:this.iconRight, style:{cursor:'pointer', margin:'2px'}});
        el.createChild({tag: 'br'});
        this.removeIcon = el.createChild({tag:'img', src:this.switchToFrom?this.iconRight:this.iconLeft, style:{cursor:'pointer', margin:'2px'}});
        el.createChild({tag: 'br'});
        if (!this.toSortField) {
            this.downIcon = el.createChild({tag:'img', src:this.iconDown, style:{cursor:'pointer', margin:'2px'}});
            el.createChild({tag: 'br'});
            this.toBottomIcon = el.createChild({tag:'img', src:this.iconBottom, style:{cursor:'pointer', margin:'2px'}});
        }
        if (!this.readOnly) {
            if (!this.toSortField) {
                this.toTopIcon.on('click', this.toTop, this);
                this.upIcon.on('click', this.up, this);
                this.downIcon.on('click', this.down, this);
                this.toBottomIcon.on('click', this.toBottom, this);
            }
            this.addIcon.on('click', this.fromTo, this);
            this.removeIcon.on('click', this.toFrom, this);
        }
        if (!this.drawUpIcon || this.hideNavIcons) { this.upIcon.dom.style.display='none'; }
        if (!this.drawDownIcon || this.hideNavIcons) { this.downIcon.dom.style.display='none'; }
        if (!this.drawLeftIcon || this.hideNavIcons) { this.addIcon.dom.style.display='none'; }
        if (!this.drawRightIcon || this.hideNavIcons) { this.removeIcon.dom.style.display='none'; }
        if (!this.drawTopIcon || this.hideNavIcons) { this.toTopIcon.dom.style.display='none'; }
        if (!this.drawBotIcon || this.hideNavIcons) { this.toBottomIcon.dom.style.display='none'; }

        var tb = p.body.first();
        this.el.setWidth(p.body.first().getWidth());
        p.body.removeClass();
        
        this.hiddenName = this.name;
        var hiddenTag={tag: "input", type: "hidden", value: "", name:this.name};
        this.hiddenField = this.el.createChild(hiddenTag);
        this.valueChanged(this.toStore);        
       
		
        
        //Load data
        //this.fromStore.load();
        //this.fromStore.on('load',this.onDataLoad(this.fromMultiselect,''));
    },
    
    initValue:Ext.emptyFn,
    
    toTop : function() {
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        var records = [];
        if (selectionsArray.length > 0) {
            selectionsArray.sort();
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                records.push(record);
            }
            selectionsArray = [];
            for (var i=records.length-1; i>-1; i--) {
                record = records[i];
                this.toMultiselect.view.store.remove(record);
                this.toMultiselect.view.store.insert(0, record);
                selectionsArray.push(((records.length - 1) - i));
            }
        }
        this.toMultiselect.view.refresh();
        this.toMultiselect.view.select(selectionsArray);
    },

    toBottom : function() {
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        var records = [];
        if (selectionsArray.length > 0) {
            selectionsArray.sort();
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                records.push(record);
            }
            selectionsArray = [];
            for (var i=0; i<records.length; i++) {
                record = records[i];
                this.toMultiselect.view.store.remove(record);
                this.toMultiselect.view.store.add(record);
                selectionsArray.push((this.toMultiselect.view.store.getCount()) - (records.length - i));
            }
        }
        this.toMultiselect.view.refresh();
        this.toMultiselect.view.select(selectionsArray);
    },
    
    up : function() {
        var record = null;
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        selectionsArray.sort();
        var newSelectionsArray = [];
        if (selectionsArray.length > 0) {
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                if ((selectionsArray[i] - 1) >= 0) {
                    this.toMultiselect.view.store.remove(record);
                    this.toMultiselect.view.store.insert(selectionsArray[i] - 1, record);
                    newSelectionsArray.push(selectionsArray[i] - 1);
                }
            }
            this.toMultiselect.view.refresh();
            this.toMultiselect.view.select(newSelectionsArray);
        }
    },

    down : function() {
        var record = null;
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        selectionsArray.sort();
        selectionsArray.reverse();
        var newSelectionsArray = [];
        if (selectionsArray.length > 0) {
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                if ((selectionsArray[i] + 1) < this.toMultiselect.view.store.getCount()) {
                    this.toMultiselect.view.store.remove(record);
                    this.toMultiselect.view.store.insert(selectionsArray[i] + 1, record);
                    newSelectionsArray.push(selectionsArray[i] + 1);
                }
            }
            this.toMultiselect.view.refresh();
            this.toMultiselect.view.select(newSelectionsArray);
        }
    },
    
    fromTo : function() {
        var selectionsArray = this.fromMultiselect.view.getSelectedIndexes();
        var records = [];
        if (selectionsArray.length > 0) {
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.fromMultiselect.view.store.getAt(selectionsArray[i]);
                records.push(record);
            }
            if(!this.allowDup)selectionsArray = [];
            for (var i=0; i<records.length; i++) {
                record = records[i];
                if(this.allowDup){
                    var x=new Ext.data.Record();
                    record.id=x.id;
                    delete x;   
                    this.toMultiselect.view.store.add(record);
                }else{
                    this.fromMultiselect.view.store.remove(record);
                    this.toMultiselect.view.store.add(record);
                    selectionsArray.push((this.toMultiselect.view.store.getCount() - 1));
                }
            }
        }
        this.toMultiselect.view.refresh();
        this.fromMultiselect.view.refresh();
        if(this.toSortField)this.toMultiselect.store.sort(this.toSortField, this.toSortDir);
        if(this.allowDup)this.fromMultiselect.view.select(selectionsArray);
        else this.toMultiselect.view.select(selectionsArray);
    },
    
    toFrom : function() {
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        var records = [];
        if (selectionsArray.length > 0) {
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                records.push(record);
            }
            selectionsArray = [];
            for (var i=0; i<records.length; i++) {
                record = records[i];
                this.toMultiselect.view.store.remove(record);
                if(!this.allowDup){
                    this.fromMultiselect.view.store.add(record);
                    selectionsArray.push((this.fromMultiselect.view.store.getCount() - 1));
                }
            }
        }
        this.fromMultiselect.view.refresh();
        this.toMultiselect.view.refresh();
        if(this.fromSortField)this.fromMultiselect.store.sort(this.fromSortField, this.fromSortDir);
        this.fromMultiselect.view.select(selectionsArray);
    },
    
    valueChanged: function(store) {
        var record = null;
        var values = [];
        for (var i=0; i<store.getCount(); i++) {
            record = store.getAt(i);
            values.push(record.get(this.valueField));
        }
        this.hiddenField.dom.value = values.join(this.delimiter);
        this.fireEvent('change', this, this.getValue(), this.hiddenField.dom.value);
    },
    
    getValue : function() {
        return this.hiddenField.dom.value;
    },
    
    onRowDblClick : function(vw, index, node, e) {
        return this.fireEvent('rowdblclick', vw, index, node, e);
    },
    
    reset: function(){
        range = this.toMultiselect.store.getRange();
        this.toMultiselect.store.removeAll();
        if (!this.allowDup) {
            this.fromMultiselect.store.add(range);
            this.fromMultiselect.store.sort(this.displayField,'ASC');
        }
        this.valueChanged(this.toMultiselect.store);
    }
});

Ext.reg("itemselectorautosuggest", Ext.ux.ItemSelectorAutoSuggest);

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/multiselect/Ext.ux.plugins.ItemSelectorAutoSuggest.js  ]*/

Ext.ns("Ext.ux.plugins");
Ext.ux.plugins.ExtendedDayTimeSelect = Ext.extend(Ext.form.Field, {
    border:true,
    width:250,
    dayTimeFieldWidth:60,
    weekdayMonthdayWidth:50,
    fromFieldWidth:60,
    toFieldWidth:60,
    buttonWidth:50,
    listWidth:200,
    layout:'form',
    hidden:true,
  
    prepareValue:function(dataType,dataArray){
    	da = Array();
    	j = 0;
    	for(i=0;i<dataArray.length;i++){
    		if(dataArray[i] != '' && dataArray[i] != null){
    			da[j] = dataArray[i];
    			j++;
    		}
    	}
    	if(this.returnType == 'json'){
    		var returnJSON = Array();
    		returnJSON[0] = dataType;
    		returnJSON[1] = da;
    		json = Ext.util.JSON.encode(returnJSON); 
    		this.setValue(json);
    	}else{
    		commaS = '';
    		
    		for(i=0;i<dataArray.length;i++){
	    		if(dataArray[i] != '' && dataArray[i] != null){
	    			commaS += dataArray[i];
	    			if(i != (dataArray.length-1)) commaS += ",";
	    		}	    		
    		}
    		if(commaS != "") commaS = dataType+","+commaS;
    		this.setValue(commaS);
    		
    	}
    },
    resizeComponents:function(type){    	
    	if(type == 'auto'){    		
    		var fw = this.width-10;
    		//Upper row widths
    		this.dayTimeFieldWidth = Math.floor((80/100)*fw);
    		this.listWidth = Math.floor((80/100)*fw);
    		this.weekdayMonthdayWidth = Math.floor((20/100)*fw);
    		this.fromFieldWidth = Math.floor((50/100)*(this.dayTimeFieldWidth-10));
    		this.toFieldWidth = Math.floor((50/100)*(this.dayTimeFieldWidth-10));
    		this.buttonWidth = Math.floor((20/100)*fw);
    		
    		    		
    		//Lower row widths
    		//this.listWidth = this.width - this.buttonWidth - 5;     			
    	}
    }    
    ,initComponent:function() {
        var config = {
  	
        };
        Ext.apply(this, config);

        Ext.ux.plugins.ExtendedDayTimeSelect.superclass.initComponent.apply(this, arguments);
    }     
    ,onRender:function(ct,position){
    	//this.el.style.display = "none";
    	this.resizeComponents('auto');
    	
        Ext.ux.plugins.ExtendedDayTimeSelect.superclass.onRender.apply(this, arguments);
        var randomN = Math.floor(Math.random()*10000000);
        var dataType = 'day';
        var dataArray = Array();
        mainDiv = Ext.DomHelper.insertFirst(ct, {tag: 'div', style:'width:'+this.width+'px; margin:0px; padding:0px'});
		topRowDiv = Ext.DomHelper.append(mainDiv,{tag:'div',style:'width:inherit;float:left;'});
		dayTimeDiv = Ext.DomHelper.append(topRowDiv,{tag:'div',style:'width:'+this.dayTimeFieldWidth+'px;float:left; margin-right:10px;'});
		weekdayMonthdayDiv = Ext.DomHelper.append(topRowDiv,{tag:'div',style:'width:'+this.weekdayMonthdayWidth+'px;float:left; margin-right:0px;'});
		clearDiv = Ext.DomHelper.append(topRowDiv,{tag:'div',style:'clear:both; height:2px; width:float:left'+this.width+'px'});		
		fromDiv = Ext.DomHelper.append(topRowDiv,{tag:'div',style:'float:left;width:'+this.fromFieldWidth+'px;'});
		hyphenDiv = Ext.DomHelper.append(topRowDiv,{tag:'div',style:'text-align:center;float:left;width:10px;',html:'-'});						
		toDiv = Ext.DomHelper.append(topRowDiv,{tag:'div',style:'float:left;margin-right:10px;width:'+this.toFieldWidth+'px;'});
		okButtonDiv = Ext.DomHelper.append(topRowDiv,{tag:'div',style:'float:left; width:'+this.buttonWidth+'px;'});
		clearDiv = Ext.DomHelper.append(mainDiv,{tag:'div',style:'clear:both; '});
	    clearDiv.id = 'daytimeselect_'+randomN+'_cleardiv0_';
		
		dayStore = new Ext.data.SimpleStore({				        
	        fields: [
	            'valueField',
	            'displayField'
	        ],
	        data: [
			        ['1', '1'], ['2', '2'],['3', '3'], ['4', '4'],['5', '5'], ['6', '6'],['7', '7'], ['8', '8'], ['9', '9'],['10', '10'],
			        ['11', '11'], ['12', '12'],['13', '13'], ['14', '14'],['15', '15'], ['16', '16'],['17', '17'], ['18', '18'], ['19', '19'],['20', '20'],
			        ['21', '21'], ['22', '22'],['23', '23'], ['24', '24'],['25', '25'], ['26', '26'],['27', '27'], ['28', '28'], ['29', '29'],['30', '30'],['31', '31']
			      ]
	    });
	    timeStore = new Ext.data.SimpleStore({				        
	        fields: [
	            'valueField',
	            'displayField'
	        ],
	        data: [
			        ['1', '1'], ['2', '2'],['3', '3'], ['4', '4'],['5', '5'], ['6', '6'],['7', '7'], ['8', '8'], ['9', '9'],['10', '10'],
			        ['11', '11'], ['12', '12'],['13', '13'], ['14', '14'],['15', '15'], ['16', '16'],['17', '17'], ['18', '18'], ['19', '19'],['20', '20'],
			        ['21', '21'], ['22', '22'],['23', '23'], ['24', '24']
			      ]
	    });
	    weekStore = new Ext.data.SimpleStore({				        
	        fields: [
	            'valueField',
	            'displayField'
	        ],
	        data: [
			        ['sunday', 'Sun'], ['monday', 'Mon'],['tuesday', 'Tue'], ['wednesday', 'Wed'],['thursday', 'Thur'], ['friday', 'Fri'],['saturday', 'Sat']
			      ]
	    }); 
	    
	     
		dayTimeCombo = new Ext.form.ComboBox({
			typeAhead: true,
			id:'daytimeselect_daytimecombo_'+randomN,
		    triggerAction: 'all',
		    width:this.dayTimeFieldWidth,
		    lazyRender:true,
		    mode: 'local',
		    parent:this,
		    store: new Ext.data.SimpleStore({				        
		        fields: [
		            'valueField',
		            'displayField'
		        ],
		        data: [['24x7', '24x7'],['daily', 'Daily'],['weekly', 'Weekly'], ['monthly', 'Monthly']]
		    }),
		    listeners:{
		    	select:function(){	
					
					weeklyMonthlyCombo.enable();
					fromCombo.enable();
					toCombo.enable();
					okButton.enable();
					if(this.getValue()=="24x7"){
						dayTimeCombo.enable();
						weeklyMonthlyCombo.disable();
						fromCombo.disable();
						toCombo.disable();
						okButton.disable();
						this.parent.setValue("24x7");
						
		    		}
					if(this.getValue()=="daily"){
						weeklyMonthlyCombo.setValue('daily');
						weeklyMonthlyCombo.disable();						
		    		}
		    		if(this.getValue()=="weekly"){				    		
		    			Ext.getCmp('daytimeselect_weeklymonthlycombo_'+randomN).bindStore(weekStore);
		    			Ext.getCmp('daytimeselect_weeklymonthlycombo_'+randomN).setValue('sunday');		    			
		    		}
		    		if(this.getValue()=="monthly"){				    		
		    			Ext.getCmp('daytimeselect_weeklymonthlycombo_'+randomN).bindStore(dayStore);
		    			Ext.getCmp('daytimeselect_weeklymonthlycombo_'+randomN).setValue('1');
		    		}
		    		if(!dataArray.length){
		    			dataType = this.getValue();						
		    		}
		    		
		    	}
		    },
		    valueField: 'valueField',
		    displayField: 'displayField',
		    emptyText:'Select time frame'
		});
		
		dayTimeCombo.setValue('weekly');
		dayTimeCombo.render(dayTimeDiv);
		
		
		weeklyMonthlyCombo = new Ext.form.ComboBox({
			typeAhead: true,
			id:'daytimeselect_weeklymonthlycombo_'+randomN,
		    triggerAction: 'all',
		    width:this.weekdayMonthdayWidth,
		    lazyRender:true,
		    mode: 'local',
		    store: weekStore,
		    
		    valueField: 'valueField',
		    displayField: 'displayField'
		});
		
		weeklyMonthlyCombo.setValue('sunday');
		weeklyMonthlyCombo.render(weekdayMonthdayDiv);
		
		fromCombo = new Ext.form.ComboBox({
			typeAhead: true,
			id:'daytimeselect_fromcombo_'+randomN,
		    triggerAction: 'all',
		    width:this.fromFieldWidth,
		    lazyRender:true,
		    mode: 'local',
		    store: timeStore,
		    valueField: 'valueField',
		    displayField: 'displayField',
		    emptyText:'Hour From',
		    listeners:{
				change: function(){	
			//console.log(parseInt(toCombo.getValue()));
					if(parseInt(toCombo.getValue())){
						if(parseInt(fromCombo.getValue()) > parseInt(toCombo.getValue())){
							fromCombo.setValue("");
							Ext.Msg.alert("Error","Please select a lower/equal value than Hour To");
							
						}
					}
				}			
			}
		   
		});
		
		//fromCombo.setValue('day');
		fromCombo.render(fromDiv);
		
		toCombo = new Ext.form.ComboBox({
			typeAhead: true,
			id:'daytimeselect_tocombo_'+randomN,
		    triggerAction: 'all',
		    width:this.toFieldWidth,
		    lazyRender:true,
		    mode: 'local',
		    store: timeStore,
		    valueField: 'valueField',
		    displayField: 'displayField',
		    emptyText:'Hour To',
		    listeners:{
				change: function(){	
					if(parseInt(fromCombo.getValue())){
						if(parseInt(fromCombo.getValue()) > parseInt(toCombo.getValue())){
							toCombo.setValue("");
							Ext.Msg.alert("Error","Please select a greater/equal value than Hour From");
							
						}
					}
				}			
			}
		   
		});
		
		//toCombo.setValue('day');
		toCombo.render(toDiv);
		
		okButton = new Ext.Button({
			//text:"Add",
			parent:this,			
			style:'width:100%',
			count:0,
			iconCls:"icon-plus",
			
			t_item:0,
			listeners:{
				click:function(){
					
					if(!dataArray.length)
					dataType = dayTimeCombo.getValue();
					
					if(Ext.getCmp('daytimeselect_weeklymonthlycombo_'+randomN).getValue() == "daily" && this.t_item > 0) return;
					if(Ext.getCmp('daytimeselect_daytimecombo_'+randomN).getValue() == "" || Ext.getCmp('daytimeselect_fromcombo_'+randomN).getValue() == "" || Ext.getCmp('daytimeselect_tocombo_'+randomN).getValue() == ""){
						Ext.Msg.show({
						   title:'Error !',
						   msg: 'Invalid Selections',
						   buttons: Ext.Msg.OK,
						   //fn: processResult,
						   icon: Ext.MessageBox.ERROR
						});
						return;
					}
					//Uncomment if from should be less than to value
					/*if(Ext.getCmp('daytimeselect_fromcombo_'+randomN).getValue() > Ext.getCmp('daytimeselect_tocombo_'+randomN).getValue()){
						Ext.Msg.show({
						   title:'Error !',
						   msg: 'Invalid range !<br>From value is greater than To value',
						   buttons: Ext.Msg.OK,
						   //fn: processResult,
						   icon: Ext.MessageBox.ERROR
						});
						return;
					}*/
					if(this.t_item && dataType != Ext.getCmp('daytimeselect_daytimecombo_'+randomN).getValue()){
						Ext.Msg.show({
						   title:'Error !',
						   msg: 'Invalid data type for this series',
						   buttons: Ext.Msg.OK,
						   //fn: processResult,
						   icon: Ext.MessageBox.ERROR
						});
						return;
					}							
					text = Ext.getCmp('daytimeselect_weeklymonthlycombo_'+randomN).getValue()+"-"+Ext.getCmp('daytimeselect_fromcombo_'+randomN).getValue()+"-"+Ext.getCmp('daytimeselect_tocombo_'+randomN).getValue();
					var found = false;
					for(var i = 0; i<dataArray.length; i++) {
						if(dataArray[i] == text) {
							found = true;
						}
					}
					if(found){
						Ext.Msg.show({
						   title:'Error !',
						   msg: 'Data already in list',
						   buttons: Ext.Msg.OK,
						   //fn: processResult,
						   icon: Ext.MessageBox.ERROR
						});
						return;
					}
					
					dataArray[this.count] = Ext.getCmp('daytimeselect_weeklymonthlycombo_'+randomN).getValue()+"-"+Ext.getCmp('daytimeselect_fromcombo_'+randomN).getValue()+"-"+Ext.getCmp('daytimeselect_tocombo_'+randomN).getValue();
					this.t_item++;
					this.parent.prepareValue(dataType,dataArray);
					
					//alert(dataArray.length);
					//alert(dataType)
					
					
	        		clearDiv = Ext.DomHelper.append(mainDiv,{tag:'div',style:'width:inherit;clear:both;font-size:0px;height:3px'});
	        		clearDiv.id = 'daytimeselect_'+randomN+'_cleardiv_'+this.count;
	        		
					listRowDiv = Ext.DomHelper.append(mainDiv,{tag:'div',style:'width:inherit;'});
					listRowDiv.id = 'daytimeselect_'+randomN+'_listrowdiv_'+this.count;
					
					listDiv = Ext.DomHelper.append(listRowDiv,{tag:'div',style:'width:'+this.parent.listWidth+'px; margin-right:5px;float:left;'});
					listDiv.id = 'daytimeselect_'+randomN+'_listdiv_'+this.count;
					
					removeButtonDiv = Ext.DomHelper.append(listRowDiv,{tag:'div',style:'width:inherit;float:right; width:'+this.parent.buttonWidth+'px;'});
					removeButtonDiv.id = 'daytimeselect_'+randomN+'_removebuttondiv_'+this.count;
				    
				    //clearDiv = Ext.DomHelper.append(mainDiv,{tag:'div',style:'width:inherit;clear:both;font-size:0px;height:3px'});
	        		//clearDiv.id = 'daytimeselect_'+randomN+'_cleardiv_'+this.count;
	        		
	        		    	
					list = new Ext.form.TextField({
						bodyStyle:'background-color:#f5f5f5',
						height:20,
						width:this.parent.listWidth,
						readOnly:true
					});
					list.setValue(text);
					list.render(listDiv);
					removeButton = new Ext.Button({
						//text:"DEL",
						id:'daytimeselect_'+randomN+'_removebutton_'+this.count,
						c:this.count,
						t_item:this.t_item,
						parent:this,
						width:this.buttonWidth,
						iconCls:"icon-delete",
						listeners:{
							click:function(){
								
								dataArray[this.c] = '';
								Ext.DomHelper.applyStyles(Ext.get('daytimeselect_'+randomN+'_listrowdiv_'+this.c),'display:none');
								Ext.DomHelper.applyStyles(Ext.get('daytimeselect_'+randomN+'_cleardiv_'+this.c),'display:none');
								this.parent.t_item--;
								if(this.parent.t_item == 0){ dataArray = new Array();dayTimeCombo.enable();}
								this.parent.parent.prepareValue(dataType,dataArray);
							}
						}
					});				
					removeButton.render(removeButtonDiv);
					this.count++;
					if(dataArray.length){
						dayTimeCombo.disable();
					}
					
					//Reset combo
					toCombo.setValue('');
					fromCombo.setValue('');
				}
			}
		});				
		okButton.render(okButtonDiv);	
		data = this.value;
    	dataArr = data.split(',');
    	dataType = dataArr[0];
    	dayTimeCombo.setValue(dataType);
    	if(dataType == "daily"){
    		var v = dataArr[1];    		
    		v = v.replace("sunday","daily");
    		
    		dataArr = new Array("daily",v);
    	}
    	if(dataType != "24x7"){    	
	    	for(i=1;i<dataArr.length;i++){
	    		valArr = dataArr[i].split("-");
	    		weeklyMonthlyCombo.setValue(valArr[0]);
	    		fromCombo.setValue(valArr[1]);
	    		toCombo.setValue(valArr[2]);
	    		okButton.fireEvent('click');
	    		//dataArray[i-1] = dataArr[i];
	    		//okButton.count = i-1;
	    	}
    	}else{
    		dataArr = new Array();    		
    	}
    	if(dataArr.length > 0 && data != "" && data != "24x7" && data != "daily"){
			dayTimeCombo.disable();
    	}
    	if(dataType == "24x7"){
    		fromCombo.disable();
    		toCombo.disable();
    		okButton.disable();
    		weeklyMonthlyCombo.disable();
    	}
    	if(dataType == "daily"){
    		weeklyMonthlyCombo.setValue('daily');
    		weeklyMonthlyCombo.disable();
    	}
    	
    }

});
Ext.reg('extendedDayTimeSelect', Ext.ux.plugins.ExtendedDayTimeSelect); 

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/datetime/Ext.ux.plugins.ExtendedDayTimeSelect.js  ]*/

// vim: ts=4:sw=4:nu:fdc=4:nospell
/*global Ext */
/**
 * @class Ext.ux.form.DateTime
 * @extends Ext.form.Field
 *
 * DateTime field, combination of DateField and TimeField
 *
 * @author	  Ing. Jozef Sakáloš
 * @copyright (c) 2008, Ing. Jozef Sakáloš
 * @version   2.0
 * @revision  $Id: Ext.ux.form.DateTime.js 589 2009-02-21 23:30:18Z jozo $
 *
 * @license Ext.ux.form.DateTime is licensed under the terms of
 * the Open Source LGPL 3.0 license.  Commercial use is permitted to the extent
 * that the code/component(s) do NOT become part of another Open Source or Commercially
 * licensed development library or toolkit without explicit permission.
 * 
 * <p>License details: <a href="http://www.gnu.org/licenses/lgpl.html"
 * target="_blank">http://www.gnu.org/licenses/lgpl.html</a></p>
 *
 * @forum      22661
 *
 * @donate
 * <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
 * <input type="hidden" name="cmd" value="_s-xclick">
 * <input type="hidden" name="hosted_button_id" value="3430419">
 * <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-butcc-donate.gif" 
 * border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
 * <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
 * </form>
 */

Ext.ns('Ext.ux.form');

/**
 * Creates new DateTime
 * @constructor
 * @param {Object} config A config object
 */
Ext.ux.form.DateTime = Ext.extend(Ext.form.Field, {
	/**
	* increment the time field from one hour to another
	* @author radu
	*/
	timeIncrement:60
	/**
	* the time field will be visible&enabled OR not
	* @author radu
	*/
	,timeActive:true
	/**
	 * @cfg {String/Object} defaultAutoCreate DomHelper element spec
	 * Let superclass to create hidden field instead of textbox. Hidden will be submittend to server
	 */
	,defaultAutoCreate:{tag:'input', type:'hidden'}
	/**
	 * @cfg {Number} timeWidth Width of time field in pixels (defaults to 100)
	 */
	,timeWidth:100
	/**
	 * @cfg {String} dtSeparator Date - Time separator. Used to split date and time (defaults to ' ' (space))
	 */
	,dtSeparator:' '
	/**
	 * @cfg {String} hiddenFormat Format of datetime used to store value in hidden field
	 * and submitted to server (defaults to 'Y-m-d H:i:s' that is mysql format)
	 */
	,hiddenFormat:'Y-m-d H:i:s'
	/**
	 * @cfg {Boolean} otherToNow Set other field to now() if not explicly filled in (defaults to true)
	 */
	,otherToNow:true
	/**
	 * @cfg {Boolean} emptyToNow Set field value to now on attempt to set empty value.
	 * If it is true then setValue() sets value of field to current date and time (defaults to false)
	 */
	/**
	 * @cfg {String} timePosition Where the time field should be rendered. 'right' is suitable for forms
	 * and 'below' is suitable if the field is used as the grid editor (defaults to 'right')
	 */
	,timePosition:'right' // valid values:'below', 'right'
	/**
	 * @cfg {String} dateFormat Format of DateField. Can be localized. (defaults to 'm/y/d')
	 */
	,dateFormat:'d/m/Y'
	/**
	 * @cfg {String} timeFormat Format of TimeField. Can be localized. (defaults to 'g:i A')
	 */
	,timeFormat:'H:i'
	/**
	 * @cfg {Object} dateConfig Config for DateField constructor.
	 */
	/**
	 * @cfg {Object} timeConfig Config for TimeField constructor.
	 */

	// {{{
	/**
	 * @private
	 * creates DateField and TimeField and installs the necessary event handlers
	 */
	,initComponent:function() {
		// call parent initComponent
		Ext.ux.form.DateTime.superclass.initComponent.call(this);

		// create DateField
		var dateConfig = Ext.apply({}, {
			 id:this.id + '-date'
			,format:this.dateFormat || Ext.form.DateField.prototype.format
			,width:this.timeWidth
			,selectOnFocus:this.selectOnFocus
			,listeners:{
				  blur:{scope:this, fn:this.onBlur}
				 ,focus:{scope:this, fn:this.onFocus}
			}
		}, this.dateConfig);
		this.df = new Ext.form.DateField(dateConfig);
		this.df.ownerCt = this;
		delete(this.dateFormat);

		// create TimeField
		var timeConfig = Ext.apply({}, {
			 id:this.id + '-time'
			,format:this.timeFormat || Ext.form.TimeField.prototype.format
			,width:this.timeWidth
			,selectOnFocus:this.selectOnFocus
			,increment:this.timeIncrement
			,listeners:{
				  blur:{scope:this, fn:this.onBlur}
				 ,focus:{scope:this, fn:this.onFocus}
			}
		}, this.timeConfig);
		this.tf = new Ext.form.TimeField(timeConfig);
		this.tf.ownerCt = this;
		delete(this.timeFormat);

		// relay events
		this.relayEvents(this.df, ['focus', 'specialkey', 'invalid', 'valid']);
		this.relayEvents(this.tf, ['focus', 'specialkey', 'invalid', 'valid']);

	} // eo function initComponent
	// }}}
	// {{{
	/**
	 * @private
	 * Renders underlying DateField and TimeField and provides a workaround for side error icon bug
	 */
	,onRender:function(ct, position) {
		// don't run more than once
		if(this.isRendered) {
			return;
		}

		// render underlying hidden field
		Ext.ux.form.DateTime.superclass.onRender.call(this, ct, position);

		// render DateField and TimeField
		// create bounding table
		var t;
		if('below' === this.timePosition || 'bellow' === this.timePosition) {
			t = Ext.DomHelper.append(ct, {tag:'table',style:'border-collapse:collapse',children:[
				 {tag:'tr',children:[{tag:'td', style:'padding-bottom:1px', cls:'ux-datetime-date'}]}
				,{tag:'tr',children:[{tag:'td', cls:'ux-datetime-time'}]}
			]}, true);
		}
		else {
			t = Ext.DomHelper.append(ct, {tag:'table',style:'border-collapse:collapse',children:[
				{tag:'tr',children:[
					{tag:'td',style:'padding-right:4px', cls:'ux-datetime-date'},{tag:'td', cls:'ux-datetime-time'}
				]}
			]}, true);
		}

		this.tableEl = t;
//		this.wrap = t.wrap({cls:'x-form-field-wrap'});
		this.wrap = t.wrap();
        this.wrap.on("mousedown", this.onMouseDown, this, {delay:10});
                                
		// render DateField & TimeField
		this.df.render(t.child('td.ux-datetime-date'));
		this.tf.render(t.child('td.ux-datetime-time'));

		// workaround for IE trigger misalignment bug
		if(Ext.isIE && Ext.isStrict) {
			t.select('input').applyStyles({top:0});
		}

		this.on('specialkey', this.onSpecialKey, this);
		this.df.el.swallowEvent(['keydown', 'keypress']);
		this.tf.el.swallowEvent(['keydown', 'keypress']);

		// create icon for side invalid errorIcon
		if('side' === this.msgTarget) {
			var elp = this.el.findParent('.x-form-element', 10, true);
			this.errorIcon = elp.createChild({cls:'x-form-invalid-icon'});

			this.df.errorIcon = this.errorIcon;
			this.tf.errorIcon = this.errorIcon;
		}

		// setup name for submit
		this.el.dom.name = this.hiddenName || this.name || this.id;

		// prevent helper fields from being submitted
		this.df.el.dom.removeAttribute("name");
		this.tf.el.dom.removeAttribute("name");

		// we're rendered flag
		this.isRendered = true;

		// update hidden field
		this.updateHidden();
		
		//set activation of time field
		this.setActive(this.tf,this.timeActive);

		//Ext.util.Observable.capture(this, function(e){console.log(e);});
		
	} // eo function onRender
	// }}}
	// {{{
	/**
	 * @private
	 */
    ,adjustSize:Ext.BoxComponent.prototype.adjustSize
	// }}}
	// {{{
	/**
	 * @private
	 */
	,alignErrorIcon:function() {
        this.errorIcon.alignTo(this.tableEl, 'tl-tr', [2, 0]);
	}
	// }}}
	// {{{
	/**
	 * @private initializes internal dateValue
	 */
	,initDateValue:function() {
		this.dateValue = this.otherToNow ? new Date() : new Date(1970, 0, 1, 0, 0, 0);
	}
	// }}}
	// {{{
    /**
     * Calls clearInvalid on the DateField and TimeField
     */
    ,clearInvalid:function(){
        this.df.clearInvalid();
        this.tf.clearInvalid();
    } // eo function clearInvalid
    // }}}
	// {{{
    /**
     * Calls markInvalid on both DateField and TimeField
	 * @param {String} msg Invalid message to display
     */
    ,markInvalid:function(msg){
        this.df.markInvalid(msg);
        this.tf.markInvalid(msg);
    } // eo function markInvalid
    // }}}
	// {{{
	/**
	 * @private
	 * called from Component::destroy. 
	 * Destroys all elements and removes all listeners we've created.
	 */
	,beforeDestroy:function() {
		if(this.isRendered) {
//			this.removeAllListeners();
			this.wrap.removeAllListeners();
			this.wrap.remove();
			this.tableEl.remove();
			this.df.destroy();
			this.tf.destroy();
		}
	} // eo function beforeDestroy
	// }}}
	// {{{
    /**
     * Disable this component.
     * @return {Ext.Component} this
     */
    ,disable:function() {
		if(this.isRendered) {
			this.df.disabled = this.disabled;
			this.df.onDisable();
			this.tf.onDisable();
		}
		this.disabled = true;
		this.df.disabled = true;
		this.tf.disabled = true;
        this.fireEvent("disable", this);
        return this;
    } // eo function disable
	// }}}
	// {{{
    /**
     * Enable this component.
     * @return {Ext.Component} this
     */
    ,enable:function() {
        if(this.rendered){
			this.df.onEnable();
			this.tf.onEnable();
        }
        this.disabled = false;
		this.df.disabled = false;
		this.tf.disabled = false;
        this.fireEvent("enable", this);
        return this;
    } // eo function enable
	// }}}
	// {{{
	/**
	 * @private Focus date filed
	 */
	,focus:function() {
		this.df.focus();
	} // eo function focus
	// }}}
	// {{{
	/**
	 * @private
	 */
	,getPositionEl:function() {
		return this.wrap;
	}
	// }}}
	// {{{
	/**
	 * @private
	 */
	,getResizeEl:function() {
		return this.wrap;
	}
	// }}}
	// {{{
	/**
	 * @return {Date/String} Returns value of this field
	 */
	,getValue:function() {
		// create new instance of date
		return this.dateValue ? new Date(this.dateValue) : '';
	} // eo function getValue
	// }}}
	// {{{
	/**
	 * @return {Boolean} true = valid, false = invalid
	 * @private Calls isValid methods of underlying DateField and TimeField and returns the result
	 */
	,isValid:function() {
		return this.df.isValid() && this.tf.isValid();
	} // eo function isValid
	// }}}
    // {{{
    /**
     * Returns true if this component is visible
     * @return {boolean} 
     */
    ,isVisible : function(){
        return this.df.rendered && this.df.getActionEl().isVisible();
    } // eo function isVisible
	// }}}
	// {{{
	/** 
	 * @private Handles blur event
	 */
	,onBlur:function(f) {
		// called by both DateField and TimeField blur events

		// revert focus to previous field if clicked in between
		if(this.wrapClick) {
			f.focus();
			this.wrapClick = false;
		}

		// update underlying value
		if(f === this.df) {
			this.updateDate();
		}
		else {
			this.updateTime();
		}
		this.updateHidden();

		// fire events later
		(function() {
			if(!this.df.hasFocus && !this.tf.hasFocus) {
				var v = this.getValue();
				if(String(v) !== String(this.startValue)) {
					this.fireEvent("change", this, v, this.startValue);
				}
				this.hasFocus = false;
				this.fireEvent('blur', this);
			}
		}).defer(100, this);

	} // eo function onBlur
	// }}}
	// {{{
	/**
	 * @private Handles focus event
	 */
	,onFocus:function() {
        if(!this.hasFocus){
            this.hasFocus = true;
            this.startValue = this.getValue();
            this.fireEvent("focus", this);
        }
	}
	// }}}
	// {{{
	/**
	 * @private Just to prevent blur event when clicked in the middle of fields
	 */
	,onMouseDown:function(e) {
		if(!this.disabled) {
			this.wrapClick = 'td' === e.target.nodeName.toLowerCase();
		}
	}
	// }}}
	// {{{
	/**
	 * @private
	 * Handles Tab and Shift-Tab events
	 */
	,onSpecialKey:function(t, e) {
		var key = e.getKey();
		if(key === e.TAB) {
			if(t === this.df && !e.shiftKey) {
				e.stopEvent();
				this.tf.focus();
			}
			if(t === this.tf && e.shiftKey) {
				e.stopEvent();
				this.df.focus();
			}
		}
		// otherwise it misbehaves in editor grid
		if(key === e.ENTER) {
			this.updateValue();
		}

	} // eo function onSpecialKey
	// }}}
	// {{{
	/**
	 * @private Sets the value of DateField
	 */
	,setDate:function(date) {
		this.df.setValue(date);
	} // eo function setDate
	// }}}
	// {{{
	/** 
	 * @private Sets the value of TimeField
	 */
	,setTime:function(date) {
		this.tf.setValue(date);
	} // eo function setTime
	// }}}
	// {{{
	/**
	 * @private
	 * Sets correct sizes of underlying DateField and TimeField
	 * With workarounds for IE bugs
	 */
	,setSize:function(w, h) {
		if(!w) {
			return;
		}
		if('below' === this.timePosition) {
			this.df.setSize(w, h);
			this.tf.setSize(w, h);
			if(Ext.isIE) {
				this.df.el.up('td').setWidth(w);
				this.tf.el.up('td').setWidth(w);
			}
		}
		else {
			this.df.setSize(w - this.timeWidth - 4, h);
			this.tf.setSize(this.timeWidth, h);

			if(Ext.isIE) {
				this.df.el.up('td').setWidth(w - this.timeWidth - 4);
				this.tf.el.up('td').setWidth(this.timeWidth);
			}
		}
	} // eo function setSize
	// }}}
	// {{{
	/**
	 * @param {Mixed} val Value to set
	 * Sets the value of this field
	 */
	,setValue:function(val) {
		if(!val && true === this.emptyToNow) {
			this.setValue(new Date());
			return;
		}
		else if(!val) {
			this.setDate('');
			this.setTime('');
			this.updateValue();
			return;
		}
        if ('number' === typeof val) {
          val = new Date(val);
        }
        else if('string' === typeof val && this.hiddenFormat) {
			val = Date.parseDate(val, this.hiddenFormat)
        }
		val = val ? val : new Date(1970, 0 ,1, 0, 0, 0);
		var da, time;
		if(val instanceof Date) {
			this.setDate(val);
			this.setTime(val);
			this.dateValue = new Date(val);
		}
		else {
			da = val.split(this.dtSeparator);
			this.setDate(da[0]);
			if(da[1]) {
				if(da[2]) {
					// add am/pm part back to time
					da[1] += da[2];
				}
				this.setTime(da[1]);
			}
		}
		this.updateValue();
	} // eo function setValue
	// }}}
	// {{{
	/**
     * Hide or show this component by boolean
     * @return {Ext.Component} this
     */
    ,setVisible: function(visible){
        if(visible) {
            this.df.show();
            this.tf.show();
        }else{
            this.df.hide();
            this.tf.hide();
        }
        return this;
    } // eo function setVisible
    // }}}
	//{{{
	,show:function() {
		return this.setVisible(true);
	} // eo function show
	//}}}
	//{{{
	,hide:function() {
		return this.setVisible(false);
	} // eo function hide
	//}}}
	// {{{
	,setActive: function(object,active) {
				
		if(active)
		{
			object.show();
	        
	        this.hiddenFormat='Y-m-d H:i:s';
	        
	        this.updateValue();
	        
	        return this;
		}
		else
		{
			object.hide();
	        
	        this.hiddenFormat='Y-m-d';
	        
	        this.updateValue();
	        
	        return this;
		}
	}
	/**
	 * @private Updates the date part
	 */
	,updateDate:function() {

		var d = this.df.getValue();
		if(d) {
			if(!(this.dateValue instanceof Date)) {
				this.initDateValue();
				if(!this.tf.getValue()) {
					this.setTime(this.dateValue);
				}
			}
			this.dateValue.setMonth(0); // because of leap years
			this.dateValue.setFullYear(d.getFullYear());
			this.dateValue.setMonth(d.getMonth(), d.getDate());
//			this.dateValue.setDate(d.getDate());
		}
		else {
			this.dateValue = '';
			this.setTime('');
		}
	} // eo function updateDate
	// }}}
	// {{{
	/**
	 * @private
	 * Updates the time part
	 */
	,updateTime:function() {
		var t = this.tf.getValue();
		if(t && !(t instanceof Date)) {
			t = Date.parseDate(t, this.tf.format);
		}
		if(t && !this.df.getValue()) {
			this.initDateValue();
			this.setDate(this.dateValue);
		}
		if(this.dateValue instanceof Date) {
			if(t) {
				this.dateValue.setHours(t.getHours());
				this.dateValue.setMinutes(t.getMinutes());
				this.dateValue.setSeconds(t.getSeconds());
			}
			else {
				this.dateValue.setHours(0);
				this.dateValue.setMinutes(0);
				this.dateValue.setSeconds(0);
			}
		}
	} // eo function updateTime
	// }}}
	// {{{
	/**
	 * @private Updates the underlying hidden field value
	 */
	,updateHidden:function() {
		if(this.isRendered) {
			var value = this.dateValue instanceof Date ? this.dateValue.format(this.hiddenFormat) : '';
			this.el.dom.value = value;
		}
	}
	// }}}
	// {{{
	/**
	 * @private Updates all of Date, Time and Hidden
	 */
	,updateValue:function() {

		this.updateDate();
		this.updateTime();
		this.updateHidden();

		return;
	} // eo function updateValue
	// }}}
	// {{{
	/**
	 * @return {Boolean} true = valid, false = invalid
	 * calls validate methods of DateField and TimeField
	 */
	,validate:function() {
		return this.df.validate() && this.tf.validate();
	} // eo function validate
	// }}}
	// {{{
	/**
	 * Returns renderer suitable to render this field
	 * @param {Object} Column model config
	 */
	,renderer: function(field) {
		var format = field.editor.dateFormat || Ext.ux.form.DateTime.prototype.dateFormat;
		format += ' ' + (field.editor.timeFormat || Ext.ux.form.DateTime.prototype.timeFormat);
		var renderer = function(val) {
			var retval = Ext.util.Format.date(val, format);
			return retval;
		};
		return renderer;
	} // eo function renderer
	// }}}

}); // eo extend

// register xtype
Ext.reg('xdatetime', Ext.ux.form.DateTime);

// eof

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/datetime/Ext.ux.form.DateTime.js  ]*/

Date.prototype.getFirstDateOfWeek = function(startDay) {
//set startDay to Sunday by default
	if (typeof startDay === "undefined") {
		startDay=(Ext.DatePicker?Ext.DatePicker.prototype.startDay:0);
	}
	var dayDiff = this.getDay()-startDay;
	if (dayDiff<0) {
		dayDiff+=7;
	}
	return this.add(Date.DAY,-dayDiff);
};

Array.prototype.sortDates = function() {
	return this.sort(function(a,b){
		return a.getTime() - b.getTime();		
	});
};


if (!Ext.util.EasterDate) {
	Ext.util.EasterDate = function(year, plusDays) {
		if (typeof year === "undefined") {
			year = new Date().getFullYear();
		}
		year = parseInt(year,10);
	
		if (typeof plusDays === "undefined") {
			plusDays = 0;
		}
		plusDays = parseInt(plusDays,10);
		
	//difference to first sunday after first fullmoon after beginning of spring
		var a = year % 19;
		var d = (19 * a + 24) % 30;
		var diffDay = d + (2 * (year % 4) + 4 * (year % 7) + 6 * d + 5) % 7;
		if ((diffDay == 35) || ((diffDay == 34) && (d == 28) && (a > 10))) {
			diffDay -= 7;
		}
	
		var EasterDate = new Date(year, 2, 22);	//beginning of spring
		EasterDate.setTime(EasterDate.getTime() + 86400000 * diffDay + 86400000 * plusDays);
		return EasterDate;
	};
}


Ext.namespace('Ext.ux','Ext.ux.form');

/**
 * @class Ext.ux.DatePickerPlus
 * @extends Ext.DatePicker
 * @constructor
  * @param {Object} config The config object
 */
Ext.ux.DatePickerPlus = Ext.extend(Ext.DatePicker, {
								   
	version: "1.4",
    /**
    * @cfg {Number} noOfMonth
    * No of Month to be displayed
	* Default to 1 so it will displayed as original Datepicker 
    */
    noOfMonth : 1,
	/**
    * @cfg {Array} noOfMonthPerRow
    * No. Of Month to be displayed in a row
    */    
    noOfMonthPerRow : 3,
    /**
    * @cfg {Array} fillupRows
    * eventually extends the number of months to view to fit the given row/column matrix and avoid odd white gaps (especially when using as datemenu fill will lookup ugly when set to false
    */    
	fillupRows : true,
    /**
    * @cfg {Function returns Array} eventDates
    * a Function which returns an Object List of Dates which have an event (show in separate given css-class)
	* This function is called everytime a year has changed when rendering the calendar
	* attributes are date, text(optional) and cls(optional)
	* Its implemented as a function to be able to create cycling days for year
	* example
	* eventDates: function(year) {
		var myDates = 
		[{
			date: new Date(2008,0,1), //fixed date marked only on 2008/01/01
			text: "New Year 2008",
			cls: "x-datepickerplus-eventdates"			
		},
		{
			date: new Date(year,4,11), //will be marked every year on 05/11
			text: "May 11th, Authors Birthday (Age:"+(year-1973)+")",
			cls: "x-datepickerplus-eventdates"
		}];
		return myDates;
	*
	*
    */    
    eventDates : function(year) {
		return [];
	},
	
	styleDisabledDates: false,
	eventDatesSelectable : true,

	defaultEventDatesText : '',
	defaultEventDatesCls : 'x-datepickerplus-eventdates',
	
	setEventDates : function(edArray,update) {
		if (typeof update === "undefined") {
			update=true;
		}
		this.edArray = [];
		for (var i=0,il=edArray.length;i<il;++i) {
			if (Ext.isDate(edArray[i])) {
				this.edArray.push({
					date:edArray[i],
					text:this.defaultEventDatesText,
					cls:this.defaultEventDatesCls
				});
			}
			else if (edArray[i].date) {
				edArray[i].date = this.jsonDate(edArray[i].date);
				this.edArray.push(edArray[i]);				
			}
		}
		this.eventDates = function(year) {
			return this.edArray;
		};
		if (this.rendered && update) {
			this.eventDatesNumbered = this.convertCSSDatesToNumbers(this.eventDates(this.activeDate.getFullYear()));
			this.update(this.activeDate);
		}
	},
	/**
	 * @cfg {Boolean} eventDatesRE
	 * To selected specific Days over a regular expression
	 */
	eventDatesRE : false,
	
	/**
	 * @cfg {String} eventDatesRECls
	 * Specifies what CSS Class will be applied to the days found by "eventDatesRE"
	 */
	eventDatesRECls : '',
	
	/**
	 * @cfg {String} eventDatesRECls
	 * Specifies what Quicktip will be displayed to the days found by "eventDatesRE"
	 */
	eventDatesREText : '',	
	
	/**
	 * @cfg {Boolean} showWeekNumber
	 * Whether the week number should be shown
	 */
	showWeekNumber : true,
	/**
	 * @cfg {String} weekName
	 * The short name of the week number column
	 */
	weekName : "Wk.",
	/**
	 * @cfg {String} selectWeekText
	 * Text to display when hovering over the weekNumber and multiSelection is enabled
	 */
	selectWeekText : "Click to select all days of this week",
	/**
	 * @cfg {String} selectMonthText
	 * Text to display when hovering over the MonthNumber and multiSelection is enabled
	 * Whole Month selection is disabled when displaying only 1 Month (think twice..)	 
	 */
	selectMonthText : "Click to select all weeks of this month",

	/**
	 * @cfg {String} multiSelection
	 * whether multiselection of dates is allowed. selection of weeks depends on displaying of weeknumbers
	 */
	multiSelection : false,
	/**
	 * @cfg {String} multiSelectByCTRL
	 * whether multiselection is made by pressing CTRL (default behaviour, a single click without CTRL will set the selection list to the last selected day/week) or without (ever click a day is added/removed)
	 */
	
	multiSelectByCTRL : true,

/**
    * @cfg {Array of Dateobjects} selectedDates
    * List of Dates which have been selected when multiselection is set to true (this.value only sets the startmonth then)
    */    
    selectedDates : [],


/**
    * @cfg {String/Bool} prevNextDaysView
    * "mark" selected days will be marke in prev/next months also
	* "nomark" will not be marked and are not selectable
	* false: will hide them, thus are not selectable too
    */    
	prevNextDaysView: "mark",
	
	/**
    * @cfg {Array of Dateobjects} preSelectedDates
    * contains the same at selection runtime (until "OK" is pressed)
	*/
	preSelectedDates : [], 

	/**
    * @cfg {Object} lastSelectedDate
    * contains the last selected Date or false right after initializing the object..
    */    
	lastSelectedDate : false, 

	/**
	 * @cfg {Array} markNationalHolidays
	 * trigger to add existing nationalHolidays to the eventdates list (nationalholidays can be changed in locale files, so these are independant from custom event Dates
	 */
	markNationalHolidays :true,

	/**
	 * @cfg {String} nationalHolidaysCls
	 * CSS Class displayed to national Holidays if markNationalHolidays is set to true
	 */
	nationalHolidaysCls : 'x-datepickerplus-nationalholidays',
	
	/**
    * @cfg {Function} nationalHolidays
    * returns an Array-List of national Holiday Dates which could by marked with separate given CSS. Will be shown if markNationalHolidays is set to true
	* Change this in your local file to override it with you country's own national Holiday Dates
	*
	* if markNationalHolidays is set to true, a new instance of this array (and thus recalculation of holidays) will be generated at month update, if year has been changed from last drawn month.
	*
    */  

	nationalHolidays : function(year) {
		year = (typeof year === "undefined" ? (this.lastRenderedYear ? this.lastRenderedYear : new Date().getFullYear()) : parseInt(year,10));
//per default the US national holidays are calculated (according to http://en.wikipedia.org/wiki/Public_holidays_of_the_United_States) 
//override this function in your local file to calculate holidays for your own country
//but remember to include the locale file _AFTER_ datepickerplus !
		var dayOfJan01 = new Date(year,0,1).getDay();
		var dayOfFeb01 = new Date(year,1,1).getDay();
		var dayOfMay01 = new Date(year,4,1).getDay();
		var dayOfSep01 = new Date(year,8,1).getDay();
		var dayOfOct01 = new Date(year,9,1).getDay();
		var dayOfNov01 = new Date(year,10,1).getDay();		

		var holidays = 
		[{
			text: "New Year's Day",
			date: new Date(year,0,1)
		},
		{
			text: "Martin Luther King Day", //(every third monday in january)
			date: new Date(year,0,(dayOfJan01>1?16+7-dayOfJan01:16-dayOfJan01))
		},
		{
			text: "Washington's Birthday", //(every third monday in february)
			date: new Date(year,1,(dayOfFeb01>1?16+7-dayOfFeb01:16-dayOfFeb01))
		},
		{
			text: "Memorial Day",//(last Monday in May)
			date: new Date(year,4,(dayOfMay01==6?31:30-dayOfMay01))
		},
		{
			text: "Independence Day",
			date: new Date(year,6,4)
		},
		{
			text: "Labor Day",//(every first monday in September)
			date: new Date(year,8,(dayOfSep01>1?2+7-dayOfSep01:2-dayOfSep01))
		},
		{
			text: "Columbus Day",//(every second monday in october)
			date: new Date(year,9,(dayOfOct01>1?9+7-dayOfOct01:9-dayOfOct01))
		},
		{
			text: "Veterans Day",
			date: new Date(year,10,11)
		},
		{
			text: "Thanksgiving Day",//(Fourth Thursday in November)
			date: new Date(year,10,(dayOfNov01>4?26+7-dayOfNov01:26-dayOfNov01))
		},
		{
			text: "Christmas Day",
			date: new Date(year,11,25)
		}];
		
		return holidays;
	},
	
	/**
	 * @cfg {Boolean} markWeekends
	 * whether weekends should be displayed differently
	 */
	markWeekends :true,
	/**
	 * @cfg {String} weekendCls
	 * CSS class to use for styling Weekends
	 */
	weekendCls : 'x-datepickerplus-weekends',
	/**
	 * @cfg {String} weekendText
	 * Quicktip for Weekends
	 */
	weekendText :'',
	/**
	 * @cfg {Array} weekendDays
	 * Array of Days (according to Days from dateobject thus Sunday=0,Monday=1,...Saturday=6)
	 * Additionally to weekends, you could use this to display e.g. every Tuesday and Thursday with a separate CSS class
	 */
	weekendDays: [6,0],
	
	/**
	 * @cfg {Boolean} useQuickTips
	 * Wheter TIps should be displayed as Ext.quicktips or browsercontrolled title-attributes
	 */
	useQuickTips : true,
	
	/**
	 * @cfg {Number} pageKeyWarp
	 * Amount of Months the picker will move forward/backward when pressing the pageUp/pageDown Keys
	 */
	pageKeyWarp : 1,

	/**
	 * @cfg {Number} maxSelectionDays
	 * Amount of Days that are selectable, set to false for unlimited selection
	 */
	maxSelectionDays : false,
	
	maxSelectionDaysTitle : 'Datepicker',
	maxSelectionDaysText : 'You can only select a maximum amount of %0 days',
	undoText : "Undo",
	
	
	/**
	 * @cfg {Boolean} stayInAllowedRange
	 * used then mindate/maxdate is set to prevent changing to a month that does not contain allowed dates
	 */
	stayInAllowedRange: true,

	/**
	 * @cfg {Boolean} summarizeHeader
	 * displays the from/to daterange on top of the datepicker
	 */
	summarizeHeader:false,
	
	/**
	 * @cfg {Boolean} resizable
	 * Whether the calendar can be extended with more/less months by simply resizing it like window
	 */
	resizable: false,
	
	/**
	 * @cfg {Boolean} renderOkUndoButtons
	 * If set to true, the OK- and Undo-Buttons will not be rendered on Multiselection Calendars
	 */
	renderOkUndoButtons : true,

	/**
	 * @cfg {Boolean} renderTodayButton
	 * Whether the Today Button should be rendered
	 */
	renderTodayButton : true,
	/**
	 * @cfg {Boolean} disablePartialUnselect
	 * When multiselecting whole months or weeks, already selected days within this week/month will _not_ get unselected anymore. Set this to false, if you want them to get unselected.
	 * Note: When the _whole set_ of the month/week are already selected, they get _all_ unselected anyway.
	 */
	disablePartialUnselect: true,
	
	allowedDates : false,
	allowedDatesText : '',

	strictRangeSelect : false,

	/**
	 * @cfg {Boolean/Number} displayMask
	 * As huge multimonth calendars can take some updating time this will display a mask when the noOfMonth property is higher than the given value in displayMask.
	 * Set to false to never display the mask
	 * default is 3
	 */
	displayMask:3,
	
	displayMaskText: 'Please wait...',
	
	renderPrevNextButtons: true,
	renderPrevNextYearButtons: false,
	disableMonthPicker:false,
	
	nextYearText: "Next Year (Control+Up)",
	prevYearText: "Previous Year (Control+Down)",
	
	showActiveDate: false,
	shiftSpaceSelect: true,
	disabledLetter: false,
	
	allowMouseWheel: true,
	
//this is accidently called too often in the original (when hovering over monthlabel or bottombar..there is no need to update the cells again and just leaks performance)
	focus: Ext.emptyFn,
	
	initComponent : function(){
		Ext.ux.DatePickerPlus.superclass.initComponent.call(this);
		this.noOfMonthPerRow = this.noOfMonthPerRow > this.noOfMonth ?this.noOfMonth : this.noOfMonthPerRow;
        this.addEvents(
            /**
             * @event beforeyearchange
             * Fires before a new year is selected (or prevYear/nextYear buttons)
             * @param {DatePicker} this
             * @param {oldyearnumber} dates The previous selected year
             * @param {newyearnumber} dates The new selected year
             */
            'beforeyearchange',
            /**
             * @event afteryearchange
             * Fires before a new year is selected (by prevYear/nextYear buttons)
             * @param {DatePicker} this
             * @param {oldyearnumber} dates The previous selected year		 
             * @param {newyearnumber} dates The new selected year
             */
            'afteryearchange',
            /**
             * @event beforemonthchange
             * Fires before a new startmonth is selected (by monthpicker or prev/next buttons)
             * @param {DatePicker} this
             * @param {oldmonthnumber} dates The previous selected month	 
             * @param {newmonthnumber} dates The new selected month
             */
            'beforemonthchange',
            /**
             * @event aftermonthchange
             * Fires before a new startmonth is selected (by monthpicker or prev/next buttons)
             * @param {DatePicker} this
             * @param {oldmonthnumber} dates The previous selected month			 
             * @param {newmonthnumber} dates The new selected month
             */
            'aftermonthchange',
            /**
             * @event beforemonthclick
             * Fires before a full month is (un)selected
             * @param {DatePicker} this
             * @param {monthnumber} dates The selected month
             */
            'beforemonthclick',
            /**
             * @event beforeweekclick
             * Fires before a week is (un)selected
             * @param {DatePicker} this
             * @param {dateobject} dates The first date of selected week
             */
            'beforeweekclick',
            /**
             * @event beforeweekclick
             * Fires before a single day is (un)selected
             * @param {DatePicker} this
             * @param {dateobject} dates The selected date
             */
            'beforedateclick',
            /**
             * @event aftermonthclick
             * Fires after a full month is (un)selected
             * @param {DatePicker} this
             * @param {monthnumber} dates The selected month
             */
            'aftermonthclick',
            /**
             * @event afterweekclick
             * Fires after a week is (un)selected
             * @param {DatePicker} this
             * @param {dateobject} dates The first date of selected week
             */
            'afterweekclick',
            /**
             * @event afterweekclick
             * Fires after a single day is (un)selected
             * @param {DatePicker} this
             * @param {dateobject} dates The selected date
             */
            'afterdateclick',
            /**
             * @event undo
             * Fires when Undo Button is clicked on multiselection right before deleting the preselected dates
             * @param {DatePicker} this
             * @param {Array} dates The preselected Dates
             */
            'undo',
            /**
             * @event beforemousewheel
             * Fires before a mousewheel event should be triggered return false in your function to disable the month change
             * @param {DatePicker} this
             * @param {object} event object
             */
			'beforemousewheel',
            /**
             * @event beforemousewheel
             * Fires before the default message box appears when max days have been reached
			 * return false to cancel the messagebox (to do something on your own)
             * @param {DatePicker} this
             * @param {object} event object
             */
			'beforemaxdays');
	},  
	
	activeDateKeyNav: function(direction) {
		if (this.showActiveDate) {
			this.activeDate = this.activeDate.add("d", direction);
			var adCell = this.activeDateCell.split("#");
			var tmpMonthCell = parseInt(adCell[0],10);
			var tmpDayCell = parseInt(adCell[1],10);
			var currentGetCell = Ext.get(this.cellsArray[tmpMonthCell].elements[tmpDayCell]);
//cursor gets out of visible range?
			if (	(tmpDayCell+direction>41 && tmpMonthCell+1>=this.cellsArray.length)	||
					(tmpDayCell+direction<0 && tmpMonthCell-1<0)	){
				this.update(this.activeDate);
			}
			else {
				currentGetCell.removeClass("x-datepickerplus-activedate");
				tmpDayCell+=direction;
				if (tmpDayCell>41) {
					tmpDayCell-=42;
					tmpMonthCell++;
				}
				else if (tmpDayCell<0) {
					tmpDayCell+=42;
					tmpMonthCell--;
				}
				currentGetCell = Ext.get(this.cellsArray[tmpMonthCell].elements[tmpDayCell]);
				currentGetCell.addClass("x-datepickerplus-activedate");
				this.activeDateCell = tmpMonthCell+"#"+tmpDayCell;
			}
		}
	},

    handleMouseWheel : function(e){
        if(this.fireEvent("beforemousewheel", this,e) !== false){
			var oldStartMonth = (this.activeDate ? this.activeDate.getMonth() : 99);
			var oldStartYear = (this.activeDate ? this.activeDate.getFullYear() : 0);			
			Ext.ux.DatePickerPlus.superclass.handleMouseWheel.call(this,e);
			var newStartMonth = (this.activeDate ? this.activeDate.getMonth() : 999);
			var newStartYear = (this.activeDate ? this.activeDate.getFullYear() : 9999);
			if (oldStartMonth!=newStartMonth) {
				this.fireEvent("aftermonthchange", this, oldStartMonth, newStartMonth);
			}
			if (oldStartYear!=newStartYear) {
				this.fireEvent("afteryearchange", this, oldStartYear, newStartYear);
			}
		}
	},
	

    doDisabled: function(disabled){
        this.keyNav.setDisabled(disabled);
		if (this.renderPrevNextButtons) {
			this.leftClickRpt.setDisabled(disabled);
			this.rightClickRpt.setDisabled(disabled);
		}
		if (this.renderPrevNextYearButtons) {
			this.leftYearClickRpt.setDisabled(disabled);
			this.rightYearClickRpt.setDisabled(disabled);
		}
        if(this.todayBtn){
            this.todayKeyListener.setDisabled(disabled);
            this.todayBtn.setDisabled(disabled);
        }
    },

// private
	onRender : function(container, position){    	
		if (this.noOfMonthPerRow===0) {
			this.noOfMonthPerRow = 1;
		}
		if (this.fillupRows && this.noOfMonthPerRow > 1 && this.noOfMonth % this.noOfMonthPerRow!==0) {
			this.noOfMonth+= (this.noOfMonthPerRow - (this.noOfMonth % this.noOfMonthPerRow));
		}
		var addIEClass = (Ext.isIE?' x-datepickerplus-ie':'');
		var m = ['<table cellspacing="0"',(this.multiSelection?' class="x-date-multiselect'+addIEClass+'" ':(addIEClass!==''?'class="'+addIEClass+'" ':'')),'>'];

		m.push("<tr>");

		var widfaker = (Ext.isIE?'<img src="'+Ext.BLANK_IMAGE_URL+'" />':'');
		var weekNumberQuickTip = (this.multiSelection ? (this.useQuickTips? ' ext:qtip="'+this.selectWeekText+'" ' :' title="'+this.selectWeekText+'" ') : '');
//as weekends (or defined weekly cycles) are displayed on every month at the same place, we can render the quicktips here to save time in update process
		var weekEndQuickTip = (this.markWeekends && this.weekendText!==''? (this.useQuickTips? ' ext:qtip="'+this.weekendText+'" ' :' title="'+this.weekendText+'" '):'');


//calculate the HTML of one month at first to gain some speed when rendering many calendars
		var mpre = ['<thead><tr>'];
		if (this.showWeekNumber) {
			mpre.push('<th class="x-date-weeknumber-header"><a href="#" hidefocus="on" class="x-date-weeknumber" tabIndex="1"><em><span ',(this.multiSelection ? (this.useQuickTips? ' ext:qtip="'+this.selectMonthText+'" ' :' title="'+this.selectMonthText+'" ') : ''),'>' + this.weekName + '</span></em></a></th>');
		}
		
		var dn = this.dayNames;
		for(var i = 0; i < 7; ++i){
		   var d = this.startDay+i;
		   if(d > 6){
			   d = d-7;
		   }
			mpre.push('<th><span>', dn[d].substr(0,1), '</span></th>');
		}
		mpre.push('</tr></thead><tbody><tr>');

		if (this.showWeekNumber) {
			mpre.push('<td class="x-date-weeknumber-cell"><a href="#" hidefocus="on" class="x-date-weeknumber" tabIndex="1"><em><span ',weekNumberQuickTip,'></span></em></a></td>');
		}
		
		for(var k = 0; k < 42; ++k) {
			if(k % 7 === 0 && k > 0){
				if (this.showWeekNumber) {
					mpre.push('</tr><tr><td class="x-date-weeknumber-cell"><a href="#" hidefocus="on" class="x-date-weeknumber" tabIndex="1"><em><span ',weekNumberQuickTip,'></span></em></a></td>');
				} else {
					mpre.push('</tr><tr>');
				}
			}
			mpre.push('<td class="x-date-date-cell"><a href="#" hidefocus="on" class="x-date-date" tabIndex="1"><em><span ',(this.weekendDays.indexOf((k+this.startDay)%7)!=-1?weekEndQuickTip:''),'></span></em></a></td>');
		}
		mpre.push('</tr></tbody></table></td></tr></table></td>');
		var prerenderedMonth = mpre.join("");

		if (this.summarizeHeader && this.noOfMonth > 1) {
			m.push('<td align="center" id="',this.id,'-summarize" colspan="',this.noOfMonthPerRow,'" class="x-date-middle x-date-pickerplus-middle"></td></tr>');
			m.push("<tr>");
		}

		for(var x=0,xk=this.noOfMonth; x<xk; ++x) {            
            m.push('<td><table class="x-date-pickerplus',(x%this.noOfMonthPerRow===0?'':' x-date-monthtable'),(!this.prevNextDaysView?" x-date-pickerplus-prevnexthide":""),'" cellspacing="0"><tr>');
			if (x===0) {
				m.push('<td class="x-date-left">');
				if (this.renderPrevNextButtons) {
					m.push('<a class="npm" href="#" ',(this.useQuickTips? ' ext:qtip="'+this.prevText+'" ' :' title="'+this.prevText+'" '),'></a>');
				}
				if (this.renderPrevNextYearButtons) {
					m.push('<a class="npy" href="#" ',(this.useQuickTips? ' ext:qtip="'+this.prevYearText+'" ' :' title="'+this.prevYearText+'" '),'></a>');
				}
				m.push('</td>');
			}			
			else if (x==this.noOfMonthPerRow-1) {
				if (this.renderPrevNextButtons) {				
					m.push('<td class="x-date-dummy x-date-middle">',widfaker,'</td>');
				}
			}			
            m.push("<td class='x-date-middle x-date-pickerplus-middle",(x===0 && !this.disableMonthPicker ?" x-date-firstMonth":""),"' align='center'>");
			if (x>0 || this.disableMonthPicker) {
				m.push('<span id="',this.id,'-monthLabel', x , '"></span>');
			}
			m.push('</td>');
			if (x==this.noOfMonthPerRow-1)	{
				m.push('<td class="x-date-right">');
				if (this.renderPrevNextButtons) {				
					m.push('<a class="npm" href="#" ', (this.useQuickTips? ' ext:qtip="'+this.nextText+'" ' :' title="'+this.nextText+'" ') ,'></a>');
				}
				if (this.renderPrevNextYearButtons) {
					m.push('<a class="npy" href="#" ',(this.useQuickTips? ' ext:qtip="'+this.nextYearText+'" ' :' title="'+this.nextYearText+'" '),'></a>');
				}
				m.push('</td>');				
			}
			else if (x===0) {
				if (this.renderPrevNextButtons) {				
					m.push('<td class="x-date-dummy x-date-middle">',widfaker,'</td>');
				}
			}			
			
            m.push('</tr><tr><td',(x===0 || x==this.noOfMonthPerRow-1?' colspan="3" ':''),'><table class="x-date-inner" id="',this.id,'-inner-date', x ,'" cellspacing="0">');

			m.push(prerenderedMonth);
	
            if( (x+1) % this.noOfMonthPerRow === 0) {
                m.push("</tr><tr>");
            }            
        }
        m.push('</tr>');
		
		m.push('<tr><td',(this.noOfMonthPerRow>1?' colspan="'+this.noOfMonthPerRow+'"':''),' class="x-date-bottom" align="center"><div><table width="100%" cellpadding="0" cellspacing="0"><tr><td align="right" class="x-date-multiokbtn">',widfaker,'</td><td align="center" class="x-date-todaybtn">',widfaker,'</td><td align="left" class="x-date-multiundobtn">',widfaker,'</td></tr></table></div></td></tr>');
		
		m.push('</table><div class="x-date-mp"></div>');
        var el = document.createElement("div");
        el.className = "x-date-picker";
        el.innerHTML = m.join("");  

        container.dom.insertBefore(el, position);

        this.el = Ext.get(el);        
        this.eventEl = Ext.get(el.firstChild);

		if (this.renderPrevNextButtons) {
			this.leftClickRpt = new Ext.util.ClickRepeater(this.el.child("td.x-date-left a.npm"), {
				handler: this.showPrevMonth,
				scope: this,
				preventDefault:true,
				stopDefault:true
			});
	
			this.rightClickRpt = new Ext.util.ClickRepeater(this.el.child("td.x-date-right a.npm"), {
				handler: this.showNextMonth,
				scope: this,
				preventDefault:true,
				stopDefault:true
			});
		}
		
		if (this.renderPrevNextYearButtons) {
			this.leftYearClickRpt = new Ext.util.ClickRepeater(this.el.child("td.x-date-left a.npy"), {
				handler: this.showPrevYear,
				scope: this,
				preventDefault:true,
				stopDefault:true
			});
	
			this.rightYearClickRpt = new Ext.util.ClickRepeater(this.el.child("td.x-date-right a.npy"), {
				handler: this.showNextYear,
				scope: this,
				preventDefault:true,
				stopDefault:true
			});
		}
		if (this.allowMouseWheel) {
			this.eventEl.on("mousewheel", this.handleMouseWheel,  this);
		}


        this.keyNav = new Ext.KeyNav(this.eventEl, {
            "left" : function(e){
                (!this.disabled && e.ctrlKey && (!this.disableMonthPicker || this.renderPrevNextButtons) ?
                    this.showPrevMonth() :
					this.activeDateKeyNav(-1));
            },

            "right" : function(e){
                (!this.disabled && e.ctrlKey && (!this.disableMonthPicker || this.renderPrevNextButtons) ?
                    this.showNextMonth() :
					this.activeDateKeyNav(1));
            },

            "up" : function(e){
                (!this.disabled && e.ctrlKey && (!this.disableMonthPicker || this.renderPrevNextYearButtons) ?
                    this.showNextYear() :
					this.activeDateKeyNav(-7));
            },

            "down" : function(e){
                (!this.disabled && e.ctrlKey && (!this.disableMonthPicker || this.renderPrevNextYearButtons) ?
                    this.showPrevYear() :
					this.activeDateKeyNav(7));
            },

            "pageUp" : function(e){
				if (!this.disabled) {
			        this.update(this.activeDate.add("mo", this.pageKeyWarp*(-1)));
				}
            },

            "pageDown" : function(e){
				if (!this.disabled) {				
			        this.update(this.activeDate.add("mo", this.pageKeyWarp));
				}
            },

            "enter" : function(e){
                e.stopPropagation();
				if (!this.disabled) {				
					if (this.multiSelection) {
						this.okClicked();
					}
					else {
						this.finishDateSelection(this.activeDate);
					}
				}
                return true;
            }, 
            scope : this 
        });

		if (!this.disableSingleDateSelection) {
			this.eventEl.on("click", this.handleDateClick,  this, {delegate: "a.x-date-date"});
		}
		if (this.multiSelection && this.showWeekNumber) {
			this.eventEl.on("click", this.handleWeekClick,  this, {delegate: "a.x-date-weeknumber"});
		}
        
        this.cellsArray = [];
        this.textNodesArray = [];
        this.weekNumberCellsArray = [];
        this.weekNumberTextElsArray = [];		
		this.weekNumberHeaderCellsArray = [];
	
		var cells,textNodes,weekNumberCells,weekNumberTextEls,weekNumberHeaderCells;
        for(var xx=0,xxk=this.noOfMonth; xx< xxk; ++xx) {
            cells = Ext.get(this.id+'-inner-date'+xx).select("tbody td.x-date-date-cell");
            textNodes = Ext.get(this.id+'-inner-date'+xx).query("tbody td.x-date-date-cell span");
            this.cellsArray[xx] = cells;
            this.textNodesArray[xx] = textNodes;
			if (this.showWeekNumber) {
				weekNumberCells = Ext.get(this.id+'-inner-date'+xx).select("tbody td.x-date-weeknumber-cell");
				weekNumberTextEls = Ext.get(this.id+'-inner-date'+xx).select("tbody td.x-date-weeknumber-cell span");				
				this.weekNumberCellsArray[xx] = weekNumberCells;
				this.weekNumberTextElsArray[xx] = weekNumberTextEls;				
				weekNumberHeaderCells = Ext.get(this.id+'-inner-date'+xx).select("th.x-date-weeknumber-header");
				this.weekNumberHeaderCellsArray[xx] = weekNumberHeaderCells;
			}
        }

//set the original monthpicker again to the first month only to be able to quickly change the startmonth		
		if (!this.disableMonthPicker) {
	        this.monthPicker = this.el.down('div.x-date-mp');
	        this.monthPicker.enableDisplayMode('block');
			
			this.mbtn = new Ext.Button({
				text: "&#160;",
				tooltip: this.monthYearText,
				renderTo: this.el.child("td.x-date-firstMonth", true)			
			});
	
			this.mbtn.on('click', this.showMonthPickerPlus, this);
	        this.mbtn.el.child('em').addClass('x-btn-arrow');						
//			this.mbtn.el.child(this.mbtn.menuClassTarget).addClass("x-btn-with-menu");
		}

//showtoday from Ext 2.2
		if (this.renderTodayButton || this.showToday) {
	        this.todayKeyListener = this.eventEl.addKeyListener(Ext.EventObject.SPACE, this.spaceKeyPressed,  this);					
	        var today = new Date().dateFormat(this.format);			
			this.todayBtn = new Ext.Button({
				renderTo: this.el.child("td.x-date-bottom .x-date-todaybtn", true),
                text: String.format(this.todayText, today),
				tooltip: String.format(this.todayTip, today),
				handler: this.selectToday,
				scope: this
			});
		}
		
		if (this.multiSelection && this.renderOkUndoButtons) {
			this.OKBtn = new Ext.Button({
	            renderTo: this.el.child("td.x-date-bottom .x-date-multiokbtn", true),
				text: this.okText,
				handler: this.okClicked,
				scope: this
			});

			this.undoBtn = new Ext.Button({
	            renderTo: this.el.child("td.x-date-bottom .x-date-multiundobtn", true),
				text: this.undoText,
				handler: function() {
					if (!this.disabled) {
						this.fireEvent("undo", this, this.preSelectedDates);
						this.preSelectedDates = [];
						for (var i=0,il=this.selectedDates.length;i<il;++i) {
							this.preSelectedDates.push(this.selectedDates[i].clearTime().getTime());
						}
						this.update(this.activeDate);
					}
				},
				scope: this
			});
		}
		
//In development...
/*
		if (this.resizable) {		
			var resizer = new Ext.Resizable(this.el, {
				handles: 'all',
// at least one month should be displayed				
				minWidth:200,
				minHeight:300,
				maxWidth: 1000,
				maxHeight: 800,
				heightIncrement: 250,
				widthIncrement: 200,
				adjustments: 'auto',	
				transparent:true
			});
			resizer.on("resize", function(){
	//			alert("you resized the calendar,ouch!");
			},this);
		}
*/

		if(Ext.isIE){
            this.el.repaint();
        }
//preselect dates if given
		this.preSelectedDates = [];
		for(var sdc=0, sdcl=this.selectedDates.length; sdc < sdcl; ++sdc) {
		   this.preSelectedDates.push(this.selectedDates[sdc].clearTime().getTime());
		}

        this.update(this.value);
    },
	
	showMonthPickerPlus: function() {
		if (!this.disabled) {
			this.showMonthPicker();
		}
	},

//converts all custom dates to timestamps numbers for faster calculations and splits their attributes into separate arrays
	convertCSSDatesToNumbers : function(objarr) {
//date,text,class		
		var converted =  [[],[],[]];
		for (var i=0,il=objarr.length;i<il;++i) {
			converted[0][i] = objarr[i].date.clearTime().getTime();
			converted[1][i] = (objarr[i].text ? objarr[i].text : this.defaultEventDatesText);
			converted[2][i] = (objarr[i].cls ? objarr[i].cls : this.defaultEventDatesCls);
		}
		return converted;
	},

	clearSelectedDates : function(update) {
		if (typeof update === "undefined") {
			update=true;
		}
		this.selectedDates = [];
		this.preSelectedDates = [];
		if (this.rendered && update) {
			this.update(this.activeDate);
		}
	},
	
//support json dates
	jsonDate: function(dates) {
		if (!Ext.isArray(dates)) {
			if (typeof dates === "string") {
				return Date.parseDate(dates.replace(/T/," "),'Y-m-d H:i:s');
			}
		}
		else {
			for (var i=0,il=dates.length;i<il;i++) {
				if (typeof dates[i] === "string") {
					dates[i] = Date.parseDate(dates[i].replace(/T/," "),'Y-m-d H:i:s');
				}
			}
		}
		return dates;
	},
	
	setSelectedDates : function(dates,update) {
		if (typeof update === "undefined") {
			update=true;
		}
		dates = this.jsonDate(dates);
		if (!Ext.isArray(dates)) {
			dates = [dates];
		}
		var d, dt;
		for (var i=0,il=dates.length;i<il;++i) {
			d = dates[i];
			dt = d.clearTime().getTime();
			if (this.preSelectedDates.indexOf(dt)==-1) {
				this.preSelectedDates.push(dt);
				this.selectedDates.push(d);				
			}
		}
		if (this.rendered && update) {
			this.update(this.activeDate);
		}
	},

	setAllowedDates : function(dates,update) {
		if (typeof update === "undefined") {
			update=true;
		}
		this.allowedDates = this.jsonDate(dates);
		if (this.rendered && update) {
			this.update(this.activeDate);
		}
	},

	setMinDate: function(minDate) {
		this.minDate = this.jsonDate(minDate);
        this.update(this.value, true);
	},

	setMaxDate: function(maxDate) {
		this.maxDate = this.jsonDate(maxDate);
        this.update(this.value, true);
	},

	setDateLimits: function(minDate,maxDate) {
		this.minDate = this.jsonDate(minDate);
		this.maxDate = this.jsonDate(maxDate);
        this.update(this.value, true);
	},

	
	// private
//forcerefresh option from ext 2.2 just included to be compatible	
    update : function(date, forceRefresh ,masked){
		if (typeof masked==="undefined")  {
			masked = false;
		}
		if (typeof forceRefresh==="undefined")  {
			forceRefresh = false;
		}
		
		if (forceRefresh) {
			var ad = this.activeDate;
			this.activeDate = null;
			date = ad;			
		}				
		
		var dMask = (this.displayMask && (isNaN(this.displayMask) || this.noOfMonth > this.displayMask)? true: false);
		
		if (!masked && dMask) {
			this.el.mask(this.displayMaskText);
//set forcerefresh to false because new date (from old activedate) is already calculated
			this.update.defer(10, this, [date,false,true]);
			return false;
		}
		
		if (this.stayInAllowedRange && (this.minDate||this.maxDate)) {
			if (this.minDate && (this.minDate.getFullYear() > date.getFullYear() || (this.minDate.getMonth() > date.getMonth() && this.minDate.getFullYear() == date.getFullYear()))) {
				date = new Date(this.minDate.getTime());
			}
			else if (this.maxDate && (this.maxDate.getFullYear() < date.getFullYear() || (this.maxDate.getMonth() < date.getMonth() && this.maxDate.getFullYear() == date.getFullYear()))) {
				date = new Date(this.maxDate.getTime());
			}
		}
		
		var newStartMonth = date.getMonth();
		var oldStartMonth = (this.activeDate ? this.activeDate.getMonth() : newStartMonth);
		var newStartYear = date.getFullYear();
		var oldStartYear = (this.activeDate ? this.activeDate.getFullYear() : newStartYear);
		
		if (oldStartMonth!=newStartMonth) {
            this.fireEvent("beforemonthchange", this, oldStartMonth, newStartMonth);			
		}
		if (oldStartYear!=newStartYear) {
            this.fireEvent("beforeyearchange", this, oldStartYear, newStartYear);
		}
		
        this.activeDate = date.clearTime();
		this.preSelectedCells = [];
		this.lastSelectedDateCell = '';
		this.activeDateCell = '';
		var lsd = (this.lastSelectedDate?this.lastSelectedDate:0);
		var today = new Date().clearTime().getTime();
		var min = this.minDate ? this.minDate.clearTime().getTime() : Number.NEGATIVE_INFINITY;
		var max = this.maxDate ? this.maxDate.clearTime().getTime() : Number.POSITIVE_INFINITY;
		var ddMatch = this.disabledDatesRE;
		var ddText = this.disabledDatesText;
		var ddays = this.disabledDays ? this.disabledDays.join("") : false;
		var ddaysText = this.disabledDaysText;
		
		var edMatch = this.eventDatesRE;
		var edCls = this.eventDatesRECls;
		var edText = this.eventDatesREText;		

		var adText = this.allowedDatesText;
		
		var format = this.format;
		var adt = this.activeDate.getTime();
		
		this.todayMonthCell	= false;
		this.todayDayCell = false;
		if (this.allowedDates) {
			this.allowedDatesT = [];
			for (var k=0, kl=this.allowedDates.length;k<kl;++k) {
				this.allowedDatesT.push(this.allowedDates[k].clearTime().getTime());
			}
		}
		var setCellClass = function(cal, cell,textnode,d){
	
			var foundday, eCell = Ext.get(cell), eTextNode = Ext.get(textnode), t = d.getTime(), tiptext=false, fvalue;
			cell.title = "";
			cell.firstChild.dateValue = t;

//check this per day, so holidays between years in the same week will be recognized (newyear in most cases),
//yearly eventdates are also possible then
			var dfY = d.getFullYear();
			if (cal.lastRenderedYear!==dfY) {
				cal.lastRenderedYear=dfY;
				if(cal.markNationalHolidays) {
//calculate new holiday list for current year
					cal.nationalHolidaysNumbered = cal.convertCSSDatesToNumbers(cal.nationalHolidays(dfY));
				}
				cal.eventDatesNumbered = cal.convertCSSDatesToNumbers(cal.eventDates(dfY));
			}
			
			// disabling
			if(t < min) {
				cell.className = " x-date-disabled";
				tiptext = cal.minText;				
			}
			if(t > max) {
				cell.className = " x-date-disabled";
				tiptext = cal.maxText;
			}
			if(ddays){
				if(ddays.indexOf(d.getDay()) != -1){
					tiptext = ddaysText;
					cell.className = " x-date-disabled";
				}
			}
			if(ddMatch && format){
				fvalue = d.dateFormat(format);
				if(ddMatch.test(fvalue)){
					tiptext = ddText.replace("%0", fvalue);					
					cell.className = " x-date-disabled";
				}
			}

			if (cal.allowedDates && cal.allowedDatesT.indexOf(t)==-1){
				cell.className = " x-date-disabled";
				tiptext = adText;
			}

			//mark weekends
			if(cal.markWeekends && cal.weekendDays.indexOf(d.getDay()) != -1 && !eCell.hasClass('x-date-disabled')) {
				eCell.addClass(cal.weekendCls);
			}
			

			if(!eCell.hasClass('x-date-disabled') || cal.styleDisabledDates) {
//mark dates with specific css (still selectable) (higher priority than weekends)
				if (cal.eventDatesNumbered[0].length>0) {
					foundday = cal.eventDatesNumbered[0].indexOf(t);
					if (foundday!=-1) {
						if(cal.eventDatesNumbered[2][foundday]!==""){						
							eCell.addClass(cal.eventDatesNumbered[2][foundday]+(cal.eventDatesSelectable?"":"-disabled"));
							tiptext = (cal.eventDatesNumbered[1][foundday]!=="" ? cal.eventDatesNumbered[1][foundday] : false);
						}
					}
				}

//regular Expression custom CSS Dates
				if(edMatch && format){
					fvalue = d.dateFormat(format);
					if(edMatch.test(fvalue)){
						tiptext = edText.replace("%0", fvalue);					
						cell.className = edCls;
					}
				}
			}
			
			
			if(!eCell.hasClass('x-date-disabled')) {
//mark Holidays				
				if(cal.markNationalHolidays && cal.nationalHolidaysNumbered[0].length>0) {
					foundday = cal.nationalHolidaysNumbered[0].indexOf(t);
					if (foundday!=-1) {
						eCell.addClass(cal.nationalHolidaysCls);
						tiptext = (cal.nationalHolidaysNumbered[1][foundday]!=="" ? cal.nationalHolidaysNumbered[1][foundday] : false);
					}
				}
				
				
//finally mark already selected items as selected
				if (cal.preSelectedDates.indexOf(t)!=-1) {
					eCell.addClass("x-date-selected");
					cal.preSelectedCells.push(cell.firstChild.monthCell+"#"+cell.firstChild.dayCell);
				}
				
				if (t == lsd) {
					cal.lastSelectedDateCell = cell.firstChild.monthCell+"#"+cell.firstChild.dayCell;
				}
				
			}
			else if (cal.disabledLetter){
				textnode.innerHTML = cal.disabledLetter;
			}

//mark today afterwards to ensure today CSS has higher priority
			if(t == today){
				eCell.addClass("x-date-today");
				tiptext = cal.todayText;
			}

//keynavigation?
			if(cal.showActiveDate && t == adt && cal.activeDateCell === ''){
				eCell.addClass("x-datepickerplus-activedate");
				cal.activeDateCell = cell.firstChild.monthCell+"#"+cell.firstChild.dayCell;
			}

//any quicktips necessary?
			if (tiptext) {
				if (cal.useQuickTips) {
					Ext.QuickTips.register({
						target: eTextNode,
						text: tiptext
					});
				}
				else {
					cell.title = tiptext;
				}
			}
			
			
		};

		var cells,textEls,days,firstOfMonth,startingPos,pm,prevStart,d,sel,i,intDay,weekNumbers,weekNumbersTextEls,curWeekStart,weekNumbersHeader,monthLabel,main,w;
		var summarizeHTML = [];
		for(var x=0,xk=this.noOfMonth;x<xk;++x) {
			if (this.summarizeHeader && this.noOfMonth > 1 && (x===0||x==this.noOfMonth-1)) {
				summarizeHTML.push(this.monthNames[date.getMonth()]," ",date.getFullYear());
				if (x===0) {
					summarizeHTML.push(" - ");
				}
			}
			cells = this.cellsArray[x].elements;
			textEls = this.textNodesArray[x];

			if ((this.markNationalHolidays || this.eventDates.length>0) && this.useQuickTips) {
				for (var e=0,el=textEls.length;e<el;++e) {
					Ext.QuickTips.unregister(textEls[e]);
				}
			}
			
			days = date.getDaysInMonth();
			firstOfMonth = date.getFirstDateOfMonth();
			startingPos = firstOfMonth.getDay()-this.startDay;
	
			if(startingPos <= this.startDay){
				startingPos += 7;
			}
	
			pm = date.add("mo", -1);
			prevStart = pm.getDaysInMonth()-startingPos;
	
			days += startingPos;
	
			d = new Date(pm.getFullYear(), pm.getMonth(), prevStart).clearTime();
	
			i = 0;
			if (this.showWeekNumber) {
				weekNumbers = this.weekNumberCellsArray[x].elements;
				weekNumbersTextEls = this.weekNumberTextElsArray[x].elements;				
				curWeekStart = new Date(d);
				curWeekStart.setDate(curWeekStart.getDate() + 7);
				
				weekNumbersHeader = this.weekNumberHeaderCellsArray[x].elements;
				weekNumbersHeader[0].firstChild.monthValue = date.getMonth();
				weekNumbersHeader[0].firstChild.dateValue = curWeekStart.getTime();				
				weekNumbersHeader[0].firstChild.monthCell = x;
				weekNumbersHeader[0].firstChild.dayCell = 0;
				
				while(i < weekNumbers.length) {
					weekNumbersTextEls[i].innerHTML = curWeekStart.getWeekOfYear();
					weekNumbers[i].firstChild.dateValue = curWeekStart.getTime();
					weekNumbers[i].firstChild.monthCell = x;
					weekNumbers[i].firstChild.dayCell = (i*7);
					curWeekStart.setDate(curWeekStart.getDate() + 7);
					i++;
				}
				i = 0;
			}

			for(; i < startingPos; ++i) {
				textEls[i].innerHTML = (++prevStart);
				cells[i].firstChild.monthCell = x;
				cells[i].firstChild.dayCell = i;
				
				d.setDate(d.getDate()+1);
				cells[i].className = "x-date-prevday";
				setCellClass(this, cells[i],textEls[i],d);
			}
			
			for(; i < days; ++i){
				intDay = i - startingPos + 1;
				textEls[i].innerHTML = (intDay);
				cells[i].firstChild.monthCell = x;
				cells[i].firstChild.dayCell = i;
				d.setDate(d.getDate()+1);
				cells[i].className = "x-date-active";
				setCellClass(this, cells[i],textEls[i],d);
				if(d.getTime() == today){
					this.todayMonthCell	= x;
					this.todayDayCell = i;
				}
			}
		
			var extraDays = 0;
			for(; i < 42; ++i) {
				textEls[i].innerHTML = (++extraDays);
				cells[i].firstChild.monthCell = x;
				cells[i].firstChild.dayCell = i;
				d.setDate(d.getDate()+1);
				cells[i].className = "x-date-nextday";
				setCellClass(this, cells[i],textEls[i],d);
			}

			if (x===0 && !this.disableMonthPicker) {
				this.mbtn.setText(this.monthNames[date.getMonth()] + " " + date.getFullYear());
			}
			else {
				monthLabel = Ext.get(this.id+'-monthLabel' + x);                    
				monthLabel.update(this.monthNames[date.getMonth()] + " " + date.getFullYear());
			}
			date = date.add('mo',1);

			
			if(!this.internalRender){
				main = this.el.dom.firstChild;
				w = main.offsetWidth;
				this.el.setWidth(w + this.el.getBorderWidth("lr"));
				Ext.fly(main).setWidth(w);
				this.internalRender = true;
				// opera does not respect the auto grow header center column
				// then, after it gets a width opera refuses to recalculate
				// without a second pass
//Not needed anymore (tested with opera 9)
/*
				if(Ext.isOpera && !this.secondPass){
					main.rows[0].cells[1].style.width = (w - (main.rows[0].cells[0].offsetWidth+main.rows[0].cells[2].offsetWidth)) + "px";
					this.secondPass = true;
					this.update.defer(10, this, [date]);
				}
*/							
			}
		}
		if (this.summarizeHeader && this.noOfMonth > 1) {
			var topHeader = Ext.get(this.id+'-summarize');
			topHeader.update(summarizeHTML.join(""));
		}
		this.el.unmask();
		if (oldStartMonth!=newStartMonth) {
            this.fireEvent("aftermonthchange", this, oldStartMonth, newStartMonth);
		}
		if (oldStartYear!=newStartYear) {
            this.fireEvent("afteryearchange", this, oldStartYear, newStartYear);
		}
	
    },

	beforeDestroy : function() {
		if(this.rendered) {		
            this.keyNav.disable();
            this.keyNav = null;
			if (this.renderPrevNextButtons) {
				Ext.destroy(
					this.leftClickRpt,
					this.rightClickRpt
				);
			}
			if (this.renderPrevNextYearButtons) {
				Ext.destroy(
					this.leftYearClickRpt,
					this.rightYearClickRpt
				);
			}
			if (!this.disableMonthPicker) {
				Ext.destroy(
					this.monthPicker,
					this.mbtn
				);
			}
			if (this.todayBtn) {
				this.todayBtn.destroy();
			}
			if (this.OKBtn){
				this.OKBtn.destroy();
			}
			if (this.undoBtn){
				this.undoBtn.destroy();			
			}
			this.eventEl.remove();
		}
	},


    handleWeekClick : function(e, t){
		if (!this.disabled) {
			e.stopEvent();
			var startweekdate = new Date(t.dateValue).getFirstDateOfWeek(this.startDay), amount=0, startmonth, curmonth,enableUnselect;
			var monthcell = t.monthCell;
			var daycell = t.dayCell;
			switch(t.parentNode.tagName.toUpperCase()) {
			case "TH":
				amount=42;
				startmonth = t.monthValue;
				break;
			case "TD":
				amount=7;
				break;
			}
			
			if ((amount==42 && this.fireEvent("beforemonthclick", this, startmonth,this.lastStateWasSelected) !== false) ||
			    (amount==7 && this.fireEvent("beforeweekclick", this, startweekdate,this.lastStateWasSelected) !== false)) {
			
				if (!Ext.EventObject.ctrlKey && this.multiSelectByCTRL) {
					this.removeAllPreselectedClasses();
				}
				
				enableUnselect=true;	
				if (this.disablePartialUnselect) {
					var teststartweekdate = startweekdate;
					for (var k=0;k<amount;++k) {
		//check, if the whole set is still selected, then make unselection possible again
						curmonth = teststartweekdate.getMonth();		
						if ((amount == 7 || curmonth === startmonth) && this.preSelectedDates.indexOf(teststartweekdate.clearTime().getTime())==-1) {
							enableUnselect=false;
							break;
						}
						teststartweekdate = teststartweekdate.add(Date.DAY,1);
					}
				}
		
				var reverseAdd =  false;
				var dateAdder = 1;
				if (this.strictRangeSelect &&	(
													(this.preSelectedDates.indexOf(startweekdate.add(Date.DAY,-1).clearTime().getTime())==-1 && !enableUnselect) ||
													(this.preSelectedDates.indexOf(startweekdate.add(Date.DAY,-1).clearTime().getTime())!=-1 && enableUnselect)
												)
					) {
					reverseAdd = true;
					startweekdate = startweekdate.add(Date.DAY,amount-1);
					dateAdder = -1;
				}
				
				this.maxNotified = false;
				for (var i=0,ni;i<amount;++i) {
					curmonth = startweekdate.getMonth();
					ni = (reverseAdd ? amount-1-i : i);
					if (amount == 7 || curmonth === startmonth) {
						this.markDateAsSelected(startweekdate.clearTime().getTime(),true,monthcell,daycell+ni,enableUnselect);
					}
					startweekdate = startweekdate.add(Date.DAY,dateAdder);
				}
				if (amount==42) {
					this.fireEvent("aftermonthclick", this, startmonth,this.lastStateWasSelected);
				}
				else {
					this.fireEvent("afterweekclick", this, new Date(t.dateValue).getFirstDateOfWeek(this.startDay),this.lastStateWasSelected);
				}
			}
		}
	},

	markDateAsSelected : function(t,fakeCTRL,monthcell,daycell,enableUnselect) {
		var currentGetCell = Ext.get(this.cellsArray[monthcell].elements[daycell]);
	
		if ((currentGetCell.hasClass("x-date-prevday") || currentGetCell.hasClass("x-date-nextday") ) && this.prevNextDaysView!=="mark") {		
			return false;
		}

		if (this.multiSelection && (Ext.EventObject.ctrlKey || fakeCTRL)) {
			var beforeDate = new Date(t).add(Date.DAY,-1).clearTime().getTime();
			var afterDate = new Date(t).add(Date.DAY,1).clearTime().getTime();				
			
			if (this.preSelectedDates.indexOf(t)==-1) {
				if (this.maxSelectionDays === this.preSelectedDates.length) {
					if (!this.maxNotified)  {
				        if(this.fireEvent("beforemaxdays", this) !== false){
							Ext.Msg.alert(this.maxSelectionDaysTitle,this.maxSelectionDaysText.replace(/%0/,this.maxSelectionDays));
						}
						this.maxNotified = true;
					}
					return false;
				}
				if (currentGetCell.hasClass("x-date-disabled")) {
					return false;
				}
				
				if (this.strictRangeSelect && this.preSelectedDates.indexOf(afterDate)==-1 && this.preSelectedDates.indexOf(beforeDate)==-1 && this.preSelectedDates.length > 0) {
					return false;
				}
				
				this.preSelectedDates.push(t);
				this.markSingleDays(monthcell,daycell,false);
				this.markGhostDatesAlso(monthcell,daycell,false);
				this.lastStateWasSelected = true;
			}
			else {
				if (enableUnselect &&	(!this.strictRangeSelect ||
											(this.strictRangeSelect && 
											 	(
													(this.preSelectedDates.indexOf(afterDate)==-1 && this.preSelectedDates.indexOf(beforeDate)!=-1 ) ||
													(this.preSelectedDates.indexOf(afterDate)!=-1 && this.preSelectedDates.indexOf(beforeDate)==-1 )
												)
											)
										)
					){
					this.preSelectedDates.remove(t);
					this.markSingleDays(monthcell,daycell,true);
					this.markGhostDatesAlso(monthcell,daycell,true);
					this.lastStateWasSelected = false;
				}
			}
		}
		else {
//calling update in any case would get too slow on huge multiselect calendars, so set the class for the selected cells manually	 (MUCH faster if not calling update() every time!)
			this.removeAllPreselectedClasses();
			this.preSelectedDates = [t];			
			this.preSelectedCells = [];
			this.markSingleDays(monthcell,daycell,false);
			this.markGhostDatesAlso(monthcell,daycell,false);
			this.lastStateWasSelected = true;
		}
		this.lastSelectedDate = t;
		this.lastSelectedDateCell = monthcell+"#"+daycell;
		if (this.multiSelection && !this.renderOkUndoButtons) {
			this.copyPreToSelectedDays();
		}
		return true;
	},

	markSingleDays : function(monthcell,daycell,remove) {
		if(!remove) {
			Ext.get(this.cellsArray[monthcell].elements[daycell]).addClass("x-date-selected");
			this.preSelectedCells.push((monthcell)+"#"+(daycell));
		}
		else {
			Ext.get(this.cellsArray[monthcell].elements[daycell]).removeClass("x-date-selected");
			this.preSelectedCells.remove((monthcell)+"#"+(daycell));
		}
	},

	markGhostDatesAlso : function(monthcell,daycell,remove) {
		if (this.prevNextDaysView=="mark") {
			var currentGetCell = Ext.get(this.cellsArray[monthcell].elements[daycell]), dayCellDiff;
			if(currentGetCell.hasClass("x-date-prevday") && monthcell>0) {
				dayCellDiff = (5-Math.floor(daycell/7))*7;
				if(Ext.get(this.cellsArray[monthcell-1].elements[daycell+dayCellDiff]).hasClass("x-date-nextday")) {
					dayCellDiff-=7;
				}
				this.markSingleDays(monthcell-1,daycell+dayCellDiff,remove);
			}
			else if(currentGetCell.hasClass("x-date-nextday") && monthcell<this.cellsArray.length-1) {
				dayCellDiff = 28;
				if(this.cellsArray[monthcell].elements[daycell].firstChild.firstChild.firstChild.innerHTML != this.cellsArray[monthcell+1].elements[daycell-dayCellDiff].firstChild.firstChild.firstChild.innerHTML) {
					dayCellDiff=35;
				}
				this.markSingleDays(monthcell+1,daycell-dayCellDiff,remove);
			}
			else if(currentGetCell.hasClass("x-date-active") && ((daycell < 14 && monthcell>0) || (daycell > 27 && monthcell<this.cellsArray.length-1))){
				if (daycell<14) {
					dayCellDiff = 28;
					if(!Ext.get(this.cellsArray[monthcell-1].elements[daycell+dayCellDiff]).hasClass("x-date-nextday")) {
						dayCellDiff=35;
					}
					if(daycell+dayCellDiff < 42 && this.cellsArray[monthcell].elements[daycell].firstChild.firstChild.firstChild.innerHTML == this.cellsArray[monthcell-1].elements[daycell+dayCellDiff].firstChild.firstChild.firstChild.innerHTML) {
						this.markSingleDays(monthcell-1,daycell+dayCellDiff,remove);
					}
				}
				else {
					dayCellDiff = 28;
					if(!Ext.get(this.cellsArray[monthcell+1].elements[daycell-dayCellDiff]).hasClass("x-date-prevday")) {
						dayCellDiff=35;
					}
					if(daycell-dayCellDiff >= 0 && this.cellsArray[monthcell].elements[daycell].firstChild.firstChild.firstChild.innerHTML == this.cellsArray[monthcell+1].elements[daycell-dayCellDiff].firstChild.firstChild.firstChild.innerHTML) {
						this.markSingleDays(monthcell+1,daycell-dayCellDiff,remove);
					}
				}
			}
		}
	},
	
	
	removeAllPreselectedClasses : function() {
		for (var e=0,el=this.preSelectedCells.length;e<el;++e) {												
			var position = this.preSelectedCells[e].split("#");
			Ext.get(this.cellsArray[position[0]].elements[position[1]]).removeClass("x-date-selected");
		}
		this.preSelectedDates = [];
		this.preSelectedCells = [];
	},

    handleDateClick : function(e, t){
		
		e.stopEvent();
		var tp = Ext.fly(t.parentNode);

		var startweekdate = new Date(t.dateValue).getFirstDateOfWeek(this.startDay);
		var startmonthdate = new Date(t.dateValue).getFirstDateOfMonth();
		
        if(!this.disabled && t.dateValue && !tp.hasClass("x-date-disabled") && !tp.hasClass("x-datepickerplus-eventdates-disabled") && this.fireEvent("beforedateclick", this,t) !== false){
			if (( !tp.hasClass("x-date-prevday") && !tp.hasClass("x-date-nextday") ) || this.prevNextDaysView=="mark") {
				var eO = Ext.EventObject;
				if ((!eO.ctrlKey && this.multiSelectByCTRL) || eO.shiftKey || !this.multiSelection) {
					this.removeAllPreselectedClasses();
				}
				var ctrlfaker = (((!eO.ctrlKey && !this.multiSelectByCTRL) || eO.shiftKey) && this.multiSelection ? true:false);
	
				//radu
				if (eO.shiftKey && this.multiSelection && this.lastSelectedDate && this.selectionType!='week' && this.selectionType!='month') {
					var startdate = this.lastSelectedDate;
					var targetdate = t.dateValue;
					var dayDiff = (startdate<targetdate? 1:-1);
					var lsdCell = this.lastSelectedDateCell.split("#");
					var tmpMonthCell = parseInt(lsdCell[0],10);
					var tmpDayCell = parseInt(lsdCell[1],10);
					var testCell,ghostCounter=0,ghostplus=0;
	
					this.maxNotified = false;
	
	
	
	//startdate lies in nonvisible month ?
					var firstVisibleDate = this.activeDate.getFirstDateOfMonth().clearTime().getTime();
					var lastVisibleDate = this.activeDate.add(Date.MONTH,this.noOfMonth-1).getLastDateOfMonth().clearTime().getTime();
	
					if (startdate<firstVisibleDate ||
						startdate>lastVisibleDate) {
				
	//prepare for disabledCheck
						var min = this.minDate ? this.minDate.clearTime().getTime() : Number.NEGATIVE_INFINITY;
						var max = this.maxDate ? this.maxDate.clearTime().getTime() : Number.POSITIVE_INFINITY;
						var ddays = this.disabledDays ? this.disabledDays.join("") : "";
						var ddMatch = this.disabledDatesRE;
						var format = this.format;
						var allowedDatesT =  this.allowedDates ? this.allowedDatesT : false;
						var d,ddMatchResult,fvalue;
	//check, if the days would be disabled
						while(startdate<firstVisibleDate || startdate>lastVisibleDate) {
							d=new Date(startdate);
	
							ddMatchResult = false;
							if(ddMatch){
								fvalue = d.dateFormat(format);
								ddMatchResult = ddMatch.test(fvalue);
							}
	//don't use >= and <= here for datecomparison, because the dates can differ in timezone
							if(	!(startdate < min) &&
								!(startdate > max) &&
								ddays.indexOf(d.getDay()) == -1 &&
								!ddMatchResult &&
								( !allowedDatesT || allowedDatesT.indexOf(startdate)!=-1 )
							   ) {
	//is not disabled and can be processed
	
								if (this.maxSelectionDays === this.preSelectedDates.length) {
									if(this.fireEvent("beforemaxdays", this) !== false){								
										Ext.Msg.alert(this.maxSelectionDaysTitle,this.maxSelectionDaysText.replace(/%0/,this.maxSelectionDays));
									}
									break;
								}
								this.preSelectedDates.push(startdate);
	
							}
							startdate = new Date(startdate).add(Date.DAY,dayDiff).clearTime().getTime();
						}
					
						tmpMonthCell = (dayDiff>0 ? 0 : this.cellsArray.length-1);
						tmpDayCell = (dayDiff>0 ? 0 : 41);
	
	//mark left ghostdates aswell
						testCell = Ext.get(this.cellsArray[tmpMonthCell].elements[tmpDayCell]);
						while (testCell.hasClass("x-date-prevday") || testCell.hasClass("x-date-nextday")) {
							testCell.addClass("x-date-selected");
							this.preSelectedCells.push((tmpMonthCell)+"#"+(tmpDayCell));
							tmpDayCell+=dayDiff;
							testCell = Ext.get(this.cellsArray[tmpMonthCell].elements[tmpDayCell]);
						}
					}
					
	//mark range of visible dates
					while ((targetdate-startdate)*dayDiff >0 && tmpMonthCell>=0 && tmpMonthCell<this.cellsArray.length) {									
						this.markDateAsSelected(startdate,ctrlfaker,tmpMonthCell,tmpDayCell,true);
	
	//take care of summertime changing (would return different milliseconds)
						startdate = new Date(startdate).add(Date.DAY,dayDiff).clearTime().getTime();
										
						testCell = Ext.get(this.cellsArray[tmpMonthCell].elements[tmpDayCell]);
	
						if (testCell.hasClass("x-date-active")) {
							ghostCounter=0;						
						}
						else {
							ghostCounter++;
						}
						tmpDayCell+=dayDiff;
						if (tmpDayCell==42) {
							tmpMonthCell++;
							tmpDayCell=(ghostCounter>=7?14:7);
						}
						else if (tmpDayCell<0) {
							tmpMonthCell--;
							tmpDayCell=34;
							
							testCell = Ext.get(this.cellsArray[tmpMonthCell].elements[tmpDayCell]);
							if (testCell.hasClass("x-date-nextday") || ghostCounter==7) {
								tmpDayCell=27;
							}
						}
					}
	
				}
				
	
				//radu: diff selectionTypes
				if (this.selectionType!='week'&&this.selectionType!='month') 
				{
					this.markDateAsSelected(t.dateValue,ctrlfaker,t.monthCell,t.dayCell,true);
				}
				else if(this.selectionType=='week')
				{
					amount=7;
					var monthcell = t.monthCell;
					var daycell = t.dayCell;
					var reverseAdd =  false;
					var dateAdder = 1;
					var startWeekCell=Math.floor(daycell/7)*7;
					
					this.removeAllPreselectedClasses();
					
					for (var i=0;i<amount;i++) 
					{
						this.markDateAsSelected(startweekdate.clearTime().getTime(),true,monthcell,startWeekCell+i,0);
						
						startweekdate = startweekdate.add(Date.DAY,dateAdder);
					}
				}
				else if(this.selectionType=='month')
				{
					amount=42;
					var monthcell = t.monthCell;
					var daycell = t.dayCell;
					var reverseAdd =  false;
					var dateAdder = 1;
									
					this.removeAllPreselectedClasses();		
									
					for (var i=0;i<amount;i++) 
					{
						testCell = Ext.get(this.cellsArray[monthcell].elements[i]);
						
						if (testCell.hasClass("x-date-active"))
						{					
							this.markDateAsSelected(startmonthdate.clearTime().getTime(),true,monthcell,i,0);					
							
							startmonthdate = startmonthdate.add(Date.DAY,dateAdder);
						}					
					}
				}
					
				this.finishDateSelection(new Date(t.dateValue));
			}
		}
    },
	
	copyPreToSelectedDays : function() {
		this.selectedDates = [];
		for (var i=0,il=this.preSelectedDates.length;i<il;++i) {
			this.selectedDates.push(new Date(this.preSelectedDates[i]));
		}
	},
	okClicked : function() {
		this.copyPreToSelectedDays();
		this.selectedDates = this.selectedDates.sortDates();
		this.fireEvent("select", this, this.selectedDates);
	},

	spaceKeyPressed: function(e) {
		var ctrlfaker = (((!Ext.EventObject.ctrlKey && !this.multiSelectByCTRL) || Ext.EventObject.shiftKey) && this.multiSelection ? true:false);
		if (!this.disabled && this.shiftSpaceSelect == Ext.EventObject.shiftKey && this.showActiveDate) {
			var adCell = this.activeDateCell.split("#");
			var tmpMonthCell = parseInt(adCell[0],10);
			var tmpDayCell = parseInt(adCell[1],10);
			this.markDateAsSelected(this.activeDate.getTime(),ctrlfaker,tmpMonthCell,tmpDayCell,true);
			this.finishDateSelection(this.activeDate);
		}
		else {
			this.selectToday();
		}
	},

	finishDateSelection: function(date) {
        this.setValue(date);		
		if (this.multiSelection) {
			this.fireEvent("afterdateclick", this, date,this.lastStateWasSelected);
		}
		else {
			this.fireEvent("afterdateclick", this, date,this.lastStateWasSelected);				
	        this.fireEvent("select", this, this.value);
		}
	},

    selectToday : function(){
        if(!this.disabled && this.todayBtn && !this.todayBtn.disabled){
			var today = new Date().clearTime();
			var todayT = today.getTime();
		//today already visible?
			if (typeof this.todayMonthCell === "number") {
				this.markDateAsSelected(todayT,false,this.todayMonthCell,this.todayDayCell,true);
			}
			else if (this.multiSelection){
				this.update(today);
			}
			this.finishDateSelection(today);
        }		
    },
	
    setValue : function(value){    	
		if (Ext.isArray(value)) {			
			this.selectedDates = [];
			this.preSelectedDates = [];			
			this.setSelectedDates(value,true);
			value = value[0];
		}
        this.value = value.clearTime(true);

        if(this.el && !this.multiSelection && this.noOfMonth==1){
            this.update(this.value);
        }
		
    },
	
/* this is needed to get it displayed in a panel correctly, it is called several times...*/	
	setSize: Ext.emptyFn
	
});
Ext.reg('datepickerplus', Ext.ux.DatePickerPlus);  


/*
To use DatepickerPlus in menus and datefields, DateItem and datefield needs to be rewritten. This way Ext.DateMenu stays original and by supplying new config item usePickerPlus:true will use the datepickerplus insted of the original picker. 
*/

	
if (parseInt(Ext.version.substr(0,1),10)>2) {
//ext 3.0		
	Ext.menu.DateItem = Ext.ux.DatePickerPlus;
	Ext.override(Ext.menu.DateMenu,{
		initComponent: function(){
			this.on('beforeshow', this.onBeforeShow, this);
			if(this.strict = (Ext.isIE7 && Ext.isStrict)){
				this.on('show', this.onShow, this, {single: true, delay: 20});
			}
			var PickerWidget = (this.initialConfig.usePickerPlus ? Ext.ux.DatePickerPlus : Ext.DatePicker);
			
			Ext.apply(this, {
				plain: true,
				showSeparator: false,
				items: this.picker = new PickerWidget(Ext.apply({
					internalRender: this.strict || !Ext.isIE,
					ctCls: 'x-menu-date-item'
				}, this.initialConfig))
			});
			Ext.menu.DateMenu.superclass.initComponent.call(this);
			this.relayEvents(this.picker, ["select"]);
			this.on('select', this.menuHide, this);
			if(this.handler){
				this.on('select', this.handler, this.scope || this);
			}
		}
	});
	
}
else {
//ext 2.x
	Ext.menu.DateItem = function(config){
		if (config && config.usePickerPlus) {
			Ext.menu.DateItem.superclass.constructor.call(this, new Ext.ux.DatePickerPlus(config), config);	//NEW LINE			
		}
		else {
			Ext.menu.DateItem.superclass.constructor.call(this, new Ext.DatePicker(config), config);
		}
		this.picker = this.component;
		this.addEvents('select');
		
		this.picker.on("render", function(picker){
			picker.getEl().swallowEvent("click");
			picker.container.addClass("x-menu-date-item");
		});

		this.picker.on("select", this.onSelect, this);
	};
//this breaks in ext 3.0 (Ext.menu.Adapter and Ext.menu.DateItem do not exist in ext 3.0 anymore)
	Ext.extend(Ext.menu.DateItem, Ext.menu.Adapter,{
		// private
		onSelect : function(picker, date){
			this.fireEvent("select", this, date, picker);
			Ext.menu.DateItem.superclass.handleClick.call(this);
		}
	});
}


if (Ext.form && Ext.form.DateField) {
	Ext.ux.form.DateFieldPlus = Ext.extend(Ext.form.DateField, {
		usePickerPlus: true,
		showWeekNumber: true,
		noOfMonth : 1,
		noOfMonthPerRow : 3,
		nationalHolidaysCls: 'x-datepickerplus-nationalholidays',
		markNationalHolidays:true,
		eventDates: function(year) {
			return [];
		},
		eventDatesRE : false,
		eventDatesRECls : '',
		eventDatesREText : '',
		multiSelection: false,
		multiSelectionDelimiter: ',',			
		multiSelectByCTRL: true,	
		fillupRows: true,
		markWeekends:true,
		weekendText:'',
		weekendCls: 'x-datepickerplus-weekends',
		weekendDays: [6,0],
		useQuickTips: true,
		pageKeyWarp: 1,
		maxSelectionDays: false,
		resizable: false,
		renderTodayButton: true,
		renderOkUndoButtons: true,
		tooltipType: 'qtip',
		allowedDates : false,
		allowedDatesText : '',
		renderPrevNextButtons: true,
		renderPrevNextYearButtons: false,
		disableMonthPicker:false,
		showActiveDate: false,
		shiftSpaceSelect: true,
		disabledLetter: false,
		allowMouseWheel:  true,
		summarizeHeader: false,
		stayInAllowedRange: true,
		disableSingleDateSelection: false,
		eventDatesSelectable: false,
		styleDisabledDates: false,
		prevNextDaysView: "mark",
		selectionType:'day',

		allowOtherMenus: false,

		onBeforeYearChange : function(picker, oldStartYear, newStartYear){
			this.fireEvent("beforeyearchange", this, oldStartYear, newStartYear, picker);
		},
		
		onAfterYearChange : function(picker, oldStartYear, newStartYear){
			this.fireEvent("afteryearchange", this, oldStartYear, newStartYear, picker);
		},
		
		onBeforeMonthChange : function(picker, oldStartMonth, newStartMonth){
			this.fireEvent("beforemonthchange", this, oldStartMonth, newStartMonth, picker);
		},
		
		onAfterMonthChange : function(picker, oldStartMonth, newStartMonth){
			this.fireEvent("aftermonthchange", this, oldStartMonth, newStartMonth, picker);
		},
		
		onAfterMonthClick : function(picker, month, wasSelected){
			this.fireEvent("aftermonthclick", this, month, wasSelected, picker);
		},
		
		onAfterWeekClick : function(picker, startOfWeek, wasSelected){
			this.fireEvent("afterweekclick", this, startOfWeek, wasSelected, picker);
		},

		onAfterDateClick : function(picker, date, wasSelected){
			this.fireEvent("afterdateclick", this, date, wasSelected, picker);
		},
		
		onBeforeMonthClick : function(picker, month, wasSelected){
			this.fireEvent("beforemonthclick", this, month, wasSelected, picker);
		},
		
		onBeforeWeekClick : function(picker, startOfWeek, wasSelected){
			this.fireEvent("beforeweekclick", this, startOfWeek, wasSelected, picker);
		},

		onBeforeDateClick : function(picker, date){
			this.fireEvent("beforedateclick", this, date);
		},

		onBeforeMouseWheel : function(picker, event){
			this.fireEvent("beforemousewheel", this, event, picker);
		},
		
		onBeforeMaxDays : function(picker){
			this.fireEvent("beforemaxdays", this, picker);
		},
		
		onUndo : function(picker, preSelectedDates){
			this.fireEvent("undo", this, preSelectedDates, picker);
		},

		onTriggerClick : function(){
			if(this.disabled){
				return;
			}
			if(!this.menu){
				this.menu = new Ext.menu.DateMenu({
					allowOtherMenus: this.allowOtherMenus,
//is needed at initialisation		
					usePickerPlus:this.usePickerPlus,
					noOfMonth:this.noOfMonth,
					noOfMonthPerRow:this.noOfMonthPerRow,
					listeners: {
						'beforeyearchange': {fn:this.onBeforeYearChange,scope:this},
						'afteryearchange': {fn:this.onAfterYearChange,scope:this},
						'beforemonthchange': {fn:this.onBeforeMonthChange,scope:this},
						'aftermonthchange': {fn:this.onAfterMonthChange,scope:this},
						'afterdateclick': {fn:this.onAfterDateClick,scope:this},
						'aftermonthclick': {fn:this.onAfterMonthClick,scope:this},
						'afterweekclick': {fn:this.onAfterWeekClick,scope:this},
						'beforedateclick': {fn:this.onBeforeDateClick,scope:this},
						'beforemonthclick': {fn:this.onBeforeMonthClick,scope:this},
						'beforeweekclick': {fn:this.onBeforeWeekClick,scope:this},
						'beforemousewheel': {fn:this.onBeforeMouseWheel,scope:this},
						'beforemaxdays': {fn:this.onBeforeMaxDays,scope:this},
						'undo': {fn:this.onUndo,scope:this}
					}
				});
//do this only once!					
				this.relayEvents(this.menu, ["select"]);
			}

			if (this.menu.isVisible()) {
				this.menu.hide();
				return;
			}
			if (this.disabledDatesRE) {
				this.ddMatch = this.disabledDatesRE;
			}
			if(typeof this.minDate == "string"){
				this.minDate = this.parseDate(this.minDate);
			}
			if(typeof this.maxDate == "string"){
				this.maxDate = this.parseDate(this.maxDate);
			}			
			Ext.apply(this.menu.picker,  {
				minDate : this.minValue || this.minDate,
				maxDate : this.maxValue || this.maxDate,
				disabledDatesRE : this.ddMatch,
				disabledDatesText : this.disabledDatesText,
				disabledDays : this.disabledDays,
				disabledDaysText : this.disabledDaysText,
				showToday : this.showToday,	//from Ext 2.2
				format : this.format,
				minText : String.format(this.minText, this.formatDate(this.minValue || this.minDate)),
				maxText : String.format(this.maxText, this.formatDate(this.maxValue || this.maxDate)),
				showWeekNumber: this.showWeekNumber,
				nationalHolidaysCls: this.nationalHolidaysCls,
				markNationalHolidays:this.markNationalHolidays,
				multiSelectByCTRL: this.multiSelectByCTRL,	
				fillupRows: this.fillupRows,
				multiSelection: this.multiSelection,
				markWeekends:this.markWeekends,
				weekendText:this.weekendText,
				weekendCls: this.weekendCls,
				weekendDays: this.weekendDays,
				useQuickTips: this.useQuickTips,
				eventDates: this.eventDates,
				eventDatesRE: this.eventDatesRE,
				eventDatesRECls: this.eventDatesRECls,
				eventDatesREText: this.eventDatesREText,
				pageKeyWarp: this.pageKeyWarp,
				maxSelectionDays: this.maxSelectionDays,
				resizable: this.resizable,
				renderTodayButton: this.renderTodayButton,
				renderOkUndoButtons: this.renderOkUndoButtons,
				allowedDates : this.allowedDates,
				allowedDatesText : this.allowedDatesText,
				renderPrevNextButtons: this.renderPrevNextButtons,
				renderPrevNextYearButtons: this.renderPrevNextYearButtons,
				disableMonthPicker:this.disableMonthPicker,
				showActiveDate: this.showActiveDate,
				shiftSpaceSelect: this.shiftSpaceSelect,
				disabledLetter: this.disabledLetter,
				allowMouseWheel: this.allowMouseWheel,
				summarizeHeader: this.summarizeHeader,
				stayInAllowedRange: this.stayInAllowedRange,
				disableSingleDateSelection: this.disableSingleDateSelection,
				eventDatesSelectable: this.eventDatesSelectable,
				styleDisabledDates: this.styleDisabledDates,
				prevNextDaysView : this.prevNextDaysView,
				//radu
				startDay: this.startDay,
				selectionType: this.selectionType
			});
//Ext 3.0
			if (this.menuEvents) {
				this.menuEvents('on');
			}
			else {
//ext 2.2.x				
				this.menu.on(Ext.apply({}, this.menuListeners, {
					scope:this
				}));
			}
			if( typeof this.defaultValue == 'string' ) {
				this.defaultValue = Date.parseDate( this.defaultValue, this.format );
			}
			
			this.menu.picker.setValue(this.getValue() || this.defaultValue || new Date());
			this.menu.show(this.el, "tl-bl?");
			this.menu.focus();
		},
		
		setValue : function(date){			
			var field = this; 
			/**
			 * if date is string and comma separated (not array) make it array by split
			 * fix for the selected dates not retained in date field.
			 */
			if(!Ext.isArray(date) && date.toString().match(/,/)){				
				date = date.toString().split(",");
			}
			/****************************************************************************/
			if (Ext.isArray(date)) {
				var formatted = [];
				for (var e=0,el=date.length;e<el;++e) {
					formatted.push(field.formatDate(date[e]));
				}
			
				var value = formatted.join(this.multiSelectionDelimiter);
			
//bypass setValue validation on Ext.DateField
				Ext.form.DateField.superclass.setValue.call(this, value);
			}
			else {				
				Ext.form.DateField.superclass.setValue.call(this, this.formatDate(this.parseDate(date)));				   
			}
		},

		validateValue : function(value){
			if (this.multiSelection){
				var field = this;
				var values = value.split(this.multiSelectionDelimiter);
				var isValid = true;
				for (var e=0,el=values.length;e<el;++e) {											  
					if (!Ext.ux.form.DateFieldPlus.superclass.validateValue.call(field, values[e])) {
						isValid = false;
					}
				}
				return isValid;
			}
			else {
				return Ext.ux.form.DateFieldPlus.superclass.validateValue.call(this, value);
			}         
		},

		getValue : function() {
			if (this.multiSelection) {
				var value = Ext.form.DateField.superclass.getValue.call(this);
				var field = this;					
				var values = value.split(this.multiSelectionDelimiter);
				var dates = [];
				for (var e=0,el=values.length;e<el;++e) {											  
					var checkDate = field.parseDate(values[e]);
					if (checkDate) {
						dates.push(checkDate);
					}
				}
				return (dates.length>0?dates:"");
			}
			else {
				return Ext.ux.form.DateFieldPlus.superclass.getValue.call(this);
			}
		},			


		beforeBlur : function(){
			if (this.multiSelection) {
				this.setValue(this.getRawValue().split(this.multiSelectionDelimiter));
			}
			else {
				var v = this.parseDate(this.getRawValue());
				if(v){
					this.setValue(v);
				}
			}
		},


		//radu
		submitFormat:'d/m/Y',
		submitFormatAddon: '-format',			
		onRender:function() {
	
			Ext.ux.form.DateFieldPlus.superclass.onRender.apply(this, arguments);
//be sure not to have duplicate formfield names (at least IE moans about it and gets confused)				
//				this.name =  (typeof this.name==="undefined"?this.id+this.submitFormatAddon:(this.name==this.id?this.name+this.submitFormatAddon:this.name));		
			var name =  this.name || this.el.dom.name || (this.id+this.submitFormatAddon);
			if (name==this.id) {
				name+= this.submitFormatAddon;
			}
			this.hiddenField = this.el.insertSibling({
				tag:'input',
				type:'hidden',
				name: name,
				value:this.formatHiddenDate(this.parseDate(this.value))
			});
			this.hiddenName = name;
			this.el.dom.removeAttribute('name');
			this.el.on({
				keyup:{scope:this, fn:this.updateHidden},
				blur:{scope:this, fn:this.updateHidden}
			});
	
			this.setValue = this.setValue.createSequence(this.updateHidden);
			
			if(this.tooltip){
				if(typeof this.tooltip == 'object'){
					Ext.QuickTips.register(Ext.apply({
						  target: this.trigger
					}, this.tooltip));
				} else {
					this.trigger.dom[this.tooltipType] = this.tooltip;
				}
			}

			function removeDuplicatedParams(url) {
				var parts = /([^?]*\?)(.*)/.exec(url);
				var params = Ext.urlDecode(parts[2], true);
				return parts[1] + Ext.urlEncode(params);
			}
			
			//radu
			this.on('select',function(a,b){
					
				//radu
				var hidden_value=this.hiddenField.dom.value;
				
				if(hidden_value&&hidden_value!='-'&&this.url)
				{
					//radu: redirect to some specific url suffix based on hidden value
					var target = Ext.urlAppend(document.location.href,
						this.url + hidden_value);
					document.location.href = removeDuplicatedParams(target);
				}
				
			},this);	
		},
		onDisable: function(){
			Ext.ux.form.DateFieldPlus.superclass.onDisable.apply(this, arguments);
			if(this.hiddenField) {
				this.hiddenField.dom.setAttribute('disabled','disabled');
			}
		},
		
		onEnable: function(){
			Ext.ux.form.DateFieldPlus.superclass.onEnable.apply(this, arguments);
			if(this.hiddenField) {
				this.hiddenField.dom.removeAttribute('disabled');
			}
		},
		
		formatHiddenDate : function(date){
			return Ext.isDate(date) ? Ext.util.Format.date(date, this.submitFormat) : date;
		},
		
		formatMultiHiddenDate : function(date) {
			var field = this, formatted = [],value;
			for (var e=0,el=date.length;e<el;++e) {
				formatted.push(field.formatHiddenDate(date[e]));
			}
			
			//radu
			if(this.selectionType=='day')
			{
				var value = formatted.join(this.multiSelectionDelimiter);
			}
			else
			{
				var value = formatted[0]+'-'+formatted[formatted.length-1];
			}
						
			this.hiddenField.dom.value = value;
		},
		
		updateHidden:function(date) {
			if (Ext.isArray(date)) {
				this.formatMultiHiddenDate(date);
			}
			else {
				var value = this.getValue();
				if (Ext.isArray(value)) {
					this.formatMultiHiddenDate(value);
				} else {
					this.hiddenField.dom.value = this.formatHiddenDate(value);
				}
			}
		}

	});
	Ext.reg('datefieldplus', Ext.ux.form.DateFieldPlus);
}

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/datepickerplus/Ext.ux.datepickerplus.js  ]*/

/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 *
 * http://extjs.com/license
 */

/**
 * @class Ext.YearPicker
 * @extends Ext.Component
 * Simple date picker class.
 * @constructor
 * Create a new YearPicker
 * @param {Object} config The config object
 */
Ext.YearPicker = Ext.extend(Ext.Component, {
    /**
     * @cfg {String} okText
     * The text to display on the ok button
     */
    okText : " OK ", //   to give the user extra clicking room
    /**
     * @cfg {String} todayTip
     * The tooltip to display for the button that selects the current date (defaults to "{current date} (Spacebar)")
     */
    todayTip : "{0} (Spacebar)",
    /**
     * @cfg {String} minText
     * The error text to display if the minDate validation fails (defaults to "This date is before the minimum date")
     */
    minText : "This date is before the minimum date",
    /**
     * @cfg {String} maxText
     * The error text to display if the maxDate validation fails (defaults to "This date is after the maximum date")
     */
    maxText : "This date is after the maximum date",
    /**
     * @cfg {String} format
     * The default date format string which can be overriden for localization support.  The format must be
     * valid according to {@link Date#parseDate} (defaults to 'm/d/y').
     */
    format : "m/01/y",
    /**
     * @cfg {String} disabledDaysText
     * The tooltip to display when the date falls on a disabled day (defaults to "Disabled")
     */
    disabledDaysText : "Disabled",
    /**
     * @cfg {String} disabledDatesText
     * The tooltip text to display when the date falls on a disabled date (defaults to "Disabled")
     */
    disabledDatesText : "Disabled",
    /**
     * @cfg {Boolean} constrainToViewport
     * <b>Deprecated</b> (not currently used). True to constrain the date picker to the viewport (defaults to true)
     */
    constrainToViewport : true,
    /**
     * @cfg {Array} monthNames
     * An array of textual month names which can be overriden for localization support (defaults to Date.monthNames)
     */
    monthNames : Date.monthNames,
    /**
     * @cfg {Array} dayNames
     * An array of textual day names which can be overriden for localization support (defaults to Date.dayNames)
     */
    dayNames : Date.dayNames,
    /**
     * @cfg {String} nextText
     * The next month navigation button tooltip (defaults to 'Next Month (Control+Right)')
     */
    nextText: 'Next Month (Control+Right)',
    /**
     * @cfg {String} prevText
     * The previous month navigation button tooltip (defaults to 'Previous Month (Control+Left)')
     */
    prevText: 'Previous Month (Control+Left)',
    /**
     * @cfg {String} monthYearText
     * The header month selector tooltip (defaults to 'Choose a month (Control+Up/Down to move years)')
     */
    monthYearText: 'Choose a month (Control+Up/Down to move years)',
    /**
     * @cfg {Date} minDate
     * Minimum allowable date (JavaScript date object, defaults to null)
     */
    /**
     * @cfg {Date} maxDate
     * Maximum allowable date (JavaScript date object, defaults to null)
     */
    /* * Not implemented yet
     * @cfg {Array} disabledDays
     * An array of days to disable, 0-based. For example, [0, 6] disables Sunday and Saturday (defaults to null).
     */
    /* * Not implemented yet
     * @cfg {RegExp} disabledDatesRE
     * JavaScript regular expression used to disable a pattern of dates (defaults to null).  The {@link #disabledDates}
     * config will generate this regex internally, but if you specify disabledDatesRE it will take precedence over the
     * disabledDates value.
     */
    /* * Not implemented yet
     * @cfg {Array} disabledDates
     * An array of "dates" to disable, as strings. These strings will be used to build a dynamic regular
     * expression so they are very powerful. Some examples:
     * <ul>
     * <li>["03/08/2003", "09/16/2003"] would disable those exact dates</li>
     * <li>["03/08", "09/16"] would disable those days for every year</li>
     * <li>["^03/08"] would only match the beginning (useful if you are using short years)</li>
     * <li>["03/../2006"] would disable every day in March 2006</li>
     * <li>["^03"] would disable every day in every March</li>
     * </ul>
     * Note that the format of the dates included in the array should exactly match the {@link #format} config.
     * In order to support regular expressions, if you are using a date format that has "." in it, you will have to
     * escape the dot when restricting dates. For example: ["03\\.08\\.03"].
     */

    // private
    initComponent : function(){
        Ext.YearPicker.superclass.initComponent.call(this);

        this.value = this.value ?
                 this.value.clearTime() : new Date().clearTime();

        this.addEvents(
            /**
             * @event select
             * Fires when a date is selected
             * @param {YearPicker} this
             * @param {Date} date The selected date
             */
            'select'
        );

        if(this.handler){
            this.on("select", this.handler,  this.scope || this);
        }

        //this.initDisabledDays();
    },

    // private
    /*initDisabledDays : function(){
        if(!this.disabledDatesRE && this.disabledDates){
            var dd = this.disabledDates;
            var re = "(?:";
            for(var i = 0; i < dd.length; i++){
                re += dd[i];
                if(i != dd.length-1) re += "|";
            }
            this.disabledDatesRE = new RegExp(re + ")");
        }
    },*/

    /**
     * Replaces any existing disabled dates with new values and refreshes the YearPicker.
     * @param {Array/RegExp} disabledDates An array of date strings (see the {@link #disabledDates} config
     * for details on supported values), or a JavaScript regular expression used to disable a pattern of dates.
     */
    /*setDisabledDates : function(dd){
        if(Ext.isArray(dd)){
            this.disabledDates = dd;
            this.disabledDatesRE = null;
        }else{
            this.disabledDatesRE = dd;
        }
        this.initDisabledDays();
        this.update(this.value, true);
    },*/

    /**
     * Replaces any existing disabled days (by index, 0-6) with new values and refreshes the YearPicker.
     * @param {Array} disabledDays An array of disabled day indexes. See the {@link #disabledDays} config
     * for details on supported values.
     */
    /*setDisabledDays : function(dd){
        this.disabledDays = dd;
        this.update(this.value, true);
    },*/

    /**
     * Replaces any existing {@link #minDate} with the new value and refreshes the YearPicker.
     * @param {Date} value The minimum date that can be selected
     */
    setMinDate : function(dt){
        this.minDate = dt;
        this.update(this.value, true);
    },

    /**
     * Replaces any existing {@link #maxDate} with the new value and refreshes the YearPicker.
     * @param {Date} value The maximum date that can be selected
     */
    setMaxDate : function(dt){
        this.maxDate = dt;
        this.update(this.value, true);
    },

    /**
     * Sets the value of the date field
     * @param {Date} value The date to set
     */
    setValue : function(value){
        var old = this.value;
        this.value = value.clearTime(true);
        if(this.el){
            this.update(this.value);
        }
    },

    /**
     * Gets the current selected value of the date field
     * @return {Date} The selected date
     */
    getValue : function(){
        return this.value;
    },

    // private
    focus : function(){
        if(this.el){
            this.update(this.activeDate);
        }
    },

    // private
    onRender : function(container, position){
        var m = ['<table class="x-month-mp" border="0" cellspacing="0">'];
        for(var i = 0; i < 6; i++){
            m.push(
                /*'<tr class="x-month-mp-month"><td class="x-date-mp-month"><a href="#">', this.monthNames[i].substr(0, 3), '</a></td>',
                '<td class="x-date-mp-month x-date-mp-sep"><a href="#">', this.monthNames[i+6].substr(0, 3), '</a></td>',*/
                i == 0 ?
                '<td class="x-date-mp-ybtn" align="center"><a class="x-date-mp-prev"></a></td><td class="x-date-mp-ybtn" align="center"><a class="x-date-mp-next"></a></td></tr>' :
                '<td class="x-date-mp-year"><a href="#"></a></td><td class="x-date-mp-year"><a href="#"></a></td></tr>'
            );
        }
        m.push(
            '<tr><td class="x-date-bottom" colspan="4" style="text-align:center;"></td></tr></table>'
        );

        var el = document.createElement("div");
        el.className = "x-date-picker";
        el.innerHTML = m.join("");

        container.dom.insertBefore(el, position);
        this.el = Ext.get(el);

        this.yearPicker = this.el.down('table.x-month-mp');
        this.yearPicker.enableDisplayMode('block');
        this.mpMonths = this.yearPicker.select('td.x-date-mp-month');
        this.mpYears = this.yearPicker.select('td.x-date-mp-year');

        this.mpMonths.each(function(m, a, i){
            i += 1;
            if((i%2) == 0){
                m.dom.xmonth = 5 + Math.round(i * .5);
            }else{
                m.dom.xmonth = Math.round((i-1) * .5);
            }
        });


        if(Ext.isIE){
            this.el.repaint();
        }

        this.yearPicker.on('click', this.onMonthClick, this);
        this.yearPicker.on('dblclick', this.onMonthDblClick, this);


        this.okBtn = new Ext.Button({
            renderTo: this.el.child("td.x-date-bottom", true),
            text: this.okText,
            handler: this.selectOk,
            scope: this
        });


        this.mpSelMonth = (this.activeDate || this.value).getMonth();
        this.mpSelYear = (this.activeDate || this.value).getFullYear();
        this.updateMPMonth();
        this.updateMPYear();
        this.update(this.value);
        
        this.on('select',function(a,b){
					
			//radu
			var value=this.getValue();
			value=this.myFormatDate(value);	
					
			if(value&&value!='-'&&this.url)
			{
				//radu: redirect to some specific url based on value
				document.location.href=this.url+value;					
			}
			
		},this);
        
    },

    // private
    updateMPYear : function(){
        this.mpyear = this.mpSelYear;
        var ys = this.mpYears.elements;
        for(var i = 1; i <= 10; i++){
            var td = ys[i-1], y2;
            if((i%2) == 0){
                y2 = this.mpSelYear + Math.round(i * .5);
                td.firstChild.innerHTML = y2;
                td.xyear = y2;
            }else{
                y2 = this.mpSelYear - (5-Math.round(i * .5));
                td.firstChild.innerHTML = y2;
                td.xyear = y2;
            }
            this.mpYears.item(i-1)[y2 == this.mpSelYear ? 'addClass' : 'removeClass']('x-date-mp-sel');
            if ((this.maxDate&&(this.maxDate.getFullYear() < y2)) ||  (this.minDate&&(this.minDate.getFullYear() > y2))) {
                Ext.get(ys[i-1].firstChild).addClass('x-date-mp-disabled');
            } else {
                Ext.get(ys[i-1].firstChild).removeClass('x-date-mp-disabled');
            }
        }
    },

    // private
    updateMPMonth : function(){
        var sm = this.mpSelMonth;
        var sy = this.mpSelYear;
        var maxDate = this.maxDate;
        var minDate = this.minDate;
        this.mpMonths.each(function(m, a, i){
            m[m.dom.xmonth == sm ? 'addClass' : 'removeClass']('x-date-mp-sel');
            if (
                (
                    maxDate &&
                    (maxDate.getFullYear() < sy)
                ) ||
                (
                    maxDate &&
                    (maxDate.getFullYear() == sy) &&
                    (maxDate.getMonth() < m.dom.xmonth)
                ) ||
                (
                    minDate &&
                    (minDate.getFullYear() > sy)
                ) ||
                (
                    minDate &&
                    (minDate.getFullYear() == sy) &&
                    (minDate.getMonth() > m.dom.xmonth)
                )
            ) {
                Ext.get(m.dom.firstChild).addClass('x-date-mp-disabled');
            } else {
                Ext.get(m.dom.firstChild).removeClass('x-date-mp-disabled');
            }
        });
        if (
            (
                maxDate &&
                (maxDate.getFullYear() < sy)
            ) ||
            (
                maxDate &&
                (maxDate.getFullYear() == sy) &&
                (maxDate.getMonth() < sm)
            ) ||
            (
                minDate &&
                (minDate.getFullYear() > sy)
            ) ||
            (
                minDate &&
                (minDate.getFullYear() == sy) &&
                (minDate.getMonth() > sm)
            )
        ) {
            this.okBtn.disable();
        }else {
            this.okBtn.enable();
        }
    },


    // private
    onMonthClick : function(e, t){
        if(Ext.fly(t).hasClass('x-date-mp-disabled')){
            return;
        }
        e.stopEvent();
        var el = new Ext.Element(t), pn;
        if(pn = el.up('td.x-date-mp-month', 2)){
            this.mpMonths.removeClass('x-date-mp-sel');
            pn.addClass('x-date-mp-sel');
            this.mpSelMonth = pn.dom.xmonth;
            this.updateMPMonth();
        }
        else if(pn = el.up('td.x-date-mp-year', 2)){
            this.mpYears.removeClass('x-date-mp-sel');
            pn.addClass('x-date-mp-sel');
            this.mpSelYear = pn.dom.xyear;
            this.updateMPMonth();
        }
        else if(el.is('a.x-date-mp-prev')){
            this.mpSelYear = this.mpyear-10;
            this.mpYears.removeClass('x-date-mp-sel');
            this.updateMPYear();
            this.updateMPMonth();
        }
        else if(el.is('a.x-date-mp-next')){
            this.mpSelYear = this.mpyear+10;
            this.mpYears.removeClass('x-date-mp-sel');
            this.updateMPYear();
            this.updateMPMonth();
        }
    },

    // private
    onMonthDblClick : function(e, t){
        if(Ext.fly(t).hasClass('x-date-mp-disabled')){
            return;
        }
        e.stopEvent();
        var el = new Ext.Element(t), pn;
        if(pn = el.up('td.x-date-mp-month', 2)){
            this.mpSelMonth = pn.dom.xmonth;
            this.selectOk();
        }
        else if(pn = el.up('td.x-date-mp-year', 2)){
            this.mpSelYear = pn.dom.xyear;
            this.selectOk();
        }
    },

    // private
    selectOk : function(){    	
        var d = new Date(this.mpSelYear, this.mpSelMonth, 1);
        if(d.getMonth() != this.mpSelMonth){
            // "fix" the JS rolling date conversion if needed
            d = new Date(this.mpSelYear, this.mpSelMonth, 1).getLastDateOfMonth();
        }
        this.update(d);
        this.setValue(d);       
        this.fireEvent("select", this, this.value);
    },

    // private
    update : function(date){
        this.activeDate = date;

        if(!this.internalRender){
            var main = this.el.dom.firstChild;
            var w = main.offsetWidth;
            this.el.setWidth(w + this.el.getBorderWidth("lr"));
            Ext.fly(main).setWidth(w);
            this.internalRender = true;
            // opera does not respect the auto grow header center column
            // then, after it gets a width opera refuses to recalculate
            // without a second pass
            if(Ext.isOpera && !this.secondPass){
                main.rows[0].cells[1].style.width = (w - (main.rows[0].cells[0].offsetWidth+main.rows[0].cells[2].offsetWidth)) + "px";
                this.secondPass = true;
                this.update.defer(10, this, [date]);
            }
        }
    },

    // private
    beforeDestroy : function() {
        if(this.rendered){
            Ext.destroy(this.mbtn, this.okBtn);
        }
    },
    
    myFormatDate : function(date){
		return Ext.isDate(date) ? Ext.util.Format.date(date, 'Y') : date;
	}

    /**
     * @cfg {String} autoEl @hide
     */
});
Ext.reg('yearpicker', Ext.YearPicker);

/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 *
 * http://extjs.com/license
 */

/**
 * @class Ext.menu.YearMenu
 * @extends Ext.menu.Menu
 * A menu containing a {@link Ext.menu.DateItem} component (which provides a date picker).
 * @constructor
 * Creates a new YearMenu
 * @param {Object} config Configuration options
 */
Ext.menu.YearMenu = function(config){
    Ext.menu.YearMenu.superclass.constructor.call(this, config);
    this.plain = true;
    var di = new Ext.menu.YearItem(config);
    this.add(di.picker);
    /**
     * The {@link Ext.YearPicker} instance for this YearMenu
     * @type YearPicker
     */
    this.picker = di.picker;   
    /**
     * @event select
     * @param {MonthPicker} picker
     * @param {Date} date
     */
    this.relayEvents(di, ["select"]);

};
Ext.extend(Ext.menu.YearMenu, Ext.menu.Menu, {
    cls:'x-date-menu',

    // private
    beforeDestroy : function() {
        this.picker.destroy();
    }
});

/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 *
 * http://extjs.com/license
 */

/**
 * @class Ext.menu.YearItem
 * @extends Ext.menu.Adapter
 * A menu item that wraps the {@link Ext.YearPicker} component.
 * @constructor
 * Creates a new YearItem
 * @param {Object} config Configuration options
 */
Ext.menu.YearItem = function(config){
	/**
	 * Changes are made in this section for ext 3 support
	 */
    Ext.menu.YearItem.superclass.constructor.call(this,config);
    /** The Ext.YearPicker object @type Ext.YearPicker */
    this.picker = new Ext.YearPicker(config);    
    this.addEvents('select');
    this.picker.on("render", function(picker){
        picker.getEl().swallowEvent("click");
        picker.container.addClass("x-menu-date-item");
    });

    this.picker.on("select", this.onSelect, this);
   
};

Ext.extend(Ext.menu.YearItem, Ext.menu.Item, {
    // private
    onSelect : function(picker, date){
        this.fireEvent("select", this, date, picker);
        //Ext.menu.YearItem.superclass.handleClick.call(this);
    }
});

/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 *
 * http://extjs.com/license
 */

/**
 * @class Ext.form.YearField
 * @extends Ext.form.TriggerField
 * Provides a date input field with a {@link Ext.YearPicker} dropdown and automatic date validation.
* @constructor
* Create a new YearField
* @param {Object} config
 */
Ext.form.YearField = Ext.extend(Ext.form.TriggerField,  {
    /**
     * @cfg {String} format
     * The default date format string which can be overriden for localization support.  The format must be
     * valid according to {@link Date#parseDate} (defaults to 'm/d/Y').
     */
    format : "Y",
    /**
     * @cfg {String} altFormats
     * Multiple date formats separated by "|" to try when parsing a user input value and it doesn't match the defined
     * format (defaults to 'm/d/Y|n/j/Y|n/j/y|m/j/y|n/d/y|m/j/Y|n/d/Y|m-d-y|m-d-Y|m/d|m-d|md|mdy|mdY|d|Y-m-d').
     */
    altFormats : "m/Y|m-Y|mY|m/01/Y|m-01-Y|m01Y|m/d/Y|n/j/Y|n/j/y|m/j/y|n/d/y|m/j/Y|n/d/Y|m-d-y|m-d-Y|m/d|m-d|md|mdy|mdY|d|Y-m-d",
    /**
     * @cfg {String} disabledDaysText
     * The tooltip to display when the date falls on a disabled day (defaults to 'Disabled')
     */
    disabledDaysText : "Disabled",
    /**
     * @cfg {String} disabledDatesText
     * The tooltip text to display when the date falls on a disabled date (defaults to 'Disabled')
     */
    disabledDatesText : "Disabled",
    /**
     * @cfg {String} minText
     * The error text to display when the date in the cell is before minValue (defaults to
     * 'The date in this field must be after {minValue}').
     */
    minText : "The date in this field must be equal to or after {0}",
    /**
     * @cfg {String} maxText
     * The error text to display when the date in the cell is after maxValue (defaults to
     * 'The date in this field must be before {maxValue}').
     */
    maxText : "The date in this field must be equal to or before {0}",
    /**
     * @cfg {String} invalidText
     * The error text to display when the date in the field is invalid (defaults to
     * '{value} is not a valid date - it must be in the format {format}').
     */
    invalidText : "{0} is not a valid date - it must be in the format {1}",
    /**
     * @cfg {String} triggerClass
     * An additional CSS class used to style the trigger button.  The trigger will always get the
     * class 'x-form-trigger' and triggerClass will be <b>appended</b> if specified (defaults to 'x-form-date-trigger'
     * which displays a calendar icon).
     */
    triggerClass : 'x-form-date-trigger',
    /**
     * @cfg {Date/String} minValue
     * The minimum allowed date. Can be either a Javascript date object or a string date in a
     * valid format (defaults to null).
     */
    /**
     * @cfg {Date/String} maxValue
     * The maximum allowed date. Can be either a Javascript date object or a string date in a
     * valid format (defaults to null).
     */
    /* * Not implemented yet
     * @cfg {Array} disabledDays
     * An array of days to disable, 0 based. For example, [0, 6] disables Sunday and Saturday (defaults to null).
     */
    /* * Not implemented yet
     * @cfg {Array} disabledDates
     * An array of "dates" to disable, as strings. These strings will be used to build a dynamic regular
     * expression so they are very powerful. Some examples:
     * <ul>
     * <li>["03/08/2003", "09/16/2003"] would disable those exact dates</li>
     * <li>["03/08", "09/16"] would disable those days for every year</li>
     * <li>["^03/08"] would only match the beginning (useful if you are using short years)</li>
     * <li>["03/../2006"] would disable every day in March 2006</li>
     * <li>["^03"] would disable every day in every March</li>
     * </ul>
     * Note that the format of the dates included in the array should exactly match the {@link #format} config.
     * In order to support regular expressions, if you are using a date format that has "." in it, you will have to
     * escape the dot when restricting dates. For example: ["03\\.08\\.03"].
     */
    /**
     * @cfg {String/Object} autoCreate
     * A DomHelper element spec, or true for a default element spec (defaults to
     * {tag: "input", type: "text", size: "10", autocomplete: "off"})
     */

    // private
    defaultAutoCreate : {tag: "input", type: "text", size: "10", autocomplete: "off"},

    initComponent : function(){
        Ext.form.YearField.superclass.initComponent.call(this);
        if(typeof this.minValue == "string"){
            this.minValue = this.parseDate(this.minValue);
        }
        if(typeof this.maxValue == "string"){
            this.maxValue = this.parseDate(this.maxValue);
        }
        this.ddMatch = null;
        //this.initDisabledDays();
    },

    // private
    /*initDisabledDays : function(){
        if(this.disabledDates){
            var dd = this.disabledDates;
            var re = "(?:";
            for(var i = 0; i < dd.length; i++){
                re += dd[i];
                if(i != dd.length-1) re += "|";
            }
            this.disabledDatesRE = new RegExp(re + ")");
        }
    },*/

    /**
     * Replaces any existing disabled dates with new values and refreshes the MonthPicker.
     * @param {Array} disabledDates An array of date strings (see the {@link #disabledDates} config
     * for details on supported values) used to disable a pattern of dates.
     */
    /*setDisabledDates : function(dd){
        this.disabledDates = dd;
        this.initDisabledDays();
        if(this.menu){
            this.menu.picker.setDisabledDates(this.disabledDatesRE);
        }
    },*/

    /**
     * Replaces any existing disabled days (by index, 0-6) with new values and refreshes the MonthPicker.
     * @param {Array} disabledDays An array of disabled day indexes. See the {@link #disabledDays} config
     * for details on supported values.
     */
    /*setDisabledDays : function(dd){
        this.disabledDays = dd;
        if(this.menu){
            this.menu.picker.setDisabledDays(dd);
        }
    },*/

    /**
     * Replaces any existing {@link #minValue} with the new value and refreshes the MonthPicker.
     * @param {Date} value The minimum date that can be selected
     */
    setMinValue : function(dt){
        this.minValue = (typeof dt == "string" ? this.parseDate(dt) : dt);
        if(this.menu){
            this.menu.picker.setMinDate(this.minValue);
        }
    },

    /**
     * Replaces any existing {@link #maxValue} with the new value and refreshes the MonthPicker.
     * @param {Date} value The maximum date that can be selected
     */
    setMaxValue : function(dt){
        this.maxValue = (typeof dt == "string" ? this.parseDate(dt) : dt);
        if(this.menu){
            this.menu.picker.setMaxDate(this.maxValue);
        }
    },

    // private
    validateValue : function(value){
        value = this.formatDate(value);
        if(!Ext.form.YearField.superclass.validateValue.call(this, value)){
            return false;
        }
        if(value.length < 1){ // if it's blank and textfield didn't flag it then it's valid
             return true;
        }
        var svalue = value;
        value = this.parseDate(value);
        if(!value){
            this.markInvalid(String.format(this.invalidText, svalue, this.format));
            return false;
        }
        var time = value.getTime();
        if(this.minValue && time < this.minValue.getTime()){
            this.markInvalid(String.format(this.minText, this.formatDate(this.minValue)));
            return false;
        }
        if(this.maxValue && time > this.maxValue.getTime()){
            this.markInvalid(String.format(this.maxText, this.formatDate(this.maxValue)));
            return false;
        }
        if(this.disabledDays){
            var day = value.getDay();
            for(var i = 0; i < this.disabledDays.length; i++) {
            	if(day === this.disabledDays[i]){
            	    this.markInvalid(this.disabledDaysText);
                    return false;
            	}
            }
        }
        var fvalue = this.formatDate(value);
        if(this.ddMatch && this.ddMatch.test(fvalue)){
            this.markInvalid(String.format(this.disabledDatesText, fvalue));
            return false;
        }
        return true;
    },

    // private
    // Provides logic to override the default TriggerField.validateBlur which just returns true
    validateBlur : function(){
        return !this.menu || !this.menu.isVisible();
    },

    /**
     * Returns the current date value of the date field.
     * @return {Date} The date value
     */
    getValue : function(){
        return this.parseDate(Ext.form.YearField.superclass.getValue.call(this)) || "";
    },

    /**
     * Sets the value of the date field.  You can pass a date object or any string that can be parsed into a valid
     * date, using YearField.format as the date format, according to the same rules as {@link Date#parseDate}
     * (the default format used is "m/d/Y").
     * <br />Usage:
     * <pre><code>
//All of these calls set the same date value (May 4, 2006)

//Pass a date object:
var dt = new Date('5/4/2006');
dateField.setValue(dt);

//Pass a date string (default format):
dateField.setValue('05/04/2006');

//Pass a date string (custom format):
dateField.format = 'Y-m-d';
dateField.setValue('2006-05-04');
</code></pre>
     * @param {String/Date} date The date or valid date string
     */
    setValue : function(date){
        Ext.form.YearField.superclass.setValue.call(this, this.formatDate(this.parseDate(date)));
    },

    // private
    parseDate : function(value){
        if(!value || Ext.isDate(value)){
            return value;
        }
        var v = Date.parseDate(value, this.format);
        if(!v && this.altFormats){
            if(!this.altFormatsArray){
                this.altFormatsArray = this.altFormats.split("|");
            }
            for(var i = 0, len = this.altFormatsArray.length; i < len && !v; i++){
                v = Date.parseDate(value, this.altFormatsArray[i]);
            }
        }
        return v;
    },

    // private
    onDestroy : function(){
        if(this.menu) {
            this.menu.destroy();
        }
        if(this.wrap){
            this.wrap.remove();
        }
        Ext.form.YearField.superclass.onDestroy.call(this);
    },

    // private
    formatDate : function(date){
        return Ext.isDate(date) ? date.dateFormat(this.format) : date;
    },

    // private
    menuListeners : {
        select: function(m, d){
            this.setValue(d);
            this.fireEvent('select', this, d);
        },
        show : function(){ // retain focus styling
            this.onFocus();
        },
        hide : function(){
            this.focus.defer(10, this);
            var ml = this.menuListeners;
            this.menu.un("select", ml.select,  this);
            this.menu.un("show", ml.show,  this);
            this.menu.un("hide", ml.hide,  this);
        }
    },

    /**
     * @method onTriggerClick
     * @hide
     */
    // private
    // Implements the default empty TriggerField.onTriggerClick function to display the MonthPicker
    onTriggerClick : function(){
        if(this.disabled){
            return;
        }
        if(this.menu == null){
            this.menu = new Ext.menu.YearMenu();
        }
        Ext.apply(this.menu.picker,  {
            minDate : this.minValue,
            maxDate : this.maxValue,
            disabledDatesRE : this.ddMatch,
            disabledDatesText : this.disabledDatesText,
            disabledDays : this.disabledDays,
            disabledDaysText : this.disabledDaysText,
            format : this.format,
            showToday : this.showToday,
            minText : String.format(this.minText, this.formatDate(this.minValue)),
            maxText : String.format(this.maxText, this.formatDate(this.maxValue)),
            url:this.url
        });
        this.menu.on(Ext.apply({}, this.menuListeners, {
            scope:this
        }));
        this.menu.picker.setValue(this.getValue() || new Date());
        this.menu.show(this.el, "tl-bl?");
    },

    // private
    beforeBlur : function(){
        var v = this.parseDate(this.getRawValue());
        if(v){
            this.setValue(v);
        }
    }

    /**
     * @cfg {Boolean} grow @hide
     */
    /**
     * @cfg {Number} growMin @hide
     */
    /**
     * @cfg {Number} growMax @hide
     */
    /**
     * @hide
     * @method autoSize
     */
});
Ext.reg('yearfield', Ext.form.YearField);

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/yearpicker/Ext.YearPicker.js  ]*/

/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/*
 * Software License Agreement (BSD License)
 * Copyright (c) 2008, Nige "Animal" White
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 *     * Neither the name of the original author nor the names of its contributors
 *       may be used to endorse or promote products derived from this software
 *       without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */
/**
 * @class Ext.ux.DDView
 * <p>A DnD-enabled version of {@link Ext.DataView}. Drag/drop is implemented by adding
 * {@link Ext.data.Record}s to the target DDView. If copying is not being performed,
 * the original {@link Ext.data.Record} is removed from the source DDView.</p>
 * @constructor
 * Create a new DDView
 * @param {Object} config The configuration properties.
 */
Ext.ux.DDView = function(config) {
    if (!config.itemSelector) {
        var tpl = config.tpl;
        if (this.classRe.test(tpl)) {
            config.tpl = tpl.replace(this.classRe, 'class=$1x-combo-list-item $2$1');
        }
        else {
            config.tpl = tpl.replace(this.tagRe, '$1 class="x-combo-list-item" $2');
        }
        config.itemSelector = ".x-combo-list-item";
    }
    Ext.ux.DDView.superclass.constructor.call(this, Ext.apply(config, {
        border: false
    }));
};

Ext.extend(Ext.ux.DDView, Ext.DataView, {
    /**
     * @cfg {String/Array} dragGroup The ddgroup name(s) for the View's DragZone (defaults to undefined).
     */
    /**
     * @cfg {String/Array} dropGroup The ddgroup name(s) for the View's DropZone (defaults to undefined).
     */
    /**
     * @cfg {Boolean} copy Causes drag operations to copy nodes rather than move (defaults to false).
     */
    /**
     * @cfg {Boolean} allowCopy Causes ctrl/drag operations to copy nodes rather than move (defaults to false).
     */
    /**
     * @cfg {String} sortDir Sort direction for the view, 'ASC' or 'DESC' (defaults to 'ASC').
     */
    sortDir: 'ASC',

    // private
    isFormField: true,
    classRe: /class=(['"])(.*)\1/,
    tagRe: /(<\w*)(.*?>)/,
    reset: Ext.emptyFn,
    clearInvalid: Ext.form.Field.prototype.clearInvalid,

    // private
    afterRender: function() {
        Ext.ux.DDView.superclass.afterRender.call(this);
        if (this.dragGroup) {
            this.setDraggable(this.dragGroup.split(","));
        }
        if (this.dropGroup) {
            this.setDroppable(this.dropGroup.split(","));
        }
        if (this.deletable) {
            this.setDeletable();
        }
        this.isDirtyFlag = false;
        this.addEvents(
            "drop"
        );
    },

    // private
    validate: function() {
        return true;
    },

    // private
    destroy: function() {
        this.purgeListeners();
        this.getEl().removeAllListeners();
        this.getEl().remove();
        if (this.dragZone) {
            if (this.dragZone.destroy) {
                this.dragZone.destroy();
            }
        }
        if (this.dropZone) {
            if (this.dropZone.destroy) {
                this.dropZone.destroy();
            }
        }
    },

	/**
	 * Allows this class to be an Ext.form.Field so it can be found using {@link Ext.form.BasicForm#findField}.
	 */
    getName: function() {
        return this.name;
    },

	/**
	 * Loads the View from a JSON string representing the Records to put into the Store.
     * @param {String} value The JSON string
	 */
    setValue: function(v) {
        if (!this.store) {
            throw "DDView.setValue(). DDView must be constructed with a valid Store";
        }
        var data = {};
        data[this.store.reader.meta.root] = v ? [].concat(v) : [];
        this.store.proxy = new Ext.data.MemoryProxy(data);
        this.store.load();
    },

	/**
	 * Returns the view's data value as a list of ids.
     * @return {String} A parenthesised list of the ids of the Records in the View, e.g. (1,3,8).
	 */
    getValue: function() {
        var result = '(';
        this.store.each(function(rec) {
            result += rec.id + ',';
        });
        return result.substr(0, result.length - 1) + ')';
    },

    getIds: function() {
        var i = 0, result = new Array(this.store.getCount());
        this.store.each(function(rec) {
            result[i++] = rec.id;
        });
        return result;
    },

    /**
     * Returns true if the view's data has changed, else false.
     * @return {Boolean}
     */
    isDirty: function() {
        return this.isDirtyFlag;
    },

	/**
	 * Part of the Ext.dd.DropZone interface. If no target node is found, the
	 * whole Element becomes the target, and this causes the drop gesture to append.
	 */
    getTargetFromEvent : function(e) {
        var target = e.getTarget();
        while ((target !== null) && (target.parentNode != this.el.dom)) {
            target = target.parentNode;
        }
        if (!target) {
            target = this.el.dom.lastChild || this.el.dom;
        }
        return target;
    },

	/**
	 * Create the drag data which consists of an object which has the property "ddel" as
	 * the drag proxy element.
	 */
    getDragData : function(e) {
        var target = this.findItemFromChild(e.getTarget());
        if(target) {
            if (!this.isSelected(target)) {
                delete this.ignoreNextClick;
                this.onItemClick(target, this.indexOf(target), e);
                this.ignoreNextClick = true;
            }
            var dragData = {
                sourceView: this,
                viewNodes: [],
                records: [],
                copy: this.copy || (this.allowCopy && e.ctrlKey)
            };
            if (this.getSelectionCount() == 1) {
                var i = this.getSelectedIndexes()[0];
                var n = this.getNode(i);
                dragData.viewNodes.push(dragData.ddel = n);
                dragData.records.push(this.store.getAt(i));
                dragData.repairXY = Ext.fly(n).getXY();
            } else {
                dragData.ddel = document.createElement('div');
                dragData.ddel.className = 'multi-proxy';
                this.collectSelection(dragData);
            }
            return dragData;
        }
        return false;
    },

    // override the default repairXY.
    getRepairXY : function(e){
        return this.dragData.repairXY;
    },

	// private
    collectSelection: function(data) {
        data.repairXY = Ext.fly(this.getSelectedNodes()[0]).getXY();
        if (this.preserveSelectionOrder === true) {
            Ext.each(this.getSelectedIndexes(), function(i) {
                var n = this.getNode(i);
                var dragNode = n.cloneNode(true);
                dragNode.id = Ext.id();
                data.ddel.appendChild(dragNode);
                data.records.push(this.store.getAt(i));
                data.viewNodes.push(n);
            }, this);
        } else {
            var i = 0;
            this.store.each(function(rec){
                if (this.isSelected(i)) {
                    var n = this.getNode(i);
                    var dragNode = n.cloneNode(true);
                    dragNode.id = Ext.id();
                    data.ddel.appendChild(dragNode);
                    data.records.push(this.store.getAt(i));
                    data.viewNodes.push(n);
                }
                i++;
            }, this);
        }
    },

	/**
	 * Specify to which ddGroup items in this DDView may be dragged.
     * @param {String} ddGroup The DD group name to assign this view to.
	 */
    setDraggable: function(ddGroup) {
        if (ddGroup instanceof Array) {
            Ext.each(ddGroup, this.setDraggable, this);
            return;
        }
        if (this.dragZone) {
            this.dragZone.addToGroup(ddGroup);
        } else {
            this.dragZone = new Ext.dd.DragZone(this.getEl(), {
                containerScroll: true,
                ddGroup: ddGroup
            });
            // Draggability implies selection. DragZone's mousedown selects the element.
            if (!this.multiSelect) { this.singleSelect = true; }

            // Wire the DragZone's handlers up to methods in *this*
            this.dragZone.getDragData = this.getDragData.createDelegate(this);
            this.dragZone.getRepairXY = this.getRepairXY;
            this.dragZone.onEndDrag = this.onEndDrag;
        }
    },

	/**
	 * Specify from which ddGroup this DDView accepts drops.
     * @param {String} ddGroup The DD group name from which to accept drops.
	 */
    setDroppable: function(ddGroup) {
        if (ddGroup instanceof Array) {
            Ext.each(ddGroup, this.setDroppable, this);
            return;
        }
        if (this.dropZone) {
            this.dropZone.addToGroup(ddGroup);
        } else {
            this.dropZone = new Ext.dd.DropZone(this.getEl(), {
                owningView: this,
                containerScroll: true,
                ddGroup: ddGroup
            });

            // Wire the DropZone's handlers up to methods in *this*
            this.dropZone.getTargetFromEvent = this.getTargetFromEvent.createDelegate(this);
            this.dropZone.onNodeEnter = this.onNodeEnter.createDelegate(this);
            this.dropZone.onNodeOver = this.onNodeOver.createDelegate(this);
            this.dropZone.onNodeOut = this.onNodeOut.createDelegate(this);
            this.dropZone.onNodeDrop = this.onNodeDrop.createDelegate(this);
        }
    },

	// private
    getDropPoint : function(e, n, dd){
        if (n == this.el.dom) { return "above"; }
        var t = Ext.lib.Dom.getY(n), b = t + n.offsetHeight;
        var c = t + (b - t) / 2;
        var y = Ext.lib.Event.getPageY(e);
        if(y <= c) {
            return "above";
        }else{
            return "below";
        }
    },

    // private
    isValidDropPoint: function(pt, n, data) {
        if (!data.viewNodes || (data.viewNodes.length != 1)) {
            return true;
        }
        var d = data.viewNodes[0];
        if (d == n) {
            return false;
        }
        if ((pt == "below") && (n.nextSibling == d)) {
            return false;
        }
        if ((pt == "above") && (n.previousSibling == d)) {
            return false;
        }
        return true;
    },

    // private
    onNodeEnter : function(n, dd, e, data){
        if (this.highlightColor && (data.sourceView != this)) {
            this.el.highlight(this.highlightColor);
        }
        return false;
    },

    // private
    onNodeOver : function(n, dd, e, data){
        var dragElClass = this.dropNotAllowed;
        var pt = this.getDropPoint(e, n, dd);
        if (this.isValidDropPoint(pt, n, data)) {
            if (this.appendOnly || this.sortField) {
                return "x-tree-drop-ok-below";
            }

            // set the insert point style on the target node
            if (pt) {
                var targetElClass;
                if (pt == "above"){
                    dragElClass = n.previousSibling ? "x-tree-drop-ok-between" : "x-tree-drop-ok-above";
                    targetElClass = "x-view-drag-insert-above";
                } else {
                    dragElClass = n.nextSibling ? "x-tree-drop-ok-between" : "x-tree-drop-ok-below";
                    targetElClass = "x-view-drag-insert-below";
                }
                if (this.lastInsertClass != targetElClass){
                    Ext.fly(n).replaceClass(this.lastInsertClass, targetElClass);
                    this.lastInsertClass = targetElClass;
                }
            }
        }
        return dragElClass;
    },

    // private
    onNodeOut : function(n, dd, e, data){
        this.removeDropIndicators(n);
    },

    // private
    onNodeDrop : function(n, dd, e, data){
        if (this.fireEvent("drop", this, n, dd, e, data) === false) {
            return false;
        }
        var pt = this.getDropPoint(e, n, dd);
        var insertAt = (this.appendOnly || (n == this.el.dom)) ? this.store.getCount() : n.viewIndex;
        if (pt == "below") {
            insertAt++;
        }

        // Validate if dragging within a DDView
        if (data.sourceView == this) {
            // If the first element to be inserted below is the target node, remove it
            if (pt == "below") {
                if (data.viewNodes[0] == n) {
                    data.viewNodes.shift();
                }
            } else {  // If the last element to be inserted above is the target node, remove it
                if (data.viewNodes[data.viewNodes.length - 1] == n) {
                    data.viewNodes.pop();
                }
            }

            // Nothing to drop...
            if (!data.viewNodes.length) {
                return false;
            }

            // If we are moving DOWN, then because a store.remove() takes place first,
            // the insertAt must be decremented.
            if (insertAt > this.store.indexOf(data.records[0])) {
                insertAt--;
            }
        }

        // Dragging from a Tree. Use the Tree's recordFromNode function.
        if (data.node instanceof Ext.tree.TreeNode) {
            var r = data.node.getOwnerTree().recordFromNode(data.node);
            if (r) {
                data.records = [ r ];
            }
        }

        if (!data.records) {
            alert("Programming problem. Drag data contained no Records");
            return false;
        }

        for (var i = 0; i < data.records.length; i++) {
            var r = data.records[i];
            var dup = this.store.getById(r.id);
            if (dup && (dd != this.dragZone)) {
                if(!this.allowDup && !this.allowTrash){
                    Ext.fly(this.getNode(this.store.indexOf(dup))).frame("red", 1);
                    return true
                }
                var x=new Ext.data.Record();
                r.id=x.id;
                delete x;
            }
            if (data.copy) {
                this.store.insert(insertAt++, r.copy());
            } else {
                if (data.sourceView) {
                    data.sourceView.isDirtyFlag = true;
                    data.sourceView.store.remove(r);
                }
                if(!this.allowTrash)this.store.insert(insertAt++, r);
            }
            if(this.sortField){
                this.store.sort(this.sortField, this.sortDir);
            }
            this.isDirtyFlag = true;
        }
        this.dragZone.cachedTarget = null;
        return true;
    },

    // private
    onEndDrag: function(data, e) {
        var d = Ext.get(this.dragData.ddel);
        if (d && d.hasClass("multi-proxy")) {
            d.remove();
            //delete this.dragData.ddel;
        }
    },

    // private
    removeDropIndicators : function(n){
        if(n){
            Ext.fly(n).removeClass([
                "x-view-drag-insert-above",
                "x-view-drag-insert-left",
                "x-view-drag-insert-right",
                "x-view-drag-insert-below"]);
            this.lastInsertClass = "_noclass";
        }
    },

	/**
	 * Add a delete option to the DDView's context menu.
	 * @param {String} imageUrl The URL of the "delete" icon image.
	 */
    setDeletable: function(imageUrl) {
        if (!this.singleSelect && !this.multiSelect) {
            this.singleSelect = true;
        }
        var c = this.getContextMenu();
        this.contextMenu.on("itemclick", function(item) {
            switch (item.id) {
                case "delete":
                    this.remove(this.getSelectedIndexes());
                    break;
            }
        }, this);
        this.contextMenu.add({
            icon: imageUrl || AU.resolveUrl("/images/delete.gif"),
            id: "delete",
            text: AU.getMessage("deleteItem")
        });
    },

	/**
	 * Return the context menu for this DDView.
     * @return {Ext.menu.Menu} The context menu
	 */
    getContextMenu: function() {
        if (!this.contextMenu) {
            // Create the View's context menu
            this.contextMenu = new Ext.menu.Menu({
                id: this.id + "-contextmenu"
            });
            this.el.on("contextmenu", this.showContextMenu, this);
        }
        return this.contextMenu;
    },

    /**
     * Disables the view's context menu.
     */
    disableContextMenu: function() {
        if (this.contextMenu) {
            this.el.un("contextmenu", this.showContextMenu, this);
        }
    },

    // private
    showContextMenu: function(e, item) {
        item = this.findItemFromChild(e.getTarget());
        if (item) {
            e.stopEvent();
            this.select(this.getNode(item), this.multiSelect && e.ctrlKey, true);
            this.contextMenu.showAt(e.getXY());
        }
    },

	/**
	 * Remove {@link Ext.data.Record}s at the specified indices.
	 * @param {Array/Number} selectedIndices The index (or Array of indices) of Records to remove.
	 */
    remove: function(selectedIndices) {
        selectedIndices = [].concat(selectedIndices);
        for (var i = 0; i < selectedIndices.length; i++) {
            var rec = this.store.getAt(selectedIndices[i]);
            this.store.remove(rec);
        }
    },

	/**
	 * Double click fires the {@link #dblclick} event. Additionally, if this DDView is draggable, and there is only one other
	 * related DropZone that is in another DDView, it drops the selected node on that DDView.
	 */
    onDblClick : function(e){
        var item = this.findItemFromChild(e.getTarget());
        if(item){
            if (this.fireEvent("dblclick", this, this.indexOf(item), item, e) === false) {
                return false;
            }
            if (this.dragGroup) {
                var targets = Ext.dd.DragDropMgr.getRelated(this.dragZone, true);

                // Remove instances of this View's DropZone
                while (targets.indexOf(this.dropZone) !== -1) {
                    targets.remove(this.dropZone);
                }

                // If there's only one other DropZone, and it is owned by a DDView, then drop it in
                if ((targets.length == 1) && (targets[0].owningView)) {
                    this.dragZone.cachedTarget = null;
                    var el = Ext.get(targets[0].getEl());
                    var box = el.getBox(true);
                    targets[0].onNodeDrop(el.dom, {
                        target: el.dom,
                        xy: [box.x, box.y + box.height - 1]
                    }, null, this.getDragData(e));
                }
            }
        }
    },

    // private
    onItemClick : function(item, index, e){
        // The DragZone's mousedown->getDragData already handled selection
        if (this.ignoreNextClick) {
            delete this.ignoreNextClick;
            return;
        }

        if(this.fireEvent("beforeclick", this, index, item, e) === false){
            return false;
        }
        if(this.multiSelect || this.singleSelect){
            if(this.multiSelect && e.shiftKey && this.lastSelection){
                this.select(this.getNodes(this.indexOf(this.lastSelection), index), false);
            } else if (this.isSelected(item) && e.ctrlKey) {
                this.deselect(item);
            }else{
                this.deselect(item);
                this.select(item, this.multiSelect && e.ctrlKey);
                this.lastSelection = item;
            }
            e.preventDefault();
        }
        return true;
    }
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/multiselect/DDView.js  ]*/

/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/*
 * Note that this control should still be treated as an example and that the API will most likely
 * change once it is ported into the Ext core as a standard form control.  This is still planned
 * for a future release, so this should not yet be treated as a final, stable API at this time.
 */
 
/** 
 * @class Ext.ux.MultiSelect
 * @extends Ext.form.Field
 * A control that allows selection and form submission of multiple list items. The MultiSelect control
 * depends on the Ext.ux.DDView class to provide drag/drop capability both within the list and also 
 * between multiple MultiSelect controls (see the Ext.ux.ItemSelector).
 * 
 *  @history
 *    2008-06-19 bpm Original code contributed by Toby Stuart
 *    2008-06-19 bpm Docs and demo code clean up
 * 
 * @constructor
 * Create a new MultiSelect
 * @param {Object} config Configuration options
 */
Ext.ux.Multiselect = Ext.extend(Ext.form.Field,  {
    /**
     * @cfg {String} legend Wraps the object with a fieldset and specified legend.
     */
    /**
     * @cfg {Store} store The {@link Ext.data.Store} used by the underlying Ext.ux.DDView.
     */
    /**
     * @cfg {Ext.ux.DDView} view The Ext.ux.DDView used to render the multiselect list.
     */
    /**
     * @cfg {String/Array} dragGroup The ddgroup name(s) for the DDView's DragZone (defaults to undefined). 
     */ 
    /**
     * @cfg {String/Array} dropGroup The ddgroup name(s) for the DDView's DropZone (defaults to undefined). 
     */ 
    /**
     * @cfg {Object/Array} tbar The top toolbar of the control. This can be a {@link Ext.Toolbar} object, a 
     * toolbar config, or an array of buttons/button configs to be added to the toolbar.
     */
    /**
     * @cfg {String} fieldName The name of the field to sort by when sorting is enabled.
     */
    /**
     * @cfg {String} appendOnly True if the list should only allow append drops when drag/drop is enabled 
     * (use for lists which are sorted, defaults to false).
     */
    appendOnly:false,
    /**
     * @cfg {Array} dataFields Inline data definition when not using a pre-initialised store. Known to cause problems 
     * in some browswers for very long lists. Use store for large datasets.
     */
    dataFields:[],
    /**
     * @cfg {Array} data Inline data when not using a pre-initialised store. Known to cause problems in some 
     * browswers for very long lists. Use store for large datasets.
     */
    data:[],
    /**
     * @cfg {Number} width Width in pixels of the control (defaults to 100).
     */
    width:100,
    /**
     * @cfg {Number} height Height in pixels of the control (defaults to 100).
     */
    height:100,
    /**
     * @cfg {String/Number} displayField Name/Index of the desired display field in the dataset (defaults to 0).
     */
    displayField:0,
    /**
     * @cfg {String/Number} valueField Name/Index of the desired value field in the dataset (defaults to 1).
     */
    valueField:1,
    /**
     * @cfg {Boolean} allowBlank True to require at least one item in the list to be selected, false to allow no 
     * selection (defaults to true).
     */
    allowBlank:true,
    /**
     * @cfg {Number} minLength Minimum number of selections allowed (defaults to 0).
     */
    minLength:0,
    /**
     * @cfg {Number} maxLength Maximum number of selections allowed (defaults to Number.MAX_VALUE). 
     */
    maxLength:Number.MAX_VALUE,
    /**
     * @cfg {String} blankText Default text displayed when the control contains no items (defaults to the same value as
     * {@link Ext.form.TextField#blankText}.
     */
    blankText:Ext.form.TextField.prototype.blankText,
    /**
     * @cfg {String} minLengthText Validation message displayed when {@link #minLength} is not met (defaults to 'Minimum {0} 
     * item(s) required').  The {0} token will be replaced by the value of {@link #minLength}.
     */
    minLengthText:'Minimum {0} item(s) required',
    /**
     * @cfg {String} maxLengthText Validation message displayed when {@link #maxLength} is not met (defaults to 'Maximum {0} 
     * item(s) allowed').  The {0} token will be replaced by the value of {@link #maxLength}.
     */
    maxLengthText:'Maximum {0} item(s) allowed',
    /**
     * @cfg {String} delimiter The string used to delimit between items when set or returned as a string of values
     * (defaults to ',').
     */
    delimiter:',',
    
    // DDView settings
    copy:false,
    allowDup:false,
    allowTrash:false,
    focusClass:undefined,
    sortDir:'ASC',
    
    // private
    defaultAutoCreate : {tag: "div"},
    
    // private
    initComponent: function(){
        Ext.ux.Multiselect.superclass.initComponent.call(this);
        this.addEvents({
            'dblclick' : true,
            'click' : true,
            'change' : true,
            'drop' : true
        });     
    },
    
    // private
    onRender: function(ct, position){
        Ext.ux.Multiselect.superclass.onRender.call(this, ct, position);
       
        var cls = 'ux-mselect';
        var fs = new Ext.form.FieldSet({
            renderTo:this.el,
            title:this.legend,
            height:this.height,
            width:this.width,
            style:"padding:0;",
            tbar:this.tbar
        });
        //if(!this.legend)fs.el.down('.'+fs.headerCls).remove();
        fs.body.addClass(cls);

        var tpl = '<tpl for="."><div class="' + cls + '-item';
        if(Ext.isIE || Ext.isIE7){
            tpl+='" unselectable=on';
        }else{
            tpl+=' x-unselectable"';
        }
        tpl+='>{' + this.displayField + '}</div></tpl>';

        if(!this.store){
            this.store = new Ext.data.SimpleStore({
                fields: this.dataFields,
                data : this.data
            });
        }

        this.view = new Ext.ux.DDView({
            multiSelect: true, 
            store: this.store, 
            selectedClass: cls+"-selected", 
            tpl:tpl,
            allowDup:this.allowDup, 
            copy: this.copy, 
            allowTrash: this.allowTrash, 
            dragGroup: this.dragGroup, 
            dropGroup: this.dropGroup, 
            itemSelector:"."+cls+"-item",
            isFormField:false, 
            applyTo:fs.body,
            appendOnly:this.appendOnly,
            sortField:this.sortField, 
            sortDir:this.sortDir
            
        });

        fs.add(this.view);
        
        this.view.on('click', this.onViewClick, this);
        this.view.on('beforeClick', this.onViewBeforeClick, this);
        this.view.on('dblclick', this.onViewDblClick, this);
        this.view.on('drop', function(ddView, n, dd, e, data){
            return this.fireEvent("drop", ddView, n, dd, e, data);
        }, this);
        
        this.hiddenName = this.name;
        var hiddenTag={tag: "input", type: "hidden", value: "", name:this.name};
        if (this.isFormField) { 
            this.hiddenField = this.el.createChild(hiddenTag);
        } else {
            this.hiddenField = Ext.get(document.body).createChild(hiddenTag);
        }
        fs.doLayout();
        /**
         * Fix for the double multicombo not rendered while placed inside multiple tabs
         * by Prakash
         */
        fs.on("afterlayout",function(ct,layout){
            if(this.ownerCt && this.ownerCt.container){
            	this.ownerCt.container.dom.style.width = ((this.width*2)+20)+"px";
            }        	
        },this)
        /*******************************************************************************/
    },
    
    // private
    initValue:Ext.emptyFn,
    
    // private
    onViewClick: function(vw, index, node, e) {
        var arrayIndex = this.preClickSelections.indexOf(index);
        if (arrayIndex  != -1)
        {
            this.preClickSelections.splice(arrayIndex, 1);
            this.view.clearSelections(true);
            this.view.select(this.preClickSelections);
        }
        this.fireEvent('change', this, this.getValue(), this.hiddenField.dom.value);
        this.hiddenField.dom.value = this.getValue();
        this.fireEvent('click', this, e);
        this.validate();        
    },

    // private
    onViewBeforeClick: function(vw, index, node, e) {
        this.preClickSelections = this.view.getSelectedIndexes();
        if (this.disabled) {return false;}
    },

    // private
    onViewDblClick : function(vw, index, node, e) {
        return this.fireEvent('dblclick', vw, index, node, e);
    },  
    
    /**
     * Returns an array of data values for the selected items in the list. The values will be separated
     * by {@link #delimiter}.
     * @return {Array} value An array of string data values
     */
    getValue: function(valueField){
        var returnArray = [];
        var selectionsArray = this.view.getSelectedIndexes();
        if (selectionsArray.length == 0) {return '';}
        for (var i=0; i<selectionsArray.length; i++) {
            returnArray.push(this.store.getAt(selectionsArray[i]).get(((valueField != null)? valueField : this.valueField)));
        }
        return returnArray.join(this.delimiter);
    },

    /**
     * Sets a delimited string (using {@link #delimiter}) or array of data values into the list.
     * @param {String/Array} values The values to set
     */
    setValue: function(values) {
        var index;
        var selections = [];
        this.view.clearSelections();
        this.hiddenField.dom.value = '';
        
        if (!values || (values == '')) { return; }
        
        if (!(values instanceof Array)) { values = values.split(this.delimiter); }
        for (var i=0; i<values.length; i++) {
            index = this.view.store.indexOf(this.view.store.query(this.valueField, 
                new RegExp('^' + values[i] + '$', "i")).itemAt(0));
            selections.push(index);
        }
        this.view.select(selections);
        this.hiddenField.dom.value = this.getValue();
        this.validate();
    },
    
    // inherit docs
    reset : function() {
        this.setValue('');
    },
    
    // inherit docs
    getRawValue: function(valueField) {
        var tmp = this.getValue(valueField);
        if (tmp.length) {
            tmp = tmp.split(this.delimiter);
        }
        else{
            tmp = [];
        }
        return tmp;
    },

    // inherit docs
    setRawValue: function(values){
        setValue(values);
    },

    // inherit docs
    validateValue : function(value){
        if (value.length < 1) { // if it has no value
             if (this.allowBlank) {
                 this.clearInvalid();
                 return true;
             } else {
                 this.markInvalid(this.blankText);
                 return false;
             }
        }
        if (value.length < this.minLength) {
            this.markInvalid(String.format(this.minLengthText, this.minLength));
            return false;
        }
        if (value.length > this.maxLength) {
            this.markInvalid(String.format(this.maxLengthText, this.maxLength));
            return false;
        }
        return true;
    }
});

Ext.reg("multiselect", Ext.ux.Multiselect);

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/multiselect/MultiSelect.js  ]*/

/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/*
 * Note that this control will most likely remain as an example, and not as a core Ext form
 * control.  However, the API will be changing in a future release and so should not yet be
 * treated as a final, stable API at this time.
 */
 
/** 
 * @class Ext.ux.ItemSelector
 * @extends Ext.form.Field
 * A control that allows selection of between two Ext.ux.MultiSelect controls.
 * 
 *  @history
 *    2008-06-19 bpm Original code contributed by Toby Stuart
 * 
 * @constructor
 * Create a new ItemSelector
 * @param {Object} config Configuration options
 */
Ext.ux.ItemSelector = Ext.extend(Ext.form.Field,  {
    msWidth:200,
    msHeight:300,
    hideNavIcons:false,
    imagePath:"",
    iconUp:"up2.gif",
    iconDown:"down2.gif",
    iconLeft:"left2.gif",
    iconRight:"right2.gif",
    iconTop:"top2.gif",
    iconBottom:"bottom2.gif",
    drawUpIcon:true,
    drawDownIcon:true,
    drawLeftIcon:true,
    drawRightIcon:true,
    drawTopIcon:true,
    drawBotIcon:true,
    fromStore:null,
    toStore:null,
    fromData:null, 
    toData:null,
    displayField:0,
    valueField:1,
    switchToFrom:false,
    allowDup:false,
    focusClass:undefined,
    delimiter:',',
    readOnly:false,
    toLegend:null,
    fromLegend:null,
    toSortField:null,
    fromSortField:null,
    toSortDir:'ASC',
    fromSortDir:'ASC',
    toTBar:null,
    fromTBar:null,
    bodyStyle:null,
    border:false,
    defaultAutoCreate:{tag: "div"},
    
    initComponent: function(){
        Ext.ux.ItemSelector.superclass.initComponent.call(this);
        this.addEvents({
            'rowdblclick' : true,
            'change' : true
        });         
    },

    onRender: function(ct, position){
        Ext.ux.ItemSelector.superclass.onRender.call(this, ct, position);

        this.fromMultiselect = new Ext.ux.Multiselect({
            legend: this.fromLegend,
            delimiter: this.delimiter,
            allowDup: this.allowDup,
            copy: this.allowDup,
            allowTrash: this.allowDup,
            dragGroup: this.readOnly ? null : "drop2-"+this.el.dom.id,
            dropGroup: this.readOnly ? null : "drop1-"+this.el.dom.id,
            width: this.msWidth,
            height: this.msHeight,
            dataFields: this.dataFields,
            data: this.fromData,
            displayField: this.displayField,
            valueField: this.valueField,
            store: this.fromStore,
            isFormField: false,
            tbar: this.fromTBar,
            appendOnly: true,
            sortField: this.fromSortField,
            sortDir: this.fromSortDir
        });
        this.fromMultiselect.on('dblclick', this.onRowDblClick, this);

        if (!this.toStore) {
            this.toStore = new Ext.data.SimpleStore({
                fields: this.dataFields,
                data : this.toData
            });
        }
        this.toStore.on('add', this.valueChanged, this);
        this.toStore.on('remove', this.valueChanged, this);
        this.toStore.on('load', this.valueChanged, this);

        this.toMultiselect = new Ext.ux.Multiselect({
            legend: this.toLegend,
            delimiter: this.delimiter,
            allowDup: this.allowDup,
            dragGroup: this.readOnly ? null : "drop1-"+this.el.dom.id,
            //dropGroup: this.readOnly ? null : "drop2-"+this.el.dom.id+(this.toSortField ? "" : ",drop1-"+this.el.dom.id),
            dropGroup: this.readOnly ? null : "drop2-"+this.el.dom.id+",drop1-"+this.el.dom.id,
            width: this.msWidth,
            height: this.msHeight,
            displayField: this.displayField,
            valueField: this.valueField,
            store: this.toStore,
            isFormField: false,
            tbar: this.toTBar,
            sortField: this.toSortField,
            sortDir: this.toSortDir
        });
        this.toMultiselect.on('dblclick', this.onRowDblClick, this);
                
        var p = new Ext.Panel({
            bodyStyle:this.bodyStyle,
            border:this.border,
            layout:"table",
            layoutConfig:{columns:3}
        });
        p.add(this.switchToFrom ? this.toMultiselect : this.fromMultiselect);
        var icons = new Ext.Panel({header:false});
        p.add(icons);
        p.add(this.switchToFrom ? this.fromMultiselect : this.toMultiselect);
        p.render(this.el);
        icons.el.down('.'+icons.bwrapCls).remove();

        if (this.imagePath!="" && this.imagePath.charAt(this.imagePath.length-1)!="/")
            this.imagePath+="/";
        this.iconUp = this.imagePath + (this.iconUp || 'up2.gif');
        this.iconDown = this.imagePath + (this.iconDown || 'down2.gif');
        this.iconLeft = this.imagePath + (this.iconLeft || 'left2.gif');
        this.iconRight = this.imagePath + (this.iconRight || 'right2.gif');
        this.iconTop = this.imagePath + (this.iconTop || 'top2.gif');
        this.iconBottom = this.imagePath + (this.iconBottom || 'bottom2.gif');
        var el=icons.getEl();
        if (!this.toSortField) {
            this.toTopIcon = el.createChild({tag:'img', src:this.iconTop, style:{cursor:'pointer', margin:'2px'}});
            el.createChild({tag: 'br'});
            this.upIcon = el.createChild({tag:'img', src:this.iconUp, style:{cursor:'pointer', margin:'2px'}});
            el.createChild({tag: 'br'});
        }
        this.addIcon = el.createChild({tag:'img', src:this.switchToFrom?this.iconLeft:this.iconRight, style:{cursor:'pointer', margin:'2px'}});
        el.createChild({tag: 'br'});
        this.removeIcon = el.createChild({tag:'img', src:this.switchToFrom?this.iconRight:this.iconLeft, style:{cursor:'pointer', margin:'2px'}});
        el.createChild({tag: 'br'});
        if (!this.toSortField) {
            this.downIcon = el.createChild({tag:'img', src:this.iconDown, style:{cursor:'pointer', margin:'2px'}});
            el.createChild({tag: 'br'});
            this.toBottomIcon = el.createChild({tag:'img', src:this.iconBottom, style:{cursor:'pointer', margin:'2px'}});
        }
        if (!this.readOnly) {
            if (!this.toSortField) {
                this.toTopIcon.on('click', this.toTop, this);
                this.upIcon.on('click', this.up, this);
                this.downIcon.on('click', this.down, this);
                this.toBottomIcon.on('click', this.toBottom, this);
            }
            this.addIcon.on('click', this.fromTo, this);
            this.removeIcon.on('click', this.toFrom, this);
        }
        if (!this.drawUpIcon || this.hideNavIcons) { this.upIcon.dom.style.display='none'; }
        if (!this.drawDownIcon || this.hideNavIcons) { this.downIcon.dom.style.display='none'; }
        if (!this.drawLeftIcon || this.hideNavIcons) { this.addIcon.dom.style.display='none'; }
        if (!this.drawRightIcon || this.hideNavIcons) { this.removeIcon.dom.style.display='none'; }
        if (!this.drawTopIcon || this.hideNavIcons) { this.toTopIcon.dom.style.display='none'; }
        if (!this.drawBotIcon || this.hideNavIcons) { this.toBottomIcon.dom.style.display='none'; }

        var tb = p.body.first();
        this.el.setWidth(p.body.first().getWidth());
        p.body.removeClass();
        
        this.hiddenName = this.name;
        var hiddenTag={tag: "input", type: "hidden", value: "", name:this.name};
        this.hiddenField = this.el.createChild(hiddenTag);
        this.valueChanged(this.toStore);
    },
    
    initValue:Ext.emptyFn,
    
    toTop : function() {
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        var records = [];
        if (selectionsArray.length > 0) {
            selectionsArray.sort();
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                records.push(record);
            }
            selectionsArray = [];
            for (var i=records.length-1; i>-1; i--) {
                record = records[i];
                this.toMultiselect.view.store.remove(record);
                this.toMultiselect.view.store.insert(0, record);
                selectionsArray.push(((records.length - 1) - i));
            }
        }
        this.toMultiselect.view.refresh();
        this.toMultiselect.view.select(selectionsArray);
    },

    toBottom : function() {
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        var records = [];
        if (selectionsArray.length > 0) {
            selectionsArray.sort();
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                records.push(record);
            }
            selectionsArray = [];
            for (var i=0; i<records.length; i++) {
                record = records[i];
                this.toMultiselect.view.store.remove(record);
                this.toMultiselect.view.store.add(record);
                selectionsArray.push((this.toMultiselect.view.store.getCount()) - (records.length - i));
            }
        }
        this.toMultiselect.view.refresh();
        this.toMultiselect.view.select(selectionsArray);
    },
    
    up : function() {
        var record = null;
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        selectionsArray.sort();
        var newSelectionsArray = [];
        if (selectionsArray.length > 0) {
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                if ((selectionsArray[i] - 1) >= 0) {
                    this.toMultiselect.view.store.remove(record);
                    this.toMultiselect.view.store.insert(selectionsArray[i] - 1, record);
                    newSelectionsArray.push(selectionsArray[i] - 1);
                }
            }
            this.toMultiselect.view.refresh();
            this.toMultiselect.view.select(newSelectionsArray);
        }
    },

    down : function() {
        var record = null;
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        selectionsArray.sort();
        selectionsArray.reverse();
        var newSelectionsArray = [];
        if (selectionsArray.length > 0) {
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                if ((selectionsArray[i] + 1) < this.toMultiselect.view.store.getCount()) {
                    this.toMultiselect.view.store.remove(record);
                    this.toMultiselect.view.store.insert(selectionsArray[i] + 1, record);
                    newSelectionsArray.push(selectionsArray[i] + 1);
                }
            }
            this.toMultiselect.view.refresh();
            this.toMultiselect.view.select(newSelectionsArray);
        }
    },
    
    fromTo : function() {
        var selectionsArray = this.fromMultiselect.view.getSelectedIndexes();
        var records = [];
        if (selectionsArray.length > 0) {
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.fromMultiselect.view.store.getAt(selectionsArray[i]);
                records.push(record);
            }
            if(!this.allowDup)selectionsArray = [];
            for (var i=0; i<records.length; i++) {
                record = records[i];
                if(this.allowDup){
                    var x=new Ext.data.Record();
                    record.id=x.id;
                    delete x;   
                    this.toMultiselect.view.store.add(record);
                }else{
                    this.fromMultiselect.view.store.remove(record);
                    this.toMultiselect.view.store.add(record);
                    selectionsArray.push((this.toMultiselect.view.store.getCount() - 1));
                }
            }
        }
        this.toMultiselect.view.refresh();
        this.fromMultiselect.view.refresh();
        if(this.toSortField)this.toMultiselect.store.sort(this.toSortField, this.toSortDir);
        if(this.allowDup)this.fromMultiselect.view.select(selectionsArray);
        else this.toMultiselect.view.select(selectionsArray);
    },
    
    toFrom : function() {
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        var records = [];
        if (selectionsArray.length > 0) {
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                records.push(record);
            }
            selectionsArray = [];
            for (var i=0; i<records.length; i++) {
                record = records[i];
                this.toMultiselect.view.store.remove(record);
                if(!this.allowDup){
                    this.fromMultiselect.view.store.add(record);
                    selectionsArray.push((this.fromMultiselect.view.store.getCount() - 1));
                }
            }
        }
        this.fromMultiselect.view.refresh();
        this.toMultiselect.view.refresh();
        if(this.fromSortField)this.fromMultiselect.store.sort(this.fromSortField, this.fromSortDir);
        this.fromMultiselect.view.select(selectionsArray);
    },
    
    valueChanged: function(store) {
        var record = null;
        var values = [];
        for (var i=0; i<store.getCount(); i++) {
            record = store.getAt(i);
            values.push(record.get(this.valueField));
        }
        this.hiddenField.dom.value = values.join(this.delimiter);
        this.fireEvent('change', this, this.getValue(), this.hiddenField.dom.value);
    },
    
    getValue : function() {
        return this.hiddenField.dom.value;
    },
    
    onRowDblClick : function(vw, index, node, e) {
        return this.fireEvent('rowdblclick', vw, index, node, e);
    },
    
    reset: function(){
        range = this.toMultiselect.store.getRange();
        this.toMultiselect.store.removeAll();
        if (!this.allowDup) {
            this.fromMultiselect.store.add(range);
            this.fromMultiselect.store.sort(this.displayField,'ASC');
        }
        this.valueChanged(this.toMultiselect.store);
    }
});

Ext.reg("itemselector", Ext.ux.ItemSelector);

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/multiselect/ItemSelector.js  ]*/

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

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/multiselect/Ext.ux.TreeItemSelector.js  ]*/

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

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/tree/Ext.tree.TreeSerializer.js  ]*/

Ext.ns('Ext.ux');

Ext.ux.ClassicFormPanel = function(config){Ext.ux.ClassicFormPanel.superclass.constructor.call(this, config)};
Ext.extend(Ext.ux.ClassicFormPanel, Ext.form.FormPanel, {
    onSubmit: Ext.emptyFn,
    submit: function(o) {
    	if(this.fileUpload) {
            this.getForm().getEl().dom.enctype = 'multipart/form-data';
        }
    	this.getForm().getEl().dom.action = o.url;
    	this.getForm().getEl().dom.method = o.method;
    	this.getForm().getEl().dom.submit();
    }
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/form/Ext.ux.ClassicFormPanel.js  ]*/

/**
 * Different from Extjs-2 Version
 * Do not overwrite the full code
 */
/**
* @author radu
*/
Ext.ns('Ext.ux.form'); 
/** 
 * @class Ext.ux.form.ComboWButton
 * @extends Ext.form.Field
 */
Ext.ux.form.ComboWButton = Ext.extend(Ext.form.Field,  {
	/**
	* buttonConfig:{text,icon},
	* windowConfig:{title,component: extJs Object private name var,class,method}
	*/
	readOnly:false,
    bodyStyle:null,
    border:true,
    oneLineLayout:true,
    defaultAutoCreate:{tag: "div"},
        
    initComponent: function(){
        Ext.ux.form.ComboWButton.superclass.initComponent.call(this);         
    },

    onRender: function(ct, position){
        Ext.ux.form.ComboWButton.superclass.onRender.call(this, ct, position);		
        this.comboConfig = this.comboConfig || {};
        Ext.applyIf(this.comboConfig, {
			forceSelection: this.forceSelection,
			disableKeyFilter: this.disableKeyFilter,
			mode: this.mode,
			triggerAction: this.triggerAction,
			width:this.width-30,
			store:this.store,
			value:this.value,
			hiddenName:this.hiddenName
		});
		
			
		this.button = new Ext.Button({			
			iconCls: 'icon-plus',
			tooltip: 'Not in list? click to add one'
		});
		this.button.on({
			click: {scope:this,fn:this.onClick}
		});
		this.windowConfig = this.windowConfig || {};		
		this.combo = new Ext.form.ComboBox(this.comboConfig);		
		var p = new Ext.Panel({
            bodyStyle:this.bodyStyle,
            border:this.border,
            layout:"table",
            layoutConfig:{columns:3}
            
        });
		
		
		var mainDiv = Ext.DomHelper.insertFirst(ct, {tag: 'div', style:'width:'+this.width+'px; margin:0px; padding:0px'});		
		var comboDiv = Ext.DomHelper.append(mainDiv, {tag: 'div', style:'float:left;margin:0px; padding:0px'});
		var buttonDiv = Ext.DomHelper.append(mainDiv, {tag: 'div', style:'float:left; margin:0px; margin-left:8px; padding:0px'});
		Ext.DomHelper.append(mainDiv, {tag: 'div', style:'clear:both;'});
		//this.mainDiv = mainDiv;
		this.combo.render(comboDiv);
		this.button.render(buttonDiv);
       // p.add(this.combo);
        //p.add({html:'&nbsp;&nbsp;'});
        //p.add(this.button);
        //p.render(this.el);
       
        
       /// var tb = p.body.first();
       // this.el.setWidth(p.body.getWidth());
       // p.body.removeClass();
    },
	markInvalid : function(msg){	
        //don't set the error icon if we're not rendered or marking is prevented
		
		/*
		* Fix for the mark invalid red border and icon
		*/
		this.el = this.combo.getEl();		
		//*********************************************/
        if (this.rendered && !this.preventMark) {
            msg = msg || this.invalidText;
            var mt = this.getMessageHandler();			
            if(mt){
                mt.mark(this, msg);				
				/*
				* Fix for the mark invalid red border and icon
				*/
				var errorIcon = Ext.select(".x-form-invalid-icon",false,this.getErrorCt().dom);				
				if(errorIcon && errorIcon.elements && errorIcon.elements[0]){
					errorIcon.elements[0].style.left = errorIcon.elements[0].offsetLeft + 47 +"px";
					errorIcon.elements[0].style.display = "block";
					this.eI = errorIcon.elements[0];
				}				
				//*********************************************/
            }else if(this.msgTarget){			
                this.el.addClass(this.invalidClass);
                var t = Ext.getDom(this.msgTarget);
                if(t){
                    t.innerHTML = msg;
                    t.style.display = this.msgDisplay;
                }
            }
        }        
        this.setActiveError(msg);
    },
	clearInvalid : function(){
        //don't remove the error icon if we're not rendered or marking is prevented
		if(this.eI){
			this.eI.style.display="none";
			
		}
		this.el = this.combo.getEl();			
        if (this.rendered && !this.preventMark) {
            this.el.removeClass(this.invalidClass);
            var mt = this.getMessageHandler();
            if(mt){
                mt.clear(this);	
				mt.clear(this.combo);
				
            }else if(this.msgTarget){
                this.el.removeClass(this.invalidClass);
                var t = Ext.getDom(this.msgTarget);
                if(t){
                    t.innerHTML = '';
                    t.style.display = 'none';
                }
            }
        }		
       
        this.unsetActiveError();
    },
    
    initValue:Ext.emptyFn,
    
	onClick: function(button,event){
		/*
		 * Using the forms with name edit[99][] for multiple forms in same page will have problem in action page.
		 * Even using a singel edit[99][] form, we need to change the action for the respective widget, since
		 * it listens to edit[0][].
		 * 
		 * Now using the afApp.widgetPopup will use the edit[0][] for every form and actions need not be changed.
		 */
		afApp.widgetPopup(this.windowConfig.component,this.windowConfig.title?this.windowConfig.title:'Add combo option',this)			
	},
	
	onHide: function(window){
		var combo = this.combo;
		if(this.windowConfig.className&&this.windowConfig.methodName)
		{
			Ext.Ajax.request({ 
				url: "/appFlower/getComboOptions", 
				method:"post", 
				params: {
					'class':this.windowConfig.className,
					'method':this.windowConfig.methodName}, 
					success:function(response, options){						
						response=Ext.decode(response.responseText);
						var s = eval(response.store);
						combo.bindStore(s);					
						var last = s[s.length-1];   
						if(last[1]){
							combo.setValue(last[0]);
						}
				},
				failure: function(response,options) {
					Ext.Msg.alert("Failure","Could not retrieve combo options !");
				},
				scope:this
			});
		}
	},
	    
    getValue : function() {
        return this.combo.getValue();
    }
});

Ext.reg("combowbutton", Ext.ux.form.ComboWButton);

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/form/Ext.ux.form.ComboWButton.js  ]*/

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

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/settings/ux_VerticalTabPanel.js  ]*/

/*
* Ext.ux.Settings
* @author: Prakash Paudel
* Setting layout with tabbed structrue
*/
Ext.ns("Ext.ux");
Ext.ux.Settings = function(config){
	var newConfig = [];
	var windowConfig = {
		width:600,
		height:400		
	};
	config.windowConfig = Ext.apply(windowConfig,config.windowConfig || []);
	Ext.apply(config.windowConfig,config.windowConfig || []);
	Ext.apply(newConfig,config,{
		windowTitle: 'Settings',				
		activeTab: 0,
		tabPosition: 'left',
		window:null,
		deferredRender:false,
		hideHeading:false
	});
	Ext.ux.Settings.superclass.constructor.call(this,newConfig);
}
Ext.extend(Ext.ux.Settings, Ext.ux.VerticalTabPanel,{	
	afterRender:function(){		
		Ext.ux.Settings.superclass.afterRender.call(this);		
		var el = this.getEl().dom;
		var headPanelDiv = Ext.DomHelper.insertFirst(el,{tag:'div'});
		if(!this.hideHeading)
		this.headPanel = new Ext.Panel({
			renderTo:headPanelDiv,
			frame:true,
			html:'<div float:left; width:100%><span style="font-size:15px;float:left;margin-bottom:5px;font-weight:bold">'+this.title+'</span>'+
				 '<span style="float:right">'+(this.user?'User: '+this.user:'')+'</span></div>'+
				 '<div style="clear:both; width:100%; padding-bottom:5px;">'+this.description+'</div>'
		});	
	}
});
Ext.reg("settings",Ext.ux.Settings);

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/settings/Ext.ux.Settings.js  ]*/

/*!
 * Ext JS Library 3.3.0
 * Copyright(c) 2006-2010 Ext JS, Inc.
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
/**
 * @class Ext.ux.GMapPanel
 * @extends Ext.Panel
 * @author Shea Frederick
 */
Ext.ux.GMapPanel = Ext.extend(Ext.Panel, {
    initComponent : function(){
        
        var defConfig = {
            plain: true,
            zoomLevel: 3,
            yaw: 180,
            pitch: 0,
            zoom: 0,
            gmapType: 'map',
            border: false
        };
        
        Ext.applyIf(this,defConfig);
        
        Ext.ux.GMapPanel.superclass.initComponent.call(this);        

    },
    afterRender : function(){
        
        var wh = this.ownerCt.getSize();
        Ext.applyIf(this, wh);
        
        Ext.ux.GMapPanel.superclass.afterRender.call(this);    
        
        if (this.gmapType === 'map'){
            this.gmap = new GMap2(this.body.dom);
        }
        
        if (this.gmapType === 'panorama'){
            this.gmap = new GStreetviewPanorama(this.body.dom);
        }
        
        if (typeof this.addControl == 'object' && this.gmapType === 'map') {
            this.gmap.addControl(this.addControl);
        }
        
        if (typeof this.setCenter === 'object') {
            if (typeof this.setCenter.geoCodeAddr === 'string'){
                this.geoCodeLookup(this.setCenter.geoCodeAddr);
            }else{
                if (this.gmapType === 'map'){
                    var point = new GLatLng(this.setCenter.lat,this.setCenter.lng);
                    this.gmap.setCenter(point, this.zoomLevel);    
                }
                if (typeof this.setCenter.marker === 'object' && typeof point === 'object'){
                    this.addMarker(point,this.setCenter.marker,this.setCenter.marker.clear);
                }
            }
            if (this.gmapType === 'panorama'){
                this.gmap.setLocationAndPOV(new GLatLng(this.setCenter.lat,this.setCenter.lng), {yaw: this.yaw, pitch: this.pitch, zoom: this.zoom});
            }
        }

        GEvent.bind(this.gmap, 'load', this, function(){
            this.onMapReady();
        });

    },
    onMapReady : function(){
        this.addMarkers(this.markers);
        this.addMapControls();
        this.addOptions();  
    },
    onResize : function(w, h){

        if (typeof this.getMap() == 'object') {
            this.gmap.checkResize();
        }
        
        Ext.ux.GMapPanel.superclass.onResize.call(this, w, h);

    },
    setSize : function(width, height, animate){
        
        if (typeof this.getMap() == 'object') {
            this.gmap.checkResize();
        }
        
        Ext.ux.GMapPanel.superclass.setSize.call(this, width, height, animate);
        
    },
    getMap : function(){
        
        return this.gmap;
        
    },
    getCenter : function(){
        
        return this.getMap().getCenter();
        
    },
    getCenterLatLng : function(){
        
        var ll = this.getCenter();
        return {lat: ll.lat(), lng: ll.lng()};
        
    },
    addMarkers : function(markers) {
        
        if (Ext.isArray(markers)){
            for (var i = 0; i < markers.length; i++) {
                var mkr_point = new GLatLng(markers[i].lat,markers[i].lng);
                this.addMarker(mkr_point,markers[i].marker,false,markers[i].setCenter, markers[i].listeners);
            }
        }
        
    },
    addMarker : function(point, marker, clear, center, listeners){
        
        Ext.applyIf(marker,G_DEFAULT_ICON);

        if (clear === true){
            this.getMap().clearOverlays();
        }
        if (center === true) {
            this.getMap().setCenter(point, this.zoomLevel);
        }

        var mark = new GMarker(point,marker);
        if (typeof listeners === 'object'){
            for (evt in listeners) {
                GEvent.bind(mark, evt, this, listeners[evt]);
            }
        }
        this.getMap().addOverlay(mark);

    },
    addMapControls : function(){
        
        if (this.gmapType === 'map') {
            if (Ext.isArray(this.mapControls)) {
                for(i=0;i<this.mapControls.length;i++){
                    this.addMapControl(this.mapControls[i]);
                }
            }else if(typeof this.mapControls === 'string'){
                this.addMapControl(this.mapControls);
            }else if(typeof this.mapControls === 'object'){
                this.getMap().addControl(this.mapControls);
            }
        }
        
    },
    addMapControl : function(mc){
        
        var mcf = window[mc];
        if (typeof mcf === 'function') {
            this.getMap().addControl(new mcf());
        }    
        
    },
    addOptions : function(){
        
        if (Ext.isArray(this.mapConfOpts)) {
            var mc;
            for(i=0;i<this.mapConfOpts.length;i++){
                this.addOption(this.mapConfOpts[i]);
            }
        }else if(typeof this.mapConfOpts === 'string'){
            this.addOption(this.mapConfOpts);
        }        
        
    },
    addOption : function(mc){
        
        var mcf = this.getMap()[mc];
        if (typeof mcf === 'function') {
            this.getMap()[mc]();
        }    
        
    },
    geoCodeLookup : function(addr) {
        
        this.geocoder = new GClientGeocoder();
        this.geocoder.getLocations(addr, this.addAddressToMap.createDelegate(this));
        
    },
    addAddressToMap : function(response) {
        
        if (!response || response.Status.code != 200) {
            Ext.MessageBox.alert('Error', 'Code '+response.Status.code+' Error Returned');
        }else{
            place = response.Placemark[0];
            addressinfo = place.AddressDetails;
            accuracy = addressinfo.Accuracy;
            if (accuracy === 0) {
                Ext.MessageBox.alert('Unable to Locate Address', 'Unable to Locate the Address you provided');
            }else{
                if (accuracy < 7) {
                    Ext.MessageBox.alert('Address Accuracy', 'The address provided has a low accuracy.<br><br>Level '+accuracy+' Accuracy (8 = Exact Match, 1 = Vague Match)');
                }else{
                    point = new GLatLng(place.Point.coordinates[1], place.Point.coordinates[0]);
                    if (typeof this.setCenter.marker === 'object' && typeof point === 'object'){
                        this.addMarker(point,this.setCenter.marker,this.setCenter.marker.clear,true, this.setCenter.listeners);
                    }
                }
            }
        }
        
    }
 
});

Ext.reg('gmappanel', Ext.ux.GMapPanel); 

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/window/Ext.ux.GMapPanel.js  ]*/

/*
 *  Dynamic widgets
 *  @author1: Prakash Paudel  
 *  @author2: Radu Topala
 */
Ext.ns('afApp');

function strstr (haystack, needle, bool) {   
    var pos = 0;    
    haystack += '';
    pos = haystack.indexOf( needle );
    if (pos == -1) {
        return false;
    } else{
        if (bool){
            return haystack.substr( 0, pos );
        } else{
            return haystack.slice( pos );
        }
        return true;
    }
}
function in_array (needle, haystack, argStrict) {   
    var key = '', strict = !!argStrict;
    if (strict) {
        for (key in haystack) {
            if (strstr(haystack[key],needle)) {
                return true;
            }
        }
    } else {
        for (key in haystack) {
        	
            if (strstr(haystack[key],needle)) {            	
                return true;
            }
        }
    }
    return false;
}

String.prototype.ucfirst = function () {
	   
    return this.substr(0,1).toUpperCase()+this.substr(1);
};

Array.prototype.in_array = function(needle, argStrict) {
	   
    var key = '', strict = !!argStrict, haystack = this;

    if (strict) {
        for (key in haystack) {
            if (haystack[key] === needle) {
                return true;
            }
        }
    } else {
        for (key in haystack) {
            if (haystack[key] == needle) {
                return true;
            }
        }
    }

    return false;
};

afApp.createAddon = function(filename, filetype, callback) {
	
	if (filename.indexOf('http://') != -1) {
		filename = afApp.urlPrefix + filename;
	}
	
	if (!filetype) {
		var f = filename.split('.');
		filetype = f[f.length-1];
	}
	
	//console.log(filename+":"+filetype);
	if (filetype == "js") { // if filename is a external JavaScript file
		var fileref = document.createElement('script');
		fileref.setAttribute("type", "text/javascript");
		fileref.setAttribute("src", filename);
		GLOBAL_JS_VAR.push(filename);
	} else if (filetype == "css") { // if filename is an external CSS file
		var fileref = document.createElement("link");
		fileref.setAttribute("rel", "stylesheet");
		fileref.setAttribute("type", "text/css");
		fileref.setAttribute("href", filename);
		GLOBAL_CSS_VAR.push(filename);
	}
	
	if (typeof fileref != "undefined") {
		document.getElementsByTagName("head")[0].appendChild(fileref);
	}
		
	if (filetype == "js") { // if filename is a external JavaScript file
		fileref.onload = fileref.onreadystatechange = function() {
			if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") 
			{
				callback();
			}
		}
	} else if (filetype == "css") { // if filename is an external CSS file
		callback();
	}	
};

/*
* Works only with App build in layout for real AF project
* @return boolean
*/
afApp.hasDesktop = function ()
{
	var has = false;
	
	try {
		if (App && App.desktop) {
			has = true;
		}
	} catch(e) {
		has = false;
	}

	return has;
};
/*
* Popup windows manager
*/
afApp.windows = new Ext.WindowGroup();
afApp.activeWindow;

afApp.minimizeWin = function (win) {
	win.minimized = true;
    win.hide();
}

afApp.markActive = function (win) {
	var widget = win.id;
    if (afApp.activeWindow && afApp.activeWindow != win) {
        afApp.markInactive(afApp.activeWindow);
    }
    if(afApp.hasDesktop())
    {
    	App.desktop.taskbar.setActiveButton(win.taskButton);
    	Ext.fly(win.taskButton.el).addClass('active-win');
    	
    	if(Ext.History.getToken()!=widget)
		{
			Ext.History.add(widget);
		}
    }
    afApp.activeWindow = win;
    win.minimized = false;
}

afApp.markInactive = function (win,Application) {
    if (win == afApp.activeWindow) {
        afApp.activeWindow = null;
        if(afApp.hasDesktop())
    	{
        	Ext.fly(win.taskButton.el).removeClass('active-win');
    	}
    }
}

afApp.removeWin = function(win) {
	if(afApp.hasDesktop())
    {
    	App.desktop.taskbar.removeTaskButton(win.taskButton);
    	afApp.layout();
    }
}

afApp.layout = function() {
	if(afApp.hasDesktop())
    {
    	var desktopEl = Ext.get('x-desktop');
    	var taskbarEl = Ext.get('ux-taskbar');
    	
    	desktopEl.setHeight(Ext.lib.Dom.getViewHeight() - taskbarEl.getHeight());
    }
}
    
//Ext.EventManager.onWindowResize(afApp.layout);

afApp.getWindow = function(id) {
	return afApp.windows.get(id);
};

/**
* pack logic for window
*/
afApp.pack = function(win,winConfig,Application){
	var winConfig = winConfig || {};
	Application = Application?Application : App; //App is default application for all Appflower apps
	var viewport;//if viewport exist then use it, else use page's body
	try{
		viewport=Application.getViewport();
	}
	catch (e)
	{
		viewport = Ext.get("body");
	}
	win.on("show",function(win){
		if(winConfig.applyTo) return;		
		var childs = win.findBy(function(component,container){
			return true;
		});
		if(childs && childs[0]){
			var firstChild = childs[0];
			var vpWidth = viewport.getBox().width;
			var vpHeight = viewport.getBox().height;
			var winWidth = firstChild.getBox().width+35;
			var winHeight = firstChild.getBox().height+35;
			winWidth = winWidth>vpWidth?(0.7*vpWidth):winWidth;
			winHeight = winHeight>vpHeight?(0.7*vpHeight):winHeight;
			win.setSize(winWidth,winHeight);
			win.center();
		}
		/*
		* By some reason if the window head moved out of 
		* viewport visibility range, bring back it.
		*/
		var pos = win.getPosition(); 
		if(pos[1]<0) win.setPosition(pos[0],0);
		
		/*
		* On window move, do not let the window to completely go out of 
		* viewport range, keep some portion of window always visible
		*/
		win.on("move",function(win,x,y){
			if(y<0) win.setPosition(x,0);
			if(x < 100-win.getWidth()) win.setPosition(100-win.getWidth(),y);
			if(x > Ext.getBody().getWidth()-100) win.setPosition(Ext.getBody().getWidth()-100,y);
			if(y > Ext.getBody().getHeight()-100) win.setPosition(x,Ext.getBody().getHeight()-100);
		});
	});
}

afApp.executeAddons = function(addons, json, title, superClass, winConfig, Application, widget) {
	//App is default application for all Appflower apps
	Application = Application?Application : App; 
	var maskEl; //used as maskElement, if viewport exist then use center, else use page's body
	try {
		var viewport = Application.getViewport();
		maskEl = viewport.layout.center.panel.getEl();
	} catch (e) {
		maskEl = Ext.get("body");
	}
	
	var counter = 0,
		backup = new Array(),
		finish;
		
	var load = function() {	
		if (counter >= addons.length) {
			finish();
			return;
		}
		afApp.loadingProgress(maskEl, (counter + 1) / addons.length);
		var nextAddon = addons[counter++];
		
		afApp.createAddon(nextAddon, false, load);
	};

	finish = function() {
		//backupForms();
		eval(json.source);	
		
		var backendWinConfig = eval(json.winConfig);
						
		var center_panel = (function(){ return eval(json.center_panel); })();
		
		Ext.apply(center_panel, {
			frame: winConfig.applyTo ? false : true
			,width: "auto"
//			,layout: "form"
		});
					
		Ext.applyIf(winConfig, {
			id: widget,
			autoScroll: true,
			minimizable: true,
			maximizable: true,
			draggable: true,					
			closeAction: 'close',
			manager: afApp.windows, // general popup windows manager								
			items: center_panel
		});
		
		Ext.apply(winConfig, backendWinConfig);
		
		if (winConfig.applyTo) {
			winConfig = Ext.apply(winConfig, {
				frame: false
			});
			var win = new Ext.Panel(winConfig);					
		} else {
			var win = new Ext.Window(winConfig);
		}
		
		if (title) {
			win.setTitle(title);
		}
		
		//win.dd.xTickSize = 1;
        //win.dd.yTickSize = 1;
        if (win.resizer) {
            win.resizer.widthIncrement = 1;
            win.resizer.heightIncrement = 1;
        }
		
		if (afApp.hasDesktop()) {
        	win.taskButton = Application.desktop.taskbar.addTaskButton(win);
        	win.animateTarget = win.taskButton.el;
        }
		
		win.on({
            'activate': {
                fn: afApp.markActive
            },
            'beforeshow': {
                fn: afApp.markActive
            },
            'deactivate': {
                fn: afApp.markInactive
            },
            'minimize': {
                fn: afApp.minimizeWin
            },
            'close': {
                fn: afApp.removeWin
            },
            'resize': {
            	fn: function(){/*console.log('x');*/}
            }
        });
		
        //afApp.layout();
        
		if (win.doLayout) {
			win.doLayout();
		}
		if (win.show) {
			win.show();
		}
		
		/* window resize, pack and onmove adjustments */
		afApp.pack(win, winConfig, Application);
						
		if (win.doLayout) {
			win.doLayout();
		}
		if (win.show) {
			win.show();				
		}
		if (win.center) {
			win.center();
		}
		win.on("render", function(win){ eval(json.public_source); }, null, {single:true});
		
		afApp.loadingProgress(maskEl, 1);
		
		win.on("hide",function(){	
			if (superClass) {
				superClass.onHide(win);									
			}
			//win.destroy();
			//win.close();
			//restoreBackup();
		});		        
	};

	/*function restoreBackup(){
		for(id in backup){
			var el = document.getElementById(id);
			if(el){
				el.id = backup[id]
			}
		}
	}
	function backupForms(comp){		
		var randomnumber=Math.floor(Math.random()*11);
		var randomId = "x-form-el-random-"+randomnumber;
		var inputs = document.getElementsByTagName("input");
		var textareas = document.getElementsByTagName("textarea");
		var selects = document.getElementsByTagName("select");
		
		var arr = new Array();
		arr.push(inputs);
		arr.push(textareas);
		arr.push(selects);
		for(var j=0;j<arr.length;j++){
			var forms = arr[j];
			for(var i=0;i<forms.length;i++){
				if(forms[i].id){
					if(forms[i].id.match("edit")){					
						var el = document.getElementById("x-form-el-"+forms[i].id);							
						if(el){ 
							backup[randomId+"-"+i] = el.id;
							el.id = randomId+"-"+i;
						}						
					}
				}
			}
		}
		
	}*/

	load();
};

afApp.widgetPopup = function(widget, title, superClass, winConfig, Application) {
	//App is default application for all Appflower apps
	Application = Application ? Application : App;
	
	var maskEl; //used as maskElement, if viewport exist then use center, else use page's body
	try {
		var viewport = Application.getViewport();
		maskEl = viewport.layout.center.panel.getEl();
	} catch (e) {
		maskEl = Ext.get("body");
	}
	
	if (!winConfig) {
		var winConfig = {};
		winConfig.width = 800;
		winConfig.height = 500;
	} else {
		winConfig = eval('({' + unescape(winConfig) + '});');		
		winConfig.width = winConfig.width ? winConfig.width : 800;
		winConfig.height = winConfig.height ? winConfig.height : 500;
	}
	
	var getWidgetText = function(widget) {
		if (widget.length > 45) {
			return widget.substring(0, 20) + "...." + widget.substring(widget.length - 20, widget.length);
		}
		
		return widget;
	};
	
	widget = widget.replace(document.location.protocol + '//' + document.location.host + afApp.urlPrefix, '');
	var uri = widget.split('#');
	uri[0] = uri[0] || '/';
	var futureTab = uri[1] ? '#' + uri[1] : '';
	//hash contains the value without #in front of the internal link
	var futureHash = uri[0] + futureTab;
	
	afApp.currentWidget = uri[0];
	afApp.observable.fireEvent('beforeload', uri[0]);
				
	var win = afApp.getWindow(widget);
	
	if (win) {
		win.show();
	} else {
		afApp.initLoadingProgress(maskEl);
	
		var ajax = Ext.Ajax.request({
			url: afApp.urlPrefix + uri[0],
			method: "GET",
			params: {
				widget_popup_request : true
			},
			success: function(r) {
				var json = Ext.util.JSON.decode(r.responseText);
				
				if (json.redirect && json.message && json.load) {
					Ext.Msg.alert("Failure", json.message, function(){ afApp.load(json.redirect,json.load); });
				} else {
					var total_addons = new Array();
					
					if (json.addons && json.addons.js) {
						for (var i = 0; i < json.addons.js.length; i++) {
							var addon = json.addons.js[i];
							if (!in_array(addon, GLOBAL_JS_VAR) && addon != null) {
								total_addons.push(addon);
							}
						}
					}
					
					if (json.addons && json.addons.css) {
						for (var i = 0; i < json.addons.css.length; i++) {
							var addon = json.addons.css[i];
							if (!in_array(addon, GLOBAL_CSS_VAR) && addon != null) {
								total_addons.push(addon);
							}
						}
					}
					
					//adding a referer param to all Ajax request in Ext objects
					Ext.Ajax.extraParams = Ext.Ajax.extraParams || {};
					Ext.Ajax.extraParams['af_referer'] = futureHash;
					
					afApp.executeAddons(total_addons, json, title, superClass, winConfig, Application, widget);		
				}
			}
		});	
	}
};//eo widgetPopup

// <a/> tags with widgetLoad CSS class will be loaded inside the center panel.
afApp.attachHrefWidgetLoad = (function () {
	var listener = function(e) {
		e.stopEvent();
		
		var el = Ext.get(e.getTarget());	

		var href = el.dom.href || el.dom.parentNode.href;
		 
		afApp.load(href);
	};

	return function() {
		//remove the listener before adding, because it might add the same listener multiple times
		var internalUrls = Ext.select('a.widgetLoad');
		internalUrls.un('click', listener);
		internalUrls.on('click', listener);
	};
})();

afApp.initLoadingProgress = function(el){	
	el.mask();
	var pb   = Ext.getCmp("progress-bar"),	
		pbEl = Ext.get('progress-bar-el');
	
	if (!pbEl) {	
		pbEl = Ext.DomHelper.append(el, {
			tag: 'div',
			id: 'progress-bar-el',
			style: 'z-index:1000; position:absolute; top:40%; left:40%; width:20%'
		});
	}
	
	if (!pb) {
		pb = new Ext.ProgressBar({
			id: 'progress-bar',
			text: "Loading.... Please wait....."
		});
		pb.render(pbEl);
	} else {
		pb.updateProgress(0, "Loading.... Please wait.....");
		pb.show();
	}
};

afApp.loadingProgress = function(el, percent) {	
	var pb = Ext.getCmp("progress-bar");
	pb.updateProgress(percent, Math.ceil(percent * 100) + "% complete...");
	if (!pb.isVisible()) {
		pb.show();
	}
	if (percent >= 1) {
		el.unmask();
		setTimeout(function(){
			pb.hide();
		}, 500);
	}
};

afApp.executeAddonsLoadCenterWidget = function(viewport, addons, json) {
	var pb,
		counter = 0,
		finish;
		
	var load = function() {	
		if (counter >= addons.length) {
			finish();
			return;
		}
		
		if (!Ext.getCmp("progress-bar")) {
			pb = new Ext.ProgressBar();		
		} else {
			pb = Ext.getCmp('progress-bar');
		}
		afApp.loadingProgress(viewport.layout.center.panel.getEl(), (counter + 1) / addons.length);
		var nextAddon = addons[counter++];
			
		afApp.createAddon(nextAddon, false, load);
	};

	finish = function(){
		eval(json.source);				
		
		var panel = viewport.layout.center.panel;
		panel.add(eval(json.center_panel_first));
		
		//radu: remove the first added div after addition, workaround for extjs 3.3.0
		var divs = Ext.query('div[id=center_panel_first]');
		if(divs[0]){Ext.fly(divs[0]).remove();}
		
		//if (window.console) { console.time('doLayout'); }
		panel.doLayout();
		//if (window.console) { console.timeEnd('doLayout'); }
		afApp.loadingProgress(viewport.layout.center.panel.getEl(), 1);
	};
	
	load();
};

afApp.loadCenterWidget = function(widget) {
	
	widget = widget.replace(document.location.protocol + '//' + document.location.host + afApp.urlPrefix, '');
	var uri = widget.split('#');
	uri[0] = uri[0] || '/';
	var futureTab = uri[1] ? '#' + uri[1] : '';
	//hash contains the value without #in front of the internal link
	var futureHash = uri[0] + futureTab;
		
	afApp.currentWidget = uri[0];
	afApp.observable.fireEvent('beforeload', uri[0]);	
	
	var viewport = App.getViewport();
	afApp.initLoadingProgress(viewport.layout.center.panel.getEl());
	var ajax = Ext.Ajax.request( {
		url: afApp.urlPrefix + uri[0],
		method: "GET",		
		params : {
			widget_load : true
		},
		success: function(r) {
			var json = Ext.util.JSON.decode(r.responseText);
			json.load = json.load ? json.load : 'center';
			json.title = json.title ? json.title : '...';
							
			if (json.success === false) {
				Ext.Msg.alert('Failure', json.message);
				return;
			}

			if(json.redirect)
			{
				afApp.loadingProgress(viewport.layout.center.panel.getEl(),1);
												
				if(json.message)
				{
					Ext.Msg.alert(json.title, json.message, function(){
						afApp.load(json.redirect,json.load);
					});
				}
				else
				{
					afApp.load(json.redirect,json.load);
				}
			}
			else
			{				
				var total_addons = new Array();
				
				if(json.addons && json.addons.js)
				{
					for ( var i = 0; i < json.addons.js.length; i++) {
						var addon = json.addons.js[i];
						if(!in_array(addon,GLOBAL_JS_VAR)){
							if(addon != null)
							total_addons.push(addon);	
						}
					}
				}
				if(json.addons && json.addons.css)
				{
					for ( var i = 0; i < json.addons.css.length; i++) {
						var addon = json.addons.css[i];
						if(!in_array(addon,GLOBAL_CSS_VAR)){
							if(addon != null)
							total_addons.push(addon);
						}
					}
				}
										
				//adding a referer param to all Ajax request in Ext objects
				Ext.Ajax.extraParams = Ext.Ajax.extraParams || {};
				Ext.Ajax.extraParams['af_referer'] = futureHash;
				
				afApp.executeAddonsLoadCenterWidget(viewport, total_addons, json);	
			}				
			
			if(json.executeAfter)
			{
				eval(json.executeAfter);
			}
		},
		failure : function(response) {
			var msg =  'Unable to load the content: ' +
				response.status + ' ' + response.statusText;
			Ext.Msg.alert('Failure', msg);
		}
	});
};

afApp.logTime = function (msg) {
	var today=new Date();
	
	var day=today.getDate();
	var month = today.getMonth();
	var year = today.getFullYear();
	var h=today.getHours();
	var m=today.getMinutes();
	var s=today.getSeconds();
	
	if(console)console.log('[',msg,']',day,'/',month,'/',year,' ',h,':',m,':',s,' | ',today.getTime());
}
/*
* reloads the grids data inside a portal page
*
* example of usage: afApp.reloadGridsData(['ticket/list','module/action',etc..]);
*/
afApp.reloadGridsData = function (idXmls)
{	
	var portals=new Array();
	
	var center_panel_first_portal=Ext.getCmp('center_panel_first_portal');
	if(center_panel_first_portal)
	{
		if(center_panel_first_portal.layoutType=='NORMAL')
		{
			portals[0]=center_panel_first_portal;
		}
		else if(center_panel_first_portal.layoutType=='TABBED')
		{
			for(var i = 0; i < center_panel_first_portal.items.items.length; i++) {
				portals[i]=center_panel_first_portal.items.items[i].items.items[0];
			}
		}
		
		for(var i=0; i<portals.length; i++)
		{
			var col;
	        for(var c = 0; c < portals[i].items.getCount(); c++) {
	            col = portals[i].items.get(c); 	            
	            if(col.items) {
	                for(var s = 0; s < col.items.getCount(); s++) {
	                	var widget=col.items.get(s);
	                	if(idXmls.in_array(widget.idxml)&&widget.getEl()&&widget.getXType().toLowerCase().indexOf('grid')!=-1)
	                	{
	                		widget.store.reload();
	                	}
	                }
	            }
	        }
		}
	}
}
/**
* page/widget loader
*/
afApp.load = function (location, load, target, winProp)
{	
	if(location=='/false'||!location)return false;
	
	load = load || 'center';
	target = target || '_self';
	winProp = winProp || null;
	if(winProp && winProp.isPopup && !winProp.forceRedirect){ return false;}	
	if(target!='_self')
	{
		load='page';
	}
	
	if(location!='')
	{
		switch(load)
		{
			case "page":
				window.open(location,target,winProp);
				break;
			case "center":
				location=location.replace(document.location.protocol+'//'+document.location.host,'');
												
				//Ext History, also loads center widget if last loken is different from current one
				if(Ext.History.getToken()!=location)
				{					
					Ext.History.add(location);
				}
				else
				{
					if(!afApp.hasDesktop())
					{
						afApp.loadCenterWidget(location);
					}
					else
					{
						afApp.widgetPopup(location);
					}
				}
			    break;
		}
	}
}
afApp.loadPopupHelp = function(widget) {
	
	var viewport=App.getViewport();
	var mask = new Ext.LoadMask(viewport.layout.center.panel.getEl(), {msg: "<b>Loading help</b> <br>Please Wait...",removeMask:true});
	mask.show();
	var ajax = Ext.Ajax.request( {
		url : afApp.urlPrefix + '/appFlower/popupHelp?idXml='+widget,
		method : "GET",		
		success : function(r) {
			var json = Ext.util.JSON.decode(r.responseText);
			
			if(json.redirect&&json.message)
			{
				mask.hide();
				
				Ext.Msg.alert("Failure", json.message, function(){window.location.href=json.redirect;});
			}
			else
			{			
				Ext.applyIf(json.winConfig, {
					autoScroll : true,
					maximizable : true,
					draggable:true,					
					closeAction:'hide',
					html : json.html
				});
				
				var win = new Ext.Window( json.winConfig );
				
				win.on("show",function(win){var pos = win.getPosition(); if(pos[1]<0) win.setPosition(pos[0],0);});
				
				win.doLayout()
				win.show();				
				
				win.on("move",function(win,x,y){
					if(y<0) win.setPosition(x,0);
					if(x < 100-win.getWidth()) win.setPosition(100-win.getWidth(),y);
					if(x > Ext.getBody().getWidth()-100) win.setPosition(Ext.getBody().getWidth()-100,y);
					if(y > Ext.getBody().getHeight()-100) win.setPosition(x,Ext.getBody().getHeight()-100);
				});
				
				mask.hide();
			}				
		}
	});
}
afApp.changeTabHash = function(tab)
{
	var uri=document.location.href.split('#');
	uri[1]=uri[1] || '/';
	uri[2]=tab.slug;
	
	var futureHash=uri[1]+'#'+uri[2];

	//Ext History, also loads center widget if last loken is different from current one
	if(Ext.History.getToken()!=futureHash)
	{
    	Ext.History.add(futureHash);
	}
		
	//adding a referer param to all Ajax request in Ext objects
	Ext.Ajax.extraParams = Ext.Ajax.extraParams || {};
	Ext.Ajax.extraParams['af_referer'] = futureHash;
}
/**
* load first request made to browser directly
*
* @param hasDesktop - tells if App will load desktop template
*/
afApp.loadFirst = function(hasDesktop)
{
	hasDesktop = hasDesktop || false;
	
	var uri=document.location.href.split('#');
	uri[1]=uri[1] || '/';
	uri[2]=uri[2]?'#'+uri[2]:'';
	
	var firstUri=uri[1]+uri[2];
	
	if(!hasDesktop)
	{
		//load the toolbar inside the north panel, after rendering of layout, because of QuickTips bug
		var north = App.getNorth();
		var toolbar = App.getToolbar();
	
		north.add(toolbar);
		north.doLayout();
		
		afApp.loadCenterWidget(firstUri);
	}
	else
	{
		afApp.widgetPopup(firstUri);
	}
	/**
	* checking if firebug is on
	*/
	if(Boolean(window.console&&window.console.firebug))
	{
		var config = {title: 'Firebug is on :(', message: 'If you would like to have a better experience with our products, please disable <b style="color:red;">Firebug</b>. You can do this using this shortcut: <b>SHIFT+F12</b>.<br><br>Thank you,<br>AppFlower Team', type: 'ERROR', duration: 20};	
	
		if(hasDesktop)
		{
			config.heightPlus = 30;
		}
		
		new Ext.ux.InstantNotification(config);
	}
}

Ext.onReady(function(){

	afApp.attachHrefWidgetLoad();

});


//Ext History
Ext.History.on('change', function(token){
	//do not load the center/popup widget if we are changing tabs
	if(token)
	{
		var tokenS=token.split('#');
		
		if(!afApp.hasDesktop())
		{
			if(afApp.currentWidget!=tokenS[0])
			{
				afApp.loadCenterWidget(token);
			}
			//this means that the center contains tabs
			else if(tokenS[1]){
				var viewport=App.getViewport();
				var tabPanel=viewport.layout.center.panel.items.items[0].items.items[0];
				if(tabPanel.getXType()=='tabpanel')
				{
					new Portals().onTabChange(tabPanel, tokenS);
				}
			}
		}
		else
		{
			if(afApp.currentWidget!=tokenS[0])
			{
				afApp.widgetPopup(token);
			}
			//this means that the popup contains tabs
			else if(tokenS[1]){
				var tabPanel=afApp.activeWindow.items.items[0].items.items[0];
				if(tabPanel.getXType()=='tabpanel')
				{
					new Portals().onTabChange(tabPanel, tokenS);
				}
			}
		}
	}
});
//used for triggering loading of latest content for some west panel item
afApp.loadWestWidget = function(widget)
{
	var viewport=App.getViewport();
	var westItems=viewport.layout.west.items;
	var panelItems=viewport.layout.west.panel.items.items;
	
	for(var i=0;i<westItems.length;i++){
		if(westItems[i].id == widget)
		{
			var westItem=westItems[i];
			var panelItem=panelItems[i];

			if(westItem.loadClass&&westItem.loadMethod)
			{
				var mask = new Ext.LoadMask(panelItem.getEl(), {msg: "<b>Loading</b> <br>Please Wait...",removeMask:true});
				mask.show();
				var ajax = Ext.Ajax.request( {
					url : afApp.urlPrefix + '/appFlower/loadWestContent',
					method : "POST",		
					success : function(r) {
						var response = Ext.util.JSON.decode(r.responseText);
						
						if(response.title)
						panelItem.setTitle(response.title);
						
						if(response.html)
						panelItem.body.dom.innerHTML=response.html;
						
						mask.hide();
					},
					params : {
						loadClass : westItem.loadClass,
						loadMethod : westItem.loadMethod
					}
				});
			}
		}
	}
}
//used to set/get current loaded widget
afApp.currentWidget = false;
afApp.observable = new Ext.util.Observable();

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/js/custom/widgetJS.js  ]*/

Ext.override(Ext.layout.BorderLayout, {

        southTitleAdded  : false,

            // private

        onLayout : function(ct, target){



            var collapsed;

            if(!this.rendered){

                

                target.position();

                target.addClass('x-border-layout-ct');

                var items = ct.items.items;

                collapsed = [];

                for(var i = 0, len = items.length; i < len; i++) {

                    var c = items[i];

                    var pos = c.region;

                    if(c.collapsed){

                        collapsed.push(c);

                    }

                    c.collapsed = false;

                    if(!c.rendered){

                        c.cls = c.cls ? c.cls +' x-border-panel' : 'x-border-panel';

                        c.render(target, i);

                    }

                    this[pos] = pos != 'center' && c.split ?

                        new Ext.layout.BorderLayout.SplitRegion(this, c.initialConfig, pos) :

                        new Ext.layout.BorderLayout.Region(this, c.initialConfig, pos);

                    this[pos].render(target, c);

                }

                this.rendered = true;

            }

    

            var size = target.getViewSize();

            if(size.width < 20 || size.height < 20){ // display none?

                if(collapsed){

                    this.restoreCollapsed = collapsed;

                }

                return;

            }else if(this.restoreCollapsed){

                collapsed = this.restoreCollapsed;

                delete this.restoreCollapsed;

            }

    

            var w = size.width, h = size.height;

            var centerW = w, centerH = h, centerY = 0, centerX = 0;

    

            var n = this.north, s = this.south, west = this.west, e = this.east, c = this.center;

            if(!c){

                throw 'No center region defined in BorderLayout ' + ct.id;

            }

			

            if(n && n.isVisible()){

                var b = n.getSize();

                var m = n.getMargins();

                b.width = w - (m.left+m.right);

                b.x = m.left;

                b.y = m.top;

                centerY = b.height + b.y + m.bottom;

                centerH -= centerY;

                n.applyLayout(b);

            }

            if(s && s.isVisible()){
				
                var b = s.getSize();

                var m = s.getMargins();

                b.width = w - (m.left+m.right);

                b.x = m.left;

                var totalHeight = (b.height + m.top + m.bottom);

                b.y = h - totalHeight + m.top;

                centerH -= totalHeight;

                s.applyLayout(b);


                
                //new config options for south region:

                //  collapsedTitle        : 'string'

                //  collapsedTitleCls    :  'string'

                //  collapsedTitleStyle :  'string'

                if (typeof s.collapsedEl != 'undefined' && s.collapsedTitle && this.southTitleAdded == false) {

                    this.southTitleAdded = true;

                    var cDiv = s.collapsedEl;

                    var tpl  = new Ext.Template('<div style="float: left;">{txt}</div>');

                    var insertedHtml = tpl.insertFirst(cDiv,{ txt : s.collapsedTitle });

                    if (s.collapsedTitleStyle) {

                        cDiv.applyStyles(s.collapsedTitleStyle);

                    }                    

                    if (s.collapsedTitleCls) {

                        Ext.get(insertedHtml).addClass(s.collapsedTitleCls);

                    }
                }                

            }

            if(west && west.isVisible()){

                var b = west.getSize();

                var m = west.getMargins();

                b.height = centerH - (m.top+m.bottom);

                b.x = m.left;

                b.y = centerY + m.top;

                var totalWidth = (b.width + m.left + m.right);

                centerX += totalWidth;

                centerW -= totalWidth;

                west.applyLayout(b);

            }

            if(e && e.isVisible()){

                var b = e.getSize();

                var m = e.getMargins();

                b.height = centerH - (m.top+m.bottom);

                var totalWidth = (b.width + m.left + m.right);

                b.x = w - totalWidth + m.left;

                b.y = centerY + m.top;

                centerW -= totalWidth;

                e.applyLayout(b);

            }

    

            var m = c.getMargins();

            var centerBox = {

                x: centerX + m.left,

                y: centerY + m.top,

                width: centerW - (m.left+m.right),

                height: centerH - (m.top+m.bottom)

            };

            c.applyLayout(centerBox);

    

            if(collapsed){

                for(var i = 0, len = collapsed.length; i < len; i++){

                    collapsed[i].collapse(false);

                }

            }    

            if(Ext.isIE && Ext.isStrict){ // workaround IE strict repainting issue

                target.repaint();

            }

        }

});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/js/custom/BorderLayoutOverride.js  ]*/

var gridUtil = function(grid,config){
	config = Ext.decode(config);
	var proceed = function(){
		if(config.is_ajax){
			if(config.url == "#") return;
			Ext.Ajax.request({
				url: config.url,
				params:config.params,
				success: function(response){
					if(config.onsuccess == "RELOAD"){
						grid.getStore().reload();
						if(grid.getSelectionModel){
							var sm = grid.getSelectionModel();
							sm.clearSelections();
						}
					}
					if(config.onsuccess == "REDIRECT"){
						window.location.href = config.redirect;
					}
				}
			})
		}else{		
			window.location.href = config.url
		}
	}
		
	if(!grid){alert("Component not found"); return;}
	
	if(config.confirmMsg){
		Ext.Msg.show({
			title:'Confirmation required.',
			msg: config.confirmMsg,
			buttons: Ext.Msg.YESNOCANCEL,
			fn: function(btn,text){
				if (btn == 'yes'){
			        proceed();
			    }
			},
			icon: Ext.MessageBox.QUESTION
		})
	}else{
		proceed();
	}
	
}

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/js/custom/gridUtil.js  ]*/

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

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/js/custom/keyMaps.js  ]*/

/*
 *  Override for Ext.data.SortTypes
 *  @author: Prakash Paudel
 *  
 */
Ext.apply(Ext.data.SortTypes,{
	asText: function(s){
		return Ext.data.SortTypes.asUCText(s);
	},
	htmlAsInt : function(s) {		
		return Ext.data.SortTypes.asInt(Ext.data.SortTypes.asUCText(s));		
	},
	htmlAsFloat : function(s) {		
		return Ext.data.SortTypes.asFloat(Ext.data.SortTypes.asUCText(s));
	},
	htmlAsText: function(s){
		var re= /<\S[^>]*>/gi; 
		return String(s).replace(re,""); 
	},
	asIp: function(s){		
		var parts = String(s).split(".");
		var ret = '';
		for(var i=0;i<parts.length;i++){
			var k = 3-parts[i].length;
			for(var j=0;j<k;j++){
				ret += "0";
			}
			ret += parts[i];
		}		
		return Ext.data.SortTypes.asText(ret==''?s:ret);
	},
	htmlAsIp: function(s){
		return Ext.data.SortTypes.asIp(Ext.data.SortTypes.asUCText(s));
	},
	asSize: function(s){
		s = s.toUpperCase();
		var str = s.match(/([a-zA-Z\s]+)/)
		if(str) str = str[0].replace(/^\s*/, "").replace(/\s*$/, "");
		var factor = 1;
		var patterns = [
            [/^BYTE/,/^KILO/,/^MEGA/,/^GIGA/,/^TERA/,/^PETA/,/^EXA/,/^ZETTA/,/^YOTTA/],
            [/^B/,/^KB/,/^MB/,/^GB/,/^TB/,/^PB/,/^EB/,/^ZB/,/^YB/],
            [/^B/,/^K/,/^M/,/^G/,/^T/,/^P/,/^E/,/^Z/,/^Y/]
		];			
		for(var i=0;i<patterns.length;i++){
			for(var j=0;j<patterns[i].length;j++){
				if(patterns[i][j].test(str)){
					factor = 1024;
					factor = Math.pow(factor,j);
					break;
				}
			}
		}
		return parseFloat(s)*factor;
	},
	htmlAsSize: function(s){
		return Ext.data.SortTypes.asSize(Ext.data.SortTypes.asUCText(s));
	},
	maskAsIp: function(s){
		var pattern = /\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b/;
		var ip = s.match(pattern);
		if(!ip){			
			return;
		}
		return Ext.data.SortTypes.htmlAsIp(ip[0]);
	}
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/overrides/Override.Ext.data.SortTypes.js  ]*/

/*
 *  Override for form fields
 *  @author: Prakash Paudel
 *  
 */

/**
 * Fix for the checkbox focus visible
 */
Ext.override(Ext.form.Checkbox, {
  onFocus: function(){
    var wrap = this.wrap;
    if(!wrap) return
    wrap.setStyle("float","left")
    wrap.setStyle("height","auto")
    wrap.setStyle("border","1px solid #7eadd9")
    Ext.DomHelper.insertAfter(wrap,{tag:'div',style:'clear:both'})
    
  },
  onBlur: function(){
    var wrap = this.wrap;
    if(!wrap) return
    wrap.setStyle("border","0px solid #7eadd9")
  }
});

/**
 * Fix for the radio focus visible
 */
Ext.override(Ext.form.Radio, {
  onFocus: function(){    
  },
  onBlur: function(){
  }
});


/**
 * Fix for the button focus visible, looks like mouse overed when focused
 */
Ext.override(Ext.Button, {
    onFocus: function(){  
    this.addClass("x-btn-over")
    },
    onBlur: function(){
      this.removeClass("x-btn-over")
    }
});

/**
 * Fix for enter key press form submit
 */
Ext.override(Ext.form.Field,{
  fireKeys : function(e) {    
      if(((Ext.isIE && e.type == 'keydown') || e.type == 'keypress') && e.isSpecialKey()) {
        if(e.getKey() == e.ENTER){
          if(this.getXType() != "textarea" && this.getXType() != "superboxselect"){
            var form = this.findParentByType('form');
            //this.fireEvent("specialkey",this);
            if(form){           
              Ext.each(form.buttons,function(button){           
                if(button.url && button.url == form.url){             
                  button.handler.call(button.scope);
                }
              })            
            }
          }
        }
      }     
  },
  initEvents : function() {   
      this.el.on("keydown", this.fireKeys, this);
      this.el.on("keypress", this.fireKeys, this);
      this.el.on("keyup", this.fireKeys, this);
      this.el.on("focus", this.onFocus, this);
      this.el.on("blur", this.onBlur, this);
  }
});

/**
 * The textfield shows 10.00 as 10, but 10.01 as 10.01. We need 10.00 to be 10.00 not 10
 * Fix for http://tickets.appflower.com/ticket/editView?id=153
 * @author: Prakash Paudel
 */
Ext.override(Ext.form.TextField,{
  initValue : function(){
    if(this.value){     
      this.value = this.value.toString().trim();
    }
      if(this.value !== undefined){
          this.setValue(this.value);
      }else if(!Ext.isEmpty(this.el.dom.value) && this.el.dom.value != this.emptyText){
          this.setValue(this.el.dom.value);
      }
      
      /**
       * The original value of the field as configured in the {@link #value} configuration, or
       * as loaded by the last form load operation if the form's {@link Ext.form.BasicForm#trackResetOnLoad trackResetOnLoad}
       * setting is true.
       * @type mixed
       * @property originalValue
       */
      this.originalValue = this.getValue();
  },
  preFocus : function(){
    var el = this.el,isEmpty;
    if(this.emptyText){
      if(el.dom.value==this.emptyText){
        this.setRawValue('');
        isEmpty=true;
      }
      el.removeClass(this.emptyClass);
    }
    if(this.SelectOnFocus || isEmpty){
      el.dom.select();
    }
    
    if(this.PasswordFocus){
      el.dom.setAttribute('type','password');
    }
  },
  beforeBlur : function(){
    if(this.PasswordFocus && this.getValue()==""){
      this.el.dom.setAttribute('type','text');
      this.reset();
    }
  }
});

/**
* Override for error markup on HtmlEditor
*
* @author radu
*/
Ext.override(Ext.form.HtmlEditor, {
    markInvalid: function(msg){
        if(!this.rendered || this.preventMark){
            return;
        }
        msg = msg || this.invalidText;
        
        switch(this.msgTarget){
            case 'qtip':
              this.iframe.qtip = msg;
                this.iframe.qclass = 'x-form-invalid-tip';
                Ext.get(this.iframe).addClass(this.invalidClass);
                break;
            case 'side':
              Ext.get(this.iframe).addClass(this.invalidClass);
              
              this.errorDiv=Ext.DomHelper.append(this.wrap,{tag:'div',style:'top:0px;left:'+(this.wrap.getWidth()+85)+'px;z-index:1000;position:absolute;visibility:visible;',cls:'x-form-invalid-icon',qtip:msg,qclass:'x-form-invalid-tip'});             
              break;
        }
        return Ext.form.TextArea.superclass.markInvalid.call(this, [msg]);
    },
    clearInvalid: function(){
        if(!this.rendered || this.preventMark){
            return;
        }
        switch(this.msgTarget){
            case 'qtip':
                this.iframe.qtip = '';
                Ext.get(this.iframe).removeClass(this.invalidClass);
                break;
            case 'side':
              if(this.errorDiv)
              {               
                  Ext.get(this.errorDiv).remove();
              }
                Ext.get(this.iframe).removeClass(this.invalidClass);
                break;
        }
        return Ext.form.TextArea.superclass.clearInvalid.call(this);
    }
});


/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/overrides/Override.Ext.form.Field.js  ]*/

/**
 * author: Prakash Paudel
 * Override methods for custom requirements..
 */
Ext.lib.Event.resolveTextNode = Ext.isGecko ? function(node){
	if(!node){
		return;
	}
	var s = HTMLElement.prototype.toString.call(node);
	if(s == '[xpconnect wrapped native prototype]' || s == '[object XULElement]'){
		return;
	}
	return node.nodeType == 3 ? node.parentNode : node;
} : function(node){
	return node && node.nodeType == 3 ? node.parentNode : node;
};

Ext.override(Ext.ToolTip,{
	onMouseMove : function(e){		
		var x = e.getPageX(),y = e.getPageY();
		var t = this.delegate ? e.getTarget(this.delegate) : this.triggerElement = true;
	    if (t) {
	        this.targetXY = e.getXY();	        
	        if (t === this.triggerElement) {
	            if(!this.hidden && this.trackMouse){
	            	var box = this.getBox();
	    	    	if(box.x+box.width > Ext.getBody().getWidth()) {
	    	    		x = Ext.getBody().getWidth()-(box.width+10);
	    	    		this.targetXY = [x,y];
	    	    	}
	    	    	this.setPagePosition(this.getTargetXY());
	            }
	        } else {
	            this.hide();
	            this.lastActive = new Date(0);
	            this.onTargetOver(e);
	        }
	    } else if (!this.closable && this.isVisible()) {
	        this.hide();
	    }
	}
});

Ext.override(Ext.Panel, {
	setIconClass : function(cls){	
		var old = this.iconCls;
		this.iconCls = cls;		
		if(this.rendered && this.header){
			/**
			* Skip frame check to fix window icon issue...
			*
			if(this.frame){
				this.header.addClass('x-panel-icon');
				this.header.replaceClass(old, this.iconCls);				
			}else*/
			{
				var hd = this.header.dom;				
				var img = hd.firstChild && String(hd.firstChild.tagName).toLowerCase() == 'img' ? hd.firstChild : null;				
				if(img){
					Ext.fly(img).replaceClass(old, this.iconCls);
				}else{
					Ext.DomHelper.insertBefore(hd.firstChild, {
						tag:'img', src: Ext.BLANK_IMAGE_URL, cls:'x-panel-inline-icon '+this.iconCls
					});
				 }
			}
		}
	}
});
Ext.override(Ext.TabPanel, {	
    initTab : function(item, index){
		var tt = new Ext.Template(
			'<li class="{cls}" id="{id}" style="overflow:hidden">',
			    '<tpl if="closable">',
			       '<a class="x-tab-strip-close"></a>',
			    '</tpl>',
			    '<a class="x-tab-right" href="#" style="padding-left:6px">',
			       '<em class="x-tab-left">',
			           '<span class="x-tab-strip-inner">',
			               '<img src="'+Ext.BLANK_IMAGE_URL+'" style="float:left;margin:3px 3px 0 0">',
			               '<span  class="x-tab-strip-text {iconCls}">{text} {extra}</span>',
			           '</span>',
			       '</em>',
			   '</a>',
		   '</li>'
		);
		tt.disableFormats = true;
		tt.compile();
		Ext.TabPanel.prototype.itemTpl = tt;
    	/**
    	 * Fixes for iconCls, icon with sprite image
    	 */
    	var src = Ext.BLANK_IMAGE_URL;
    	var iconCls = "";
    	
    	if(item.icon && item.icon != ''){
    		src = item.icon;    		
    	}
    	if(item.iconCls && item.iconCls != ''){
    		iconCls = "{iconCls}";
    	}    	
    	if(item.icon || item.iconCls)
    	{
	    	var tt = new Ext.Template(
	    		'<li class="{cls}" id="{id}" style="overflow:hidden">',
	    	         '<tpl if="closable">',
	    	            '<a class="x-tab-strip-close"></a>',
	    	         '</tpl>',
	    	         '<a class="x-tab-right" href="#" style="padding-left:6px">',
	    	            '<em class="x-tab-left">',
	    	                '<span class="x-tab-strip-inner">',
	    	                    '<img src="'+src+'" class="x-tab-strip-text '+iconCls+'" width="16" height="16" style="padding:0px;float:left;margin-top:2px; margin-right:4px">',
	    	                    '<span style="'+(item.icon?"margin-left:20px":"")+'" class="x-tab-strip-text ">{text} {extra}</span>',
	    	                '</span>',
	    	            '</em>',
	    	        '</a>',
	    	    '</li>'
			);	    	
			tt.disableFormats = true;
			tt.compile();
			Ext.TabPanel.prototype.itemTpl = tt;
    	}
		/***********************************************************/
        var before = this.strip.dom.childNodes[index],
            p = this.getTemplateArgs(item),
            el = before ?
                 this.itemTpl.insertBefore(before, p) :
                 this.itemTpl.append(this.strip, p),
            cls = 'x-tab-strip-over',
            tabEl = Ext.get(el);

        tabEl.hover(function(){
            if(!item.disabled){
                tabEl.addClass(cls);
            }
        }, function(){
            tabEl.removeClass(cls);
        });

        if(item.tabTip){
            tabEl.child('span.x-tab-strip-text', true).qtip = item.tabTip;
        }
        item.tabEl = el;

        // Route *keyboard triggered* click events to the tab strip mouse handler.
        tabEl.select('a').on('click', function(e){
            if(!e.getPageX()){
                this.onStripMouseDown(e);
            }
        }, this, {preventDefault: true});

        item.on({
            scope: this,
            disable: this.onItemDisabled,
            enable: this.onItemEnabled,
            titlechange: this.onItemTitleChanged,
            iconchange: this.onItemIconChanged,
            beforeshow: this.onBeforeShowItem
        });
    }
});
/*!
 * Ext JS Library 3.2.1
 * Copyright(c) 2006-2010 Ext JS, Inc.
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
/**
 * @class Ext.Loader
 * @singleton
 * Simple class to help load JavaScript files on demand
 */
Ext.Loader = Ext.apply({}, {
    /**
     * Loads a given set of .js files. Calls the callback function when all files have been loaded
     * Set preserveOrder to true to ensure non-parallel loading of files if load order is important
     * @param {Array} fileList Array of all files to load
     * @param {Function} callback Callback to call after all files have been loaded
     * @param {Object} scope The scope to call the callback in
     * @param {Boolean} preserveOrder True to make files load in serial, one after the other (defaults to false)
     */
    load: function(fileList, callback, scope, preserveOrder) {
        var scope       = scope || this,
            head        = document.getElementsByTagName("head")[0],
            fragment    = document.createDocumentFragment(),
            numFiles    = fileList.length,
            loadedFiles = 0,
            me          = this;
        
        /**
         * Loads a particular file from the fileList by index. This is used when preserving order
         */
        var loadFileIndex = function(index) {
            head.appendChild(
                me.buildScriptTag(fileList[index], onFileLoaded)
            );
        };
        
        /**
         * Callback function which is called after each file has been loaded. This calls the callback
         * passed to load once the final file in the fileList has been loaded
         */
        var onFileLoaded = function() {
            loadedFiles ++;
            
            //if this was the last file, call the callback, otherwise load the next file
            if (numFiles == loadedFiles && typeof callback == 'function') {
                callback.call(scope);
            } else {
                if (preserveOrder === true) {
                    loadFileIndex(loadedFiles);
                }
            }
        };
        
        if (preserveOrder === true) {
            loadFileIndex.call(this, 0);
        } else {
            //load each file (most browsers will do this in parallel)
            Ext.each(fileList, function(file, index) {
                fragment.appendChild(
                    this.buildScriptTag(file, onFileLoaded)
                );  
            }, this);
            
            head.appendChild(fragment);
        }
    },
    
    /**
     * @private
     * Creates and returns a script tag, but does not place it into the document. If a callback function
     * is passed, this is called when the script has been loaded
     * @param {String} filename The name of the file to create a script tag for
     * @param {Function} callback Optional callback, which is called when the script has been loaded
     * @return {Element} The new script ta
     */
    buildScriptTag: function(filename, callback) {
        var script  = document.createElement('script');
        script.type = "text/javascript";
        script.src  = filename;
        
        //IE has a different way of handling <script> loads, so we need to check for it here
        if (script.readyState) {
            script.onreadystatechange = function() {
                if (script.readyState == "loaded" || script.readyState == "complete") {
                    script.onreadystatechange = null;
                    callback();
                }
            };
        } else {
            script.onload = callback;
        }    
        
        return script;
    }
});

/**
 * Fixes for the IE portal page, where the vertical scrollbar introduces a horizontal one too.
 */
var pxMatch = /(\d+(?:\.\d+)?)px/;
Ext.override(Ext.Element, {
        getViewSize : function(contentBox){
            var doc = document,
                me = this,
                d = me.dom,
                extdom = Ext.lib.Dom,
                isDoc = (d == doc || d == doc.body),
                isBB, w, h, tbBorder = 0, lrBorder = 0,
                tbPadding = 0, lrPadding = 0;
            if (isDoc) {
                return { width: extdom.getViewWidth(), height: extdom.getViewHeight() };
            }
            isBB = me.isBorderBox();
            tbBorder = me.getBorderWidth('tb');
            lrBorder = me.getBorderWidth('lr');
            tbPadding = me.getPadding('tb');
            lrPadding = me.getPadding('lr');

            // Width calcs
            // Try the style first, then clientWidth, then offsetWidth
            if (w = me.getStyle('width').match(pxMatch)){
                if ((w = Math.round(w[1])) && isBB){
                    // Style includes the padding and border if isBB
                    w -= (lrBorder + lrPadding);
                }
                if (!contentBox){
                    w += lrPadding;
                }
                // Minimize with clientWidth if present
                d.clientWidth && (d.clientWidth < w) && (w = d.clientWidth);
            } else {
                if (!(w = d.clientWidth) && (w = d.offsetWidth)){
                    w -= lrBorder;
                }
                if (w && contentBox){
                    w -= lrPadding;
                }
            }

            // Height calcs
            // Try the style first, then clientHeight, then offsetHeight
            if (h = me.getStyle('height').match(pxMatch)){
                if ((h = Math.round(h[1])) && isBB){
                    // Style includes the padding and border if isBB
                    h -= (tbBorder + tbPadding);
                }
                if (!contentBox){
                    h += tbPadding;
                }
                // Minimize with clientHeight if present
                d.clientHeight && (d.clientHeight < h) && (h = d.clientHeight);
            } else {
                if (!(h = d.clientHeight) && (h = d.offsetHeight)){
                    h -= tbBorder;
                }
                if (h && contentBox){
                    h -= tbPadding;
                }
            }

            return {
                width : w,
                height : h
            };
        }
});
Ext.override(Ext.layout.ColumnLayout, {
    onLayout : function(ct, target, targetSize){
        var cs = ct.items.items, len = cs.length, c, i;

        if(!this.innerCt){
            // the innerCt prevents wrapping and shuffling while
            // the container is resizing
            this.innerCt = target.createChild({cls:'x-column-inner'});
            this.innerCt.createChild({cls:'x-clear'});
        }
        this.renderAll(ct, this.innerCt);

        var size = targetSize || target.getViewSize(true);

        if(size.width < 1 && size.height < 1){ // display none?
            return;
        }

        var w = size.width - this.scrollOffset,
            h = size.height,
            pw = w;

        this.innerCt.setWidth(w);

        // some columns can be percentages while others are fixed
        // so we need to make 2 passes

        for(i = 0; i < len; i++){
            c = cs[i];
            if(!c.columnWidth){
                pw -= (c.getSize().width + c.getPositionEl().getMargins('lr'));
            }
        }

        pw = pw < 0 ? 0 : pw;

        for(i = 0; i < len; i++){
            c = cs[i];
            if(c.columnWidth){
                c.setSize(Math.floor(c.columnWidth * pw) - c.getPositionEl().getMargins('lr'));
            }
        }
        // Do a second pass if the layout resulted in a vertical scrollbar (changing the available width)
        if (!targetSize && ((size = target.getViewSize(true)).width != w)) {
            this.onLayout(ct, target, size);
        }
    }
});
Ext.isJsonString = function(string){
	var rc = null;
	try{
		rc=new RegExp('^("(\\\\.|[^"\\\\\\n\\r])*?"|[,:{}\\[\\]0-9.\\-+Eaeflnr-u \\n\\r\\t])+?$')
	}catch(z){
		rc=/^(true|false|null|\[.*\]|\{.*\}|".*"|\d+|\d+\.\d+)$/
	}
	return rc.test(string);
}

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/overrides/Override.Fixes.js  ]*/

Ext.ux.NotificationMgr = {
    positions: [],
    heights: []
};
Ext.ux.Notification = function(config){
	this.config = Ext.apply({},config); 
}
Ext.ux.Notification = Ext.extend(Ext.Window, {
	notificationType:'ERROR',
	heightPlus:0,
	initComponent : function(){
		Ext.apply(this, {
			//iconCls: this.iconCls || 'icon-notification-info',
			autoHeight: true,
			closeAction:'close',			
			plain: false,
			shadow:false,
			draggable: false,
			bodyStyle: 'text-align:left;padding:10px;',
			resizable: false
		});
		if(this.autoDestroy){
			this.task = new Ext.util.DelayedTask(this.close, this);
		}else{
			this.closable = true;
		}
		Ext.ux.Notification.superclass.initComponent.call(this);
    },
	setMessage : function(msg){
		this.body.update(msg);
	},
	/**
	* Comment this section for now. Since for window when setting iconClass frame is checked, 
	* which is currently overridden to avoid, that may result double icons in window title. 
	* Whenever we decide not to override that, we may want to keep this method.
	* Because I saw that the overriden section is refactored in latest version of extjs.
	 
	setTitle : function(title, iconCls){
        Ext.ux.Notification.superclass.setTitle.call(this, title, iconCls||this.iconCls);
    },*/
    onRender : function(ct, position) {
		Ext.ux.Notification.superclass.onRender.call(this, ct, position);
	},
	onDestroy : function(){
		Ext.ux.NotificationMgr.positions.remove(this.pos);
		Ext.ux.Notification.superclass.onDestroy.call(this);
	},
	afterShow : function(){
		Ext.ux.Notification.superclass.afterShow.call(this);
		this.on('move', function(){
			Ext.ux.NotificationMgr.positions.remove(this.pos);
			if(this.autoDestroy){
				this.task.cancel();
			}
		}, this);
		if(this.autoDestroy){
			this.task.delay(this.hideDelay || 5000);
		}
	},
	animShow : function(){			
		this.pos = 0;
		var h = 0;
		while(Ext.ux.NotificationMgr.positions.indexOf(this.pos)>-1){
			h+=Ext.ux.NotificationMgr.heights[this.pos];
			this.pos++;			
		}
		Ext.ux.NotificationMgr.positions.push(this.pos);		
		Ext.ux.NotificationMgr.heights[this.pos] = this.getSize().height+10;
		
		h=h+this.heightPlus;
		
		this.el.alignTo(this.animateTarget || document, "br-tr", [ -1, -1-(h) ]);
		this.el.slideIn('b', {
			duration: .7
			, callback: this.afterShow
			, scope: this
		});
	},
	animHide : function(){
		Ext.ux.NotificationMgr.positions.remove(this.pos);
		if(this.el)
		this.el.ghost("b", {
			duration: 1
			, remove: false
		});		
	},
	start: function(url){
		new Ext.ux.Notification.start(url);
	}
});
Ext.ns("Ext.ux.Notification");
Ext.ux.Notification.Base = function(){};
Ext.ux.Notification.Base = Ext.extend(Ext.util.Observable,{
	showNotification:  function(config) {
	    var win = new Ext.ux.Notification(Ext.apply({
	    	animateTarget: this.notificationEl
			,autoDestroy: false					
			,notificationType:'ERROR'
	    }, config));
	    if(win.notificationType == "ERROR"){
	    	win.iconCls = 'icon-notification-error';
	    	win.bodyStyle += 'background-color:#fddcdc;';
	    }
	    if(win.notificationType == "WARNING"){
	    	win.iconCls = 'icon-notification-warning';
	    	win.bodyStyle += 'background-color:#fefcc7;';
	    }
	    if(win.notificationType == "INFO"){
	    	win.iconCls = 'icon-notification-info';
	    	win.bodyStyle += 'background-color:#fff;';
	    }
	    win.bodyStyle += 'padding-bottom:20px;';	    
	    win.show();	   
	    return win;
	},
	hideNotification: function(win, delay) {
	    if (win) {
	    	win.close.defer(delay || 3000, win);
	    }
	},
	growl: function(TITLE,MESSAGE,TYPE,DURATION,HEIGHTPLUS){			
		var n = this.showNotification({
			width:300,	
			notificationType:TYPE,
			title:TITLE,
			html:MESSAGE,
			heightPlus:HEIGHTPLUS
		});
		this.hideNotification(n, DURATION*1000);			
	}
})
Ext.ux.InstantNotification = Ext.extend(Ext.ux.Notification.Base,{
	constructor: function(config) {	
		Ext.ux.InstantNotification.superclass.constructor.call(this, config);		
		this.notificationEl = Ext.get("growl-notification-el")?Ext.get("growl-notification-el"):Ext.DomHelper.append(Ext.getBody(),{tag:'div',id:'growl-notification-el',style:'width:300px;position:absolute;bottom:0;right:0'});
		config.type = config.type || "INFO";
		config.duration = config.duration || 10;
		config.heightPlus = config.heightPlus || 0;
		
		this.growl(config.title,config.message,config.type,config.duration,config.heightPlus);
	}
});

Ext.ux.Notification.start = function(url){	
	Ext.onReady(function(){	
		var notificationEl = Ext.get("growl-notification-el")?Ext.get("growl-notification-el"):Ext.DomHelper.append(Ext.getBody(),{tag:'div',id:'growl-notification-el',style:'width:300px;position:absolute;bottom:0;right:0'});			
		this.showNotification = function(config) {
		    var win = new Ext.ux.Notification(Ext.apply({
		    	animateTarget: notificationEl
				, autoDestroy: false					
				,notificationType:'ERROR'
		    }, config));
		    if(win.notificationType == "ERROR"){
		    	win.iconCls = 'icon-notification-error';
		    	win.bodyStyle += 'background-color:#fddcdc;';
		    }
		    if(win.notificationType == "WARNING"){
		    	win.iconCls = 'icon-notification-warning';
		    	win.bodyStyle += 'background-color:#fefcc7;';
		    }
		    if(win.notificationType == "INFO"){
		    	win.iconCls = 'icon-notification-info';
		    	win.bodyStyle += 'background-color:#fff;';
		    }
		    win.bodyStyle += 'padding-bottom:20px;';
		    win.show();
	
		    return win;
		};
		this.hideNotification = function(win, delay) {
		    if (win) {
		      win.close.defer(delay || 3000, win);
		    }
		};
		this.growl = function(TITLE,MESSAGE,TYPE,DURATION){			
			var n = this.showNotification({
				width:300,	
				notificationType:TYPE,
				title:TITLE,
				html:MESSAGE
			});
			this.hideNotification(n, DURATION*1000);			
		}		
		this.sendRequest = {
			url: url,
			success: function(response){
				var obj = Ext.decode(response.responseText);
				if(obj){
					var count = 0;
					var max_duration = 0;
					var defer = 500;
					Ext.each(obj.data,function(d){
						count++;
						if(d.duration > max_duration) max_duration = d.duration;
						var arr = [];
						arr.push(d.title);
						arr.push(d.message);
						arr.push(d.type);
						arr.push(d.duration);
						this.growl.defer(defer*count,this,arr);						
					},this)					
					new Ext.util.DelayedTask(function(){
						this.ajax.request(Ext.apply(this.sendRequest,{
							params:{
								limit:(3-Ext.ux.NotificationMgr.positions.length)
							}
						}))
					}).delay(count*defer+10000);
				}
			}
		}
		this.ajax = Ext.Ajax;
		this.ajax.timeout = 0;  
		this.ajax.request(this.sendRequest);
		this.ajax.on("requestcomplete",function(con,response,options){
			
		},this)
		function newExcitingAlerts() {
		    var oldTitle = document.title;
		    var msg = "New Notification !! " +oldTitle;
		    var timeoutId = setInterval(function() {
		    	//window.focus();
		        document.title = document.title == msg ? ' ' : msg;
		        
		    }, 1000);
		    window.onmousemove = function() {
		        clearInterval(timeoutId);
		        document.title = oldTitle;
		        window.onmousemove = null;
		    };
		}
		//newExcitingAlerts()
	})
}


/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/plugins/Ext.ux.Notification.js  ]*/

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

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/layout/AccordionLayoutSetActiveItem.js  ]*/

Ext.ns("Ext.ux");
Ext.ux.MaximizeTool = function() {
    this.init= function(ct) {
        var maximizeTool = {
            id: 'maximize', 
            handler: handleMaximize, 
            scope: ct, 
            qtip: 'Maximize'
        }; 
        ct.tools = ct.tools || [];
        var newTools = ct.tools.slice();
        ct.tools =newTools;
        for(var i=0, len=ct.tools.length;i<len;i++) {
            if (ct.tools[i].id=='maximize') return;
        }
        
        /*
         * Add maximize just before last tool
         * Just to allow a close tool to be at the last. if any
         */
        var tmp;
        if(ct.tools.length > 0){
        	for(var k in ct.tools){
        		if(ct.tools[k].id == 'close'){
        			tmp = ct.tools[k];
        			ct.tools.splice(k,1);
        		}
        	}
        }
        if(tmp){
        	ct.tools[ct.tools.length] = tmp;
        }
        if(ct.tools.length > 0){
	        var lastTool = ct.tools[ct.tools.length-1];
	        if(lastTool.id == 'close'){
		        ct.tools[ct.tools.length-1] = maximizeTool;
		        ct.tools[ct.tools.length] = lastTool;
	        }else{
	        	ct.tools[ct.tools.length] = maximizeTool;
	        }
        }else{
        	 ct.tools[ct.tools.length] = maximizeTool;
        }       
    };

    function handleMaximize(event, toolEl, panel){
        panel.originalOwnerCt = panel.ownerCt;
        panel.originalPosition = panel.ownerCt.items.indexOf(panel);
        panel.originalSize=panel.getSize();

        if (!toolEl.window) {
            var defaultConfig = {
                id: (panel.getId() + '-MAX'),
                width: (Ext.getBody().getSize().width - 100),
                height: (Ext.getBody().getSize().height - 100),
                resizable: true,
                draggable: true,
                closable: true,
                closeAction: 'hide',
                hideBorders: true,
                plain: true,
                layout: 'fit',
                autoScroll: true,
                border: false,
                bodyBorder: false,
                frame: true,
                pinned: true,  
                maximizable:true,               
                bodyStyle: 'background-color: #ffffff;'
            };
            toolEl.window = new Ext.Window(defaultConfig);
            toolEl.window.on('hide', handleMinimize, panel);
        }
        if (!panel.dummyComponent) {
            var dummyCompConfig = {
                title: panel.title,
                width: panel.getSize().width,
                height: panel.getSize().height,
                html: '<div style="margin-top:20px;  text-align:center; font-family:verdana;font-size:10px"><b>'+panel.title+'</b><br>This widget is maximized<br><br>Closing the maximized widget window will restore the widget in this area.</div>'
            };
            panel.dummyComponent = new Ext.Panel(dummyCompConfig);
        }
        
        toolEl.window.add(panel);
        if (panel.tools['toggle']) panel.tools['toggle'].setVisible(false);
        if (panel.tools['close']) panel.tools['close'].setVisible(false);
        panel.tools['maximize'].setVisible(false);
        

        panel.originalOwnerCt.insert(panel.originalPosition, panel.dummyComponent);
        panel.originalOwnerCt.doLayout();       
        panel.dummyComponent.setSize(panel.originalSize);
        panel.dummyComponent.setVisible(true);
        toolEl.window.show(this);
    };
    
    function handleMinimize(window) {        
        this.dummyComponent.setVisible(false);
        this.originalOwnerCt.insert(this.originalPosition, this);
        this.originalOwnerCt.doLayout(); 
        this.setSize(this.originalSize);
        this.tools['maximize'].setVisible(true);
        if (this.tools['toggle']) this.tools['toggle'].setVisible(true);
        if (this.tools['close']) this.tools['close'].setVisible(true);
    }
    
    
};

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/portal/Ext.ux.MaximizeTool.js  ]*/

/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
Ext.ns('Ext.ux');

Ext.ux.GroupTabPanel = Ext.extend(Ext.TabPanel, {
    tabPosition: 'left',
    
    alternateColor: false,
    
    alternateCls: 'x-grouptabs-panel-alt',
    
    defaultType: 'grouptab',
    
    deferredRender: false,
    
    activeGroup : null,
    
    initComponent: function(){
        Ext.ux.GroupTabPanel.superclass.initComponent.call(this);
        
        this.addEvents(
            'beforegroupchange',
            'groupchange'
        );
        this.elements = 'body,header';
        this.stripTarget = 'header';
        
        this.tabPosition = this.tabPosition == 'right' ? 'right' : 'left';
        
        this.addClass('x-grouptabs-panel');
        
        if (this.tabStyle && this.tabStyle != '') {
            this.addClass('x-grouptabs-panel-' + this.tabStyle);
        }
        
        if (this.alternateColor) {
            this.addClass(this.alternateCls);
        }
        
        this.on('beforeadd', function(gtp, item, index){
            this.initGroup(item, index);
        });		     
    },
    
    initEvents : function() {
        this.mon(this.strip, 'mousedown', this.onStripMouseDown, this);
    },
        
    onRender: function(ct, position){
        Ext.TabPanel.superclass.onRender.call(this, ct, position);

        if(this.plain){
            var pos = this.tabPosition == 'top' ? 'header' : 'footer';
            this[pos].addClass('x-tab-panel-'+pos+'-plain');
        }

        var st = this[this.stripTarget];

        this.stripWrap = st.createChild({cls:'x-tab-strip-wrap ', cn:{
            tag:'ul', cls:'x-grouptabs-strip x-grouptabs-tab-strip-'+this.tabPosition}});

        var beforeEl = (this.tabPosition=='bottom' ? this.stripWrap : null);
        this.strip = new Ext.Element(this.stripWrap.dom.firstChild);

		this.header.addClass('x-grouptabs-panel-header');
		this.bwrap.addClass('x-grouptabs-bwrap');
        this.body.addClass('x-tab-panel-body-'+this.tabPosition + ' x-grouptabs-panel-body');

        if (!this.itemTpl) {
            var tt = new Ext.Template(
                '<li class="{cls}" id="{id}">', 
                '<a class="x-grouptabs-expand" onclick="return false;"></a>', 
                '<a class="x-grouptabs-text {iconCls}" href="#" onclick="return false;">',
                '<span>{text}</span></a>', 
                '</li>'
            );
            tt.disableFormats = true;
            tt.compile();
            Ext.ux.GroupTabPanel.prototype.itemTpl = tt;
        }

        this.items.each(this.initGroup, this);
    },
    
    afterRender: function(){
        Ext.ux.GroupTabPanel.superclass.afterRender.call(this);
        
        this.tabJoint = Ext.fly(this.body.dom.parentNode).createChild({
            cls: 'x-tab-joint'
        });
        
        this.addClass('x-tab-panel-' + this.tabPosition);
        this.header.setWidth(this.tabWidth);
        
        if (this.activeGroup !== undefined) {
            var group = (typeof this.activeGroup == 'object') ? this.activeGroup : this.items.get(this.activeGroup);
            delete this.activeGroup;
            this.setActiveGroup(group);
            group.setActiveTab(group.getMainItem());
        }
    },

    getGroupEl : Ext.TabPanel.prototype.getTabEl,
        
    // private
    findTargets: function(e){
        var item = null;
        var itemEl = e.getTarget('li', this.strip);
        if (itemEl) {
            item = this.findById(itemEl.id.split(this.idDelimiter)[1]);
            if (item.disabled) {
                return {
                    expand: null,
                    item: null,
                    el: null
                };
            }
        }
        return {
            expand: e.getTarget('.x-grouptabs-expand', this.strip),
            isGroup: !e.getTarget('ul.x-grouptabs-sub', this.strip),
            item: item,
            el: itemEl
        };
    },
    
    // private
    onStripMouseDown: function(e){
        if (e.button != 0) {
            return;
        }
        e.preventDefault();
        var t = this.findTargets(e);
        if (t.expand) {
            this.toggleGroup(t.el);
        }
        else if (t.item) {
            if(t.isGroup) {
                t.item.setActiveTab(t.item.getMainItem());
            }
            else {
                t.item.ownerCt.setActiveTab(t.item);
            }
        }
    },
    
    expandGroup: function(groupEl){
        if(groupEl.isXType) {
            groupEl = this.getGroupEl(groupEl);
        }
        Ext.fly(groupEl).addClass('x-grouptabs-expanded');
    },
    
    toggleGroup: function(groupEl){
        if(groupEl.isXType) {
            groupEl = this.getGroupEl(groupEl);
        }        
        Ext.fly(groupEl).toggleClass('x-grouptabs-expanded');
		this.syncTabJoint();
    },    
    
    syncTabJoint: function(groupEl){
        if (!this.tabJoint) {
            return;
        }
        
        groupEl = groupEl || this.getGroupEl(this.activeGroup);
        if(groupEl) {
            this.tabJoint.setHeight(Ext.fly(groupEl).getHeight() - 2); 
			
            var y = Ext.isGecko2 ? 0 : 1;
            if (this.tabPosition == 'left'){
                this.tabJoint.alignTo(groupEl, 'tl-tr', [-2,y]);
            }
            else {
                this.tabJoint.alignTo(groupEl, 'tr-tl', [1,y]);
            }           
        }
        else {
            this.tabJoint.hide();
        }
    },
    
    getActiveTab : function() {
        if(!this.activeGroup) return null;
        return this.activeGroup.getTabEl(this.activeGroup.activeTab) || null;  
    },
    
    onResize: function(){
        Ext.ux.GroupTabPanel.superclass.onResize.apply(this, arguments);
        this.syncTabJoint();
    },
    
    createCorner: function(el, pos){
        return Ext.fly(el).createChild({
            cls: 'x-grouptabs-corner x-grouptabs-corner-' + pos
        });
    },
    
    initGroup: function(group, index){
        var before = this.strip.dom.childNodes[index];        
        var p = this.getTemplateArgs(group);
        if (index === 0) {
            p.cls += ' x-tab-first';
        }
        p.cls += ' x-grouptabs-main';
        p.text = group.getMainItem().title;
        
        var el = before ? this.itemTpl.insertBefore(before, p) : this.itemTpl.append(this.strip, p);
        
        var tl = this.createCorner(el, 'top-' + this.tabPosition);
        var bl = this.createCorner(el, 'bottom-' + this.tabPosition);

        if (group.expanded) {
            this.expandGroup(el);
        }

        if (Ext.isIE6 || (Ext.isIE && !Ext.isStrict)){
            bl.setLeft('-10px');
            bl.setBottom('-5px');
            tl.setLeft('-10px');
            tl.setTop('-5px');
        }

        this.mon(group, 'changemainitem', this.onGroupChangeMainItem, this);
        this.mon(group, 'beforetabchange', this.onGroupBeforeTabChange, this);
    },
    
    setActiveGroup : function(group) {
        group = this.getComponent(group);
        if(!group || this.fireEvent('beforegroupchange', this, group, this.activeGroup) === false){
            return;
        }
        if(!this.rendered){
            this.activeGroup = group;
            return;
        }
        if(this.activeGroup != group){
            if(this.activeGroup){
                var oldEl = this.getGroupEl(this.activeGroup);
                if(oldEl){
                    Ext.fly(oldEl).removeClass('x-grouptabs-strip-active');
                }
                this.activeGroup.fireEvent('deactivate', this.activeTab);
            }

            var groupEl = this.getGroupEl(group);
            Ext.fly(groupEl).addClass('x-grouptabs-strip-active');
                        
            this.activeGroup = group;
            this.stack.add(group);

            this.layout.setActiveItem(group);
            this.syncTabJoint(groupEl);

            group.fireEvent('activate', group);
            this.fireEvent('groupchange', this, group);
        }        
    },
    
    onGroupBeforeTabChange: function(group, newTab, oldTab){
        if(group !== this.activeGroup || newTab !== oldTab) {
            this.strip.select('.x-grouptabs-sub > li.x-grouptabs-strip-active', true).removeClass('x-grouptabs-strip-active');
        } 
        
        this.expandGroup(this.getGroupEl(group));
        this.setActiveGroup(group);
    },
    
    getFrameHeight: function(){
        var h = this.el.getFrameWidth('tb');
        h += (this.tbar ? this.tbar.getHeight() : 0) +
        (this.bbar ? this.bbar.getHeight() : 0);
        
        return h;
    },
    
    adjustBodyWidth: function(w){
        return w - this.tabWidth;
    }
});

Ext.reg('grouptabpanel', Ext.ux.GroupTabPanel);

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grouptabs/GroupTabPanel.js  ]*/

/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
Ext.ux.GroupTab = Ext.extend(Ext.Container, {
    mainItem: 0,
    
    expanded: true,
    
    deferredRender: true,
    
    activeTab: null,
    
    idDelimiter: '__',
    
    headerAsText: false,
    
    frame: false,
    
    hideBorders: true,
    
    initComponent: function(config){
        Ext.apply(this, config);
        this.frame = false;
        
        Ext.ux.GroupTab.superclass.initComponent.call(this);
        
        this.addEvents('activate', 'deactivate', 'changemainitem', 'beforetabchange', 'tabchange');
        
        this.setLayout(new Ext.layout.CardLayout({
            deferredRender: this.deferredRender
        }));
        
        if (!this.stack) {
            this.stack = Ext.TabPanel.AccessStack();
        }
        
        this.initItems();
        
        this.on('beforerender', function(){
            this.groupEl = this.ownerCt.getGroupEl(this);
        }, this);
        
        this.on('add', this.onAdd, this, {
            target: this
        });
        this.on('remove', this.onRemove, this, {
            target: this
        });
        
        if (this.mainItem !== undefined) {
            var item = (typeof this.mainItem == 'object') ? this.mainItem : this.items.get(this.mainItem);
            delete this.mainItem;
            this.setMainItem(item);
        }
    },
    
    /**
     * Sets the specified tab as the active tab. This method fires the {@link #beforetabchange} event which
     * can return false to cancel the tab change.
     * @param {String/Panel} tab The id or tab Panel to activate
     */
    setActiveTab : function(item){
        item = this.getComponent(item);
        if(!item || this.fireEvent('beforetabchange', this, item, this.activeTab) === false){
            return;
        }
        if(!this.rendered){
            this.activeTab = item;
            return;
        }
        if(this.activeTab != item){
            if(this.activeTab && this.activeTab != this.mainItem){
                var oldEl = this.getTabEl(this.activeTab);
                if(oldEl){
                    Ext.fly(oldEl).removeClass('x-grouptabs-strip-active');
                }
                this.activeTab.fireEvent('deactivate', this.activeTab);
            }
            var el = this.getTabEl(item);
            Ext.fly(el).addClass('x-grouptabs-strip-active');
            this.activeTab = item;
            this.stack.add(item);

            this.layout.setActiveItem(item);
            if(this.layoutOnTabChange && item.doLayout){
                item.doLayout();
            }
            if(this.scrolling){
                this.scrollToTab(item, this.animScroll);
            }

            item.fireEvent('activate', item);
            this.fireEvent('tabchange', this, item);
        }
    },
    
    getTabEl: function(item){
        if (item == this.mainItem) {
            return this.groupEl;
        }
        return Ext.TabPanel.prototype.getTabEl.call(this, item);
    },
    
    onRender: function(ct, position){
        Ext.ux.GroupTab.superclass.onRender.call(this, ct, position);
        
        this.strip = Ext.fly(this.groupEl).createChild({
            tag: 'ul',
            cls: 'x-grouptabs-sub'
        });

        this.tooltip = new Ext.ToolTip({
           target: this.groupEl,
           delegate: 'a.x-grouptabs-text',
           trackMouse: true,
           renderTo: document.body,
           listeners: {
               beforeshow: function(tip) {
                   var item = (tip.triggerElement.parentNode === this.mainItem.tabEl)
                       ? this.mainItem
                       : this.findById(tip.triggerElement.parentNode.id.split(this.idDelimiter)[1]);

                   if(!item.tabTip) {
                       return false;
                   }
                   tip.body.dom.innerHTML = item.tabTip;
               },
               scope: this
           }
        });
                
        if (!this.itemTpl) {
            var tt = new Ext.Template('<li class="{cls}" id="{id}">', '<a onclick="return false;" class="x-grouptabs-text {iconCls}">{text}</a>', '</li>');
            tt.disableFormats = true;
            tt.compile();
            Ext.ux.GroupTab.prototype.itemTpl = tt;
        }
        
        this.items.each(this.initTab, this);
    },
    
    afterRender: function(){
        Ext.ux.GroupTab.superclass.afterRender.call(this);
        
        if (this.activeTab !== undefined) {
            var item = (typeof this.activeTab == 'object') ? this.activeTab : this.items.get(this.activeTab);
            delete this.activeTab;
            this.setActiveTab(item);
        }
    },
    
    // private
    initTab: function(item, index){
        var before = this.strip.dom.childNodes[index];
        var p = Ext.TabPanel.prototype.getTemplateArgs.call(this, item);
        
        if (item === this.mainItem) {
            item.tabEl = this.groupEl;
            p.cls += ' x-grouptabs-main-item';
        }
        
        var el = before ? this.itemTpl.insertBefore(before, p) : this.itemTpl.append(this.strip, p);
        
        item.tabEl = item.tabEl || el;
                
        item.on('disable', this.onItemDisabled, this);
        item.on('enable', this.onItemEnabled, this);
        item.on('titlechange', this.onItemTitleChanged, this);
        item.on('iconchange', this.onItemIconChanged, this);
        item.on('beforeshow', this.onBeforeShowItem, this);
    },
    
    setMainItem: function(item){
        item = this.getComponent(item);
        if (!item || this.fireEvent('changemainitem', this, item, this.mainItem) === false) {
            return;
        }
        
        this.mainItem = item;
    },
    
    getMainItem: function(){
        return this.mainItem || null;
    },
    
    // private
    onBeforeShowItem: function(item){
        if (item != this.activeTab) {
            this.setActiveTab(item);
            return false;
        }
    },
    
    // private
    onAdd: function(gt, item, index){
        if (this.rendered) {
            this.initTab.call(this, item, index);
        }
    },
    
    // private
    onRemove: function(tp, item){
        Ext.destroy(Ext.get(this.getTabEl(item)));
        this.stack.remove(item);
        item.un('disable', this.onItemDisabled, this);
        item.un('enable', this.onItemEnabled, this);
        item.un('titlechange', this.onItemTitleChanged, this);
        item.un('iconchange', this.onItemIconChanged, this);
        item.un('beforeshow', this.onBeforeShowItem, this);
        if (item == this.activeTab) {
            var next = this.stack.next();
            if (next) {
                this.setActiveTab(next);
            }
            else if (this.items.getCount() > 0) {
                this.setActiveTab(0);
            }
            else {
                this.activeTab = null;
            }
        }
    },
    
    // private
    onBeforeAdd: function(item){
        var existing = item.events ? (this.items.containsKey(item.getItemId()) ? item : null) : this.items.get(item);
        if (existing) {
            this.setActiveTab(item);
            return false;
        }
        Ext.TabPanel.superclass.onBeforeAdd.apply(this, arguments);
        var es = item.elements;
        item.elements = es ? es.replace(',header', '') : es;
        item.border = (item.border === true);
    },
    
    // private
    onItemDisabled: Ext.TabPanel.prototype.onItemDisabled,
    onItemEnabled: Ext.TabPanel.prototype.onItemEnabled,
    
    // private
    onItemTitleChanged: function(item){
        var el = this.getTabEl(item);
        if (el) {
            Ext.fly(el).child('a.x-grouptabs-text', true).innerHTML = item.title;
        }
    },
    
    //private
    onItemIconChanged: function(item, iconCls, oldCls){
        var el = this.getTabEl(item);
        if (el) {
            Ext.fly(el).child('a.x-grouptabs-text').replaceClass(oldCls, iconCls);
        }
    },
    
    beforeDestroy: function(){
        Ext.TabPanel.prototype.beforeDestroy.call(this);
        this.tooltip.destroy();
    }
});

Ext.reg('grouptab', Ext.ux.GroupTab);

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grouptabs/GroupTab.js  ]*/

/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.ux.Portal = Ext.extend(Ext.Panel, {
    layout: 'column',
    autoScroll:false,
    bodyStyle:'overflow-x:hidden;overflow-y:scroll;padding-right:5px;',
    cls:'x-portal',
    defaultType: 'portalcolumn',
    afterLayoutEvent: false,
    
    initComponent : function(){
        Ext.ux.Portal.superclass.initComponent.call(this);
        this.addEvents({
            validatedrop:true,
            beforedragover:true,
            dragover:true,
            beforedrop:true,
            drop:true,
            render:true
        });
        
        this.on('drop',this.onWidgetDrop,this);
        this.on('afterLayout',this.onPortalAfterLayout,this);        
    },

    onPortalAfterLayout : function(portal){
    	
    	if(!this.afterLayoutEvent)
    	{
    	  	var col;
	        for(var c = 0; c < this.items.getCount(); c++) {
	           col = this.items.get(c);    
	           
	           if(col.items) {
	                for(var s = 0; s < col.items.getCount(); s++) {
	                	var widget=col.items.get(s);
	                	
	                	if(widget.isXType('form'))
	                	{
	                		var items=widget.items.items;
	        			                		
	                		if(items.length>0)
	                		{
		                		for(var i=0;i<items.length;i++)
				        		{
				        			//this is a fieldset
				        			if(items[i].items&&items[i].items.items)
				        			{
				        				var fieldset_width=col.lastSize.width-46;
				        				
					        			var inputs=items[i].items.items;
					        			
					        			var label_width=fieldset_width*0.3;
					        			var input_width=fieldset_width*0.65;
					        			var static_width=fieldset_width*0.9;
					        			
					        			label_width=(label_width>75)?75:label_width;
					        			input_width=(input_width<250)?input_width:250;
					        			
					        			input_padding_left=label_width;
					        			
					        			for(var j=0;j<inputs.length;j++)
					        			{       		
					        				//console.log(inputs[j].getXType());
					        						
					        				if(!inputs[j].isXType('htmleditor')&&!inputs[j].isXType('textarea')&&!inputs[j].isXType('statictextfield'))
					        				{
					        					if(!inputs[j].isXType('itemselector'))
					        					{
					        						inputs[j].setSize(input_width);
					        					}
					        					else
					        					{
					        						//to do??
					        						inputs[j].msWidth=input_width;			        						
					        					}
					        				}       
					        				
					        				if(inputs[j].isXType('statictextfield'))
					        				{
					        					inputs[j].setSize(static_width);
					        				}
					        									        							
				        					if(inputs[j].wrap)
				        					{		
				        						inputs[j].wrap.dom.parentNode.style.paddingLeft=input_padding_left+'px';
				        					}
				        					if(inputs[j].container&&inputs[j].container.dom.previousSibling)
				        					{				        						
				        						inputs[j].container.dom.previousSibling.style.width=label_width+'px';
				        						inputs[j].container.dom.previousSibling.style.height='10px';
				        					}
					        			}  
				        			}
				        			else
				        			{
						        		var column_width=col.lastSize.width-24;
			        			
					        			var input=items[i];
					        			
					        			var label_width=column_width*0.3;
					        			var input_width=column_width*0.7;
					        			
					        			label_width=(label_width>75)?75:label_width;
					        			input_width=(input_width<250)?input_width:250;
					        			input_padding_left=label_width;
					        			
					        			//console.log(inputs[j].getXType());
					        						
				        				if(!input.isXType('htmleditor'))
				        				{
				        					if(!input.isXType('itemselector'))
				        					{
				        						input.setSize(input_width);
				        					}
				        					else
				        					{
				        						//to do??
				        						input.msWidth=input_width;			        						
				        					}
				        					       			
				        					if(input.wrap)
				        					{		
				        						input.wrap.dom.parentNode.style.paddingLeft=input_padding_left+'px';
				        					}
				        					/*if(input.container&&input.container.dom.previousSibling)
				        					{
				        						input.container.dom.previousSibling.style.width=label_width+'px';
				        						input.container.dom.previousSibling.style.height='10px';
				        					}*/
				        				}
				        			}
				        		}
	                		}
	                	}
	                }
	            }
	        }
	        
	        this.afterLayoutEvent=true;
    	}
    },
    
    initEvents : function(){
        Ext.ux.Portal.superclass.initEvents.call(this);
        this.dd = new Ext.ux.Portal.DropZone(this, this.dropConfig);
        //Ext.util.Observable.capture(this, function(e){if(console)console.info(e)});
    },
    
    beforeDestroy: function() {
        if(this.dd){
            this.dd.unreg();
        }
        Ext.ux.Portal.superclass.beforeDestroy.call(this);
    },
    
    onWidgetDrop:function(portal)
    {
    	var config=this.getConfig();
		    	
		Ext.Ajax.request({ 
			url: afApp.urlPrefix + "/appFlower/savePortalState", 
			method:"post", 
			params:{"config":config}, 
			success:function(response, options){
			/*	response=Ext.decode(response.responseText);if(response.message){Ext.Msg.alert("Success",response.message);}*/
			},
			failure: function(response,options) {
				if(response.message){Ext.Msg.alert("Failure",response.message);}
			}				
		});
    },
    
    getPortalTitle:function()
    {
    	switch (this.getLayoutType())
    	{
    		case "NORMAL":
    			return this.title;
    		case "TABBED":
    			return this.ownerCt.ownerCt.getActiveTab().title;
    	}
    },
    
    getLayoutItem:function()
    {
    	switch (this.getLayoutType())
    	{
    		case "NORMAL":
    			return 0;
    		case "TABBED":
    			return this.ownerCt.ownerCt.items.indexOf(this.ownerCt.ownerCt.getActiveTab());
    	}
    },
    
    getLayoutType:function()
    {
    	if(this.layoutType)
    	{
	    	return this.layoutType;
    	}
    	else
    	{
	    	return this.ownerCt.ownerCt.layoutType;
    	}
    },
    
    getIdXml:function()
    {
    	switch (this.getLayoutType())
    	{
    		case "NORMAL":
    			return this.idxml;
    		case "TABBED":
    			return this.ownerCt.ownerCt.idxml;
    	}
    },
    
    //get the configuration to send as response to browser
    getConfig:function() {
        var o = {            
            idXml:this.getIdXml(), //element of identification
            layoutType:this.getLayoutType(), //element of identification
            layoutItem:this.getLayoutItem(),
       		content: {}
        };
        
        o.content.portalTitle=this.getPortalTitle();
        o.content.portalLayoutType=this.portalLayoutType;
        o.content.portalColumns=[[]];        
        o.content.portalLayoutNewType=this.layoutNewType || false;
        
        var col;
        for(var c = 0; c < this.items.getCount(); c++) {
            col = this.items.get(c);    
            o.content.portalColumns[c] = [];
            if(col.items) {
                for(var s = 0; s < col.items.getCount(); s++) {
                	o.content.portalColumns[c].push(col.items.get(s).getWidgetConfig());
                }
            }
        }
        var encoded=Ext.encode(o);
        //console.log(encoded);
        return encoded;
    },
    
    //show the window with widget selector
    showWidgetSelector:function(button,title) {
    	if(this.widgetSelectorWindow)
    	{
    		this.widgetSelectorWindow.show(button);
    	}
    	else
    	{
    		this.createWidgetSelector(title,button);
    	}
    },
    
    //retrieve widgets
    retrieveWidgets:function(button)
	{		
		//console.log(this.portalWidgets);
		var obj=this;
			
		var treereader = new Ext.data.JsonReader ({
			fields: [
				{
					name: "title",
					sortType: "asText"
				},
				{
					name: "description",
					sortType: "asText"
				},
				{
					name: "image",
					sortType: "asText"
				},
				{
					name: "widget",
					sortType: "asText"
				},
				{
					name: "message"
				},
				{
					name: "redirect"
				},
				{
					name: "_id",
					type: "int"
				},
				{
					name: "_parent",
					type: "auto"
				},
				{
					name: "_is_leaf",
					type: "bool"
				},
				{
					name: "_color",
					type: "auto"
				},
				{
					name: "_cell_color",
					type: "auto"
				},
				{
					name: "_selected",
					type: "auto"
				}
			],
			id: "_id",
			totalProperty: "totalCount",
			root: "rows",
			properties: "properties"
		});
		
		var treestore = new Ext.ux.maximgb.tg.AdjacencyListStore ({
			sortInfo: {
				field: "title",
				direction: "ASC"
			},
			reader: treereader,
			remoteSort: false,
			proxy: new Ext.data.HttpProxy ({
				url: afApp.urlPrefix + "/appFlower/retrieveWidgetsInfo",
				method: "POST",
				disableCaching: false
			}),
			listeners: { 
				beforeload: function (object,options) { 
					if(!Ext.isIE&&!treegrid.disableLoadMask){
						treegrid.getEl().mask('Loading, please Wait...', 'x-mask-loading');
					}
				},						
				load: function (object,records,options) { 
					if(records.length>0&&records[0].json.redirect&&records[0].json.message){var rec=records[0].json;Ext.Msg.alert("Failure", rec.message, function(){window.location.href=rec.redirect;});}else{if(!Ext.isIE){treegrid.getEl().unmask();}} 
				},
				loadexception: function () {
					if(!Ext.isIE){treegrid.getEl().unmask();} 
				} 
			}
		});
		
		var treesm = new Ext.ux.CheckboxSelectionModel ();
		var treegrid = new Ext.ux.maximgb.tg.GridPanel ({
			loadMask: true,
			frame: false,
			bodyStyle: "border: 1px solid #8db2e3;",
			autoHeight: true,
			forceFit: true,
			select: true,
			listeners: { 
				render: function () { 
					this.store.load({
						params:{
							portalWidgets:Ext.encode(obj.portalWidgets),
							config:obj.getConfig()
						}
					});
					
					var gcm = treegrid.getColumnModel();
					
					if(gcm.getColumnHeader(gcm.getColumnCount()-1) == '<div class="x-grid3-hd-checker" id="hd-checker">&#160;</div>'){
						gcm.moveColumn(gcm.getColumnCount()-1,0);
					}									 
				} 
			},
			viewConfig: {
				forceFit: true
			},
			columns: [
			{
				dataIndex: "title",
				header: "Category > Title",
				sortable: true,
				hidden: false,
				hideable: false,
				align: "left",
				id: "title",
				width: 20
			},
			{
				dataIndex: "description",
				header: "Description",
				sortable: true,
				hidden: false,
				hideable: false,
				align: "left",
				id: "description",
				width: 50,
				renderer : function(value, metadata, record){
					var qtip = Ext.util.Format.htmlEncode(value); return '<span qtip="' + qtip + '">' + value + '</span>';
				}
			},
			{
				dataIndex: "image",
				header: "Image",
				sortable: true,
				hidden: false,
				hideable: true,
				align: "left",
				id: "image",
				width: 30
			},
			treesm
			],
			master_column_id: "title",
			store: treestore,
			sm: treesm
		});
		
		//creating a classic form
		var formPanel = new Ext.FormPanel ({width: "100%",bodyStyle: "border:0px;padding-left:5px",buttonAlign:'center'});
		//adding the configuration of the portal to the form
		var configHiddenField = new Ext.form.Hidden({name: 'config', value:this.getConfig()});
		formPanel.add(configHiddenField);
		
		//adding the portal widgets to the form, for comparison
		var portalWidgetsHiddenField = new Ext.form.Hidden({name: 'portalWidgets', value:Ext.encode(this.portalWidgets)});
		formPanel.add(portalWidgetsHiddenField);
				
		//adding the tree grid
		formPanel.add(treegrid);
					
		//adding a submit button that submits the classic form
		var submitButton = new Ext.Button ({text:'Save & Refresh Page',
											icon: "/images/famfamfam/accept.png",
											cls: "x-btn-text-icon",
											handler: function () { 
												
												formPanel.getForm().submit({
													url: afApp.urlPrefix + '/appFlower/changePortalWidgets',
													method:'POST',
													params:{"selections":treegrid.getSelectionModel().getSelectionsJSON(["widget"])},
													waitMsg:'loading...',
													failure:function(form,action){
														var onclose=function(){if(action.result && action.result.redirect){window.location.href=action.result.redirect;}}; if(action.result){ if(action.result.message){Ext.Msg.alert("Failure", action.result.message, onclose);}}else{Ext.Msg.alert("Failure", "Some error appeared!", onclose);}
													},
													success:function(form,action){
														if(action.result.message)
														{
															Ext.Msg.alert("Success", action.result.message, function(){
																if(action.result.redirect){
																	window.location.href=action.result.redirect;
																}
															});
														}
														else{
															if(action.result.redirect){
																window.location.href=action.result.redirect;
															}
														}
													}
												}); 
											}						
											});

		//create a portal inside the window, with two columns
		this.portal = new Ext.ux.Portal ({
			region: 'center',
			buttonAlign: 'center',
			buttons: [submitButton],
			items: [
			{
				columnWidth: 1,
				style: 'overflow-x:hidden;overflow-y:scroll;padding:5px;'
			}
			]
		});				
		
		this.portal.items.items.push(formPanel);
		
		this.widgetSelectorWindowConfig.items.push(this.portal);				
		this.widgetSelectorWindow = new Ext.Window (this.widgetSelectorWindowConfig);
		
		this.widgetSelectorWindow.show(button);
		
		this.mask.hide();
	},
    
    //create the window widget selector
    createWidgetSelector:function(title,button) {
    	this.widgetSelectorWindowConfig = this.widgetSelectorWindowConfig || {};
		Ext.applyIf(this.widgetSelectorWindowConfig, {
			constrain: true,
			layout: 'fit',
			width: '90%',
			height: 500,
			maximizable: true,
			closeAction: 'hide',
			plain: true,
			modal: true,
			items:[],
			title:title
		});
		
		this.mask = new Ext.LoadMask(Ext.get("body"), {msg: "<b>Opening Widget Selector</b> <br>Please Wait...",removeMask:true});
		this.mask.show();
		
		this.retrieveWidgets(button); 
    },
    
    //show the window with layout selector
    showLayoutSelector:function(button,title,layouts) {
    	if(this.layoutSelectorWindow)
    	{
    		this.layoutSelectorWindow.show(button);
    	}
    	else
    	{
    		this.createLayoutSelector(title,layouts);
    		this.layoutSelectorWindow.show(button);
    	}
    },
    
    //create the window layout selector
    createLayoutSelector:function(title,layouts) {
    	this.layoutSelectorWindowConfig = this.layoutSelectorWindowConfig || {};
		Ext.applyIf(this.layoutSelectorWindowConfig, {
			constrain: true,
			layout: 'fit',
			width: '60%',
			height: 500,
			closeAction: 'hide',
			plain: true,
			modal: true,
			items:[],
			title:title
		});
		//left column
		this.portalLC = [];
		//right column
		this.portalRC = [];

		for(var i=0;i<layouts.length;i++)
		{
			var items=[];
			for(var j=0;j<layouts[i].length;j++)
			{
				items[j] = {};
				items[j].title=layouts[i][j]+'%';
				items[j].columnWidth=layouts[i][j]/100;
				if(j<(layouts[i].length-1))
				{
					items[j].style='border-right:1px solid #cc0000;';
				}
			}
			
			if(i % 2 == 0)
			{
				//add to left column a panel with some columns configuration inside
				this.createLayoutSelectorColumn(this.portalLC,items,layouts[i]);
			}
			else
			{
				//add to right column a panel with some columns configuration inside
				this.createLayoutSelectorColumn(this.portalRC,items,layouts[i]);
			}
		}
		
		//create a portal inside the window, with two columns
		this.portal = new Ext.ux.Portal ({
			region: 'center',
			items: [
			{
				columnWidth: 0.50,
				style: 'padding:10px 5px 10px 10px',
				items: this.portalLC
			},
			{
				columnWidth: 0.50,
				style: 'padding:10px 10px 10px 5px',
				items: this.portalRC
			}
			]
		});

		this.layoutSelectorWindowConfig.items.push(this.portal);				
		this.layoutSelectorWindow = new Ext.Window (this.layoutSelectorWindowConfig); 
    },
    
    //create a panel for layout selector, sending a column, some items, and a layout configuration array
    createLayoutSelectorColumn:function(column,items,layout)
    {
    	var obj=this;
    	
    	var p = new Ext.Panel({
		    layout:'column',
		    hideBorders:true,
		    style:'border:1px solid #cc0000;cursor:pointer;',
		    items: items,
		    clickEventInstalled:false
		});
							
		p.on('afterlayout',function(panel){

			if(column[0].id!=panel.id)
	    	{
	    		p.el.setStyle('marginTop','20px');
	    	}
									
	        for(var i = 0; i < panel.items.items.length; i++) {
	        	panel.items.items[i].header.setStyle('height','137px');
				panel.items.items[i].header.setStyle('border','0');
				panel.items.items[i].header.dom.firstChild.style.marginLeft='40%';
	        }			
				        	        
	        if(!panel.clickEventInstalled)
	        {	        		        	
		        panel.el.on('click', function( panel, e ){            
		            
		        	//if panel is clicked change layoutNewType
		        	this.layoutNewType=Ext.encode(layout);		
		        	
		        	var config=this.getConfig();
		        	
		        	Ext.Ajax.request({ 
						url: afApp.urlPrefix + "/appFlower/savePortalState", 
						method:"post", 
						params:{"config":config}, 
						success:function(response, options){
							
							obj.layoutSelectorWindow.hide();
							
							response=Ext.decode(response.responseText);
							if(response.redirect){afApp.load(response.redirect);}
						},
						failure: function(response,options) {
							if(response.message){Ext.Msg.alert("Failure",response.message);}
						}				
					});
		        	
			    },this);
	        }
	        
	        panel.clickEventInstalled=true;
	        
		},this);
						
		column.push(p);	
    }
});
Ext.reg('portal', Ext.ux.Portal);


Ext.ux.Portal.DropZone = function(portal, cfg){
    this.portal = portal;
    Ext.dd.ScrollManager.register(portal.body);
    Ext.ux.Portal.DropZone.superclass.constructor.call(this, portal.bwrap.dom, cfg);
    portal.body.ddScrollConfig = this.ddScrollConfig;
};

Ext.extend(Ext.ux.Portal.DropZone, Ext.dd.DropTarget, {
    ddScrollConfig : {
        vthresh: 50,
        hthresh: -1,
        animate: true,
        increment: 200
    },

    createEvent : function(dd, e, data, col, c, pos){
        return {
            portal: this.portal,
            panel: data.panel,
            columnIndex: col,
            column: c,
            position: pos,
            data: data,
            source: dd,
            rawEvent: e,
            status: this.dropAllowed
        };
    },

    notifyOver : function(dd, e, data){
        var xy = e.getXY(), portal = this.portal, px = dd.proxy;

        // case column widths
        if(!this.grid){
            this.grid = this.getGrid();
        }

        // handle case scroll where scrollbars appear during drag
        var cw = portal.body.dom.clientWidth;
        if(!this.lastCW){
            this.lastCW = cw;
        }else if(this.lastCW != cw){
            this.lastCW = cw;
            portal.doLayout();
            this.grid = this.getGrid();
        }

        // determine column
        var col = 0, xs = this.grid.columnX, cmatch = false;
        for(var len = xs.length; col < len; col++){
            if(xy[0] < (xs[col].x + xs[col].w)){
                cmatch = true;
                break;
            }
        }
        // no match, fix last index
        if(!cmatch){
            col--;
        }

        // find insert position
        var p, match = false, pos = 0,
            c = portal.items.itemAt(col),
            items = c.items.items;

        for(var len = items.length; pos < len; pos++){
            p = items[pos];
            var h = p.el.getHeight();
            if(h !== 0 && (p.el.getY()+(h/2)) > xy[1]){
                match = true;
                break;
            }
        }

        var overEvent = this.createEvent(dd, e, data, col, c,
                match && p ? pos : c.items.getCount());

        if(portal.fireEvent('validatedrop', overEvent) !== false &&
           portal.fireEvent('beforedragover', overEvent) !== false){

            // make sure proxy width is fluid
            px.getProxy().setWidth('auto');

            if(p){
                px.moveProxy(p.el.dom.parentNode, match ? p.el.dom : null);
            }else{
                px.moveProxy(c.el.dom, null);
            }

            this.lastPos = {c: c, col: col, p: match && p ? pos : false};
            this.scrollPos = portal.body.getScroll();

            portal.fireEvent('dragover', overEvent);

            return overEvent.status;;
        }else{
            return overEvent.status;
        }

    },

    notifyOut : function(){
        delete this.grid;
    },
    
    notifyDrop : function(dd, e, data){
        delete this.grid;
        if(!this.lastPos){
            return;
        }
        var c = this.lastPos.c, col = this.lastPos.col, pos = this.lastPos.p;

        var dropEvent = this.createEvent(dd, e, data, col, c,
                pos !== false ? pos : c.items.getCount());

        if(this.portal.fireEvent('validatedrop', dropEvent) !== false &&
           this.portal.fireEvent('beforedrop', dropEvent) !== false){

           	dd.proxy.getProxy().remove();
            dd.panel.el.dom.parentNode.removeChild(dd.panel.el.dom);
            if(pos !== false){
                c.insert(pos, dd.panel);
            }else{
                c.add(dd.panel);
            }
            
            var widget=dd.panel;
            
            //if(console)console.log(widget);
            //if(console)console.log('type:'+widget.getXType());
            //if(console)console.log('cw:'+c.lastSize.width);
            
            //resize to column width
            widget.el.resize(c.lastSize.width-10);
                   
            //Ext.util.Observable.capture(widget, function(e){if(console)console.info(e)});      
            //Ext.util.Observable.capture(widget.view, function(e){if(console)console.info(e)});      
            //Ext.util.Observable.capture(widget.store, function(e){if(console)console.info(e)});      
                          
            //grid widget
            if(widget.getXType()=='grid'||widget.getXType()=='ux-maximgb-treegrid')
            {
            	widget.store.reload();	
            	
            	widget.body.resize(c.lastSize.width-14);
        	}
        	else if(widget.getXType()=='form')
        	{
        		widget.body.resize(c.lastSize.width-14);
        		
        		//if(console)console.log(widget);
        		        		
        		var items=widget.items.items;
        		       		
        		for(var i=0;i<items.length;i++)
        		{
        			//this is a fieldset
        			if(items[i].items)
        			{
	        			items[i].setSize(c.lastSize.width-24);
	        			
	        			var fieldset_width=c.lastSize.width-46;
	        			
	        			var inputs=items[i].items.items;
	        			
	        			var label_width=fieldset_width*0.3;
	        			var input_width=fieldset_width*0.65;
	        			
	        			label_width=(label_width>75)?75:label_width;
	        			input_width=(input_width<250)?input_width:250;
	        			input_padding_left=label_width;
	        			
	        			for(var j=0;j<inputs.length;j++)
	        			{       				
	        				if(!inputs[j].isXType('htmleditor'))
	        				{
	        					if(!inputs[j].isXType('itemselector'))
	        					{
	        						inputs[j].setSize(input_width);
	        					}
	        					else
	        					{
	        						//to do??
	        						inputs[j].msWidth=input_width;
	        					}
	        					       					
	        					if(inputs[j].wrap)
	        					{		
	        						inputs[j].wrap.dom.parentNode.style.paddingLeft=input_padding_left+'px';
	        					}
	        					if(inputs[j].container&&inputs[j].container.dom.previousSibling)
	        					{
	        						inputs[j].container.dom.previousSibling.style.width=label_width+'px';
	        						inputs[j].container.dom.previousSibling.style.height='40px';
	        					}
	        				}
	        			}
        			}
        			else
        			{
        				var column_width=c.lastSize.width-24;
			        			
	        			var input=items[i];
	        			
	        			var label_width=column_width*0.3;
	        			var input_width=column_width*0.7;
	        			
	        			label_width=(label_width>75)?75:label_width;
	        			input_width=(input_width<250)?input_width:250;
	        			input_padding_left=label_width;
	        			
	        			//console.log(inputs[j].getXType());
	        						
        				if(!input.isXType('htmleditor'))
        				{
        					if(!input.isXType('itemselector'))
        					{
        						input.setSize(input_width);
        					}
        					else
        					{
        						//to do??
        						input.msWidth=input_width;			        						
        					}
        					       			
        					if(input.wrap)
        					{		
        						input.wrap.dom.parentNode.style.paddingLeft=input_padding_left+'px';
        					}
        					/*if(input.container&&input.container.dom.previousSibling)
        					{
        						input.container.dom.previousSibling.style.width=label_width+'px';
        						input.container.dom.previousSibling.style.height='10px';
        					}*/
        				}        				
        			}
        		}
        	}
        	else if(widget.getXType()=='panel')
        	{
        		widget.body.resize(c.lastSize.width-14);
        	}
        	
        	c.doLayout();

            this.portal.fireEvent('drop', dropEvent);

            // scroll position is lost on drop, fix it
            var st = this.scrollPos.top;
            if(st){
                var d = this.portal.body.dom;
                setTimeout(function(){
                    d.scrollTop = st;
                }, 10);
            }

        }
        delete this.lastPos;
    },
    
    // internal cache of body and column coords
    getGrid : function(){
        var box = this.portal.bwrap.getBox();
        box.columnX = [];
        this.portal.items.each(function(c){
             box.columnX.push({x: c.el.getX(), w: c.el.getWidth()});
        });
        return box;
    },

    // unregister the dropzone from ScrollManager
    unreg: function() {
        //Ext.dd.ScrollManager.unregister(this.portal.body);
        Ext.ux.Portal.DropZone.superclass.unreg.call(this);
    }
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/portal/Portal.js  ]*/

/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.ux.PortalColumn = Ext.extend(Ext.Container, {
    layout: 'anchor',
    autoEl: 'div',
    defaultType: 'portlet',
    cls:'x-portal-column'
});
Ext.reg('portalcolumn', Ext.ux.PortalColumn);

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/portal/PortalColumn.js  ]*/

/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.ux.Portlet = Ext.extend(Ext.Panel, {
    anchor: '100%',
    frame:true,
    collapsible:true,
    draggable:true,
    cls:'x-portlet'
});
Ext.reg('portlet', Ext.ux.Portlet);

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/portal/Portlet.js  ]*/

/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

SampleGrid = function(limitColumns){

    function italic(value){
        return '<i>' + value + '</i>';
    }

    function change(val){
        if(val > 0){
            return '<span style="color:green;">' + val + '</span>';
        }else if(val < 0){
            return '<span style="color:red;">' + val + '</span>';
        }
        return val;
    }

    function pctChange(val){
        if(val > 0){
            return '<span style="color:green;">' + val + '%</span>';
        }else if(val < 0){
            return '<span style="color:red;">' + val + '%</span>';
        }
        return val;
    }


    var columns = [
        {id:'company',header: "Company", width: 160, sortable: true, dataIndex: 'company'},
        {header: "Price", width: 75, sortable: true, renderer: Ext.util.Format.usMoney, dataIndex: 'price'},
        {header: "Change", width: 75, sortable: true, renderer: change, dataIndex: 'change'},
        {header: "% Change", width: 75, sortable: true, renderer: pctChange, dataIndex: 'pctChange'},
        {header: "Last Updated", width: 85, sortable: true, renderer: Ext.util.Format.dateRenderer('m/d/Y'), dataIndex: 'lastChange'}
    ];

    // allow samples to limit columns
    if(limitColumns){
        var cs = [];
        for(var i = 0, len = limitColumns.length; i < len; i++){
            cs.push(columns[limitColumns[i]]);
        }
        columns = cs;
    }

    SampleGrid.superclass.constructor.call(this, {
        store: new Ext.data.Store({
            reader: new Ext.data.ArrayReader({}, [
                   {name: 'company'},
                   {name: 'price', type: 'float'},
                   {name: 'change', type: 'float'},
                   {name: 'pctChange', type: 'float'},
                   {name: 'lastChange', type: 'date', dateFormat: 'n/j h:ia'}
              ]),
            data: [
                ['3m Co',71.72,0.02,0.03,'9/1 12:00am'],
                ['Alcoa Inc',29.01,0.42,1.47,'9/1 12:00am'],
                ['Altria Group Inc',83.81,0.28,0.34,'9/1 12:00am'],
                ['American Express Company',52.55,0.01,0.02,'9/1 12:00am'],
                ['American International Group, Inc.',64.13,0.31,0.49,'9/1 12:00am'],
                ['AT&T Inc.',31.61,-0.48,-1.54,'9/1 12:00am'],
                ['Boeing Co.',75.43,0.53,0.71,'9/1 12:00am'],
                ['Caterpillar Inc.',67.27,0.92,1.39,'9/1 12:00am'],
                ['Citigroup, Inc.',49.37,0.02,0.04,'9/1 12:00am'],
                ['E.I. du Pont de Nemours and Company',40.48,0.51,1.28,'9/1 12:00am'],
                ['Exxon Mobil Corp',68.1,-0.43,-0.64,'9/1 12:00am'],
                ['General Electric Company',34.14,-0.08,-0.23,'9/1 12:00am'],
                ['General Motors Corporation',30.27,1.09,3.74,'9/1 12:00am'],
                ['Hewlett-Packard Co.',36.53,-0.03,-0.08,'9/1 12:00am'],
                ['Honeywell Intl Inc',38.77,0.05,0.13,'9/1 12:00am'],
                ['Intel Corporation',19.88,0.31,1.58,'9/1 12:00am'],
                ['International Business Machines',81.41,0.44,0.54,'9/1 12:00am'],
                ['Johnson & Johnson',64.72,0.06,0.09,'9/1 12:00am'],
                ['JP Morgan & Chase & Co',45.73,0.07,0.15,'9/1 12:00am'],
                ['McDonald\'s Corporation',36.76,0.86,2.40,'9/1 12:00am'],
                ['Merck & Co., Inc.',40.96,0.41,1.01,'9/1 12:00am'],
                ['Microsoft Corporation',25.84,0.14,0.54,'9/1 12:00am'],
                ['Pfizer Inc',27.96,0.4,1.45,'9/1 12:00am'],
                ['The Coca-Cola Company',45.07,0.26,0.58,'9/1 12:00am'],
                ['The Home Depot, Inc.',34.64,0.35,1.02,'9/1 12:00am'],
                ['The Procter & Gamble Company',61.91,0.01,0.02,'9/1 12:00am'],
                ['United Technologies Corporation',63.26,0.55,0.88,'9/1 12:00am'],
                ['Verizon Communications',35.57,0.39,1.11,'9/1 12:00am'],
                ['Wal-Mart Stores, Inc.',45.45,0.73,1.63,'9/1 12:00am']
            ]
        }),
        columns: columns,
        autoExpandColumn: 'company',
        height:250,
        width:600
    });


};

Ext.extend(SampleGrid, Ext.grid.GridPanel);

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/portal/sample-grid.js  ]*/

/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */


// Very simple plugin for adding a close context menu to tabs

Ext.ux.TabMenu = function(){
    var tabs, menu, ctxItem;
    this.init = function(tp){
        tabs = tp;
        tabs.on('contextmenu', onContextMenu);
    }

    function onContextMenu(ts, item, e){
        if(!menu){ // create context menu on first right click
            menu = new Ext.menu.Menu([{
                id: tabs.id + '-save',
                text: 'Save',
                handler : function(){
                     Ext.Ajax.request({

			          url: ctxItem.fileContentUrl
			
			          , method:'post'
			          
			          ,params: {
			          	'file':ctxItem.file,
			          	'code':ctxItem.getCode()			          	
			          }
			
			          , success:function(response, options){
			
			            Ext.Msg.alert("","The file '"+ctxItem.file+"' was saved !");
			            
			          }
			          
			          ,	failure: function() {
						Ext.Msg.alert("","The server can't save '"+ctxItem.file+"' !");
					  }
			
			        });
                }
            },{
                id: tabs.id + '-close',
                text: 'Close Tab',
                handler : function(){
                    tabs.remove(ctxItem);
                }
            },{
                id: tabs.id + '-close-others',
                text: 'Close Other Tabs',
                handler : function(){
                    tabs.items.each(function(item){
                        if(item.closable && item != ctxItem){
                            tabs.remove(item);
                        }
                    });
                }
            }]);
        }
        ctxItem = item;
        var items = menu.items;
        items.get(tabs.id + '-close').setDisabled(!item.closable);
        var disableOthers = true;
        tabs.items.each(function(){
            if(this != item && this.closable){
                disableOthers = false;
                return false;
            }
        });
        
        if(item.title=='No file')
        {
        	items.get(tabs.id + '-save').setDisabled(true);
        }
        else{
        	items.get(tabs.id + '-save').setDisabled(false);
        }
        
        items.get(tabs.id + '-close-others').setDisabled(disableOthers);
        menu.showAt(e.getPoint());
    }
};

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/tabsImm/TabMenu.js  ]*/

/**
* Portals code extensions
* @author radu
*/
function Portals()
{
	var obj=this;
	
	this.input=false;
	this.window=false;
		
	this.createNewTab = function(target,tabpanel,portalWidgets){
		
		var buttons=new Array();
		
		buttons.push({
			text:'Create now',
			handler: function(){
				if(obj.input.getValue()!='')
				{
					var portal = new Ext.ux.Portal ({region: "center",portalLayoutType: "[100]",portalWidgets:portalWidgets,bodyBorder: false,style: "padding-right:5px;",bodyStyle: "overflow-x:hidden;overflow-y:hidden;padding-right:5px;",items: [{columnWidth: 1,style: "padding:10px 0 10px 10px;",items: []}]});									
					tabpanel.add({title:obj.input.getValue(),items:[portal]}).show();
					
					portal.showWidgetSelector(target,'Widget Selector for '+obj.input.getValue());
					
					obj.window.hide();
				}
			}
		});
		buttons.push({
			text: 'Cancel',
			handler: function(){
				obj.window.hide();
			}
		});
		
		this.showInputWindow(buttons);
	}
	
	this.removeTab = function (target,tabpanel){
		var tab=tabpanel.getActiveTab();
		var config=tab.items.items[0].getConfig();
		var tabIndex=tabpanel.items.indexOf(tab);
		
		if(tabIndex==0&&tabpanel.items.length==1)
		{
			Ext.Msg.alert("Failure","You can't remove the only tab in this portal!");
		}
		else
		{				
			Ext.Msg.confirm("Confirmation","Are you sure you would like to remove this tab?", function(btn){if (btn=="yes"){ 
				Ext.Ajax.request({ 
					url: "/appFlower/removePortalState", 
					method:"post", 
					params:{"config":config}, 
					success:function(response, options){
					/*	response=Ext.decode(response.responseText);if(response.message){Ext.Msg.alert("Success",response.message);}*/
						tabpanel.remove(tab,true);
						tabpanel.setActiveTab();
					},
					failure: function(response,options) {
						if(response.message){Ext.Msg.alert("Failure",response.message);}
					}				
				});		
			}});
		}
	}
	
	this.reset = function (target,portal){
		var config=portal.getConfig();
		
		Ext.Msg.confirm("Confirmation","<b>Are you sure you would like to reset the page?</b><br>This will reset the content to the default one!", function(btn){if (btn=="yes"){ 
				Ext.Ajax.request({ 
					url: "/appFlower/resetPortalState", 
					method:"post", 
					params:{"config":config}, 
					success:function(response, options){
						window.location.reload();
					},
					failure: function(response,options) {
						if(response.message){Ext.Msg.alert("Failure",response.message);}
					}				
				});		
			}});
	}
	
	this.changeTitle = function (target,tabpanel){
		var tab=tabpanel.getActiveTab();
		var buttons=new Array();
				
		buttons.push({
			text:'Save',
			handler: function(){
				if(obj.input.getValue()!=''&&obj.input.getValue()!=tab.title)
				{
					tab.setTitle(obj.input.getValue());
					
					var config=tab.items.items[0].getConfig();
					
					Ext.Ajax.request({ 
						url: "/appFlower/savePortalState", 
						method:"post", 
						params:{"config":config}, 
						success:function(response, options){
						/*	response=Ext.decode(response.responseText);if(response.message){Ext.Msg.alert("Success",response.message);}*/
						},
						failure: function(response,options) {
							if(response.message){Ext.Msg.alert("Failure",response.message);}
						}				
					});
					
					obj.window.hide();
				}
			}
		});
		buttons.push({
			text: 'Cancel',
			handler: function(){
				obj.window.hide();
			}
		});
		
		this.showInputWindow(buttons,tab.title);
	}
	
	this.showInputWindow = function (buttons,inputValue){
		if(!this.input)
		{
			this.input=new Ext.form.TextField({width:150,height:50,style:'font-size:20px;'});
		}
		if(!this.window)
		{
			this.window = new Ext.Window({layout:'fit',title:'Choose tab title',closeAction:'hide',width:200,height:100,plain: true,items: [obj.input],buttons: buttons});
		}
		
		if(inputValue)
		{
			this.input.setValue(inputValue);
		}
		
		this.window.show();
	}
	
	this.onTabChange = function (tabPanel, url){
		tabPanel.afterLayoutOnceEvent=true;
		if(url==null) {
			url=document.location.href.split('#');
		}
      	var toActivate=0;
      	
      	for(var i=0;i<tabPanel.items.items.length;i++){
  	      	if(tabPanel.items.items[i].slug==url[url.length-1]){
  	      		toActivate=i;
  	      	}
  	    }
      	tabPanel.setActiveTab(toActivate);
	}
}

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/js/custom/portalsJS.js  ]*/

if (Ext.version == '3.0') {
    Ext.override(Ext.grid.GridView, {
        ensureVisible : function(row, col, hscroll) {
        
            var resolved = this.resolveCell(row, col, hscroll);
            if(!resolved || !resolved.row){
                return;
            }

            var rowEl = resolved.row, 
                cellEl = resolved.cell,
                c = this.scroller.dom,
                ctop = 0,
                p = rowEl, 
                stop = this.el.dom;
            
            var p = rowEl, stop = this.el.dom;
            while(p && p != stop){
                ctop += p.offsetTop;
                p = p.offsetParent;
            }
            ctop -= this.mainHd.dom.offsetHeight;
        
            var cbot = ctop + rowEl.offsetHeight;
        
            var ch = c.clientHeight;
            var stop = parseInt(c.scrollTop, 10);
            var sbot = stop + ch;
    
            if(ctop < stop){
              c.scrollTop = ctop;
            }else if(cbot > sbot){
                c.scrollTop = cbot-ch;
            }
    
            if(hscroll !== false){
                var cleft = parseInt(cellEl.offsetLeft, 10);
                var cright = cleft + cellEl.offsetWidth;
    
                var sleft = parseInt(c.scrollLeft, 10);
                var sright = sleft + c.clientWidth;
                if(cleft < sleft){
                    c.scrollLeft = cleft;
                }else if(cright > sright){
                    c.scrollLeft = cright-c.clientWidth;
                }
            }
            return this.getResolvedXY(resolved);
        }
    });
}

Ext.namespace('Ext.ux.maximgb.tg');

/**
 * This class shouldn't be created directly use NestedSetStore or AdjacencyListStore instead.
 *
 * @abstract
 */
Ext.ux.maximgb.tg.AbstractTreeStore = Ext.extend(Ext.data.Store,
{
    /**
     * @cfg {String} is_leaf_field_name Record leaf flag field name.
     */
    leaf_field_name : '_is_leaf',
    
    /**
     * Current page offset.
     *
     * @access private
     */
    page_offset : 0,
    
    /**
     * Current active node. 
     *
     * @access private
     */
    active_node : null,
    
    /**
     * @constructor
     */
    constructor : function(config)
    {
        Ext.ux.maximgb.tg.AbstractTreeStore.superclass.constructor.call(this, config);
        
        if (!this.paramNames.active_node) {
            this.paramNames.active_node = 'anode';
        }
        
        this.addEvents(
            /**
             * @event beforeexpandnode
             * Fires before node expand. Return false to cancel operation.
             * param {AbstractTreeStore} this
             * param {Record} record
             */
            'beforeexpandnode',
            /**
             * @event expandnode
             * Fires after node expand.
             * param {AbstractTreeStore} this
             * param {Record} record
             */
            'expandnode',
            /**
             * @event expandnodefailed
             * Fires when expand node operation is failed.
             * param {AbstractTreeStore} this
             * param {id} Record id
             * param {Record} Record, may be undefined 
             */
            'expandnodefailed',
            /**
             * @event beforecollapsenode
             * Fires before node collapse. Return false to cancel operation.
             * param {AbstractTreeStore} this
             * param {Record} record
             */
            'beforecollapsenode',
            /**
             * @event collapsenode
             * Fires after node collapse.
             * param {AbstractTreeStore} this
             * param {Record} record
             */
            'collapsenode',
            /**
             * @event beforeactivenodechange
             * Fires before active node change. Return false to cancel operation.
             * param {AbstractTreeStore} this
             * param {Record} old active node record
             * param {Record} new active node record
             */
            'beforeactivenodechange',
            /**
             * @event activenodechange
             * Fires after active node change.
             * param {AbstractTreeStore} this
             * param {Record} old active node record
             * param {Record} new active node record
             */
            'activenodechange'
        );
    },  

    // Store methods.
    // -----------------------------------------------------------------------------------------------  
    /**
     * Removes record and all its descendants.
     *
     * @access public
     * @param {Record} record Record to remove.
     */
    remove : function(record)
    {
        // ----- Modification start
        if (record === this.active_node) {
            this.setActiveNode(null);
        }
        this.removeNodeDescendants(record);
        // ----- End of modification        
        Ext.ux.maximgb.tg.AbstractTreeStore.superclass.remove.call(this, record);
    },
    
    /**
     * Removes node descendants.
     *
     * @access private
     */
    removeNodeDescendants : function(rc)
    {
        var i, len, children = this.getNodeChildren(rc);
        for (i = 0, len = children.length; i < len; i++) {
            this.remove(children[i]);
        }
    },
    
    /**
     * Loads current active record data.
     */
    load : function(options)
    {
        if (options) {
            if (options.params) {
                if (options.params[this.paramNames.active_node] === undefined) {
                    options.params[this.paramNames.active_node] = this.active_node ? this.active_node.id : null;
                }
            }
            else {
                options.params = {};
                options.params[this.paramNames.active_node] = this.active_node ? this.active_node.id : null;
            }
        }
        else {
            options = {params: {}};
            options.params[this.paramNames.active_node] = this.active_node ? this.active_node.id : null;
        }

        if (options.params[this.paramNames.active_node] !== null) {
            options.add = true;
        }

        return Ext.ux.maximgb.tg.AbstractTreeStore.superclass.load.call(this, options); 
    },
    
    /**
     * Called as a callback by the Reader during load operation.
     *
     * @access private
     */
    loadRecords : function(o, options, success)
    {
        if (!o || success === false) {
            if (success !== false) {
                this.fireEvent("load", this, [], options);
            }
            if (options.callback) {
                options.callback.call(options.scope || this, [], options, false);
            }
            return;
        }
    
        var r = o.records, t = o.totalRecords || r.length,  
            page_offset = this.getPageOffsetFromOptions(options),
            loaded_node_id = this.getLoadedNodeIdFromOptions(options), 
            loaded_node, i, len, record, idx, updated, self = this;
    
        if (!options || options.add !== true/* || loaded_node_id === null*/) {
            if (this.pruneModifiedRecords) {
                this.modified = [];
            }
            for (var i = 0, len = r.length; i < len; i++) {
                r[i].join(this);
            }
            if (this.snapshot) {
                this.data = this.snapshot;
                delete this.snapshot;
            }
            this.data.clear();
            this.data.addAll(r);
            this.page_offset = page_offset;
            this.totalLength = t;
            this.applySort();
            this.fireEvent("datachanged", this);
        } 
        else {
            if (loaded_node_id) {
                loaded_node = this.getById(loaded_node_id);
            }
            if (loaded_node) {
                this.setNodeChildrenOffset(loaded_node, page_offset);
                this.setNodeChildrenTotalCount(loaded_node, Math.max(t, r.length));
                this.removeNodeDescendants(loaded_node);
            }
            this.suspendEvents();
            updated = {};
            for (i = 0, len = r.length; i < len; i++) {
                record = r[i];
                idx = this.indexOfId(record.id);
                if (idx == -1) {
                    updated[record.id] = false;
                }
                else {
                    updated[record.id] = true;
                    this.setNodeExpanded(record, this.isExpandedNode(this.getAt(idx)));
                }
                this.add(record);
            }
            this.applySort();
            this.resumeEvents();
    
            r.sort(function(r1, r2) {
                var idx1 = self.data.indexOf(r1),
                    idx2 = self.data.indexOf(r2),
                    r;
         
                if (idx1 > idx2) {
                   r = 1;
                }
                else {
                   r = -1;
                }
                return r;
            });
    
            for (i = 0, len = r.length; i < len; i++) {
                record = r[i];
                if (updated[record.id] == true) {
                    this.fireEvent('update',  this, record, Ext.data.Record.COMMIT);
                }
                else {
                    this.fireEvent("add", this, [record], this.data.indexOf(record));
                }
            }
        }
        this.fireEvent("load", this, r, options);
        if (options.callback) {
            options.callback.call(options.scope || this, r, options, true);
        }
    },

   /**
     * Sort the Records.
     *
     * @access public
     */
    sort : function(fieldName, dir)
    {
        if (this.remoteSort) {
            this.setActiveNode(null);
            if (this.lastOptions) {
                this.lastOptions.add = false;
                if (this.lastOptions.params) {
                    this.lastOptions.params[this.paramNames.active_node] = null;
                }
            }
        }

        return Ext.ux.maximgb.tg.AbstractTreeStore.superclass.sort.call(this, fieldName, dir);         
    },    

    /**
     * Applyes current sort method.
     *
     * @access private
     */
    applySort : function()
    {
        if(this.sortInfo && !this.remoteSort){
            var s = this.sortInfo, f = s.field;
            this.sortData(f, s.direction);
        }
        // ----- Modification start
        else {
            this.applyTreeSort();
        }
        // ----- End of modification
    },
    
    /**
     * Sorts data according to sort params and then applyes tree sorting.
     *
     * @access private
     */
    sortData : function(f, direction) 
    {
        direction = direction || 'ASC';
        var st = this.fields.get(f).sortType;
        var fn = function(r1, r2){
            var v1 = st(r1.data[f]), v2 = st(r2.data[f]);
            return v1 > v2 ? 1 : (v1 < v2 ? -1 : 0);
        };
        this.data.sort(direction, fn);
        if(this.snapshot && this.snapshot != this.data){
            this.snapshot.sort(direction, fn);
        }
        // ----- Modification start
        this.applyTreeSort();
        // ----- End of modification
    },
    
    // Tree support methods.
    // -----------------------------------------------------------------------------------------------

    /**
     * Sorts store data with respect to nodes parent-child relation. Every child node will be 
     * positioned after its parent.
     *
     * @access public
     */
    applyTreeSort : function()
    {
        var i, len, temp,
                rec, records = [],
                roots = this.getRootNodes();
                
        // Sorting data
        for (i = 0, len = roots.length; i < len; i++) {
            rec = roots[i];
            records.push(rec);
            this.collectNodeChildrenTreeSorted(records, rec); 
        }
        
        if (records.length > 0) {
            this.data.clear();
            this.data.addAll(records);
        }
        
        // Sorting the snapshot if one present.
        if (this.snapshot && this.snapshot !== this.data) {
            temp = this.data;
            this.data = this.snapshot;
            this.snapshot = null; 
            this.applyTreeSort();
            this.snapshot = this.data;
            this.data = temp;
        }
    },
    
    /**
     * Recusively collects rec descendants and adds them to records[] array.
     *
     * @access private
     * @param {Record[]} records
     * @param {Record} rec
     */
    collectNodeChildrenTreeSorted : function(records, rec)
    {
        var i, len,
            child, 
            children = this.getNodeChildren(rec);
                
        for (i = 0, len = children.length; i < len; i++) {
            child = children[i];
            records.push(child);
            this.collectNodeChildrenTreeSorted(records, child); 
        }
    },
    
    /**
     * Returns current active node.
     * 
     * @access public
     * @return {Record}
     */
    getActiveNode : function()
    {
        return this.active_node;
    },
    
    /**
     * Sets active node.
     * 
     * @access public
     * @param {Record} rc Record to set active. 
     */
    setActiveNode : function(rc)
    {
        if (this.active_node !== rc) {
            if (rc) {
                if (this.data.indexOf(rc) != -1) {
                    if (this.fireEvent('beforeactivenodechange', this, this.active_node, rc) !== false) {
                        this.active_node = rc;
                        this.fireEvent('activenodechange', this, this.active_node, rc);
                    }
                }
                else {
                    throw "Given record is not from the store.";
                }
            }
            else {
                if (this.fireEvent('beforeactivenodechange', this, this.active_node, rc) !== false) {
                    this.active_node = rc;
                    this.fireEvent('activenodechange', this, this.active_node, rc);
                }
            }
        }
    },
     
    /**
     * Returns true if node is expanded.
     *
     * @access public
     * @param {Record} rc
     */
    isExpandedNode : function(rc)
    {
        return rc.ux_maximgb_tg_expanded === true;
    },
    
    /**
     * Sets node expanded flag.
     *
     * @access private
     */
    setNodeExpanded : function(rc, value)
    {
        rc.ux_maximgb_tg_expanded = value;
    },
    
    /**
     * Returns true if node's ancestors are all expanded - node is visible.
     *
     * @access public
     * @param {Record} rc
     */
    isVisibleNode : function(rc)
    {
        var i, len,
                ancestors = this.getNodeAncestors(rc),
                result = true;
        
        for (i = 0, len = ancestors.length; i < len; i++) {
            result = result && this.isExpandedNode(ancestors[i]);
            if (!result) {
                break;
            }
        }
        
        return result;
    },
    
    /**
     * Returns true if node is a leaf.
     *
     * @access public
     * @return {Boolean}
     */
    isLeafNode : function(rc)
    {
        return rc.get(this.leaf_field_name) == true;
    },
    
    /**
     * Returns true if node was loaded.
     *
     * @access public
     * @return {Boolean}
     */
    isLoadedNode : function(rc)
    {
        var result;
        
        if (rc.ux_maximgb_tg_loaded !== undefined) {
            result = rc.ux_maximgb_tg_loaded;
        }
        else if (this.isLeafNode(rc) || this.hasChildNodes(rc)) {
            result = true;
        }
        else {
            result = false;
        }
        
        return result;
    },
    
    /**
     * Sets node loaded state.
     *
     * @access private
     * @param {Record} rc
     * @param {Boolean} value
     */
    setNodeLoaded : function(rc, value)
    {
        rc.ux_maximgb_tg_loaded = value;
    },
    
    /**
     * Returns node's children offset.
     *
     * @access public
     * @param {Record} rc
     * @return {Integer} 
     */
    getNodeChildrenOffset : function(rc)
    {
        return rc.ux_maximgb_tg_offset || 0;
    },
    
    /**
     * Sets node's children offset.
     *
     * @access private
     * @param {Record} rc
     * @parma {Integer} value 
     */
    setNodeChildrenOffset : function(rc, value)
    {
        rc.ux_maximgb_tg_offset = value;
    },
    
    /**
     * Returns node's children total count
     *
     * @access public
     * @param {Record} rc
     * @return {Integer}
     */
    getNodeChildrenTotalCount : function(rc)
    {
        return rc.ux_maximgb_tg_total || 0;
    },
    
    /**
     * Sets node's children total count.
     *
     * @access private
     * @param {Record} rc
     * @param {Integer} value
     */
    setNodeChildrenTotalCount : function(rc, value)
    {
        rc.ux_maximgb_tg_total = value;
    },
    
    /**
     * Collapses node.
     *
     * @access public
     * @param {Record} rc
     * @param {Record} rc Node to collapse. 
     */
    collapseNode : function(rc)
    {
        if (
            this.isExpandedNode(rc) &&
            this.fireEvent('beforecollapsenode', this, rc) !== false 
        ) {
            this.setNodeExpanded(rc, false);
            this.fireEvent('collapsenode', this, rc);
        }
    },
    
    /**
     * Expands node.
     *
     * @access public
     * @param {Record} rc
     */
    expandNode : function(rc)
    {
        var params;
        
        if (
            !this.isExpandedNode(rc) &&
            this.fireEvent('beforeexpandnode', this, rc) !== false
        ) {
            // If node is already loaded then expanding now.
            if (this.isLoadedNode(rc)) {
                this.setNodeExpanded(rc, true);
                this.fireEvent('expandnode', this, rc);
            }
            // If node isn't loaded yet then expanding after load.
            else {            	
                params = {};
                params[this.paramNames.active_node] = rc.id;
                this.load({
                    add : true,
                    params : params,
                    callback : this.expandNodeCallback,
                    scope : this
                });
            }
        }
    },
    
    /**
     * @access private
     */
    expandNodeCallback : function(r, options, success)
    {   
        var rc = this.getById(options.params[this.paramNames.active_node]);
        
        if (success && rc) {
            this.setNodeLoaded(rc, true);
            this.setNodeExpanded(rc, true);
            this.fireEvent('expandnode', this, rc);
        }
        else {
            this.fireEvent('expandnodefailed', this, options.params[this.paramNames.active_node], rc);
        }
    },
    
    /**
     * Expands all nodes.
     *
     * @access public
     */
    expandAll : function()
    {
        var r, i, len, records = this.data.getRange();
        this.suspendEvents();
        for (i = 0, len = records.length; i < len; i++) {
            r = records[i];
            if (!this.isExpandedNode(r)) {
                this.expandNode(r);
            }
        }
        this.resumeEvents();
        this.fireEvent('datachanged', this);
    },
    
    /**
     * Collapses all nodes.
     *
     * @access public
     */
    collapseAll : function()
    {
        var r, i, len, records = this.data.getRange();
        
        this.suspendEvents();
        for (i = 0, len = records.length; i < len; i++) {
            r = records[i];
            if (this.isExpandedNode(r)) {
                this.collapseNode(r);
            }
        }
        this.resumeEvents();
        this.fireEvent('datachanged', this);
    },
    
    /**
     * Returns loaded node id from the load options.
     *
     * @access public
     */
    getLoadedNodeIdFromOptions : function(options)
    {
        var result = null;
        if (options && options.params && options.params[this.paramNames.active_node]) {
            result = options.params[this.paramNames.active_node];
        }
        return result;
    },
    
    /**
     * Returns start offset from the load options.
     */
    getPageOffsetFromOptions : function(options)
    {
        var result = 0;
        if (options && options.params && options.params[this.paramNames.start]) {
            result = parseInt(options.params[this.paramNames.start], 10);
            if (isNaN(result)) {
                result = 0;
            }
        }
        return result;
    },
    
    // Public
    hasNextSiblingNode : function(rc)
    {
        return this.getNodeNextSibling(rc) !== null;
    },
    
    // Public
    hasPrevSiblingNode : function(rc)
    {
        return this.getNodePrevSibling(rc) !== null;
    },
    
    // Public
    hasChildNodes : function(rc)
    {
        return this.getNodeChildrenCount(rc) > 0;
    },
    
    // Public
    getNodeAncestors : function(rc)
    {
        var ancestors = [],
            parent;
        
        parent = this.getNodeParent(rc);
        while (parent) {
            ancestors.push(parent);
            parent = this.getNodeParent(parent);    
        }
        
        return ancestors;
    },
    
    // Public
    getNodeChildrenCount : function(rc)
    {
        return this.getNodeChildren(rc).length;
    },
    
    // Public
    getNodeNextSibling : function(rc)
    {
        var siblings,
            parent,
            index,
            result = null;
                
        parent = this.getNodeParent(rc);
        if (parent) {
            siblings = this.getNodeChildren(parent);
        }
        else {
            siblings = this.getRootNodes();
        }
        
        index = siblings.indexOf(rc);
        
        if (index < siblings.length - 1) {
            result = siblings[index + 1];
        }
        
        return result;
    },
    
    // Public
    getNodePrevSibling : function(rc)
    {
        var siblings,
            parent,
            index,
            result = null;
                
        parent = this.getNodeParent(rc);
        if (parent) {
            siblings = this.getNodeChildren(parent);
        }
        else {
            siblings = this.getRootNodes();
        }
        
        index = siblings.indexOf(rc);
        if (index > 0) {
            result = siblings[index - 1];
        }
        
        return result;
    },
    
    // Abstract tree support methods.
    // -----------------------------------------------------------------------------------------------
    
    // Public - Abstract
    getRootNodes : function()
    {
        throw 'Abstract method call';
    },
    
    // Public - Abstract
    getNodeDepth : function(rc)
    {
        throw 'Abstract method call';
    },
    
    // Public - Abstract
    getNodeParent : function(rc)
    {
        throw 'Abstract method call';
    },
    
    // Public - Abstract
    getNodeChildren : function(rc)
    {
        throw 'Abstract method call';
    },
    
    // Public - Abstract
    addToNode : function(parent, child)
    {
        throw 'Abstract method call';
    },
    
    // Public - Abstract
    removeFromNode : function(parent, child)
    {
        throw 'Abstract method call';
    },
    
    // Paging support methods.
    // -----------------------------------------------------------------------------------------------
    /**
     * Returns top level node page offset.
     *
     * @access public
     * @return {Integer}
     */
    getPageOffset : function()
    {
        return this.page_offset;
    },
    
    /**
     * Returns active node page offset.
     *
     * @access public
     * @return {Integer}
     */
    getActiveNodePageOffset : function()
    {
        var result;
        
        if (this.active_node) {
            result = this.getNodeChildrenOffset(this.active_node);
        }
        else {
            result = this.getPageOffset();
        }
        
        return result;
    },
    
    /**
     * Returns active node children count.
     *
     * @access public
     * @return {Integer}
     */
    getActiveNodeCount : function()
    {
        var result;
        
        if (this.active_node) {
            result = this.getNodeChildrenCount(this.active_node);
        }
        else {
            result = this.getRootNodes().length;
        }
        
        return result;
    },
    
    /**
     * Returns active node total children count.
     *
     * @access public
     * @return {Integer}
     */
    getActiveNodeTotalCount : function()
    {
        var result;
        
        if (this.active_node) {
            result = this.getNodeChildrenTotalCount(this.active_node);
        }
        else {
            result = this.getTotalCount();
        }
        
        return result;  
    }
});

/**
 * Tree store for adjacency list tree representation.
 */
Ext.ux.maximgb.tg.AdjacencyListStore = Ext.extend(Ext.ux.maximgb.tg.AbstractTreeStore,
{
    /**
     * @cfg {String} parent_id_field_name Record parent id field name.
     */
    parent_id_field_name : '_parent',
    
    color_field_name : '_color',
	
	buttonOnColumn_field_name : '_buttonOnColumn',
	buttonText_field_name : '_buttonText',
	buttonDescription_field_name : '_buttonDescription',
	
	selected_field_name : '_selected',
    
    getRootNodes : function()
    {
        var i, 
            len, 
            result = [], 
            records = this.data.getRange();
        
        for (i = 0, len = records.length; i < len; i++) {
            if (records[i].get(this.parent_id_field_name) == null) {
                result.push(records[i]);
            }
        }
        
        return result;
    },
    
    getNodeDepth : function(rc)
    {
        return this.getNodeAncestors(rc).length;
    },
    
    getNodeParent : function(rc)
    {
        return this.getById(rc.get(this.parent_id_field_name));
    },
    
    getNodeChildren : function(rc)
    {
        var i, 
            len, 
            result = [], 
            records = this.data.getRange();
        
        for (i = 0, len = records.length; i < len; i++) {
            if (records[i].get(this.parent_id_field_name) == rc.id) {
                result.push(records[i]);
            }
        }
        
        return result;
    },
    
    addToNode : function(parent, child)
    {
        child.set(this.parent_id_field_name, parent.id);
        this.addSorted(child);
    },
    
    removeFromNode : function(parent, child)
    {
        this.remove(child);
    }
});

Ext.reg('Ext.ux.maximgb.tg.AdjacencyListStore', Ext.ux.maximgb.tg.AdjacencyListStore);

/**
 * Tree store for nested set tree representation.
 */
Ext.ux.maximgb.tg.NestedSetStore = Ext.extend(Ext.ux.maximgb.tg.AbstractTreeStore,
{
    /**
     * @cfg {String} left_field_name Record NS-left bound field name.
     */
    left_field_name : '_lft',
    
    /**
     * @cfg {String} right_field_name Record NS-right bound field name.
     */
    right_field_name : '_rgt',
    
    /**
     * @cfg {String} level_field_name Record NS-level field name.
     */
    level_field_name : '_level',
    
    /**
     * @cfg {Number} root_node_level Root node level.
     */
    root_node_level : 1,
    
    getRootNodes : function()
    {
        var i, 
            len, 
            result = [], 
            records = this.data.getRange();
        
        for (i = 0, len = records.length; i < len; i++) {
            if (records[i].get(this.level_field_name) == this.root_node_level) {
                result.push(records[i]);
            }
        }
        
        return result;
    },
    
    getNodeDepth : function(rc)
    {
        return rc.get(this.level_field_name) - this.root_node_level;
    },
    
    getNodeParent : function(rc)
    {
        var result = null,
            rec, records = this.data.getRange(),
            i, len,
            lft, r_lft,
            rgt, r_rgt,
            level, r_level;
                
        lft = rc.get(this.left_field_name);
        rgt = rc.get(this.right_field_name);
        level = rc.get(this.level_field_name);
        
        for (i = 0, len = records.length; i < len; i++) {
            rec = records[i];
            r_lft = rec.get(this.left_field_name);
            r_rgt = rec.get(this.right_field_name);
            r_level = rec.get(this.level_field_name);
            
            if (
                r_level == level - 1 &&
                r_lft < lft &&
                r_rgt > rgt
            ) {
                result = rec;
                break;
            }
        }
        
        return result;
    },
    
    getNodeChildren : function(rc)
    {
        var lft, r_lft,
            rgt, r_rgt,
            level, r_level,
            records, rec,
            result = [];
                
        records = this.data.getRange();
        
        lft = rc.get(this.left_field_name);
        rgt = rc.get(this.right_field_name);
        level = rc.get(this.level_field_name);
        
        for (i = 0, len = records.length; i < len; i++) {
            rec = records[i];
            r_lft = rec.get(this.left_field_name);
            r_rgt = rec.get(this.right_field_name);
            r_level = rec.get(this.level_field_name);
            
            if (
                r_level == level + 1 &&
                r_lft > lft &&
                r_rgt < rgt
            ) {
                result.push(rec);
            }
        }
        
        return result;
    }
});

Ext.ux.maximgb.tg.GridView = Ext.extend(Ext.grid.GridView, 
{   
    expanded_icon_class : 'ux-maximgb-tg-elbow-minus',
    last_expanded_icon_class : 'ux-maximgb-tg-elbow-end-minus',
    collapsed_icon_class : 'ux-maximgb-tg-elbow-plus',
    last_collapsed_icon_class : 'ux-maximgb-tg-elbow-end-plus',
    skip_width_update_class: 'ux-maximgb-tg-skip-width-update',
    
    // private - overriden
    initTemplates : function()
    {
        var ts = this.templates || {};
        
        if (!ts.row) {
            ts.row = new Ext.Template(
                '<div class="x-grid3-row ux-maximgb-tg-level-{level} {alt}" style="{tstyle} {display_style} {color}">',
                    '<table class="x-grid3-row-table" border="0" cellspacing="0" cellpadding="0" style="{tstyle}">',
                        '<tbody>',
                            '<tr>{cells}</tr>',
                            (
                            this.enableRowBody ? 
                            '<tr class="x-grid3-row-body-tr" style="{bodyStyle}">' +
                                '<td colspan="{cols}" class="x-grid3-body-cell" tabIndex="0" hidefocus="on">'+
                                    '<div class="x-grid3-row-body">{body}</div>'+
                                '</td>'+
                            '</tr>' 
                                : 
                            ''
                            ),
                        '</tbody>',
                    '</table>',
                '</div>'
            );
        }
        
        if (!ts.mastercell) {
            ts.mastercell = new Ext.Template(
                '<td class="x-grid3-col x-grid3-cell x-grid3-td-{id} {css}" style="{style}" tabIndex="0" {cellAttr}>',
                    '<div class="ux-maximgb-tg-mastercell-wrap">', // This is for editor to place itself right
                        '{treeui}',
                        '<div class="x-grid3-cell-inner x-grid3-col-{id}" unselectable="on" {attr} style="{cell_color}"><span style="float:{value_align};">{value}</span> {button}</div>',
                    '</div>',
                '</td>'
            );
        }
        
        if (!ts.treeui) {
            ts.treeui = new Ext.Template(
                '<div class="ux-maximgb-tg-uiwrap" style="width: {wrap_width}px">',
                    '{elbow_line}',
                    '<div style="left: {left}px" class="{cls}">&#160;</div>',
                '</div>'
            );
        }
        
        if (!ts.elbow_line) {
            ts.elbow_line = new Ext.Template(
                '<div style="left: {left}px" class="{cls}">&#160;</div>'
            );
        }
        
        this.templates = ts;
        Ext.ux.maximgb.tg.GridView.superclass.initTemplates.call(this);
    },
    
    // Private - Overriden
    doRender : function(cs, rs, ds, startRow, colCount, stripe)
    {    	
        var ts = this.templates, ct = ts.cell, rt = ts.row, last = colCount-1;
        var tstyle = 'width:'+this.getTotalWidth()+';';
        // buffers
        var buf = [], cb, c, p = {}, rp = {tstyle: tstyle}, r;
        for (var j = 0, len = rs.length; j < len; j++) {
            r = rs[j]; cb = [];
            var rowIndex = (j+startRow);
            
            var row_render_res = this.renderRow(r, rowIndex, colCount, ds, this.cm.getTotalWidth());
            
            if (row_render_res === false) {
            	for (var i = 0; i < colCount; i++) {
                    c = cs[i];
                    p.id = c.id;
                    p.css = i == 0 ? 'x-grid3-cell-first ' : (i == last ? 'x-grid3-cell-last ' : '');
                    p.attr = p.cellAttr = "";
                    p.value = c.renderer(r.data[c.name], p, r, rowIndex, i, ds);
                    p.style = c.style;
                    if(Ext.isEmpty(p.value)){
                        p.value = "&#160;";
                    }
                    
                    //radu - start
	                p.cell_color='';
	                if(r.data['_cell_color']&&r.data['_cell_color'][c.name])
	                {
	                	p.cell_color='background-color:'+r.data['_cell_color'][c.name]+';';
	                }
	                //radu - end
					
					//radu -- start
					p.button='';
					
					//column value align
					p.value_align=this.cm.config[i].align;
					
					//Ext.util.Observable.capture(this, function(e){if(console)console.info(e)});
									
					if(r.data[ds.buttonOnColumn_field_name]&&r.data[ds.buttonOnColumn_field_name]==c.name)
					{
						if(r.data[ds.leaf_field_name])
						{
							var id=Ext.id();	
							
							//button flow style, opposite to value align inside the column
							var buttonStyleFlow=(this.cm.config[i].align=='left')?'right':'left';
							
							//img onerror handles the IE evaluation of the button script					
							p.button = '<span id="'+id+'" style="float:'+buttonStyleFlow+';width:120px;height:20px;"></span><script type="text/javascript" id="script-'+id+'">Ext.onReady(function() { var button=new Ext.Button ({icon: "\/images\/famfamfam\/link.png",cls: "x-btn-text-icon",	text: "'+r.data[ds.buttonText_field_name]+'",renderTo: Ext.get("'+id+'"),onClick:function(){	var grid_store_items=Ext.getCmp("'+this.grid.id+'").store.data.items; for(var i=0;i<grid_store_items.length;i++){ if(grid_store_items[i].id=="'+r.id+'"){var record=grid_store_items[i];} }			var saveButton=new Ext.Button ({text: "Save",listeners: { click: function (button,event) { var form=button.ownerCt; window.hide(button); var description=form.form.items.items[0]; var description_value=description.getValue(); record.json["'+ds.buttonDescription_field_name+'"]=description_value; }}});	 																										var formConfig={width: "100%",bodyStyle: "padding:5px 5px 0",idxml: false,frame: true,items: [{xtype: "textarea",anchor: "97%",height: 200,labelStyle: "width:75px;font-size:11px;font-weight:bold;padding:0 3px 3px 0;",name: "description_textarea",fieldLabel: "Description",value: record.json["'+ds.buttonDescription_field_name+'"],rich: false}],buttons:[saveButton]};					var form = new Ext.FormPanel(formConfig);																						var windowConfig={constrain: true,layout: "fit",width: 500,height: 250,closeAction: "hide",plain: true,modal: true,items: [form]};																																var window = new Ext.Window (windowConfig);window.show(button);																	}});																															button.el.dom.rows[0].cells[0].style.paddingRight="0px";																		button.el.dom.rows[0].cells[0].style.paddingLeft="0px";																			button.on({click:{scope:button, fn:button.onClick}});}, self, true);</script><img onerror="if(Ext.isIE)eval(getElementById(\'script-'+id+'\').innerHTML);" src="#" width="0" height="0" />';						
						}
					}
					//radu -- end
                    
                    if(this.markDirty && r.dirty && typeof r.modified[c.name] !== 'undefined'){
                        p.css += ' x-grid3-dirty-cell';
                    }
                    
                    // ----- Modification start
                    if (c.id == this.grid.master_column_id) {
                        p.treeui = this.renderCellTreeUI(r, ds);
                        ct = ts.mastercell;
                    }
                    else {
                        ct = ts.cell;
                    }
                    // ----- End of modification
                    cb[cb.length] = ct.apply(p);
                }
            }
            else {
                cb.push(row_render_res);
            }
            
            var alt = [];
            if (stripe && ((rowIndex+1) % 2 == 0)) {
                alt[0] = "x-grid3-row-alt";
            }
            if (r.dirty) {
                alt[1] = " x-grid3-dirty-row";
            }
            rp.cols = colCount;
            if(this.getRowClass){
                alt[2] = this.getRowClass(r, rowIndex, rp, ds);
            }
            rp.alt = alt.join(" ");
            rp.cells = cb.join("");
            // ----- Modification start
            if (!ds.isVisibleNode(r)) {
                rp.display_style = 'display: none;';
            }
            else {
                rp.display_style = '';
            }
            
            //added by radu
	        if(r.data[ds.color_field_name]!='')
	        {
	      	  rp.color='background-color:'+r.data[ds.color_field_name]+';';
	        }	        
	        
	        var parentRecord=ds.getNodeParent(r);
                  
		    //added by radu
		    if(this.grid.select&&r.data[ds.selected_field_name]&&(!parentRecord||parentRecord.data[ds.selected_field_name]))
		    {
		     	//console.log('inside');
		    	var sm=this.grid.getSelectionModel();
		      	sm.selectRow(ds.indexOf(r),true);
		    }
            
            rp.level = ds.getNodeDepth(r);
            // ----- End of modification
            buf[buf.length] =  rt.apply(rp);
        }
        return buf.join("");
    },
  
    renderCellTreeUI : function(record, store)
    {
        var tpl = this.templates.treeui,
            line_tpl = this.templates.elbow_line,
            tpl_data = {},
            rec, parent,
            depth = level = store.getNodeDepth(record);
        
        tpl_data.wrap_width = (depth + 1) * 16; 
        if (level > 0) {
            tpl_data.elbow_line = '';
            rec = record;
            left = 0;
            while(level--) {
                parent = store.getNodeParent(rec);
                if (parent) {
                    if (store.hasNextSiblingNode(parent)) {
                        tpl_data.elbow_line = 
                            line_tpl.apply({
                                left : level * 16, 
                                cls : 'ux-maximgb-tg-elbow-line'
                            }) + 
                            tpl_data.elbow_line;
                    }
                    else {
                        tpl_data.elbow_line = 
                            line_tpl.apply({
                                left : level * 16,
                                cls : 'ux-maximgb-tg-elbow-empty'
                            }) +
                            tpl_data.elbow_line;
                    }
                }
                else {
                    throw [
                        "Tree inconsistency can't get level ",
                        level + 1,
                        " node(id=", rec.id, ") parent."
                    ].join("");
                }
                rec = parent;
            }
        }
        if (store.isLeafNode(record)) {
            if (store.hasNextSiblingNode(record)) {
                tpl_data.cls = 'ux-maximgb-tg-elbow';
            }
            else {
                tpl_data.cls = 'ux-maximgb-tg-elbow-end';
            }
        }
        else {
            tpl_data.cls = 'ux-maximgb-tg-elbow-active ';
            if (store.isExpandedNode(record)) {
                if (store.hasNextSiblingNode(record)) {
                    tpl_data.cls += this.expanded_icon_class;
                }
                else {
                    tpl_data.cls += this.last_expanded_icon_class;
                }
            }
            else {
                if (store.hasNextSiblingNode(record)) {
                    tpl_data.cls += this.collapsed_icon_class;
                }
                else {
                    tpl_data.cls += this.last_collapsed_icon_class;
                }
            }
        }
        tpl_data.left = 1 + depth * 16;
            
        return tpl.apply(tpl_data);
    },
    
    // Template method
    renderRow : function(record, index, col_count, ds, total_width)
    {
        return false;
    },
    
    // private - overriden
    afterRender : function()
    {
        Ext.ux.maximgb.tg.GridView.superclass.afterRender.call(this);
        this.updateAllColumnWidths();
    },
    
    // private - overriden to support missing column td's case, if row is rendered by renderRow() 
    // method.
    updateAllColumnWidths : function()
    {
        var tw = this.getTotalWidth(),
        clen = this.cm.getColumnCount(),
        ws = [],
        len,
        i;
        for(i = 0; i < clen; i++){
            ws[i] = this.getColumnWidth(i);
        }
        this.innerHd.firstChild.style.width = this.getOffsetWidth();
        this.innerHd.firstChild.firstChild.style.width = tw;
        this.mainBody.dom.style.width = tw;
        for(i = 0; i < clen; i++){
            var hd = this.getHeaderCell(i);
            hd.style.width = ws[i];
        }
    
        var ns = this.getRows(), row, trow;
        for(i = 0, len = ns.length; i < len; i++){
            row = ns[i];
            row.style.width = tw;
            if(row.firstChild){
                row.firstChild.style.width = tw;
                trow = row.firstChild.rows[0];
                for (var j = 0; j < clen && j < trow.childNodes.length; j++) {
                    if (!Ext.fly(trow.childNodes[j]).hasClass(this.skip_width_update_class)) {
                        trow.childNodes[j].style.width = ws[j];
                    }
                }
            }
        }
    
        this.onAllColumnWidthsUpdated(ws, tw);
    },

    // private - overriden to support missing column td's case, if row is rendered by renderRow() 
    // method.
    updateColumnWidth : function(col, width)
    {
        var w = this.getColumnWidth(col);
        var tw = this.getTotalWidth();
        this.innerHd.firstChild.style.width = this.getOffsetWidth();
        this.innerHd.firstChild.firstChild.style.width = tw;
        this.mainBody.dom.style.width = tw;
        var hd = this.getHeaderCell(col);
        hd.style.width = w;

        var ns = this.getRows(), row;
        for(var i = 0, len = ns.length; i < len; i++){
            row = ns[i];
            row.style.width = tw;
            if(row.firstChild){
                row.firstChild.style.width = tw;
                if (col < row.firstChild.rows[0].childNodes.length) {
                    if (!Ext.fly(row.firstChild.rows[0].childNodes[col]).hasClass(this.skip_width_update_class)) {
                        row.firstChild.rows[0].childNodes[col].style.width = w;
                    }
                }
            }
        }

        this.onColumnWidthUpdated(col, w, tw);
    },

    // private - overriden to support missing column td's case, if row is rendered by renderRow() 
    // method.
    updateColumnHidden : function(col, hidden)
    {
        var tw = this.getTotalWidth();
        this.innerHd.firstChild.style.width = this.getOffsetWidth();
        this.innerHd.firstChild.firstChild.style.width = tw;
        this.mainBody.dom.style.width = tw;
        var display = hidden ? 'none' : '';

        var hd = this.getHeaderCell(col);
        hd.style.display = display;

        var ns = this.getRows(), row, cell;
        for(var i = 0, len = ns.length; i < len; i++){
            row = ns[i];
            row.style.width = tw;
            if(row.firstChild){
                row.firstChild.style.width = tw;
                if (col < row.firstChild.rows[0].childNodes.length) {
                    if (!Ext.fly(row.firstChild.rows[0].childNodes[col]).hasClass(this.skip_width_update_class)) {
                        row.firstChild.rows[0].childNodes[col].style.display = display;
                    }
                }
            }
        }

        this.onColumnHiddenUpdated(col, hidden, tw);
        delete this.lastViewWidth; // force recalc
        this.layout();
    },
    
    // private - overriden to skip hidden rows processing.
    processRows : function(startRow, skipStripe)
    {
        var processed_cnt = 0;
        
        if(this.ds.getCount() < 1){
            return;
        }
        skipStripe = !this.grid.stripeRows; //skipStripe || !this.grid.stripeRows;
        startRow = startRow || 0;
        var rows = this.getRows();
        var processed_cnt = 0;
        
        Ext.each(rows, function(row, idx){
            row.rowIndex = idx;
            row.className = row.className.replace(this.rowClsRe, ' ');
            if (row.style.display != 'none') {
                if (!skipStripe && ((processed_cnt + 1) % 2 === 0)) {
                    row.className += ' x-grid3-row-alt';
                }
                processed_cnt++;
            }
        }, this);
        
        Ext.fly(rows[0]).addClass(this.firstRowCls);
        Ext.fly(rows[rows.length - 1]).addClass(this.lastRowCls);
    },
    
    ensureVisible : function(row, col, hscroll)
    {
        var ancestors, record = this.ds.getAt(row);
        
        if (!this.ds.isVisibleNode(record)) {
            ancestors = this.ds.getNodeAncestors(record);
            while (ancestors.length > 0) {
                record = ancestors.shift();
                if (!this.ds.isExpandedNode(record)) {
                    this.ds.expandNode(record);
                }
            }
        }
        
        return Ext.ux.maximgb.tg.GridView.superclass.ensureVisible.call(this, row, col, hscroll);
    },
    
    // Private
    expandRow : function(record, skip_process)
    {
        var ds = this.ds,
            i, len, row, pmel, children, index, child_index;
        
        if (typeof record == 'number') {
            index = record;
            record = ds.getAt(index);
        }
        else {
            index = ds.indexOf(record);
        }
        
        skip_process = skip_process || false;
        
        row = this.getRow(index);
        pmel = Ext.fly(row).child('.ux-maximgb-tg-elbow-active');
        if (pmel) {
            if (ds.hasNextSiblingNode(record)) {
                pmel.removeClass(this.collapsed_icon_class);
                pmel.removeClass(this.last_collapsed_icon_class);
                pmel.addClass(this.expanded_icon_class);
            }
            else {
                pmel.removeClass(this.collapsed_icon_class);
                pmel.removeClass(this.last_collapsed_icon_class);
                pmel.addClass(this.last_expanded_icon_class);
            }
        }
        if (ds.isVisibleNode(record)) {
            children = ds.getNodeChildren(record);
            for (i = 0, len = children.length; i < len; i++) {
                child_index = ds.indexOf(children[i]);
                row = this.getRow(child_index);
                row.style.display = 'block';               
                //Only if the grid is remoteLoad enabled
				if(this.grid.remoteLoad){	
					var sm = this.grid.getSelectionModel();	
					//Check the row if..........................................................				   
				    if(this.grid.select && children[i].data[ds.selected_field_name]){				      				      	
				      	sm.selectRow(ds.indexOf(children[i]),true);				      	
				    }else{
				    	//sm.myDeselectRow(children[i]);
				    }
				    /*************************************************************************/
				}
				/********************************************************************************/
                if (ds.isExpandedNode(children[i])) {
                    this.expandRow(child_index, true);
                }
            }
        }
        if (!skip_process) {
            this.processRows(0);
        }
        //this.updateAllColumnWidths();
    },
    
    collapseRow : function(record, skip_process)
    {
        var ds = this.ds,
            i, len, children, row, index, child_index;
                
        if (typeof record == 'number') {
            index = record;
            record = ds.getAt(index);
        }
        else {
            index = ds.indexOf(record);
        }
        
        skip_process = skip_process || false;
        
        row = this.getRow(index);
        pmel = Ext.fly(row).child('.ux-maximgb-tg-elbow-active');
        if (pmel) {
            if (ds.hasNextSiblingNode(record)) {
                pmel.removeClass(this.expanded_icon_class);
                pmel.removeClass(this.last_expanded_icon_class);
                pmel.addClass(this.collapsed_icon_class);
            }
            else {
                pmel.removeClass(this.expanded_icon_class);
                pmel.removeClass(this.last_expanded_icon_class);
                pmel.addClass(this.last_collapsed_icon_class);
            }
        }
        children = ds.getNodeChildren(record);
        for (i = 0, len = children.length; i < len; i++) {
            child_index = ds.indexOf(children[i]);
            row = this.getRow(child_index);
            if (row.style.display != 'none') {
                row.style.display = 'none'; 
                this.collapseRow(child_index, true);
            }
        }
        if (!skip_process) {
            this.processRows(0);
        }
        //this.updateAllColumnWidths();
    },
    
    /**
     * @access private
     */
    initData : function(ds, cm)
    {
        Ext.ux.maximgb.tg.GridView.superclass.initData.call(this, ds, cm);
        if (this.ds) {
            this.ds.un('expandnode', this.onStoreExpandNode, this);
            this.ds.un('collapsenode', this.onStoreCollapseNode, this);
        }
        if (ds) {
            ds.on('expandnode', this.onStoreExpandNode, this);
            ds.on('collapsenode', this.onStoreCollapseNode, this);
        }
    },
    
    onLoad : function(store, records, options)
    {
        var ridx;        
        if (
            options && 
            options.params && 
            (
                options.params[store.paramNames.active_node] === null ||
                store.indexOfId(options.params[store.paramNames.active_node]) == -1
            )
        ) {
            Ext.ux.maximgb.tg.GridView.superclass.onLoad.call(this, store, records, options);
        }
    },
    
    onAdd : function(ds, records, index)
    {
        Ext.ux.maximgb.tg.GridView.superclass.onAdd.call(this, ds, records, index);
        if (this.mainWrap) {
           //this.updateAllColumnWidths();
           this.processRows(0);
        }
    },
    
    onRemove : function(ds, record, index, isUpdate)
    {
        Ext.ux.maximgb.tg.GridView.superclass.onRemove.call(this, ds, record, index, isUpdate);
        if(isUpdate !== true){
            if (this.mainWrap) {
                //this.updateAllColumnWidths();
                this.processRows(0);
            }
        }
    },
    
    onUpdate : function(ds, record)
    {
        Ext.ux.maximgb.tg.GridView.superclass.onUpdate.call(this, ds, record);
        if (this.mainWrap) {
            //this.updateAllColumnWidths();
            this.processRows(0);
        }
    },
    
    onStoreExpandNode : function(store, rc)
    {
        this.expandRow(rc);
    },
    
    onStoreCollapseNode : function(store, rc)
    {
        this.collapseRow(rc);
    }
});

Ext.ux.maximgb.tg.GridPanel = Ext.extend(Ext.grid.GridPanel, 
{
    /**
     * @cfg {String|Integer} master_column_id Master column id. Master column cells are nested.
     * Master column cell values are used to build breadcrumbs.
     */
    master_column_id : 0,
    
    /**
     * @cfg {Stirng} TreeGrid panel custom class.
     */
    tg_cls : 'ux-maximgb-tg-panel',

    // Private
    initComponent : function()
    {
        this.initComponentPreOverride();
        Ext.ux.maximgb.tg.GridPanel.superclass.initComponent.call(this);
        this.getSelectionModel().on('selectionchange', this.onTreeGridSelectionChange, this);
        new Ext.ux.SynchronousTreeExpand({
			grid: this
		})
        this.initComponentPostOverride();
    },
    
    initComponentPreOverride : Ext.emptyFn,
    
    initComponentPostOverride : Ext.emptyFn,
    
    // Private
    onRender : function(ct, position)
    {
        Ext.ux.maximgb.tg.GridPanel.superclass.onRender.call(this, ct, position);
        this.el.addClass(this.tg_cls);
    },

    /**
     * Returns view instance.
     *
     * @access private
     * @return {GridView}
     */
    getView : function()
    {
        if (!this.view) {
            this.view = new Ext.ux.maximgb.tg.GridView(this.viewConfig);
        }
        return this.view;
    },
    
    /**
     * @access private
     */
    onClick : function(e)
    {
        var target = e.getTarget(),
            view = this.getView(),
            row = view.findRowIndex(target),
            store = this.getStore(),
            sm = this.getSelectionModel(), 
            record, record_id, do_default = true;
        
        // Row click
        if (row !== false) {
            if (Ext.fly(target).hasClass('ux-maximgb-tg-elbow-active')) {
                record = store.getAt(row);
                if (store.isExpandedNode(record)) {
                    store.collapseNode(record);
                }
                else {                	
                    store.expandNode(record);
                }
                do_default = false;
            }
        }

        if (do_default) {
            Ext.ux.maximgb.tg.GridPanel.superclass.onClick.call(this, e);
        }
    },

    /**
     * @access private
     */
    onMouseDown : function(e)
    {
        var target = e.getTarget();

        if (!Ext.fly(target).hasClass('ux-maximgb-tg-elbow-active')) {
            Ext.ux.maximgb.tg.GridPanel.superclass.onMouseDown.call(this, e);
        }
    },
    
    /**
     * @access private
     */
    onTreeGridSelectionChange : function(sm, selection)
    {
        var record, ancestors, store = this.getStore();
        // Row selection model
        if (sm.getSelected) {
            record = sm.getSelected();
            store.setActiveNode(record);
        }
        // Cell selection model
        else if (sm.getSelectedCell && selection) {
            record = selection.record;
            store.setActiveNode(record);
        }

        // Ensuring that selected node is visible.
        if (record) {
            if (!store.isVisibleNode(record)) {
                ancestors = store.getNodeAncestors(record);
                while (ancestors.length > 0) {
                    store.expandNode(ancestors.pop());
                }
            }
        }
    }
});

Ext.ux.maximgb.tg.EditorGridPanel = Ext.extend(Ext.grid.EditorGridPanel, 
{
    /**
     * @cfg {String|Integer} master_column_id Master column id. Master column cells are nested.
     * Master column cell values are used to build breadcrumbs.
     */
    master_column_id : 0,

    // Private
    initComponent : function()
    {
        this.initComponentPreOverride();
    
        Ext.ux.maximgb.tg.EditorGridPanel.superclass.initComponent.call(this);
        
        this.getSelectionModel().on(
            'selectionchange',
            this.onTreeGridSelectionChange,
            this
        );
        
        this.initComponentPostOverride();
    },
    
    initComponentPreOverride : Ext.emptyFn,
    
    initComponentPostOverride : Ext.emptyFn,
    
    // Private
    onRender : function(ct, position)
    {
        Ext.ux.maximgb.tg.EditorGridPanel.superclass.onRender.call(this, ct, position);
        this.el.addClass('ux-maximgb-tg-panel');
    },

    /**
     * Returns view instance.
     *
     * @access private
     * @return {GridView}
     */
    getView : function()
    {
        if (!this.view) {
            this.view = new Ext.ux.maximgb.tg.GridView(this.viewConfig);
        }
        return this.view;
    },
    
    /**
     * @access private
     */
    onClick : function(e)
    {
        var target = e.getTarget(),
            view = this.getView(),
            row = view.findRowIndex(target),
            store = this.getStore(),
            sm = this.getSelectionModel(), 
            record, record_id, do_default = true;
        
        // Row click
        if (row !== false) {
            if (Ext.fly(target).hasClass('ux-maximgb-tg-elbow-active')) {
                record = store.getAt(row);
                if (store.isExpandedNode(record)) {
                    store.collapseNode(record);
                }
                else {
                    store.expandNode(record);
                }
                do_default = false;
            }
        }

        if (do_default) {
            Ext.ux.maximgb.tg.EditorGridPanel.superclass.onClick.call(this, e);
        }
    },

    /**
     * @access private
     */
    onMouseDown : function(e)
    {
        var target = e.getTarget();

        if (!Ext.fly(target).hasClass('ux-maximgb-tg-elbow-active')) {
            Ext.ux.maximgb.tg.EditorGridPanel.superclass.onMouseDown.call(this, e);
        }
    },
    
    /**
     * @access private
     */
    onTreeGridSelectionChange : function(sm, selection)
    {
        var record, ancestors, store = this.getStore();
        // Row selection model
        if (sm.getSelected) {
            record = sm.getSelected();
            store.setActiveNode(record);
        }
        // Cell selection model
        else if (sm.getSelectedCell && selection) {
            record = selection.record;
            store.setActiveNode(record);
        }

        // Ensuring that selected node is visible.
        if (record) {
            if (!store.isVisibleNode(record)) {
                ancestors = store.getNodeAncestors(record);
                while (ancestors.length > 0) {
                    store.expandNode(ancestors.pop());
                }
            }
        }
    }
});

/**
 * Paging toolbar for work this AbstractTreeStore.
 */
Ext.ux.maximgb.tg.PagingToolbar = Ext.extend(Ext.PagingToolbar,
{
    onRender : function(ct, position)
    {
        Ext.ux.maximgb.tg.PagingToolbar.superclass.onRender.call(this, ct, position);
        this.updateUI();
    },

    getPageData : function()
    {
        var total = 0, cursor = 0;
        if (this.store) {
            cursor = this.store.getActiveNodePageOffset();
            total = this.store.getActiveNodeTotalCount();
        }
        return {
            total : total,
            activePage : Math.ceil((cursor + this.pageSize) / this.pageSize),
            pages :  total < this.pageSize ? 1 : Math.ceil(total / this.pageSize)
        };
    },
    
    updateInfo : function()
    {
        var count = 0, cursor = 0, total = 0, msg;
        if (this.displayItem) {
            if (this.store) {
                cursor = this.store.getActiveNodePageOffset();
                count = this.store.getActiveNodeCount();
                total = this.store.getActiveNodeTotalCount();
            }
            msg = count == 0 ?
                this.emptyMsg 
                    :
                String.format(
                    this.displayMsg,
                    cursor + 1, cursor + count, total
                );
            this.displayItem.setText(msg);
        }
    },
    
    updateUI : function()
    {
        var d = this.getPageData(), ap = d.activePage, ps = d.pages;
        
        this.afterTextItem.setText(String.format(this.afterPageText, d.pages));
        this.inputItem.setValue(ap);
        
        this.first.setDisabled(ap == 1);
        this.prev.setDisabled(ap == 1);
        this.next.setDisabled(ap == ps);
        this.last.setDisabled(ap == ps);
        this.refresh.enable();
        this.updateInfo();
    },
    
    bindStore : function(store, initial)
    {
        if (!initial && this.store) {
            this.store.un('activenodechange', this.onStoreActiveNodeChange, this);
        }
        if (store) {
            store.on('activenodechange', this.onStoreActiveNodeChange, this);
        }
        Ext.ux.maximgb.tg.PagingToolbar.superclass.bindStore.call(this, store, initial);
    },
    
    beforeLoad : function(store, options)
    {
        var paramNames = this.getParams();
        
        Ext.ux.maximgb.tg.PagingToolbar.superclass.beforeLoad.call(this, store, options);
        
        if (options && options.params) {
            if(options.params[paramNames.start] === undefined) {
                options.params[paramNames.start] = 0;
            }
            if(options.params[paramNames.limit] === undefined) {
                options.params[paramNames.limit] = this.pageSize;
            }
        }
    },
    
    /**
     * Move to the first page, has the same effect as clicking the 'first' button.
     */
    moveFirst : function()
    {
        this.doLoad(0);
    },

    /**
     * Move to the previous page, has the same effect as clicking the 'previous' button.
     */
    movePrevious : function()
    {
        var store = this.store,
            cursor = store ? store.getActiveNodePageOffset() : 0;
            
        this.doLoad(Math.max(0, cursor - this.pageSize));
    },

    /**
     * Move to the next page, has the same effect as clicking the 'next' button.
     */
    moveNext : function()
    {
        var store = this.store,
            cursor = store ? store.getActiveNodePageOffset() : 0;
            
        this.doLoad(cursor + this.pageSize);
    },

    /**
     * Move to the last page, has the same effect as clicking the 'last' button.
     */
    moveLast : function()
    {
        var store = this.store,
            cursor = store ? store.getActiveNodePageOffset() : 0,
            total = store ? store.getActiveNodeTotalCount() : 0,
            extra = total % this.pageSize;

        this.doLoad(extra ? (total - extra) : total - this.pageSize);
    },
    
    onStoreActiveNodeChange : function(store, old_rec, new_rec)
    {
        if (this.rendered) {
            this.updateUI();
        }
    }
});

Ext.reg('Ext.ux.maximgb.tg.GridPanel', Ext.ux.maximgb.tg.GridPanel);
Ext.reg('Ext.ux.maximgb.tg.EditorGridPanel', Ext.ux.maximgb.tg.EditorGridPanel);
Ext.reg('Ext.ux.maximgb.tg.PagingToolbar', Ext.ux.maximgb.tg.PagingToolbar);

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/treegrid/TreeGrid.js  ]*/

/**
 * @class Ext.ux.CheckboxSelectionModel
 * @extends Ext.grid.CheckboxSelectionModel
 * A custom selection model that renders a column of checkboxes that can be toggled to select or deselect rows.
 * @constructor
 * @param {Object} config The configuration options
 */
Ext.ux.CheckboxSelectionModel = Ext.extend(Ext.grid.CheckboxSelectionModel, {
    /**
     * @cfg {String} header Any valid text or HTML fragment to display in the header cell for the checkbox column
     * (defaults to '&lt;div class="x-grid3-hd-checker">&#160;&lt;/div>').  The default CSS class of 'x-grid3-hd-checker'
     * displays a checkbox in the header and provides support for automatic check all/none behavior on header click.
     * This string can be replaced by any valid HTML fragment, including a simple text string (e.g., 'Select Rows'), but
     * the automatic check all/none behavior will only work if the 'x-grid3-hd-checker' class is supplied.
     */
    header: '<div class="x-grid3-hd-checker" id="hd-checker">&#160;</div>',
    /**
     * @cfg {Number} width The default width in pixels of the checkbox column (defaults to 20).
     */
    width: 20,
    /**
     * @cfg {Boolean} sortable True if the checkbox column is sortable (defaults to false).
     */
    sortable: false,

    // private
    menuDisabled:true,
    fixed:true,
    dataIndex: '',
    id: 'checker',

    // private
    initEvents : function(){
        Ext.grid.CheckboxSelectionModel.superclass.initEvents.call(this);
        this.grid.on('render', function(){		
            var view = this.grid.getView();
            view.mainBody.on('mousedown', this.onMouseDown, this);
            Ext.fly(view.innerHd).on('mousedown', this.onHdMouseDown, this);   
            
           //Ext.util.Observable.capture(view, function(e){console.info('VIEW:'+e);});		
           //Ext.util.Observable.capture(this.grid.store, function(e){console.info('STORE:'+e);});
		   //Ext.util.Observable.capture(this, function(e){console.log('SM:'+e);});
            
        }, this);
    },

    // private
    onMouseDown : function(e, t){
    	
    	if(e.button === 0 && t.className == 'x-grid3-row-checker'){ // Only fire if left-click
            e.stopEvent();            
            var target = e.getTarget('.x-grid3-row'),
				view = this.grid.getView(),
				index = target.rowIndex,
				store = this.grid.getStore(),
				record,parentRecord={};
            
				record = store.getAt(index);
				
			//console.info('Cliked on: ');							 			
			//console.info(record);
			
			if(target){            	
            	if(this.isSelected(index)){
                    this.myDeselectRow(record);
                    if(this.grid.tree && !this.grid.remoteLoad){
                    	
                    	/*
                    	 * Disabled the tree nodes selection for remoteLoad
                    	 */
	                    this.clearSelectionsFrom(record);
	                    this.clearSelectionsUpFrom(record);
                    }
                    
                }else{
                    this.selectRow(index, true);
                    if(this.grid.tree && !this.grid.remoteLoad){
                    	/*
                    	 * Disabled the tree nodes selection for remoteLoad
                    	 */
		                this.selectAllFrom(record);
		                this.selectAllUpFrom(record);
                    }
                }
            }
        }		
    },

    // private
    onHdMouseDown : function(e, t){
        if(t.className == 'x-grid3-hd-checker'){
            e.stopEvent();
            var hd = Ext.fly(t.parentNode); 
			if(!hd) return;
            var isChecked = hd.hasClass('x-grid3-hd-checker-on');
            if(isChecked){
                hd.removeClass('x-grid3-hd-checker-on');
                this.clearSelections();
            }else{
                hd.addClass('x-grid3-hd-checker-on');
                this.selectAll();
            }
        }
    },
    getSelectionsJSON : function(fields)
    {
    	var selections=this.getSelections(),json_selections=new Array();
    	
    	for (i = 0, len = selections.length; i < len; i++) {
    		
    		if(fields)
    		{
    			var array=new Array();
    			
    			for (j=0;j<fields.length;j++)
    			{
    				array.push(selections[i].json[fields[j]]);
    			}    		
    			
    			json_selections.push(array);
    		}
    		else
    		{
    			json_selections.push(selections[i].json);
    		}
    	}
    	
    	return Ext.encode(json_selections);
    }
    ,
    /**
     * Selects all rows from parent record
     */
    selectAllFrom : function(record){
    	var view = this.grid.getView(),
			store = this.grid.getStore(),
			children;
			
		if(store.isLeafNode(record)) return;	
			
		//node is already expanded
    	if (store.isExpandedNode(record)) {
    		
    		//add selection on children
			if (store.isVisibleNode(record)) {
				children = store.getNodeChildren(record);
				
				if(children.length>0)
				{
					for (i = 0, len = children.length; i < len; i++) {
						child_index = store.indexOf(children[i]);
						this.selectRow(child_index, true);
						this.selectAllFrom(children[i]);
					}
				}
			}
		}
		//node is not expanded, expand it
    	else if(!this.grid.remoteLoad) {
			
			store.expandNode(record);
			children = store.getNodeChildren(record);

			if(children.length>0)
			{						
				for (i = 0, len = children.length; i < len; i++) {
					child_index = store.indexOf(children[i]);
					this.selectRow(child_index, true);
					this.selectAllFrom(children[i]);
				}
			}
			
			store.on('expandnode2', this.onStoreExpandNode, this);
		}
    },
    /**
    * override the function from TreeGrid.js, and then delete & re-register the default listener from TreeGrid.js
    */
    onStoreExpandNode : function(ds,rc){
    	var view = this.grid.getView(),
			store = this.grid.getStore();    	
    	if (ds.isVisibleNode(rc)) {
			children = ds.getNodeChildren(rc);

			if(children.length>0)
			{
				for (i = 0, len = children.length; i < len; i++) {
					child_index = ds.indexOf(children[i]);
					this.selectRow(child_index, true);
					this.selectAllFrom(children[i]);
				}
			}
		}
		
		store.un('expandnode2', this.onStoreExpandNode, this);
    },
    /**
     * Clears selection from parent record
     */
    clearSelectionsUpFrom : function(record){
    	var store = this.grid.getStore();
    	//if there is no selection in parent node
        var parentRecord=store.getNodeParent(record);
        
		if(parentRecord!=null&&!this.parentHasSelections(parentRecord))
		{
			this.myDeselectRow(parentRecord);
			this.clearSelectionsUpFrom(parentRecord);
		}
    },
    /**
     * Select all from parent record
     */
    selectAllUpFrom : function(record){    	
    	var store = this.grid.getStore();
    	//if there are all selected in parent node
        var parentRecord=store.getNodeParent(record);
        
		if(parentRecord!=null&&this.parentHasSelections(parentRecord))
		{
			this.selectRow(store.indexOf(parentRecord), true);
			this.selectAllUpFrom(parentRecord);
		}
    },
	 /**
     * Clears all selections from parent record
     */
    clearSelectionsFrom : function(record){
    	var view = this.grid.getView(),
			store = this.grid.getStore(),
			children;
			
		if(store.isLeafNode(record)) return;	
		
		if (store.isVisibleNode(record)) {
			children = store.getNodeChildren(record);
			
			if(children.length>0)
			{
				for (i = 0, len = children.length; i < len; i++) {
					this.myDeselectRow(children[i]);
					this.clearSelectionsFrom(children[i]);
				}
			}
		}
		
		//if there is no selection
		if (this.selections.length==0)
		{
			var t=Ext.get('hd-checker');
			var hd = Ext.fly(t.dom.parentNode);
			hd.removeClass('x-grid3-hd-checker-on');
		}
		
    },
    /**
    * find if parent node has any selected children
    */
    parentHasSelections : function(record,all)
    {
    	var view = this.grid.getView(),
			store = this.grid.getStore(),
			children,hasSelection;
			
		children = store.getNodeChildren(record);
		
		if(all)
		{
			hasSelection=true;
			if(children.length>0)
			{						
				for (i = 0, len = children.length; i < len; i++) {
					child_index = store.indexOf(children[i]);
					if(!this.isSelected(child_index))
					{
						hasSelection=false;
						break;
					}
				}
			}
		}
		else
		{
			hasSelection=false;
			if(children.length>0)
			{						
				for (i = 0, len = children.length; i < len; i++) {
					child_index = store.indexOf(children[i]);
					if(this.isSelected(child_index))
					{
						hasSelection=true;
						break;
					}
				}
			}		
		}	
		
		return hasSelection;
    },
    /**
     * Selects a row.
     * @param {Number} row The index of the row to select
     * @param {Boolean} keepExisting (optional) True to keep existing selections
     */
    selectRow : function(index, keepExisting, preventViewNotify){  	
    	if(this.locked || (index < 0 || index >= this.grid.store.getCount()) || this.isSelected(index)) return;
        var r = this.grid.store.getAt(index);
        
        if(r && this.fireEvent("beforerowselect", this, index, keepExisting, r) !== false){
           /* if(!keepExisting || this.singleSelect){
                this.clearSelections();
            }*/
            this.selections.add(r);
            
            this.last = this.lastActive = index;
            if(!preventViewNotify){
                this.grid.getView().onRowSelect(index);
            }
            
            /**
            * if rows has _selected attribute, then select them after html render
            */
            /*if(this.grid.select&&r.data[this.grid.store.selected_field_name])
            {
            	this.grid.getView().on('rowsinserted', function(){
	            	            	
		        	this.grid.getView().onRowSelect(index);
	            	
	            }, this);
            }*/
                                   
            this.fireEvent("rowselect", this, index, r);
            this.fireEvent("selectionchange", this);
        }
    },
    myDeselectRow : function(record)
    {
    	var view = this.grid.getView(),
			store = this.grid.getStore();
    	
		var index = store.indexOf(record);	
			
    	this.selections.remove(record);
    	record.data[this.grid.store.selected_field_name]=false;
    	view.removeRowClass(index, "x-grid3-row-selected");	
    	
    	this.fireEvent("rowdeselect", this, index, record);
        this.fireEvent("selectionchange", this);
    },
    // private
    renderer : function(v, p, record){
    	return '<div class="x-grid3-row-checker">&#160;</div>';
    }
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/treegrid/Ext.ux.CheckboxSelectionModel.js  ]*/

Ext.ns("Ext.ux");
Ext.ux.SynchronousTreeExpand = function(config){	
	var store,sm,ds,record;
	var counter = 0;
	var loading = false;
	var expandedNodes = [];
	var mask;
	var moveFurther = function(){
		while (true) {
			if(counter >= ds.getCount()) return null;		
			var record = store.getAt(counter);
			counter++;
			if(!store.isLeafNode(record) && record.get("name").match(/<font color=red>&darr;<\/font>/)){				
				return record;
			}
		}
	}
	config.grid.store.on('load',function(){		
		if(config.grid.remoteLoad && config.grid.select){	
			mask = new Ext.LoadMask(Ext.getBody(), {msg:"Retrieving data..."});
			mask.show();
			
			store = config.grid.getStore();
			sm = config.grid.getSelectionModel();
			ds = config.grid.getView().ds;			
			
			if(ds.getCount()){
				var record = moveFurther();
				if(record){
					store.expandNode(record);	
				}else{
					mask.hide();
				}
			}
		}	
	});	
}

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/treegrid/Ext.ux.SynchronousTreeExpand.js  ]*/

/*!
 * Ext JS Library 3.3.1
 * Copyright(c) 2006-2010 Sencha Inc.
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
 *		iconCls: 'user',
 *		height: 300,
 *		shadow: true,
 *		title: get_cookie('memberName'),
 *		width: 300
 *	});
 *
 * this.startMenu.add({
 *		text: 'Grid Window',
 *		iconCls:'icon-grid',
 *		handler : this.createWindow,
 *		scope: this
 *	});
 *
 * this.startMenu.addTool({
 *		text:'Logout',
 *		iconCls:'logout',
 *		handler:function(){ window.location = "logout.php"; },
 *		scope:this
 *	});
 */

Ext.namespace("Ext.ux");

Ext.ux.StartMenu = Ext.extend(Ext.menu.Menu, {
    initComponent: function(config) {
    	Ext.ux.StartMenu.superclass.initComponent.call(this, config);

        var tools = this.toolItems;
        this.toolItems = new Ext.util.MixedCollection();
        if(tools){
            this.addTool.apply(this, tools);
        }
    },

    // private
    onRender : function(ct, position){
        Ext.ux.StartMenu.superclass.onRender.call(this, ct, position);
        var el = this.el.addClass('ux-start-menu');

        var header = el.createChild({
        	tag: "div",
        	cls: "x-window-header x-unselectable x-panel-icon "+this.iconCls
        });

		this.header = header;

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
		var bl = bc.wrap({
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
        this.menuBWrap.setHeight(this.height - 28);

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

        this.setTitle(this.title);
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

    addToolItem : function(item){
        this.toolItems.add(item);
        if(this.ul){
            var li = document.createElement("li");
            li.className = "x-menu-list-item";
            this.ul.dom.appendChild(li);
            item.render(li, this);
            this.delayAutoWidth();
        }
        return item;
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

    setTitle : function(title, iconCls){
        this.title = title;
        this.header.child('span').update(title);
        return this;
    }
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/desktop/js/StartMenu.js  ]*/

/*!
 * Ext JS Library 3.3.1
 * Copyright(c) 2006-2010 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */
/**
 * @class Ext.ux.TaskBar
 * @extends Ext.util.Observable
 */
Ext.ux.TaskBar = function(app){
    this.app = app;
    this.init();
}

Ext.extend(Ext.ux.TaskBar, Ext.util.Observable, {
    init : function(){
        this.startMenu = new Ext.ux.StartMenu(Ext.apply({
            height: 300,
            shadow: true,
            width: 300
        }, this.app.startConfig));
        
        this.startMenu.doLayout();
        
        this.startBtn = new Ext.Button({
            text: 'Start',
            id: 'ux-startbutton',
            iconCls:'start',
            menu: this.startMenu,
            menuAlign: 'bl-tl',
            renderTo: 'ux-taskbar-start',
            clickEvent: 'mousedown',
            template: new Ext.Template(
                '<table cellspacing="0" class="x-btn"><tbody class="{1}"><tr>',
                '<td class="ux-startbutton-left"><i>&#160;</i></td>',
                '<td class="ux-startbutton-center"><em class="{2} unselectable="on">',
                    '<button class="x-btn-text" type="{0}" style="height:30px;"></button>',
                '</em></td>',
                '<td class="ux-startbutton-right"><i>&#160;</i></td>',
                '</tr></tbody></table>')
        });
                
        var width = this.startBtn.getEl().getWidth()+10;

        var sbBox = new Ext.BoxComponent({
            el: 'ux-taskbar-start',
            id: 'TaskBarStart',
            minWidth: width,
            region:'west',
            split: true,
            width: width
        });

        this.tbPanel = new Ext.ux.TaskButtonsPanel({
            el: 'ux-taskbuttons-panel',
            id: 'TaskBarButtons',
            region:'center'
        });

        var container = new Ext.ux.TaskBarContainer({
            el: 'ux-taskbar',
            layout: 'border',
            items: [sbBox,this.tbPanel]
        });

        return this;
    },

    addTaskButton : function(win){
        return this.tbPanel.addButton(win, 'ux-taskbuttons-panel');
    },

    removeTaskButton : function(btn){
        this.tbPanel.removeButton(btn);
    },

    setActiveButton : function(btn){
        this.tbPanel.setActiveButton(btn);
    }
});



/**
 * @class Ext.ux.TaskBarContainer
 * @extends Ext.Container
 */
Ext.ux.TaskBarContainer = Ext.extend(Ext.Container, {
    initComponent : function() {
        Ext.ux.TaskBarContainer.superclass.initComponent.call(this);

        this.el = Ext.get(this.el) || Ext.getBody();
        this.el.setHeight = Ext.emptyFn;
        this.el.setWidth = Ext.emptyFn;
        this.el.setSize = Ext.emptyFn;
        this.el.setStyle({
            overflow:'hidden',
            margin:'0',
            border:'0 none'
        });
        this.el.dom.scroll = 'no';
        this.allowDomMove = false;
        this.autoWidth = true;
        this.autoHeight = true;
        Ext.EventManager.onWindowResize(this.fireResize, this);
        this.renderTo = this.el;
    },

    fireResize : function(w, h){
        this.onResize(w, h, w, h);
        this.fireEvent('resize', this, w, h, w, h);
    }
});



/**
 * @class Ext.ux.TaskButtonsPanel
 * @extends Ext.BoxComponent
 */
Ext.ux.TaskButtonsPanel = Ext.extend(Ext.BoxComponent, {
    activeButton: null,
    enableScroll: true,
    scrollIncrement: 0,
    scrollRepeatInterval: 400,
    scrollDuration: .35,
    animScroll: true,
    resizeButtons: true,
    buttonWidth: 168,
    minButtonWidth: 118,
    buttonMargin: 2,
    buttonWidthSet: false,

    initComponent : function() {
        Ext.ux.TaskButtonsPanel.superclass.initComponent.call(this);
        this.on('resize', this.delegateUpdates);
        this.items = [];

        this.stripWrap = Ext.get(this.el).createChild({
            cls: 'ux-taskbuttons-strip-wrap',
            cn: {
                tag:'ul', cls:'ux-taskbuttons-strip'
            }
        });
        this.stripSpacer = Ext.get(this.el).createChild({
            cls:'ux-taskbuttons-strip-spacer'
        });
        this.strip = new Ext.Element(this.stripWrap.dom.firstChild);

        this.edge = this.strip.createChild({
            tag:'li',
            cls:'ux-taskbuttons-edge'
        });
        this.strip.createChild({
            cls:'x-clear'
        });
    },

    addButton : function(win){
        var li = this.strip.createChild({tag:'li'}, this.edge); // insert before the edge
        var btn = new Ext.ux.TaskBar.TaskButton(win, li);

        this.items.push(btn);

        if(!this.buttonWidthSet){
            this.lastButtonWidth = btn.container.getWidth();
        }

        this.setActiveButton(btn);
        return btn;
    },

    removeButton : function(btn){
        var li = document.getElementById(btn.container.id);
        btn.destroy();
        li.parentNode.removeChild(li);

        var s = [];
        for(var i = 0, len = this.items.length; i < len; i++) {
            if(this.items[i] != btn){
                s.push(this.items[i]);
            }
        }
        this.items = s;

        this.delegateUpdates();
    },

    setActiveButton : function(btn){
        this.activeButton = btn;
        this.delegateUpdates();
    },

    delegateUpdates : function(){
        /*if(this.suspendUpdates){
            return;
        }*/
        if(this.resizeButtons && this.rendered){
            this.autoSize();
        }
        if(this.enableScroll && this.rendered){
            this.autoScroll();
        }
    },

    autoSize : function(){
        var count = this.items.length;
        var ow = this.el.dom.offsetWidth;
        var aw = this.el.dom.clientWidth;

        if(!this.resizeButtons || count < 1 || !aw){ // !aw for display:none
            return;
        }

        var each = Math.max(Math.min(Math.floor((aw-4) / count) - this.buttonMargin, this.buttonWidth), this.minButtonWidth); // -4 for float errors in IE
        var btns = this.stripWrap.dom.getElementsByTagName('button');

        this.lastButtonWidth = Ext.get(btns[0].id).findParent('li').offsetWidth;

        for(var i = 0, len = btns.length; i < len; i++) {
            var btn = btns[i];

            var tw = Ext.get(btns[i].id).findParent('li').offsetWidth;
            var iw = btn.offsetWidth;

            btn.style.width = (each - (tw-iw)) + 'px';
        }
    },

    autoScroll : function(){
        var count = this.items.length;
        var ow = this.el.dom.offsetWidth;
        var tw = this.el.dom.clientWidth;

        var wrap = this.stripWrap;
        var cw = wrap.dom.offsetWidth;
        var pos = this.getScrollPos();
        var l = this.edge.getOffsetsTo(this.stripWrap)[0] + pos;

        if(!this.enableScroll || count < 1 || cw < 20){ // 20 to prevent display:none issues
            return;
        }

        wrap.setWidth(tw); // moved to here because of problem in Safari

        if(l <= tw){
            wrap.dom.scrollLeft = 0;
            //wrap.setWidth(tw); moved from here because of problem in Safari
            if(this.scrolling){
                this.scrolling = false;
                this.el.removeClass('x-taskbuttons-scrolling');
                this.scrollLeft.hide();
                this.scrollRight.hide();
            }
        }else{
            if(!this.scrolling){
                this.el.addClass('x-taskbuttons-scrolling');
            }
            tw -= wrap.getMargins('lr');
            wrap.setWidth(tw > 20 ? tw : 20);
            if(!this.scrolling){
                if(!this.scrollLeft){
                    this.createScrollers();
                }else{
                    this.scrollLeft.show();
                    this.scrollRight.show();
                }
            }
            this.scrolling = true;
            if(pos > (l-tw)){ // ensure it stays within bounds
                wrap.dom.scrollLeft = l-tw;
            }else{ // otherwise, make sure the active button is still visible
                this.scrollToButton(this.activeButton, true); // true to animate
            }
            this.updateScrollButtons();
        }
    },

    createScrollers : function(){
        var h = this.el.dom.offsetHeight; //var h = this.stripWrap.dom.offsetHeight;

        // left
        var sl = this.el.insertFirst({
            cls:'ux-taskbuttons-scroller-left'
        });
        sl.setHeight(h);
        sl.addClassOnOver('ux-taskbuttons-scroller-left-over');
        this.leftRepeater = new Ext.util.ClickRepeater(sl, {
            interval : this.scrollRepeatInterval,
            handler: this.onScrollLeft,
            scope: this
        });
        this.scrollLeft = sl;

        // right
        var sr = this.el.insertFirst({
            cls:'ux-taskbuttons-scroller-right'
        });
        sr.setHeight(h);
        sr.addClassOnOver('ux-taskbuttons-scroller-right-over');
        this.rightRepeater = new Ext.util.ClickRepeater(sr, {
            interval : this.scrollRepeatInterval,
            handler: this.onScrollRight,
            scope: this
        });
        this.scrollRight = sr;
    },

    getScrollWidth : function(){
        return this.edge.getOffsetsTo(this.stripWrap)[0] + this.getScrollPos();
    },

    getScrollPos : function(){
        return parseInt(this.stripWrap.dom.scrollLeft, 10) || 0;
    },

    getScrollArea : function(){
        return parseInt(this.stripWrap.dom.clientWidth, 10) || 0;
    },

    getScrollAnim : function(){
        return {
            duration: this.scrollDuration,
            callback: this.updateScrollButtons,
            scope: this
        };
    },

    getScrollIncrement : function(){
        return (this.scrollIncrement || this.lastButtonWidth+2);
    },

    /* getBtnEl : function(item){
        return document.getElementById(item.id);
    }, */

    scrollToButton : function(item, animate){
        item = item.el.dom.parentNode; // li
        if(!item){ return; }
        var el = item; //this.getBtnEl(item);
        var pos = this.getScrollPos(), area = this.getScrollArea();
        var left = Ext.fly(el).getOffsetsTo(this.stripWrap)[0] + pos;
        var right = left + el.offsetWidth;
        if(left < pos){
            this.scrollTo(left, animate);
        }else if(right > (pos + area)){
            this.scrollTo(right - area, animate);
        }
    },

    scrollTo : function(pos, animate){
        this.stripWrap.scrollTo('left', pos, animate ? this.getScrollAnim() : false);
        if(!animate){
            this.updateScrollButtons();
        }
    },

    onScrollRight : function(){
        var sw = this.getScrollWidth()-this.getScrollArea();
        var pos = this.getScrollPos();
        var s = Math.min(sw, pos + this.getScrollIncrement());
        if(s != pos){
            this.scrollTo(s, this.animScroll);
        }
    },

    onScrollLeft : function(){
        var pos = this.getScrollPos();
        var s = Math.max(0, pos - this.getScrollIncrement());
        if(s != pos){
            this.scrollTo(s, this.animScroll);
        }
    },

    updateScrollButtons : function(){
        var pos = this.getScrollPos();
        this.scrollLeft[pos == 0 ? 'addClass' : 'removeClass']('ux-taskbuttons-scroller-left-disabled');
        this.scrollRight[pos >= (this.getScrollWidth()-this.getScrollArea()) ? 'addClass' : 'removeClass']('ux-taskbuttons-scroller-right-disabled');
    }
});



/**
 * @class Ext.ux.TaskBar.TaskButton
 * @extends Ext.Button
 */
Ext.ux.TaskBar.TaskButton = function(win, el){
    this.win = win;
    Ext.ux.TaskBar.TaskButton.superclass.constructor.call(this, {
        iconCls: win.iconCls,
        text: Ext.util.Format.ellipsis(win.title, 12),
        renderTo: el,
        handler : function(){
            if(win.minimized || win.hidden){
                win.show();
            }else if(win == win.manager.getActive()){
                win.minimize();
            }else{
                win.toFront();
            }
        },
        clickEvent:'mousedown',
        template: new Ext.Template(
            '<table cellspacing="0" class="x-btn {3}"><tbody><tr>',
            '<td class="ux-taskbutton-left"><i>&#160;</i></td>',
            '<td class="ux-taskbutton-center"><em class="{5} unselectable="on">',
                '<button class="x-btn-text {2}" type="{1}" style="height:28px;">{0}</button>',
            '</em></td>',
            '<td class="ux-taskbutton-right"><i>&#160;</i></td>',
            "</tr></tbody></table>")
    });
};

Ext.extend(Ext.ux.TaskBar.TaskButton, Ext.Button, {
    onRender : function(){
        Ext.ux.TaskBar.TaskButton.superclass.onRender.apply(this, arguments);

        this.cmenu = new Ext.menu.Menu({
            items: [{
                text: 'Restore',
                handler: function(){
                    if(!this.win.isVisible()){
                        this.win.show();
                    }else{
                        this.win.restore();
                    }
                },
                scope: this
            },{
                text: 'Minimize',
                handler: this.win.minimize,
                scope: this.win
            },{
                text: 'Maximize',
                handler: this.win.maximize,
                scope: this.win
            }, '-', {
                text: 'Close',
                handler: this.closeWin.createDelegate(this, this.win, true),
                scope: this.win
            }]
        });

        this.cmenu.on('beforeshow', function(){
            var items = this.cmenu.items.items;
            var w = this.win;
            items[0].setDisabled(w.maximized !== true && w.hidden !== true);
            items[1].setDisabled(w.minimized === true);
            items[2].setDisabled(w.maximized === true || w.hidden === true);
        }, this);

        this.el.on('contextmenu', function(e){
            e.stopEvent();
            if(!this.cmenu.el){
                this.cmenu.render();
            }
            var xy = e.getXY();
            xy[1] -= this.cmenu.el.getHeight();
            this.cmenu.showAt(xy);
        }, this);
    },

    closeWin : function(cMenu, e, win){
        if(!win.isVisible()){
            win.show();
        }else{
            win.restore();
        }
        win.close();
    }
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/desktop/js/TaskBar.js  ]*/

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
    var shortcuts = Ext.get('x-shortcuts');

    var windows = new Ext.WindowGroup();
    var activeWindow;

    function minimizeWin(win) {
        win.minimized = true;
        win.hide();
    }

    function markActive(win) {
        if (activeWindow && activeWindow != win) {
            markInactive(activeWindow);
        }
        taskbar.setActiveButton(win.taskButton);
        activeWindow = win;
        Ext.fly(win.taskButton.el).addClass('active-win');
        win.minimized = false;
    }

    function markInactive(win) {
        if (win == activeWindow) {
            activeWindow = null;
            Ext.fly(win.taskButton.el).removeClass('active-win');
        }
    }

    function removeWin(win) {
        taskbar.removeTaskButton(win.taskButton);
        layout();
    }

    function layout() {
        desktopEl.setHeight(Ext.lib.Dom.getViewHeight() - taskbarEl.getHeight());
    }
    Ext.EventManager.onWindowResize(layout);

    this.layout = layout;

    this.createWindow = function(config, cls) {
        var win = new(cls || Ext.Window)(
        Ext.applyIf(config || {},
        {
            renderTo: desktopEl,
            manager: windows,
            minimizable: true,
            maximizable: true
        })
        );
        win.dd.xTickSize = this.xTickSize;
        win.dd.yTickSize = this.yTickSize;
        if (win.resizer) {
            win.resizer.widthIncrement = this.xTickSize;
            win.resizer.heightIncrement = this.yTickSize;
        }
        win.render(desktopEl);
        win.taskButton = taskbar.addTaskButton(win);

        win.cmenu = new Ext.menu.Menu({
            items: [

            ]
        });

        win.animateTarget = win.taskButton.el;

        win.on({
            'activate': {
                fn: markActive
            },
            'beforeshow': {
                fn: markActive
            },
            'deactivate': {
                fn: markInactive
            },
            'minimize': {
                fn: minimizeWin
            },
            'close': {
                fn: removeWin
            }
        });

        layout();
        return win;
    };

    this.getManager = function() {
        return windows;
    };

    this.getWindow = function(id) {
        return windows.get(id);
    };

    this.getWinWidth = function() {
        var width = Ext.lib.Dom.getViewWidth();
        return width < 200 ? 200: width;
    };

    this.getWinHeight = function() {
        var height = (Ext.lib.Dom.getViewHeight() - taskbarEl.getHeight());
        return height < 100 ? 100: height;
    };

    this.getWinX = function(width) {
        return (Ext.lib.Dom.getViewWidth() - width) / 2;
    };

    this.getWinY = function(height) {
        return (Ext.lib.Dom.getViewHeight() - taskbarEl.getHeight() - height) / 2;
    };

    this.setTickSize = function(xTickSize, yTickSize) {
        this.xTickSize = xTickSize;
        if (arguments.length == 1) {
            this.yTickSize = xTickSize;
        } else {
            this.yTickSize = yTickSize;
        }
        windows.each(function(win) {
            win.dd.xTickSize = this.xTickSize;
            win.dd.yTickSize = this.yTickSize;
            win.resizer.widthIncrement = this.xTickSize;
            win.resizer.heightIncrement = this.yTickSize;
        },
        this);
    };

    this.cascade = function() {
        var x = 0,
        y = 0;
        windows.each(function(win) {
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
        windows.each(function(win) {
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

    layout();

    if (shortcuts) {
        shortcuts.on('click',
        function(e, t) {
            t = e.getTarget('dt', shortcuts);
            if (t) {
                e.stopEvent();
                
                /*var module = app.getModule(t.id.replace('-shortcut', ''));
                if (module) {
                    module.createWindow();
                }*/
            }
        });
    }
};

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/desktop/js/Desktop.js  ]*/

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

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/layout-browser/Ext.ux.layout.CenterLayout.js  ]*/

/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */


// Very simple plugin for adding a close context menu to tabs

Ext.ux.TabCloseMenu = function(){
    var tabs, menu, ctxItem;
    this.init = function(tp){
        tabs = tp;
        tabs.on('contextmenu', onContextMenu);
    }

    function onContextMenu(ts, item, e){
        if(!menu){ // create context menu on first right click
            menu = new Ext.menu.Menu([{
                id: tabs.id + '-close',
                text: 'Close Tab',
                handler : function(){
                    tabs.remove(ctxItem);
                }
            },{
                id: tabs.id + '-close-others',
                text: 'Close Other Tabs',
                handler : function(){
                    tabs.items.each(function(item){
                        if(item.closable && item != ctxItem){
                            tabs.remove(item);
                        }
                    });
                }
            }]);
        }
        ctxItem = item;
        var items = menu.items;
        items.get(tabs.id + '-close').setDisabled(!item.closable);
        var disableOthers = true;
        tabs.items.each(function(){
            if(this != item && this.closable){
                disableOthers = false;
                return false;
            }
        });
        items.get(tabs.id + '-close-others').setDisabled(disableOthers);
        menu.showAt(e.getPoint());
    }
};

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/tabs/TabCloseMenu.js  ]*/

/**
* @author: radu
*/

Ext.ns('ux');

Ext.ux.GridColorView = function(config){
    Ext.apply(this, config);
    Ext.ux.GridColorView.superclass.constructor.call(this);
};

Ext.extend(Ext.ux.GridColorView, Ext.grid.GridView, {
	
	initTemplates : function(){
        var ts = this.templates || {};
      
        ts.row = new Ext.Template(
                    '<div class="x-grid3-row {alt}" style="{tstyle}"><table class="x-grid3-row-table" border="0" cellspacing="0" cellpadding="0" style="{tstyle} {color}">',
                    '<tbody><tr>{cells}</tr>',
                    (this.enableRowBody ? '<tr class="x-grid3-row-body-tr" style="{bodyStyle}"><td colspan="{cols}" class="x-grid3-body-cell" tabIndex="0" hidefocus="on"><div class="x-grid3-row-body">{body}</div></td></tr>' : ''),
                    '</tbody></table></div>'
                    );
                    
         ts.cell = new Ext.Template(
                    '<td class="x-grid3-col x-grid3-cell x-grid3-td-{id} {css}" style="{style}" tabIndex="0" {cellAttr}>',
                    '<div class="x-grid3-cell-inner x-grid3-col-{id}" unselectable="on" {attr} style="{cell_color}">{value}</div>',
                    '</td>'
                    );
        
        this.templates = ts;
		Ext.ux.GridColorView.superclass.initTemplates.call(this);
    },
   
    doRender : function(cs, rs, ds, startRow, colCount, stripe){
    	var ts = this.templates, ct = ts.cell, rt = ts.row, last = colCount-1;
        var tstyle = 'width:'+this.getTotalWidth()+';';
        // buffers
        var buf = [], cb, c, p = {}, rp = {tstyle: tstyle}, r;
        //console.log(rs);
        for(var j = 0, len = rs.length; j < len; j++){
            r = rs[j]; cb = [];
            
            var rowIndex = (j+startRow);
            for(var i = 0; i < colCount; i++){
                c = cs[i];
                p.id = c.id;
                p.css = i === 0 ? 'x-grid3-cell-first ' : (i == last ? 'x-grid3-cell-last ' : '');
                p.attr = p.cellAttr = "";
                p.value = c.renderer(r.data[c.name], p, r, rowIndex, i, ds);
                p.style = c.style;
                if(Ext.isEmpty(p.value)){
                    p.value = "&#160;";
                }
                if(this.markDirty && r.dirty && typeof r.modified[c.name] !== 'undefined'){
                    p.css += ' x-grid3-dirty-cell';
                }
                
                //radu - start
                p.cell_color='';
                if(r.data['_cell_color']&&r.data['_cell_color'][c.name])
                {
                	p.cell_color='background-color:'+r.data['_cell_color'][c.name]+';';
                }
                //radu - end
                
                cb[cb.length] = ct.apply(p);
            }
            var alt = [];
            if(stripe && ((rowIndex+1) % 2 === 0)){
                alt[0] = "x-grid3-row-alt";
            }
            if(r.dirty){
                alt[1] = " x-grid3-dirty-row";
            }
            rp.cols = colCount;
            if(this.getRowClass){
                alt[2] = this.getRowClass(r, rowIndex, rp, ds);
            }
            rp.alt = alt.join(" ");
            rp.cells = cb.join("");
            
            //added by radu
		    if(r.data['_color']!='')
		    {
		    	rp.color='background-color:'+r.data['_color']+';';
		    }
		    
		    //added by radu
		    if(this.grid.select&&r.data['_selected'])
		    {
		     	var sm=this.grid.getSelectionModel();
		      	sm.selectRow(ds.indexOf(r),true);
		    }
            
            buf[buf.length] =  rt.apply(rp);
        }
        return buf.join("");
    }
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid/Ext.ux.GridColorView.js  ]*/

/**
* @author: radu
*/

Ext.ns('ux');

Ext.ux.GroupingColorView = function(config){
    Ext.apply(this, config);
    Ext.ux.GroupingColorView.superclass.constructor.call(this);
};

Ext.ux.GroupingColorView = Ext.extend(Ext.ux.GridColorView, {
    /**
     * @cfg {String} groupByText Text displayed in the grid header menu for grouping by a column
     * (defaults to 'Group By This Field').
     */
    groupByText : 'Group By This Field',
    /**
     * @cfg {String} showGroupsText Text displayed in the grid header for enabling/disabling grouping
     * (defaults to 'Show in Groups').
     */
    showGroupsText : 'Show in Groups',
    /**
     * @cfg {Boolean} hideGroupedColumn <tt>true</tt> to hide the column that is currently grouped (defaults to <tt>false</tt>)
     */
    hideGroupedColumn : false,
    /**
     * @cfg {Boolean} showGroupName If <tt>true</tt> will display a prefix plus a ': ' before the group field value
     * in the group header line.  The prefix will consist of the <tt><b>{@link Ext.grid.Column#groupName groupName}</b></tt>
     * (or the configured <tt><b>{@link Ext.grid.Column#header header}</b></tt> if not provided) configured in the
     * {@link Ext.grid.Column} for each set of grouped rows (defaults to <tt>true</tt>).
     */
    showGroupName : true,
    /**
     * @cfg {Boolean} startCollapsed <tt>true</tt> to start all groups collapsed (defaults to <tt>false</tt>)
     */
    startCollapsed : false,
    /**
     * @cfg {Boolean} enableGrouping <tt>false</tt> to disable grouping functionality (defaults to <tt>true</tt>)
     */
    enableGrouping : true,
    /**
     * @cfg {Boolean} enableGroupingMenu <tt>true</tt> to enable the grouping control in the column menu (defaults to <tt>true</tt>)
     */
    enableGroupingMenu : true,
    /**
     * @cfg {Boolean} enableNoGroups <tt>true</tt> to allow the user to turn off grouping (defaults to <tt>true</tt>)
     */
    enableNoGroups : true,
    /**
     * @cfg {String} emptyGroupText The text to display when there is an empty group value (defaults to <tt>'(None)'</tt>).
     * May also be specified per column, see {@link Ext.grid.Column}.{@link Ext.grid.Column#emptyGroupText emptyGroupText}.
     */
    emptyGroupText : '(None)',
    /**
     * @cfg {Boolean} ignoreAdd <tt>true</tt> to skip refreshing the view when new rows are added (defaults to <tt>false</tt>)
     */
    ignoreAdd : false,
    /**
     * @cfg {String} groupTextTpl The template used to render the group header (defaults to <tt>'{text}'</tt>).
     * This is used to format an object which contains the following properties:
     * <div class="mdetail-params"><ul>
     * <li><b>group</b> : String<p class="sub-desc">The <i>rendered</i> value of the group field.
     * By default this is the unchanged value of the group field. If a <tt><b>{@link Ext.grid.Column#groupRenderer groupRenderer}</b></tt>
     * is specified, it is the result of a call to that function.</p></li>
     * <li><b>gvalue</b> : Object<p class="sub-desc">The <i>raw</i> value of the group field.</p></li>
     * <li><b>text</b> : String<p class="sub-desc">The configured header (as described in <tt>{@link #showGroupName})</tt>
     * if <tt>{@link #showGroupName}</tt> is <tt>true</tt>) plus the <i>rendered</i> group field value.</p></li>
     * <li><b>groupId</b> : String<p class="sub-desc">A unique, generated ID which is applied to the
     * View Element which contains the group.</p></li>
     * <li><b>startRow</b> : Number<p class="sub-desc">The row index of the Record which caused group change.</p></li>
     * <li><b>rs</b> : Array<p class="sub-desc">Contains a single element: The Record providing the data
     * for the row which caused group change.</p></li>
     * <li><b>cls</b> : String<p class="sub-desc">The generated class name string to apply to the group header Element.</p></li>
     * <li><b>style</b> : String<p class="sub-desc">The inline style rules to apply to the group header Element.</p></li>
     * </ul></div></p>
     * See {@link Ext.XTemplate} for information on how to format data using a template. Possible usage:<pre><code>
var grid = new Ext.grid.GridPanel({
    ...
    view: new Ext.ux.GroupingColorView({
        groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})'
    }),
});
     * </code></pre>
     */
    groupTextTpl : '{text}',
    /**
     * @cfg {Function} groupRenderer This property must be configured in the {@link Ext.grid.Column} for
     * each column.
     */

    // private
    gidSeed : 1000,

    // private
    initTemplates : function(){
        Ext.ux.GroupingColorView.superclass.initTemplates.call(this);
        this.state = {};

        var sm = this.grid.getSelectionModel();
        sm.on(sm.selectRow ? 'beforerowselect' : 'beforecellselect',
                this.onBeforeRowSelect, this);

        if(!this.startGroup){
            this.startGroup = new Ext.XTemplate(
                '<div id="{groupId}" class="x-grid-group {cls}">',
                    '<div id="{groupId}-hd" class="x-grid-group-hd" style="{style}"><div class="x-grid-group-title">', this.groupTextTpl ,'</div></div>',
                    '<div id="{groupId}-bd" class="x-grid-group-body">'
            );
        }
        this.startGroup.compile();
        this.endGroup = '</div></div>';
    },

    // private
    findGroup : function(el){
        return Ext.fly(el).up('.x-grid-group', this.mainBody.dom);
    },

    // private
    getGroups : function(){
        return this.hasRows() ? this.mainBody.dom.childNodes : [];
    },

    // private
    onAdd : function(){
        if(this.enableGrouping && !this.ignoreAdd){
            var ss = this.getScrollState();
            this.refresh();
            this.restoreScroll(ss);
        }else if(!this.enableGrouping){
            Ext.ux.GroupingColorView.superclass.onAdd.apply(this, arguments);
        }
    },

    // private
    onRemove : function(ds, record, index, isUpdate){
        Ext.ux.GroupingColorView.superclass.onRemove.apply(this, arguments);
        var g = document.getElementById(record._groupId);
        if(g && g.childNodes[1].childNodes.length < 1){
            Ext.removeNode(g);
        }
        this.applyEmptyText();
    },

    // private
    refreshRow : function(record){
        if(this.ds.getCount()==1){
            this.refresh();
        }else{
            this.isUpdating = true;
            Ext.ux.GroupingColorView.superclass.refreshRow.apply(this, arguments);
            this.isUpdating = false;
        }
    },

    // private
    beforeMenuShow : function(){
        var item, items = this.hmenu.items, disabled = this.cm.config[this.hdCtxIndex].groupable === false;
        if((item = items.get('groupBy'))){
            item.setDisabled(disabled);
        }
        if((item = items.get('showGroups'))){
            item.setDisabled(disabled);
		    item.setChecked(!!this.getGroupField(), true);
        }
    },

    // private
    renderUI : function(){
       /* Ext.ux.GroupingColorView.superclass.renderUI.call(this);
        this.mainBody.on('mousedown', this.interceptMouse, this);

        if(this.enableGroupingMenu && this.hmenu){
            this.hmenu.add('-',{
                itemId:'groupBy',
                text: this.groupByText,
                handler: this.onGroupByClick,
                scope: this,
                iconCls:'x-group-by-icon'
            });
            if(this.enableNoGroups){
                this.hmenu.add({
                    itemId:'showGroups',
                    text: this.showGroupsText,
                    checked: true,
                    checkHandler: this.onShowGroupsClick,
                    scope: this
                });
            }
            this.hmenu.on('beforeshow', this.beforeMenuShow, this);
        }*/
        var markup = Ext.grid.GroupingView.superclass.renderUI.call(this);

        if(this.enableGroupingMenu && this.hmenu){
            this.hmenu.add('-',{
                itemId:'groupBy',
                text: this.groupByText,
                handler: this.onGroupByClick,
                scope: this,
                iconCls:'x-group-by-icon'
            });
            if(this.enableNoGroups){
                this.hmenu.add({
                    itemId:'showGroups',
                    text: this.showGroupsText,
                    checked: true,
                    checkHandler: this.onShowGroupsClick,
                    scope: this
                });
            }
            this.hmenu.on('beforeshow', this.beforeMenuShow, this);
        }
        return markup;
    },

    // private
    onGroupByClick : function(){
        this.grid.store.groupBy(this.cm.getDataIndex(this.hdCtxIndex));
        this.beforeMenuShow(); // Make sure the checkboxes get properly set when changing groups
    },

    // private
    onShowGroupsClick : function(mi, checked){
        if(checked){
            this.onGroupByClick();
        }else{
            this.grid.store.clearGrouping();
        }
    },

    /**
     * Toggles the specified group if no value is passed, otherwise sets the expanded state of the group to the value passed.
     * @param {String} groupId The groupId assigned to the group (see getGroupId)
     * @param {Boolean} expanded (optional)
     */
    toggleGroup : function(group, expanded){
        this.grid.stopEditing(true);
        group = Ext.getDom(group);
        var gel = Ext.fly(group);
        expanded = expanded !== undefined ?
                expanded : gel.hasClass('x-grid-group-collapsed');

        this.state[gel.dom.id] = expanded;
        gel[expanded ? 'removeClass' : 'addClass']('x-grid-group-collapsed');
    },

    /**
     * Toggles all groups if no value is passed, otherwise sets the expanded state of all groups to the value passed.
     * @param {Boolean} expanded (optional)
     */
    toggleAllGroups : function(expanded){
        var groups = this.getGroups();
        for(var i = 0, len = groups.length; i < len; i++){
            this.toggleGroup(groups[i], expanded);
        }
    },

    /**
     * Expands all grouped rows.
     */
    expandAllGroups : function(){
        this.toggleAllGroups(true);
    },

    /**
     * Collapses all grouped rows.
     */
    collapseAllGroups : function(){
        this.toggleAllGroups(false);
    },

    // private
    interceptMouse : function(e){
        var hd = e.getTarget('.x-grid-group-hd', this.mainBody);
        if(hd){
            e.stopEvent();
            this.toggleGroup(hd.parentNode);
        }
    },

    // private
    getGroup : function(v, r, groupRenderer, rowIndex, colIndex, ds){
        var g = groupRenderer ? groupRenderer(v, {}, r, rowIndex, colIndex, ds) : String(v);
        if(g === ''){
            g = this.cm.config[colIndex].emptyGroupText || this.emptyGroupText;
        }
        return g;
    },

    // private
    getGroupField : function(){
        return this.grid.store.getGroupState();
    },
    
    // private
    afterRender : function(){
        Ext.ux.GroupingColorView.superclass.afterRender.call(this);
        if(this.grid.deferRowRender){
            this.updateGroupWidths();
        }
    },

    // private
    renderRows : function(){
        var groupField = this.getGroupField();
        var eg = !!groupField;
        // if they turned off grouping and the last grouped field is hidden
        if(this.hideGroupedColumn) {
            var colIndex = this.cm.findColumnIndex(groupField);
            if(!eg && this.lastGroupField !== undefined) {
                this.mainBody.update('');
                this.cm.setHidden(this.cm.findColumnIndex(this.lastGroupField), false);
                delete this.lastGroupField;
            }else if (eg && this.lastGroupField === undefined) {
                this.lastGroupField = groupField;
                this.cm.setHidden(colIndex, true);
            }else if (eg && this.lastGroupField !== undefined && groupField !== this.lastGroupField) {
                this.mainBody.update('');
                var oldIndex = this.cm.findColumnIndex(this.lastGroupField);
                this.cm.setHidden(oldIndex, false);
                this.lastGroupField = groupField;
                this.cm.setHidden(colIndex, true);
            }
        }
        return Ext.ux.GroupingColorView.superclass.renderRows.apply(
                    this, arguments);
    },

    // private
    doRender : function(cs, rs, ds, startRow, colCount, stripe){
        if(rs.length < 1){
            return '';
        }
        var groupField = this.getGroupField(),
            colIndex = this.cm.findColumnIndex(groupField),
            g;

        this.enableGrouping = !!groupField;

        if(!this.enableGrouping || this.isUpdating){
            return Ext.ux.GroupingColorView.superclass.doRender.apply(
                    this, arguments);
        }
        var gstyle = 'width:'+this.getTotalWidth()+';';

        var gidPrefix = this.grid.getGridEl().id;
        var cfg = this.cm.config[colIndex];
        var groupRenderer = cfg.groupRenderer || cfg.renderer;
        var prefix = this.showGroupName ?
                     (cfg.groupName || cfg.header)+': ' : '';

        var groups = [], curGroup, i, len, gid;
        for(i = 0, len = rs.length; i < len; i++){
            var rowIndex = startRow + i,
                r = rs[i],
                gvalue = r.data[groupField];
                
                g = this.getGroup(gvalue, r, groupRenderer, rowIndex, colIndex, ds);
            if(!curGroup || curGroup.group != g){
                gid = gidPrefix + '-gp-' + groupField + '-' + Ext.util.Format.htmlEncode(g);
               	// if state is defined use it, however state is in terms of expanded
				// so negate it, otherwise use the default.
				var isCollapsed  = typeof this.state[gid] !== 'undefined' ? !this.state[gid] : this.startCollapsed;
				var gcls = isCollapsed ? 'x-grid-group-collapsed' : '';	
                curGroup = {
                    group: g,
                    gvalue: gvalue,
                    text: prefix + g,
                    groupId: gid,
                    startRow: rowIndex,
                    rs: [r],
                    cls: gcls,
                    style: gstyle
                };
                groups.push(curGroup);
            }else{
                curGroup.rs.push(r);
            }
            r._groupId = gid;
        }

        var buf = [];
        for(i = 0, len = groups.length; i < len; i++){
            g = groups[i];
            this.doGroupStart(buf, g, cs, ds, colCount);
            buf[buf.length] = Ext.ux.GroupingColorView.superclass.doRender.call(
                    this, cs, g.rs, ds, g.startRow, colCount, stripe);

            this.doGroupEnd(buf, g, cs, ds, colCount);
        }
        return buf.join('');
    },

    /**
     * Dynamically tries to determine the groupId of a specific value
     * @param {String} value
     * @return {String} The group id
     */
    getGroupId : function(value){
        var gidPrefix = this.grid.getGridEl().id;
        var groupField = this.getGroupField();
        var colIndex = this.cm.findColumnIndex(groupField);
        var cfg = this.cm.config[colIndex];
        var groupRenderer = cfg.groupRenderer || cfg.renderer;
        var gtext = this.getGroup(value, {data:{}}, groupRenderer, 0, colIndex, this.ds);
        return gidPrefix + '-gp-' + groupField + '-' + Ext.util.Format.htmlEncode(value);
    },

    // private
    doGroupStart : function(buf, g, cs, ds, colCount){
        buf[buf.length] = this.startGroup.apply(g);
    },

    // private
    doGroupEnd : function(buf, g, cs, ds, colCount){
        buf[buf.length] = this.endGroup;
    },

    // private
    getRows : function(){
        if(!this.enableGrouping){
            return Ext.ux.GroupingColorView.superclass.getRows.call(this);
        }
        var r = [];
        var g, gs = this.getGroups();
        for(var i = 0, len = gs.length; i < len; i++){
            g = gs[i].childNodes[1].childNodes;
            for(var j = 0, jlen = g.length; j < jlen; j++){
                r[r.length] = g[j];
            }
        }
        return r;
    },

    // private
    updateGroupWidths : function(){
        if(!this.enableGrouping || !this.hasRows()){
            return;
        }
        var tw = Math.max(this.cm.getTotalWidth(), this.el.dom.offsetWidth-this.scrollOffset) +'px';
        var gs = this.getGroups();
        for(var i = 0, len = gs.length; i < len; i++){
            gs[i].firstChild.style.width = tw;
        }
    },

    // private
    onColumnWidthUpdated : function(col, w, tw){
        Ext.ux.GroupingColorView.superclass.onColumnWidthUpdated.call(this, col, w, tw);
        this.updateGroupWidths();
    },

    // private
    onAllColumnWidthsUpdated : function(ws, tw){
        Ext.ux.GroupingColorView.superclass.onAllColumnWidthsUpdated.call(this, ws, tw);
        this.updateGroupWidths();
    },

    // private
    onColumnHiddenUpdated : function(col, hidden, tw){
        Ext.ux.GroupingColorView.superclass.onColumnHiddenUpdated.call(this, col, hidden, tw);
        this.updateGroupWidths();
    },

    // private
    onLayout : function(){
        this.updateGroupWidths();
    },

    // private
    onBeforeRowSelect : function(sm, rowIndex){
        if(!this.enableGrouping){
            return;
        }
        var row = this.getRow(rowIndex);
        if(row && !row.offsetParent){
            var g = this.findGroup(row);
            this.toggleGroup(g, true);
        }
    }
});
// private
Ext.ux.GroupingColorView.GROUP_ID = 1000;

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid/Ext.ux.GroupingColorView.js  ]*/

/*
 * For the grids with no initial sort, the grouping view attempt shows a error
 * 
 * @author: prakash paudel
 */
Ext.override(Ext.data.GroupingStore, {
    applySort : function(){
        Ext.data.GroupingStore.superclass.applySort.call(this);
        if(!this.groupOnSort && !this.remoteGroup){
            var gs = this.getGroupState();
            var si = this.sortInfo || {};
            if(gs && gs != si.field){
            	this.sortData(this.groupField);
            }
        }
    }
}); 

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid/Ext.ux.Grid.GroupingStoreOverride.js  ]*/

/**
* Export UI for grids
* 
* @author:Prakash Paudel
*/
Ext.ns("Ext.ux");
Ext.ux.ExportUI = function(config){
	var cfg = {
		shadow:false,
		title:"Exports"
	};	
	this.exportOptions = new Ext.form.FieldSet({
		columnWidth:.5,
		height:120,
		xtype:'fieldset',
		bodyStyle:'padding:10px',
		style:'margin:5px',
		title:'Export options',	
		items:[{html:'Please select an export format'}]
	});
	Ext.apply(cfg,config);
	Ext.apply(this,cfg);	
	Ext.ux.ExportUI.superclass.constructor.call(this,cfg);	
};
Ext.extend(Ext.ux.ExportUI,Ext.Window,{
	findLabel: function(key){
		var label = key;
		if(this.labels){
			Ext.iterate(this.labels,function(k,v){
				if(key == k) label = v;
			});
		}
		return label;
	},
	formatHandler: function(b,s){
		this.exportOptions.removeAll();
		this.exportOptions.add(
			new Ext.form.RadioGroup({
				hideLabel:true,				
				columns:1,					
				defaults:{anchor:'95%'},					
				items:this.findOptions(Ext.isObject(b)?b.getGroupValue():b)
			})
		);		
		this.exportOptions.doLayout();
	},
	findOptions: function(key){
		var options = [];
		var format = null;
		if(this.exportConfig){			
			Ext.iterate(this.exportConfig,function(k,v){
				if(k == key) format = v;
			},this);
		}
		if(format){
			Ext.iterate(format,function(k,v){				
				options.push({
					boxLabel:this.findLabel(k),						
					name:'option',
					inputValue:k
				})
			},this);
		}
		return options;
	},
	findFormats: function(){	
		var formats = [];
		var initiated = false;
		if(this.exportConfig){			
			Ext.iterate(this.exportConfig,function(key,value){				
				formats.push({						
					boxLabel:this.findLabel(key),						
					name:'format',
					inputValue:key,
					checked:initiated?false:true,
					listeners:{
						check: this.formatHandler.createDelegate(this)
					}
				});	
				if(!initiated){
					this.formatHandler(key);
					initiated = true;
				}
				
			},this);			
		}
		return formats;
	},
	onRender: function(ct,position){	
		Ext.ux.ExportUI.superclass.onRender.call(this, ct, position);
		var win = this;
		var hideWindow = function(button){
			win.hide();
		}		
		var uiPanel = new Ext.FormPanel({
			frame:true,
			layout:'column',			
			buttons:[{
				text:"Ok",
				handler: function(b){
					var form = uiPanel.getForm();
					var vals = form.getValues();
					if(!vals.format || !vals.option){
						Ext.Msg.alert("Error","Please select both export format and export option");
						return;
					}
					eval("win."+vals.format+vals.option+"()");
					//hideWindow(b);
				}				
			},{
				text:"Cancel",
				handler: hideWindow	
			}],
			items:[{		
				columnWidth:.5,	
				height:120,				
				bodyStyle:'padding:10px',
				style:'margin:5px',
				xtype:'fieldset',
				title:'Export formats',	
				items:[{
					hideLabel:true,
					xtype:'radiogroup',					
					columns:1,					
					defaults:{anchor:'95%'},					
					items:this.findFormats()
				}]
			},this.exportOptions]
		});
		this.add(uiPanel);
	}	
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/plugins/Ext.ux.ExportUI.js  ]*/

Ext.ns('Ext.ux.grid');

Ext.ux.grid.GridSummary = function(config) {
        Ext.apply(this, config);
};

Ext.extend(Ext.ux.grid.GridSummary, Ext.util.Observable, {
    init : function(grid) {
        this.grid = grid;
        this.cm = grid.getColumnModel();
        this.view = grid.getView();

        var v = this.view;

        // override GridView's onLayout() method
        v.onLayout = this.onLayout;

        v.afterMethod('render', this.refreshSummary, this);
        v.afterMethod('refresh', this.refreshSummary, this);
        v.afterMethod('syncScroll', this.syncSummaryScroll, this);
        v.afterMethod('onColumnWidthUpdated', this.doWidth, this);
        v.afterMethod('onAllColumnWidthsUpdated', this.doAllWidths, this);
        v.afterMethod('onColumnHiddenUpdated', this.doHidden, this);

        // update summary row on store's add/remove/clear/update events
        grid.store.on({
            add: this.refreshSummary,
            remove: this.refreshSummary,
            clear: this.refreshSummary,
            update: this.refreshSummary,
            scope: this
        });

        if (!this.rowTpl) {
            this.rowTpl = new Ext.Template(
                '<div class="x-grid3-summary-row x-grid3-gridsummary-row-offset">',
                    '<table class="x-grid3-summary-table" border="0" cellspacing="0" cellpadding="0" style="{tstyle}">',
                        '<tbody><tr>{cells}</tr></tbody>',
                    '</table>',
                '</div>'
            );
            this.rowTpl.disableFormats = true;
        }
        this.rowTpl.compile();

        if (!this.cellTpl) {
            this.cellTpl = new Ext.Template(
                '<td class="x-grid3-col x-grid3-cell x-grid3-td-{id} {css}" style="{style}">',
                    '<div class="x-grid3-cell-inner x-grid3-col-{id}" unselectable="on" {attr}>{value}</div>',
                "</td>"
            );
            this.cellTpl.disableFormats = true;
        }
        this.cellTpl.compile();
    },

    calculate : function(rs, cm) {
        var data = {}, cfg = cm.config;
        for (var i = 0, len = cfg.length; i < len; i++) { // loop through all columns in ColumnModel
            var cf = cfg[i], // get column's configuration
                cname = cf.dataIndex; // get column dataIndex

            // initialise grid summary row data for
            // the current column being worked on
            data[cname] = 0;

            if (cf.summaryType) {
                for (var j = 0, jlen = rs.length; j < jlen; j++) {
                    var r = rs[j]; // get a single Record
                    data[cname] = Ext.ux.grid.GridSummary.Calculations[cf.summaryType](r.get(cname), r, cname, data, j);
                }
            }
        }

        return data;
    },

    onLayout : function(vw, vh) {
        if (Ext.type(vh) != 'number') { // handles grid's height:'auto' config
            return;
        }
        // note: this method is scoped to the GridView
        if (!this.grid.getGridEl().hasClass('x-grid-hide-gridsummary')) {
            // readjust gridview's height only if grid summary row is visible
            this.scroller.setHeight(vh - this.summary.getHeight());
        }
    },

    syncSummaryScroll : function() {
        var mb = this.view.scroller.dom;

        this.view.summaryWrap.dom.scrollLeft = mb.scrollLeft;
        this.view.summaryWrap.dom.scrollLeft = mb.scrollLeft; // second time for IE (1/2 time first fails, other browsers ignore)
    },

    doWidth : function(col, w, tw) {
        var s = this.view.summary.dom;

        s.firstChild.style.width = tw;
        s.firstChild.rows[0].childNodes[col].style.width = w;
    },

    doAllWidths : function(ws, tw) {
        var s = this.view.summary.dom, wlen = ws.length;

        s.firstChild.style.width = tw;

        var cells = s.firstChild.rows[0].childNodes;

        for (var j = 0; j < wlen; j++) {
            cells[j].style.width = ws[j];
        }
    },

    doHidden : function(col, hidden, tw) {
        var s = this.view.summary.dom,
            display = hidden ? 'none' : '';

        s.firstChild.style.width = tw;
        s.firstChild.rows[0].childNodes[col].style.display = display;
    },

    renderSummary : function(o, cs, cm) {
        cs = cs || this.view.getColumnData();
        var cfg = cm.config,
            buf = [],
            last = cs.length - 1;

        for (var i = 0, len = cs.length; i < len; i++) {
            var c = cs[i], cf = cfg[i], p = {};

            p.id = c.id;
            p.style = c.style;
            p.css = i === 0 ? 'x-grid3-cell-first ' : (i == last ? 'x-grid3-cell-last ' : '');

            if (cf.summaryType || cf.summaryRenderer) {
                p.value = (cf.summaryRenderer || c.renderer)(o.data[c.name], p, o);
            } else {
                p.value = '';
            }
			/* check if renderer in calculations */
			
			if(cf.summaryType && Ext.ux.grid.GridSummary.Calculations[cf.summaryType+'Renderer']){
				p.value = Ext.ux.grid.GridSummary.Calculations[cf.summaryType+'Renderer'](o.data[c.name], p, o);				
			}
            if (p.value === undefined || p.value === "") {
                p.value = "&#160;";
            }
            buf[buf.length] = this.cellTpl.apply(p);
        }

        return this.rowTpl.apply({
            tstyle: 'width:' + this.view.getTotalWidth() + ';',
            cells: buf.join('')
        });
    },

    refreshSummary : function() {
        var g = this.grid, ds = g.store,
            cs = this.view.getColumnData(),
            cm = this.cm,
            rs = ds.getRange(),
            data = this.calculate(rs, cm),
            buf = this.renderSummary({data: data}, cs, cm);

        if (!this.view.summaryWrap) {
            this.view.summaryWrap = Ext.DomHelper.insertAfter(this.view.scroller, {
                tag: 'div',
                cls: 'x-grid3-gridsummary-row-inner'				
            }, true);
        }
        this.view.summary = this.view.summaryWrap.update(buf).first();
    },

    toggleSummary : function(visible) { // true to display summary row
        var el = this.grid.getGridEl();

        if (el) {
            if (visible === undefined) {
                visible = el.hasClass('x-grid-hide-gridsummary');
            }
            el[visible ? 'removeClass' : 'addClass']('x-grid-hide-gridsummary');

            this.view.layout(); // readjust gridview height
        }
    },

    getSummaryNode : function() {
        return this.view.summary;
    }
});
Ext.reg('gridsummary', Ext.ux.grid.GridSummary);

/*
 * all Calculation methods are called on each Record in the Store
 * with the following 5 parameters:
 *
 * v - cell value
 * record - reference to the current Record
 * colName - column name (i.e. the ColumnModel's dataIndex)
 * data - the cumulative data for the current column + summaryType up to the current Record
 * rowIdx - current row index
 */
Ext.ux.grid.GridSummary.stripTags = function(s){
    s = String(s).replace(/<div class="ux-grid-filter-hidden-value">[\S]+<\/div>/,"");	
	var re= /<\S[^>]*>/gi; 	
	s = String(s).replace(re,""); 	
	if(isNaN(s)) return s;
	return Number(s);
}
Ext.ux.grid.GridSummary.Calculations = {
    sum : function(v, record, colName, data, rowIdx) {
        return Ext.ux.grid.GridSummary.stripTags(data[colName]) + Ext.num(Ext.ux.grid.GridSummary.stripTags(v), 0);
    },
	sumRenderer: function(v,params,data){
		params.attr = 'ext:qtip="Total sum: '+v+'"';		
		return "Sum: "+v;
	},
    count : function(v, record, colName, data, rowIdx) {
        return rowIdx + 1;
    },
	countRenderer: function(v, params, data){
		params.attr = 'ext:qtip="Total count: '+v+'"';		
		return "Count: "+v;
	},
    max : function(v, record, colName, data, rowIdx) {		
        return Math.max(Ext.num(Ext.ux.grid.GridSummary.stripTags(v), 0), Ext.ux.grid.GridSummary.stripTags(data[colName]));
    },
	maxRenderer: function(v,params,data){
		params.attr = 'ext:qtip="Max: '+v+'"';		
		return "Max: "+v;
	},
    min : function(v, record, colName, data, rowIdx) {
        return Math.min(Ext.num(Ext.ux.grid.GridSummary.stripTags(v), 0), Ext.ux.grid.GridSummary.stripTags(data[colName]));
    },
	minRenderer: function(v,params,data){
		params.attr = 'ext:qtip="Min: '+v+'"';		
		return "Min: "+v;
	},
    average : function(v, record, colName, data, rowIdx) {
        var t = Ext.ux.grid.GridSummary.stripTags(data[colName]) + Ext.num(Ext.ux.grid.GridSummary.stripTags(v), 0), count = record.store.getCount();
        return rowIdx == count - 1 ? (t / count) : t;
    },
	averageRenderer: function(v,params,data){
		params.attr = 'ext:qtip="Average: '+v+'"';		
		return "Average: "+v;
	}
};

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/gridsummary/gridsummary.js  ]*/

Ext.namespace('Ext.ux.plugins'); 
/**
 * Ext.ux.plugins.AddExpandListButton plugin for Ext.grid.GridPanel
 *
 * @author  Prakash Paudel
 * @date    November 6, 2009
 *
 * @class Ext.ux.plugins.AddExpandListButton
 * @extends Ext.util.Observable
 */
Ext.ux.plugins.AddExpandListButton = function(config) {
    Ext.apply(this, config);
    Ext.ux.plugins.AddExpandListButton.superclass.constructor.call(this);
}; 

Ext.extend(Ext.ux.plugins.AddExpandListButton, Ext.util.Observable, {	
    init:function(grid) {
		Ext.apply(grid,{
			/*
			 * Configs
			 */
			expandedText : "Expanded View",
			listText : "List View",
			buttonState:"list",
			expandedIconCls:"icon-expanded-view",
			listIconCls:"icon-list-view",	
			selectedIconCls:"icon-selected-view",
			/************************************************************************************************/
			
			onRender:grid.onRender.createSequence(function(ct, position){	
				this.expandListButton = new Ext.Button({
					text: grid.expandedText,					
				   	handler: this.mainButtonHandler,				   
				    iconCls:this.expandedIconCls,
				    tooltip:"Click to toggle the view"
				});				
				this.topToolbar = grid.getTopToolbar();				
				this.topToolbar.addFill();
				this.topToolbar.add(this.expandListButton);
				grid.getView().on("refresh",this.reconfigureGrid);
			}),
			reconfigureGrid:function(view){			
				grid.isExpanded()?grid.setExpanded():grid.unsetExpanded();
			},
			setExpanded:function(){
				grid.buttonState = "expanded";
				grid.expandListButton.setText(grid.listText);
				grid.expandListButton.setIconClass(grid.listIconCls);
				grid.getView().el.select('.x-grid3-cell-inner').setStyle({'white-space':'normal'});				
			},
			unsetExpanded:function(){
				grid.buttonState = "list";
				grid.expandListButton.setText(grid.expandedText);
				grid.expandListButton.setIconClass(grid.expandedIconCls);
				grid.getView().el.select('.x-grid3-cell-inner').setStyle({'white-space':'nowrap'})
			},
			isExpanded:function(){
				return this.buttonState == "list"?false:true;
			},			
			mainButtonHandler:function(button){
				grid.isExpanded()?grid.unsetExpanded():grid.setExpanded();
			}
		});
    }	
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid/Ext.ux.plugins.AddExpandListButton.js  ]*/

/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
Ext.ns('Ext.ux.grid');

/**
 * @class Ext.ux.grid.RowExpander
 * @extends Ext.util.Observable
 * Plugin (ptype = 'rowexpander') that adds the ability to have a Column in a grid which enables
 * a second row body which expands/contracts.  The expand/contract behavior is configurable to react
 * on clicking of the column, double click of the row, and/or hitting enter while a row is selected.
 *
 * @ptype rowexpander
 */
Ext.ux.grid.RowExpander = Ext.extend(Ext.util.Observable, {
    /**
     * @cfg {Boolean} expandOnEnter
     * <tt>true</tt> to toggle selected row(s) between expanded/collapsed when the enter
     * key is pressed (defaults to <tt>true</tt>).
     */
    expandOnEnter : true,
    /**
     * @cfg {Boolean} expandOnDblClick
     * <tt>true</tt> to toggle a row between expanded/collapsed when double clicked
     * (defaults to <tt>true</tt>).
     */
    expandOnDblClick : true,

    header : '',
    width : 20,
    sortable : false,
    fixed : true,
    menuDisabled : true,
    dataIndex : '',
    id : 'expander',
    lazyRender : true,
    enableCaching : true,

    constructor: function(config){
        Ext.apply(this, config);

        this.addEvents({
            /**
             * @event beforeexpand
             * Fires before the row expands. Have the listener return false to prevent the row from expanding.
             * @param {Object} this RowExpander object.
             * @param {Object} Ext.data.Record Record for the selected row.
             * @param {Object} body body element for the secondary row.
             * @param {Number} rowIndex The current row index.
             */
            beforeexpand: true,
            /**
             * @event expand
             * Fires after the row expands.
             * @param {Object} this RowExpander object.
             * @param {Object} Ext.data.Record Record for the selected row.
             * @param {Object} body body element for the secondary row.
             * @param {Number} rowIndex The current row index.
             */
            expand: true,
            /**
             * @event beforecollapse
             * Fires before the row collapses. Have the listener return false to prevent the row from collapsing.
             * @param {Object} this RowExpander object.
             * @param {Object} Ext.data.Record Record for the selected row.
             * @param {Object} body body element for the secondary row.
             * @param {Number} rowIndex The current row index.
             */
            beforecollapse: true,
            /**
             * @event collapse
             * Fires after the row collapses.
             * @param {Object} this RowExpander object.
             * @param {Object} Ext.data.Record Record for the selected row.
             * @param {Object} body body element for the secondary row.
             * @param {Number} rowIndex The current row index.
             */
            collapse: true
        });

        Ext.ux.grid.RowExpander.superclass.constructor.call(this);

        if(this.tpl){
            if(typeof this.tpl == 'string'){
                this.tpl = new Ext.Template(this.tpl);
            }
            this.tpl.compile();
        }

        this.state = {};
        this.bodyContent = {};
    },

    getRowClass : function(record, rowIndex, p, ds){
        p.cols = p.cols-1;
        var content = this.bodyContent[record.id];
        if(!content && !this.lazyRender){
            content = this.getBodyContent(record, rowIndex);
        }
        if(content){
            p.body = content;
        }
        return this.state[record.id] ? 'x-grid3-row-expanded' : 'x-grid3-row-collapsed';
    },

    init : function(grid){
        this.grid = grid;

        var view = grid.getView();
        view.getRowClass = this.getRowClass.createDelegate(this);

        view.enableRowBody = true;


        grid.on('render', this.onRender, this);
        grid.on('destroy', this.onDestroy, this);
    },

    // @private
    onRender: function() {
        var grid = this.grid;
        var mainBody = grid.getView().mainBody;
        mainBody.on('mousedown', this.onMouseDown, this, {delegate: '.x-grid3-row-expander'});
        if (this.expandOnEnter) {
            this.keyNav = new Ext.KeyNav(this.grid.getGridEl(), {
                'enter' : this.onEnter,
                scope: this
            });
        }
        if (this.expandOnDblClick) {
            grid.on('rowdblclick', this.onRowDblClick, this);
        }
    },
    
    // @private    
    onDestroy: function() {
        this.keyNav.disable();
        delete this.keyNav;
        var mainBody = this.grid.getView().mainBody;
        mainBody.un('mousedown', this.onMouseDown, this);
    },
    // @private
    onRowDblClick: function(grid, rowIdx, e) {
        this.toggleRow(rowIdx);
    },

    onEnter: function(e) {
        var g = this.grid;
        var sm = g.getSelectionModel();
        var sels = sm.getSelections();
        for (var i = 0, len = sels.length; i < len; i++) {
            var rowIdx = g.getStore().indexOf(sels[i]);
            this.toggleRow(rowIdx);
        }
    },

    getBodyContent : function(record, index){
        if(!this.enableCaching){
            return this.tpl.apply(record.data);
        }
        var content = this.bodyContent[record.id];
        if(!content){
            content = this.tpl.apply(record.data);
            this.bodyContent[record.id] = content;
        }
        return content;
    },

    onMouseDown : function(e, t){
        e.stopEvent();
        var row = e.getTarget('.x-grid3-row');
        this.toggleRow(row);
    },

    renderer : function(v, p, record){
        p.cellAttr = 'rowspan="2"';
        return '<div class="x-grid3-row-expander">&#160;</div>';
    },

    beforeExpand : function(record, body, rowIndex){
        if(this.fireEvent('beforeexpand', this, record, body, rowIndex) !== false){
            if(this.tpl && this.lazyRender){
                body.innerHTML = this.getBodyContent(record, rowIndex);
            }
            return true;
        }else{
            return false;
        }
    },

    toggleRow : function(row){
        if(typeof row == 'number'){
            row = this.grid.view.getRow(row);
        }
        this[Ext.fly(row).hasClass('x-grid3-row-collapsed') ? 'expandRow' : 'collapseRow'](row);
    },

    expandRow : function(row){
        if(typeof row == 'number'){
            row = this.grid.view.getRow(row);
        }
        var record = this.grid.store.getAt(row.rowIndex);
        var body = Ext.DomQuery.selectNode('tr:nth(2) div.x-grid3-row-body', row);
        if(this.beforeExpand(record, body, row.rowIndex)){
            this.state[record.id] = true;
            Ext.fly(row).replaceClass('x-grid3-row-collapsed', 'x-grid3-row-expanded');
            this.fireEvent('expand', this, record, body, row.rowIndex);
        }
    },

    collapseRow : function(row){
        if(typeof row == 'number'){
            row = this.grid.view.getRow(row);
        }
        var record = this.grid.store.getAt(row.rowIndex);
        var body = Ext.fly(row).child('tr:nth(1) div.x-grid3-row-body', true);
        if(this.fireEvent('beforecollapse', this, record, body, row.rowIndex) !== false){
            this.state[record.id] = false;
            Ext.fly(row).replaceClass('x-grid3-row-expanded', 'x-grid3-row-collapsed');
            this.fireEvent('collapse', this, record, body, row.rowIndex);
        }
    }
});

Ext.preg('rowexpander', Ext.ux.grid.RowExpander);

//backwards compat
Ext.grid.RowExpander = Ext.ux.grid.RowExpander;

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid/RowExpander.js  ]*/

Ext.namespace("Ext.ux.menu");
Ext.ux.menu.EditableItem = Ext.extend(Ext.menu.BaseItem, {
    itemCls : "x-menu-item",
    hideOnClick: false,
    
    initComponent: function(){
    	this.addEvents({keyup: true});
    	
		this.editor = this.editor || new Ext.form.TextField();
		if(this.text)
			this.editor.setValue(this.text);
    },
    
    onRender: function(container){
        var s = container.createChild({
        	cls: this.itemCls,
        	html: '<img id="my" src="' + (Ext.BLANK_IMAGE_URL)+ '" class="x-menu-item-icon'+(this.iconCls?' '+this.iconCls:'')+'" style="margin: 3px 7px 2px 2px;" />'
        });
        
        Ext.apply(this.config, {width: 125});
        this.editor.render(s);
        
        this.el = s;
        this.relayEvents(this.editor.el, ["keyup"]);
        
        if(Ext.isGecko)
			s.setStyle('overflow', 'auto');
		
		//added next line to hide the class name created automatically by Extjs
		//@author radu
		container.dom.firstChild.className="x-menu-item-icon";
			
		if(this.iconCls&&this.iconCls=='ux-rangemenu-lt')
		{
			s.dom.firstChild.style.margin='28px 7px 2px 2px';
		}
		else if(this.iconCls&&this.iconCls=='ux-rangemenu-eq')
		{
			s.dom.firstChild.style.margin='60px 7px 2px 2px';
		}
		else if(this.iconCls&&this.iconCls=='ux-rangemenu-ne')
		{
			s.dom.firstChild.style.margin='83px 7px 2px 2px';
		}
			
		this.editor.el.setStyle('marginLeft','0px');	
			
        Ext.ux.menu.EditableItem.superclass.onRender.apply(this, arguments);
    },
    
    getValue: function(){
    	return this.editor.getValue();
    },
    
    setValue: function(value){    	
    	this.editor.setValue(value);
    },
    
    isValid: function(preventMark){
    	return this.editor.isValid(preventMark);
    }
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid-filtering/ux/menu/EditableItem.js  ]*/

Ext.namespace("Ext.ux.menu");
Ext.ux.menu.ComboMenu = Ext.extend(Ext.menu.BaseItem, {
    itemCls : "x-menu-item",
    hideOnClick: false, 
    tempValue:null,
    
    initComponent: function(){
    	this.addEvents({keyup: true});
    	var combo;
    	if(this.lovcombo){
    		combo = new Ext.ux.form.LovCombo({
	            store: this.store,
	            mode: 'local',            
	            triggerAction: 'all',
	            emptyText:'Select an option...',
	            selectOnFocus:true,
	            resizable:true
	        });
    	}else{
	        combo = new Ext.form.ComboBox({
	            store: this.store,            
	            typeAhead: true,
	            mode: 'local',            
	            triggerAction: 'all',
	            emptyText:'Select an option...',
	            selectOnFocus:true,
	            resizable:true
	        });   
    	}
        this.editor = combo;
		if(this.text)
			this.editor.setValue(this.text);		
    },
    onRender: function(container){
    	
        var s = container.createChild({
        	cls: this.itemCls
        });
        
        Ext.apply(this.config, {width: 225});
        this.editor.render(s);
        
        this.el = s;
        this.relayEvents(this.editor.el, ["keyup"]);
       
        if(Ext.isGecko)
			s.setStyle('overflow', 'auto');
		if(this.tempValue !== null) this.setValue(this.tempValue);
        Ext.ux.menu.ComboMenu.superclass.onRender.apply(this, arguments);
    },
    
    getValue: function(){    	
    	if(this.editor.el){
    		return this.editor.getValue();
    	}else{
    		return this.tempValue;
    	}
    },
    
    setValue: function(value){    	
    	if(this.editor.el){    		
    		this.editor.setValue(value);
    	}else{
    		this.tempValue = value;
    		this.tempDisplayValue = value;
    	}
    	
    },
    
    isValid: function(preventMark){
    	return this.editor.isValid(preventMark);
    },
    //Get display value
    getDisplayValue: function(){
    	var values = new Array();
    	var displayValues = [];    	
    	v = this.getValue();    	
    	var store = this.editor.getStore();    	
    	if(v.toString().match(/,/)){
    		values = v.toString().split(",");
    	}else{
    		values[0] = v;
    	}    	
    	for(var i=0;i<values.length;i++){
    		if(values[i] == '') continue;    		
    		var index = store.findExact('field1',parseInt(values[i]));
    		if(index == -1) index = store.find('field1',values[i]);
        	var record = store.getAt(index);
        	displayValues.push(record.get('field2'));
    	}	
    	return displayValues.join(", ");
    }
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid-filtering/ux/menu/ComboMenu.js  ]*/

Ext.namespace("Ext.ux.menu");
Ext.ux.menu.RangeMenu = function(){
	Ext.ux.menu.RangeMenu.superclass.constructor.apply(this, arguments);
	this.updateTask = new Ext.util.DelayedTask(this.fireUpdate, this);

	var cfg = this.fieldCfg;
	var cls = this.fieldCls;
	var fields = this.fields = Ext.applyIf(this.fields || {}, {
		'gt': new Ext.ux.menu.EditableItem({
			iconCls:  this.icons.gt,
			editor: new cls(typeof cfg == "object" ? cfg.gt || '' : cfg)}),
		'lt': new Ext.ux.menu.EditableItem({
			iconCls:  this.icons.lt,
			editor: new cls(typeof cfg == "object" ? cfg.lt || '' : cfg)}),
		'eq': new Ext.ux.menu.EditableItem({
			iconCls:   this.icons.eq, 
			editor: new cls(typeof cfg == "object" ? cfg.gt || '' : cfg)}),
		'ne': new Ext.ux.menu.EditableItem({
			iconCls:   this.icons.ne, 
			editor: new cls(typeof cfg == "object" ? cfg.gt || '' : cfg)})
	});
			
	this.add(fields.gt, fields.lt, '-', fields.eq,fields.ne);
	
	for(var key in fields)
		fields[key].on('keyup', function(event, input, notSure, field){
			if(event.getKey() == event.ENTER && field.isValid()){
				this.updateTask.delay(this.updateBuffer);
				this.hide(true);
				return;
			}
			
			if(field == fields.eq){
				fields.gt.setValue(null);
				fields.lt.setValue(null);
				fields.ne.setValue(null);
			} else if(field == fields.ne){
				fields.gt.setValue(null);
				fields.lt.setValue(null);
				fields.eq.setValue(null);
			}else{
				fields.eq.setValue(null);
				fields.ne.setValue(null);
			}			
		}.createDelegate(this, [fields[key]], true));

	this.addEvents({'update': true});
};
Ext.extend(Ext.ux.menu.RangeMenu, Ext.menu.Menu, {
	fieldCls:     Ext.form.NumberField,
	fieldCfg:     '',
	updateBuffer: 500,
	icons: {
		gt: 'ux-rangemenu-gt', 
		lt: 'ux-rangemenu-lt',
		eq: 'ux-rangemenu-eq',
		ne: 'ux-rangemenu-ne'},
		
	fireUpdate: function(){
		this.fireEvent("update", this);
	},
	
	setValue: function(data){		
		for(var key in this.fields)
			this.fields[key].setValue(data[key] !== undefined ? data[key] : '');
		
		this.fireEvent("update", this);
	},
	
	getValue: function(){
		var result = {};
		
		for(var key in this.fields){
			var field = this.fields[key];
			if(field.isValid() && String(field.getValue()).length > 0)
				result[key] = field.getValue();
		}
		return result;
	}
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid-filtering/ux/menu/RangeMenu.js  ]*/

/**
 * Fix for Ext 3
 */
Ext.grid.GridView.prototype.handleHdMenuClick = function(item){
    var index = this.hdCtxIndex;
    var cm = this.cm, ds = this.ds;
    switch (item.getItemId()) {
        case "asc":
            ds.sort(cm.getDataIndex(index), "ASC");
            break;
        case "desc":
            ds.sort(cm.getDataIndex(index), "DESC");
            break;
        default:
            index = cm.getIndexById(item.getItemId().substr(4));
            if (index != -1) {
                if (item.checked && cm.getColumnsBy(this.isHideableColumn, this).length <= 1) {
                    this.onDenyColumnHide();
                    return false;
                }
                cm.setHidden(index, item.checked);
            }
    }
    return true;
};

/* fix finished **************************************************/
/**
 * Ext.ux.grid.GridFilters v0.2.7
 **/

Ext.namespace("Ext.ux.grid");
Ext.ux.grid.GridFilters = function(config){		
	this.filters = new Ext.util.MixedCollection();
	this.filters.getKey = function(o){return o ? o.dataIndex : null};
	
	for(var i=0, len=config.filters.length; i<len; i++)
		this.addFilter(config.filters[i]);
	
	this.deferredUpdate = new Ext.util.DelayedTask(this.reload, this);
	
	delete config.filters;
	Ext.apply(this, config);
};
Ext.extend(Ext.ux.grid.GridFilters, Ext.util.Observable, {
	/**
	 * @cfg {Integer} updateBuffer
	 * Number of milisecond to defer store updates since the last filter change.
	 */
	updateBuffer: 500,
	/**
	 * @cfg {String} paramPrefix
	 * The url parameter prefix for the filters.
	 */
	paramPrefix: 'filter',
	/**
	 * @cfg {String} fitlerCls
	 * The css class to be applied to column headers that active filters. Defaults to 'ux-filterd-column'
	 */
	filterCls: 'ux-filtered-column',
	/**
	 * @cfg {Boolean} local
	 * True to use Ext.data.Store filter functions instead of server side filtering.
	 */
	local: false,
	/**
	 * @cfg {Boolean} autoReload
	 * True to automagicly reload the datasource when a filter change happens.
	 */
	autoReload: true,
	/**
	 * @cfg {String} stateId
	 * Name of the Ext.data.Store value to be used to store state information.
	 */
	stateId: undefined,
	/**
	 * @cfg {Boolean} showMenu
	 * True to show the filter menus
	 */
	showMenu: true,

	menuFilterText: 'Filters',
		
	mode:'menu',
	
	/**
	* Show filter info at
	* 1. panel -for displaying filter info in a panel just above grid
	* 2. title -for displaying filter info in grid title
	* 3. false -for not displaying filter info
	*/	
	showFilterInfo: 'panel',

	init: function(grid){	
		if(grid instanceof Ext.grid.GridPanel){
			this.grid  = grid;
		    this.grid.originalTitle = this.grid.originalTitle?this.grid.originalTitle:grid.title;
			this.store = this.grid.getStore();
			
			if(this.local){
				this.store.on('load', function(store){						
					store.filterBy(this.getRecordFilter());					
				}, this);
			} else {
			  this.store.on('beforeload', this.onBeforeLoad, this);
			  this.store.on('load',function(){
					if(this.showFilterInfo)
					new Ext.ux.FilterInfo(this.grid,this.showFilterInfo);
			  },this);
			}		
			
			this.grid.filters = this;
			 
			this.grid.addEvents({"filterupdate": true});
			  
			grid.on("render", this.onRender, this);	
			grid.on("beforerender",this.applyState,this);
			if(this.applyPrivateCookie()){				
				this.saveState(this.grid,this.applyPrivateCookie());
				this.grid.saveState()
			}
			grid.on("beforestaterestore", this.applyState, this);
			grid.on("beforestatesave", this.saveState, this);
					  
		} else if(grid instanceof Ext.PagingToolbar){
		  this.toolbar = grid;
		}		
	},
		
	/** private **/
	applyState: function(grid, state){		
		this.suspendStateStore = true;
		this.clearFilters();
		var s;
		state = (s = this.applyPrivateCookie())?s:state;
		//console.log(this.applyPrivateCookie());
		if(state && state.filters)
			for(var key in state.filters){
				var filter = this.filters.get(key);
				if(filter){						
					filter.setValue(state.filters[key]);					
					filter.setActive(true);
				}
			}
		
		this.deferredUpdate.cancel();
		if(this.local)
			this.reload();
		
		this.suspendStateStore = false;
		
		/* Filter by filterby parameter */		
		if(this.filterby && this.filterbyKeyword){
			var ss = new Ext.ux.SaveSearchState(this.grid);
			ss.restore(this.filterby, this.filterbyKeyword);
		}
		
	},
	
	/** private **/
	saveState: function(grid, state){
		var filters = {};
		this.filters.each(function(filter){
			if(filter.active)
				filters[filter.dataIndex] = filter.getValue();
		});		
		return state.filters = filters;
	},
	/**
     * Private cookie for log search only
     */
	applyPrivateCookie: function(){
		var path = this.grid.path;		
		var cp = new Ext.state.CookieProvider({
			
		});		
		
		var cookie = cp.get("search_result_cookie");
		
		if(cookie){
			var cookie_obj = Ext.util.JSON.decode(cookie);
			for(key in cookie_obj){
				var filter = this.filters.get(key);
				if(filter){
					if(filter.type == "date"){
						for(k in cookie_obj[key]){						
							cookie_obj[key][k] = new Date(Date.parseDate(cookie_obj[key][k],"Y-m-d"))
						}
					}
					if(filter.type == "string"){
						cookie_obj[key] = cookie_obj[key].toString().replace(/\+/g," ");
					}
				}
			}			
			var state = {
				filters: cookie_obj
			}
			//cp.set("log_index_search","");
			//return false;
			//console.log(state)
			return state;
		}		
		return false;
		
	/******************************************/
	},
	/** private **/
	onRender: function(){
		if(this.mode == 'header'){
			new Ext.ux.RePositionFilters(this.grid);
			return;
		}
		var hmenu;		
		if(this.showMenu){
			hmenu = this.grid.getView().hmenu;
			
			this.sep  = hmenu.addSeparator();
			this.menu = hmenu.add(new Ext.menu.CheckItem({
					text: this.menuFilterText,
					menu: new Ext.menu.Menu()
				}));
			this.menu.on('checkchange', this.onCheckChange, this);
			this.menu.on('beforecheckchange', this.onBeforeCheck, this);
				
			hmenu.on('beforeshow', this.onMenu, this);
		}
		
		this.grid.getView().on("refresh", this.onRefresh, this);
		this.updateColumnHeadings(this.grid.getView());

		
	},
	
	/** private **/
	onMenu: function(filterMenu){		
		var filter = this.getMenuFilter();
		if(filter){			
			this.menu.menu = filter.menu;
			this.menu.setChecked(filter.active, false);
		}
		
		this.menu.setVisible(filter !== undefined);
		//this.sep.setVisible(filter !== undefined);
	},
	
	/** private **/
	onCheckChange: function(item, value){
		this.getMenuFilter().setActive(value);		
	},
	
	/** private **/
	onBeforeCheck: function(check, value){
		return !value || this.getMenuFilter().isActivatable();
	},
	
	/** private **/
	onStateChange: function(event, filter){
		
		if(event == "serialize") return;
    
		if(filter == this.getMenuFilter() && this.mode == 'hmenu')
			this.menu.setChecked(filter.active, false);
			
		if(this.autoReload || this.local)
			this.deferredUpdate.delay(this.updateBuffer);
		
		var view = this.grid.getView();
		this.updateColumnHeadings(view);
			
		this.grid.saveState();
			
		this.grid.fireEvent('filterupdate', this, filter);		
	},
	
	/** private **/
	onBeforeLoad: function(store, options){
		options.params = options.params || {};
		this.cleanParams(options.params);		
		var params = this.buildQuery(this.getFilterData());
		Ext.apply(options.params, params);			
	},
	
	/** private **/
	onRefresh: function(view){
		this.updateColumnHeadings(view);
	},
	
	/** private **/
	getMenuFilter: function(){
		var view = this.grid.getView();
		if(!view || view.hdCtxIndex === undefined)
			return null;
		
		return this.filters.get(
			view.cm.config[view.hdCtxIndex].dataIndex);
	},
	
	/** private **/
	updateColumnHeadings: function(view){
		if(!view || !view.mainHd) return;
		if(this.mode == 'header') return;
		var hds = view.mainHd.select('td').removeClass(this.filterCls);
		for(var i=0, len=view.cm.config.length; i<len; i++){
			var filter = this.getFilter(view.cm.config[i].dataIndex);
			if(filter && filter.active)
				hds.item(i).addClass(this.filterCls);
		}
	},
	
	/** private **/
	reload: function(){
		if(this.local){
			this.grid.store.clearFilter(true);
			this.grid.store.filterBy(this.getRecordFilter());
		} else {
			this.deferredUpdate.cancel();
			var store = this.grid.store;
			if(this.toolbar){				
				var start = this.toolbar.paramNames.start;
				if(store.lastOptions && store.lastOptions.params && store.lastOptions.params[start])
					store.lastOptions.params[start] = 0;
			}
			if(store.lastOptions && store.lastOptions.params && store.lastOptions.params.start){
				store.lastOptions.params.start = 0;
			}
			store.reload();			
		}
	},
	
	/**
	 * Method factory that generates a record validator for the filters active at the time
	 * of invokation.
	 * 
	 * @private
	 */
	getRecordFilter: function(){
		var f = [];
		this.filters.each(function(filter){
			if(filter.active) f.push(filter);
		});
		
		var len = f.length;
		return function(record){
			for(var i=0; i<len; i++)
				if(!f[i].validateRecord(record))
					return false;
				
			return true;
		};
	},
	
	/**
	 * Adds a filter to the collection.
	 * 
	 * @param {Object/Ext.ux.grid.filter.Filter} config A filter configuration or a filter object.
	 * 
	 * @return {Ext.ux.grid.filter.Filter} The existing or newly created filter object.
	 */
	addFilter: function(config){
		var filter = config.menu ? config : 
				new (this.getFilterClass(config.type))(config);
		this.filters.add(filter);
		
		Ext.util.Observable.capture(filter, this.onStateChange, this);
		return filter;
	},
	
	/**
	 * Returns a filter for the given dataIndex, if on exists.
	 * 
	 * @param {String} dataIndex The dataIndex of the desired filter object.
	 * 
	 * @return {Ext.ux.grid.filter.Filter}
	 */
	getFilter: function(dataIndex){
		return this.filters.get(dataIndex);
	},

	/**
	 * Turns all filters off. This does not clear the configuration information.
	 */
	clearFilters: function(){
		this.filters.each(function(filter){
			filter.setActive(false);
		});
	},

	/** private **/
	getFilterData: function(){
		var filters = [],
			fields  = this.grid.getStore().fields;
		
		this.filters.each(function(f){
			if(f.active){
				var d = [].concat(f.serialize());
				for(var i=0, len=d.length; i<len; i++)
					filters.push({
						field: f.dataColumn?f.dataColumn:f.dataIndex,
						data: d[i]
					});
			}
		});
		
		return filters;
	},
	
	/**
	 * Function to take structured filter data and 'flatten' it into query parameteres. The default function
	 * will produce a query string of the form:
	 * 		filters[0][field]=dataIndex&filters[0][data][param1]=param&filters[0][data][param2]=param...
	 * 
	 * @param {Array} filters A collection of objects representing active filters and their configuration.
	 * 	  Each element will take the form of {field: dataIndex, data: filterConf}. dataIndex is not assured
	 *    to be unique as any one filter may be a composite of more basic filters for the same dataIndex.
	 * 
	 * @return {Object} Query keys and values
	 */
	buildQuery: function(filters){
		var p = {};
		for(var i=0, len=filters.length; i<len; i++){
			var f    = filters[i];
			var root = [this.paramPrefix, '[', i, ']'].join('');
			p[root + '[field]'] = f.field;
			
			var dataPrefix = root + '[data]';
			for(var key in f.data)
				p[[dataPrefix, '[', key, ']'].join('')] = f.data[key];
		}
		
		return p;
	},
	
	/**
	 * Removes filter related query parameters from the provided object.
	 * 
	 * @param {Object} p Query parameters that may contain filter related fields.
	 */
	cleanParams: function(p){
		var regex = new RegExp("^" + this.paramPrefix + "\[[0-9]+\]");
		for(var key in p)
			if(regex.test(key))
				delete p[key];
	},
	
	/**
	 * Function for locating filter classes, overwrite this with your favorite
	 * loader to provide dynamic filter loading.
	 * 
	 * @param {String} type The type of filter to load.
	 * 
	 * @return {Class}
	 */
	getFilterClass: function(type){	    
		return Ext.ux.grid.filter[type.substr(0, 1).toUpperCase() + type.substr(1) + 'Filter'];
	}
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid-filtering/ux/grid/GridFilters.js  ]*/

/**
* Ext.ux.DrillFilter
* @author: Prakash Paudel
*
* Drill filtering to the grid filter, by clicking on the text or selecting the text
*/
Ext.ns("Ext.ux");
Ext.ux.DrillFilter = function(grid,e){
	
	//Find clicked target
	var t = e.getTarget();
	
	//Find column index
	var colIndex = grid.getView().findCellIndex(t.parentNode);
	
	//Find row index
    var rowIndex = grid.getView().findRowIndex(t);
    
    //Get column model
    var cm = grid.getColumnModel();
    
    //Find data index of the column
	var dataIndex = cm.getDataIndex(colIndex);
	
	//Get filters object of grid
    var filtersObj = grid.filters;
    
    //return if no filters obj
    if(!filtersObj) return;
    
    //Get filter on current column
	var filter = filtersObj.filters.get(dataIndex);
	if(!filter) return;
	
	//If no valid target and not selectable filter
	if(t.className != 'ux-grid-filter'){	            		
		if(!filter.selectable){
			return;
		}
    }
	
	//Get the data in cell
	var data = grid.getView().getCell(rowIndex,colIndex).innerHTML;
	
	var valueNode = Ext.DomQuery.selectNode(".ux-grid-filter-hidden-value",grid.getView().getCell(rowIndex,colIndex)); 
	
	var text = new Ext.Imm.SelectedText();
	text = text.toString();
    
    //Return if not value node and not text
    if(!text && !valueNode) return;
    
	var finalValue = text?text:valueNode.innerHTML; 
	//Re-format data
	if(filter.type == "numeric"){
		finalValue = {
			'eq':finalValue
		}
	}
	if(filter.type == "list"){
		finalValue =[finalValue];
	}
	if(filter.type == "text"){
		filter.makeChanges("and",[finalValue]);
		filter.execute();
		filter.setActive(true);
		return;
	}
	//Start filter	
	filter.setValue(finalValue);	
	filter.setActive(true);
	
}
Ext.ns("Ext.Imm");
Ext.Imm.SelectedText = function(){	
	var txt = null;
	if(window.getSelection){
		txt = window.getSelection();
	}else if(document.getSelection){
		txt = document.getSelection();
	}else if(document.selection){
		txt = document.selection.createRange().text;
	}else{
		return;
	}
	return txt;	
}

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid-filtering/ux/grid/DrillFilter.js  ]*/

/**
* Ext.ux.RePositionFilters
* @author: Prakash Paudel
*
* Re-position the filters from the hmenu to the just below column header..
*/
Ext.ns("Ext.ux");
Ext.ux.RePositionFilters = function(grid){	
	grid.addEvents('spresize');
	//Find column model
	var cm = grid.getColumnModel();	
	this.grid = grid;
	//Apply Template
	this.applyTemplate = function() {		
	    var colTpl = "";
	    this.eachColumn(function(col) {
	      var filterDivId = this.getFilterDivId(col.id);	      
	      var style = col.hidden ? " style='display:none'" : "";
	      colTpl += '<td' + style + '><div class="x-small-editor" id="' + filterDivId + '"></div></td>';
	    });
	    
	    var headerTpl = new Ext.Template(
	      '<table border="0" cellspacing="0" cellpadding="0" style="{tstyle}">',
	      '<thead><tr class="x-grid3-hd-row">{cells}</tr></thead>',
	      '<tbody><tr class="filter-row-header">',
	      colTpl,
	      '</tr></tbody>',
	      "</table>"
	    );	    
	   return headerTpl;
	}
	this.getFilterFieldDom = function(field) {
	    return field.wrap ? field.wrap.dom : field.el.dom;
	}
	this.eachColumn = function(func) {
		Ext.each(this.grid.getColumnModel().config, func, this);
	}
	// Returns HTML ID of element containing filter div
    this.getFilterDivId = function(columnId) {
		return this.grid.id + '-filter-' + columnId;
    }
    this.resizeSp = function(column, newColumnWidth) {
		//var col = cm.getColumnById(cm.getColumnId(colIndex));
    	if(column.sp){
    		var minW = 50;
    		if(newColumnWidth < minW){
    			cm.setColumnWidth(cm.getIndexById(column.dataIndex),minW)
    			newColumnWidth = minW;
    		}
    		column.sp.setWidth(newColumnWidth);
    	}
	}
    this.resizeAllSp = function() {
	    var cm = this.grid.getColumnModel();	    
	    this.eachColumn(function(col, i) {
	      this.resizeSp(col, cm.getColumnWidth(i));
	    });
	}
    this.renderFields = function() {    	
        this.eachColumn(function(col) {
          var filterDiv = Ext.get(this.getFilterDivId(col.id));          
          var filterMenu = this.getFilterField(col);
          this.grid.filters.menu = filterMenu;
          if(filterMenu){
              if (filterMenu.rendered) {
                filterDiv.appendChild(this.getFilterFieldDom(filterMenu));
              }
              else {               
                filterMenu.render(filterDiv);
              }
          }          
        });
    }
    // returns filter field of a column
    this.getFilterField = function(column) {
    	if(column.sp) return column.sp;
    	if(!column.dataIndex) return;
    	//return new Ext.form.TextField();
    	var filter = this.getFilterForColumn(column);
    	if(!filter) return column.sp = new Ext.Button({width:column.width,disabled:true});    	
    	var filterMenu = this.getFilterMenuForColumn(column); 
    	/*if(column.width < 50){
    		cm.setColumnWidth(cm.findColumnIndex(column.dataIndex),200);
    	}*/
    	var sp = new Ext.SplitButton({
			text: this.grid.filters.menuFilterText,
			menu: filterMenu,
			width:column.width,
			allowDepress:true,
			layout:'fit',
			arrowTooltip:'Click for filter options',
			enableToggle:true,
			tooltip:'Click to enable/disable this filter',
			disabled:filter?false:true
		});    	
    	
    	sp.on('toggle',function(btn,pressed){			
			if(filter.getValue() && filter.isActivatable()){
				filter.setActive(pressed);
			}else{
				btn.toggle(false);
			}		
    	},this);
    	sp.on("render",function(){
    		if(filter.active){
        		sp.toggle(true);
    		}
    	},this);
    	filter.on("activate",function(){
    		sp.toggle(true);
    	},this);
    	filter.on("deactivate",function(){
    		sp.toggle(false);
    	},this);
    	column.sp = sp;
    	return sp;
    }
    this.getFilterMenuForColumn = function(column){   
    	if(!this.grid.filters.filters) return;
    	var filter = this.grid.filters.filters.get(column.dataIndex);    	
    	if(filter){
    		return filter.menu;
    	}
    	return null;    	
    }
    this.getFilterForColumn = function(column){
    	if(!this.grid.filters.filters) return;
    	var filter = this.grid.filters.filters.get(column.dataIndex);    	
    	return filter;
    }
	var view = this.grid.getView();	
    Ext.applyIf(view, { templates: {} });
    view.templates.header = this.applyTemplate();
    view.refresh(true);	   
	this.renderFields();
	
	cm.on("widthchange", function(cm, colIndex, newWidth){
		this.resizeAllSp();		
	}, this);
	cm.on("hiddenchange",function(cm, colIndex, hidden) {
	    var filterDiv = Ext.get(this.getFilterDivId(cm.getColumnId(colIndex)));
	    if (filterDiv) {
	      filterDiv.parent().dom.style.display = hidden ? 'none' : '';
	    }
	    this.resizeAllSp();
	},this);
	grid.on("columnresize",function(colIndex, newSize) {	    
	    this.resizeAllSp();
	},this);
	grid.on("resize",function() {	    
	    this.resizeAllSp();	   
	},this);	
	
	cm.on("columnmoved", function(cm,oldIndex,newIndex){		
		var view = this.grid.getView();
		Ext.applyIf(view, { templates: {} });
	    view.templates.header = this.applyTemplate();
	    view.refresh(true);		   
		this.renderFields();
	}, this);		
}

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid-filtering/ux/grid/RePositionFilters.js  ]*/

/**
* Ext.ux.SaveSearchState
* @author: Prakash Paudel
*
* Get the current search state of the grid filter
*/
Ext.ns("Ext.ux");
Ext.ux.SaveSearchState = function(grid){	
    var filtersObj = grid.filters;    
    if(!filtersObj) return;
    var p = new Ext.state.Provider();
    this.saveUrl = afApp.urlPrefix+'/appFlower/saveFilter';
    this.listUrl = afApp.urlPrefix+'/appFlower/listFilter';
    this.removeUrl = afApp.urlPrefix+'/appFlower/removeFilter';
    this.save = function(){
    	var state = [];   
    	var list = this.list;
		filtersObj.filters.each(function(filter){
			if(filter.active){
				var obj = {					
					dataIndex: filter.dataIndex,
					value: p.encodeValue(filter.getValue())
				}
				state.push(obj);  
			}
		});
		if(state.length < 1){
			Ext.Msg.alert("Error","Filter criteria is empty.");
			return;
		}
		Ext.Msg.prompt("Name", "Please input filter name:", function(btn, text){
		    if (btn == "ok"){
		    	if(text == ""){
		    		Ext.Msg.alert("Error","Filter name is required.");
					return;
		    	}
		    	var json = Ext.util.JSON.encode(state);
		    	Ext.Ajax.request({
		    		method:"POST",
		    		url:this.saveUrl,
		    		success:function(r){
		    			var json = Ext.util.JSON.decode(r.responseText);
		    			Ext.Msg.show({
	    				   title:json.success?"Success":"Failed",
	    				   msg: json.message,
	    				   buttons: Ext.Msg.OK,    				   
	    				   icon: json.success?Ext.MessageBox.INFO:Ext.MessageBox.ERROR
	    				});
		    			if(list && list.getStore()){
		    				list.getStore().reload();
		    			}
		    		},
		    		failure:function(r){
		    			
		    		},
		    		params:{
		    			path:grid.path,
		    			name: text,
		    			state:json,
		    			title:grid.name?grid.name:grid.path
		    		}
		    	})
		    }
		},this)
		
		
    }
    this.viewSavedList = function(){
    	var path = grid.name?grid.name:grid.path;
    	var store = new Ext.data.JsonStore({
            fields: ['id','name','filter'],
            url:this.listUrl,
            root:'rows',
            autoLoad:true,
            baseParams:{
    			path: path
    		}
        });    	
    	var list = new Ext.grid.GridPanel({
    		store:store,
    		columns: [
    		    new Ext.grid.RowNumberer(),
	            {id:'name',header: 'Name', dataIndex: 'name',menuDisabled:true},
	            {id:'action',header:'Action',dataIndex:null, width:50,menuDisabled:true,
	            	renderer:function(){
	            		//return '<a href="#"><img class="restore-saved-filter-button" src="/images/famfamfam/connect.png" qtip="Apply this filter to grid"/></a>&nbsp;&nbsp;<a href="#"><img class="remove-saved-filter-button" src="/images/famfamfam/cross.png" qtip="Remove this filter"/></a>'
	            		return '<a href="#"><img class="remove-saved-filter-button" src="/images/famfamfam/cross.png" qtip="Remove this filter"/></a>'
	            	}
	            }
	        ],
	        tbar:[{	        	
	            text:'Save current filter',
	            icon:'/images/famfamfam/disk.png',
	            handler: function(){		        	
					var filters = grid.filters;
					if(!filters) return;							
					var saveFilter = Ext.ux.SaveSearchState(grid);
					saveFilter.save();
	        	}
	        },'-',{
	        	text:'Clear current filter',
	        	icon:'/images/famfamfam/drink_empty.png',
	        	handler: function(){
		        	var filters = grid.filters;
					if(!filters) return;
					filters.clearFilters();
	        	}
	        }],
	        autoExpandColumn: 'name',
	        loadMask:true
    	})
    	this.list = list;
    	list.on("cellclick",function(grid,rowIndex,columnIndex,e){
    		var target = e.getTarget();
    		if(target.className == "ux-grid-filter-apply"){
    			var record = grid.getStore().getAt(rowIndex);                
                var filterJson = record.get("filter");
                var keyword = record.get("name");
                this.restore(filterJson,keyword);                
    		}
    		if(target.className == "restore-saved-filter-button"){
    			var record = grid.getStore().getAt(rowIndex);                
                var filterJson = record.get("filter");
                var keyword = record.get("name");
                this.restore(filterJson,keyword);
    		}
    		if(target.className == "remove-saved-filter-button"){
    			var record = grid.getStore().getAt(rowIndex);                
                var id = record.get("id");
                Ext.Ajax.request({
		    		method:"POST",
		    		url:this.removeUrl,
		    		success:function(r){
		    			var json = Ext.util.JSON.decode(r.responseText);
		    			if(json.success){
		    				var row = grid.getView().getRow(rowIndex);                
		                    Ext.get(row).fadeOut({
		                        endOpacity: 0,
		                        easing: 'easeOut',
		                        duration: .5,
		                        remove: true,
		                        useDisplay: false,
		                        callback: function(){
			                    	grid.getStore().remove(record);
				                    grid.getView().refresh();
		                    	}
		                    });
		                    
		    			}
		    		},
		    		failure:function(r){
		    			
		    		},
		    		params:{
		    			id: id
		    		}
		    	})		    	
    		}
    	},this)
    	var win = new Ext.Window({    				
    		width:500,
    		height:300,
    		layout:'fit',
    		title:'Saved filters for '+path,
    		items: list,
    		closeAction:'close',
    		maximizable:true,
    		collapsible:true
    	})
    	
    	win.show();    
    }
    this.restore = function(json,keyword){
    	if(!grid.originalTitle){
    		grid.originalTitle = grid.title;
    	}
    	grid.setTitle(grid.originalTitle+": <font color=red>(Filtered by keyword: '"+keyword.replace(/<\S[^><]*>/g, "")+"')</font>");    	
    	filtersObj.clearFilters();
    	filtersObj.filters.each(function(filter){	
    		var json_array = Ext.util.JSON.decode(json);    		
    		for(var i=0;i<json_array.length;i++){
    			if(json_array[i].dataIndex == filter.dataIndex){
    				var val = json_array[i].value;
    				val = p.decodeValue(val);
    				filter.setActive(false);
    				filter.setValue(val);
    				filter.setActive(true);
    			}
    		}
						
		});    	   	
    }   	
    return this;
}


/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid-filtering/ux/grid/SaveSearchState.js  ]*/

/**
* Ext.ux.FilterInfo
* @author: Prakash Paudel
*
* Information about the filtered criteria
*/
Ext.ns("Ext.ux");
Ext.ux.FilterInfo = function(grid,mode){

	var filterInfo = this;	
	//Get column model
	var cm = grid.getColumnModel();
	//Get filters object of grid
    var filtersObj = grid.filters;
    
    //return if no filters obj
    if(!filtersObj) return;
    
	//Get the grid element
	var gridEl = grid.getGridEl();
	
	//Get the clear div between info and grid
	var clearDivExists = Ext.DomQuery.selectNode(".ux-grid-filter-info-clear",gridEl.dom);
	
	//Use clear div if it already exists or create new
	if(mode == 'panel')var clearDiv = clearDivExists?clearDivExists:Ext.DomHelper.insertFirst(gridEl,{tag:'span',html:'&nbsp;',cls:'ux-grid-filter-info-clear'});
	
	//Get info div if already exists
	var infoDivExists = Ext.DomQuery.selectNode(".ux-grid-filter-info",gridEl.dom);
	
	//Use info div if it already exists or create new
	if(mode == 'panel') var infoDiv = infoDivExists?infoDivExists:Ext.DomHelper.insertFirst(gridEl,{tag:'div',html:'',cls:'ux-grid-filter-info'});
	//infoDiv.style.width=gridEl.getWidth()+"px";
	//Create a template for info-box
	var tpl = Ext.DomHelper.createTemplate({tag: 'div', cls: 'ux-grid-filter-info-box', html: '{html}&nbsp;&nbsp;<a title="Remove this filter" id="{id}" href="#" onclick="Ext.ux.FilterInfo.remove(this)"></a>'});
	
	if(infoDiv) infoDiv.innerHTML = '';
	var actions = [];
	var plainText = '';
	var originalTitle = null;
	filtersObj.filters.each(function(filter){
		
    	var dataIndex = filter.dataIndex;
    	var header = cm.getColumnHeader(cm.findColumnIndex(dataIndex));    	
    	if(filter.active){
    		var val = '';
			if(filter.getDisplayValue){
				val = filter.getDisplayValue();
			}else{
				val = filter.getValue();				
			}			
			if(val){
				plainText += "'"+val+"', ";
				var id = Ext.id(null,"filter-");
				
				actions.push(new Ext.Button(new Ext.Action({				    
				    handler: function(){
						filter.setActive(false);
				    },				    
				    itemId: id+"-action",
				    hidden:true
				})));
				if(mode == 'panel'){
					tpl.append(infoDiv,{
						html:'<b>'+header+':</b> '+val,
						id:id
					});	
				}				
			}
    	}
    });	
	if(mode == 'panel')
	var panel = new Ext.Panel({
		renderTo:infoDiv,
		items: actions,
		id:'filter-info-action-panel'
	})
	if(mode == "title"){		
		if(plainText)
		grid.setTitle(grid.originalTitle+" <font color='red'>Filtered by keyword: "+plainText+"</font>");
		else
		grid.setTitle(grid.originalTitle);
	}

	
}
Ext.ns("Ext.ux.FilterInfo");
Ext.ux.FilterInfo.remove = function(anchor){
	var panel = Ext.getCmp("filter-info-action-panel");
	var action = panel.getComponent(anchor.id+"-action");
	action.baseAction.execute();

}


/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid-filtering/ux/grid/FilterInfo.js  ]*/

/**
 * Ext.ux.FilterOption
 *
 * @author: Prakash Paudel
 *
 * Add the filter option to each filters, relavent to the type of filter.
*/

Ext.ns("Ext.ux");
Ext.ux.FilterOption = function(filter,name){
    var options = {
        "string":[{
            name: "Match any",
            value: "any"
        },{
            name: "Match exact",
            value: "exact"
        },{
            name: "Starts with",
            value: "starts"
        },{
            name: "Ends with",
            value: "ends"
        },{
            name: "Not contains",
            value: "nc"
        }],
        "list":[{
            name: "Containing any of selected",
            value: "or"
        },{
            name: "Containing all of selected",
            value: "and"
        }]
    };
    //filter.menu.add(new Ext.menu.Separator());
    var ch = true;
    for(var i in options){
        if(i == name){            
            Ext.each(options[i],function(item){
                var chItem = new Ext.menu.CheckItem({
                    text: item.name,
                    hideOnClick: false,
                    group: "filter-options",
                    listeners: {
                        checkChange: function(checkItem,checked){
                            filter.setFilterOptions(item.value);
                            filter.fireUpdate();
                        }/*,
                        render: function(ci){
                            if(ch){
                                //ci.setChecked(true);
                                ch = false;
                            }
                        }*/
                    }
                });
                
                filter.menu.add(chItem);
                
            });
        }
    }    
}

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid-filtering/ux/grid/FilterOption.js  ]*/

Ext.namespace("Ext.ux.grid.filter");
Ext.ux.grid.filter.Filter = function(config){
	Ext.apply(this, config);
		
	this.events = {
		/**
		 * @event activate
		 * Fires when a inactive filter becomes active
		 * @param {Ext.ux.grid.filter.Filter} this
		 */
		'activate': true,
		/**
		 * @event deactivate
		 * Fires when a active filter becomes inactive
		 * @param {Ext.ux.grid.filter.Filter} this
		 */
		'deactivate': true,
		/**
		 * @event update
		 * Fires when a filter configuration has changed
		 * @param {Ext.ux.grid.filter.Filter} this
		 */
		'update': true,
		/**
		 * @event serialize
		 * Fires after the serialization process. Use this to apply additional parameters to the serialized data.
		 * @param {Array/Object} data A map or collection of maps representing the current filter configuration.
		 * @param {Ext.ux.grid.filter.Filter} filter The filter being serialized.
		 **/
		'serialize': true
	};
	Ext.ux.grid.filter.Filter.superclass.constructor.call(this);
	
	this.menu = new Ext.menu.Menu();
	this.init();
	
	if(config && config.value){
		this.setValue(config.value);
		this.setActive(config.active !== false, true);
		delete config.value;
	}
	this.hideTask = new Ext.util.DelayedTask(this.hideMenu, this);
};
Ext.extend(Ext.ux.grid.filter.Filter, Ext.util.Observable, {
	/**
	 * @cfg {Boolean} active
	 * Indicates the default status of the filter (defaults to false).
	 */
    /**
     * True if this filter is active. Read-only.
     * @type Boolean
     * @property
     */
	active: false,
	/**
	 * @cfg {String} dataIndex 
	 * The {@link Ext.data.Store} data index of the field this filter represents. The dataIndex does not actually
	 * have to exist in the store.
	 */
	dataIndex: null,
	/**
	 * The filter configuration menu that will be installed into the filter submenu of a column menu.
	 * @type Ext.menu.Menu
	 * @property
	 */
	menu: null,
	
	/**
	 * Initialize the filter options
	 * /
	filterOptions: null,
	
	/**
	 * Initialize the filter and install required menu items.
	 */
	init: Ext.emptyFn,
	
	hideMenu: function(){
		this.menu.hide(true);
	},
	
	fireUpdate: function(){
		this.value = this.item.getValue();
		if(this.active)
			this.fireEvent("update", this);
			
		this.setActive(this.value.length > 0);
	},
	
	/**
	 * Returns true if the filter has enough configuration information to be activated.
	 * 
	 * @return {Boolean}
	 */
	isActivatable: function(){
		return true;
	},
	
	/**
	 * Sets the status of the filter and fires that appropriate events.
	 * 
	 * @param {Boolean} active        The new filter state.
	 * @param {Boolean} suppressEvent True to prevent events from being fired.
	 */
	setActive: function(active, suppressEvent){
		if(this.active != active){
			this.active = active;
			if(suppressEvent !== true)
				this.fireEvent(active ? 'activate' : 'deactivate', this);
		}
		this.hideTask.delay(2000);
	},
	
	/**
	 * Get the value of the filter
	 * 
	 * @return {Object} The 'serialized' form of this filter
	 */
	getValue: Ext.emptyFn,
	
	/**
	 * Set the value of the filter.
	 * 
	 * @param {Object} data The value of the filter
	 */	
	setValue: Ext.emptyFn,
	
	/**
	 * Serialize the filter data for transmission to the server.
	 * 
	 * @return {Object/Array} An object or collection of objects containing key value pairs representing
	 * 	the current configuration of the filter.
	 */
	serialize: Ext.emptyFn,
	
	/**
	 * Validates the provided Ext.data.Record against the filters configuration.
	 * 
	 * @param {Ext.data.Record} record The record to validate
	 * 
	 * @return {Boolean} True if the record is valid with in the bounds of the filter, false otherwise.
	 */
	 validateRecord: function(){return true;},
	 
	 /**
	  * Set the options to filters
	  *
	  * added by Prakash Paudel
	  *
	  * The filter options are set by Ext.ux.FilterOption
	  */
	 setFilterOptions: function(options) {
		this.filterOptions = options;
	 },
	 
	 /**
	  * Globally get the filter options for each filter
	  *
	  * added by Prakash Paudel
	  */
	 getFilterOptions: function(){
		return this.filterOptions;
	 }
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid-filtering/ux/grid/filter/Filter.js  ]*/

Ext.ux.grid.filter.BooleanFilter = Ext.extend(Ext.ux.grid.filter.Filter, {
	defaultValue: false,
	yesText: "Yes",
	noText:  "No",

	init: function(){
    var gId = Ext.id();
		this.options = [
			new Ext.menu.CheckItem({text: this.yesText, group: gId, checked: this.defaultValue === true}),
			new Ext.menu.CheckItem({text: this.noText, group: gId, checked: this.defaultValue === false})];
		
		this.menu.add(this.options[0], this.options[1]);
		
		for(var i=0; i<this.options.length; i++){
			this.options[i].on('click', this.fireUpdate, this);
			this.options[i].on('checkchange', this.fireUpdate, this);
		}
	},
	
	isActivatable: function(){
		return true;
	},
	
	fireUpdate: function(){		
		this.fireEvent("update", this);			
		this.setActive(true);
	},
	
	setValue: function(value){
		this.options[value ? 0 : 1].setChecked(true);
	},
	
	getValue: function(){
		return this.options[0].checked;
	},
	
	serialize: function(){
		var args = {type: 'boolean', value: this.getValue()};
		this.fireEvent('serialize', args, this);
		return args;
	},
	
	validateRecord: function(record){
		return record.get(this.dataIndex) == this.getValue();
	}
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid-filtering/ux/grid/filter/BooleanFilter.js  ]*/

Ext.ux.grid.filter.ComboFilter = Ext.extend(Ext.ux.grid.filter.Filter, {
	updateBuffer: 500,
	icon: 'ux-gridfilter-text-icon',
	
	init: function(){
		//var store = [['1',"one"],['2',"two"]]
		var value = this.value = new Ext.ux.menu.ComboMenu({lovcombo:this.lovcombo,iconCls: this.icon,store:this.options});
		
        
		value.editor.on('select', function(){
			this.setActive(true);
			this.fireUpdate();			
		},this);
		
		this.menu.add(value);
		
		this.updateTask = new Ext.util.DelayedTask(this.fireUpdate, this);
	},
	
	onKeyUp: function(event){
		if(event.getKey() == event.ENTER){
			this.menu.hide(true);
			return;
		}			
		this.updateTask.delay(this.updateBuffer);
	},
	
	isActivatable: function(){
		return this.value.getValue()?true:false;
	},
	
	fireUpdate: function(){		
		if(this.active)
			this.fireEvent("update", this);
		
		this.setActive(this.isActivatable());
	},
	
	setValue: function(value){		
		this.value.setValue(value);
		this.fireEvent("update", this);
	},
	
	getValue: function(){
		return this.value.getValue()?this.value.getValue().toString():null;
	},
	
	serialize: function(){
		var args = {type: 'combo', value: this.getValue()};
		this.fireEvent('serialize', args, this);
		return args;
	},
	
	validateRecord: function(record){
		var val = record.get(this.dataIndex);
		
		if(typeof val != "combo")
			return this.getValue().length == 0;
			
		return val.toLowerCase().indexOf(this.getValue().toLowerCase()) > -1;
	},
	// Get the display value
	getDisplayValue: function(){
		return this.value.getDisplayValue();
	}
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid-filtering/ux/grid/filter/ComboFilter.js  ]*/

Ext.ux.grid.filter.DateFilter = Ext.extend(Ext.ux.grid.filter.Filter, {
	dateFormat: 'Y-m-d',
	pickerOpts: {},
	
	
    beforeText: 'To',
    afterText:  'From',
    onText:     'On',
	
	init: function(){
		var opts = Ext.apply(this.pickerOpts, {
			minDate: this.minDate, 
			maxDate: this.maxDate, 
			format:  this.dateFormat
		});
		
		var dates = this.dates = {
			'before': new Ext.menu.CheckItem({text: this.beforeText, menu: new Ext.menu.DateMenu(opts)}),
			'after':  new Ext.menu.CheckItem({text: this.afterText, menu: new Ext.menu.DateMenu(opts)}),
			'on':     new Ext.menu.CheckItem({text: this.onText, menu: new Ext.menu.DateMenu(opts)})};
				
		this.menu.add( dates.after, dates.before , "-", dates.on);
		
		for(var key in dates){
			var date = dates[key];
			date.menu.on('select', function(date, menuItem, value, picker){
				date.setChecked(true);
				
				if(date == dates.on){
					dates.before.setChecked(false, true);
					dates.after.setChecked(false, true);
				} else {
					dates.on.setChecked(false, true);
					
					if(date == dates.after && dates.before.menu.picker.value < value)
            dates.before.setChecked(false, true);
          else if (date == dates.before && dates.after.menu.picker.value > value)
            dates.after.setChecked(false, true);
				}
				
				this.fireEvent("update", this);
			}.createDelegate(this, [date], 0));
			
			date.on('checkchange', function(){
				this.setActive(this.isActivatable());
			}, this);
		};
	},
	
	getFieldValue: function(field){
		return this.dates[field].menu.picker.getValue();
	},
	
	getPicker: function(field){
		return this.dates[field].menu.picker;
	},
	
	isActivatable: function(){
		return this.dates.on.checked || this.dates.after.checked || this.dates.before.checked;
	},
	
	setValue: function(value){
		for(var key in this.dates)
			if(value[key]){
				this.dates[key].menu.picker.setValue(value[key]);
				this.dates[key].setChecked(true);
			} else {
				this.dates[key].setChecked(false);
			}
	},
	
	getValue: function(){
		var result = {};
		for(var key in this.dates)
			if(this.dates[key].checked)
				result[key] = this.dates[key].menu.picker.getValue();
				
		return result;
	},
	
	serialize: function(){
		var args = [];
		if(this.dates.before.checked)
			args = [{type: 'date', comparison: 'lt', value: this.getFieldValue('before').format(this.dateFormat)}];
		if(this.dates.after.checked)
			args.push({type: 'date', comparison: 'gt', value: this.getFieldValue('after').format(this.dateFormat)});
		if(this.dates.on.checked)
			args = {type: 'date', comparison: 'eq', value: this.getFieldValue('on').format(this.dateFormat)};

    this.fireEvent('serialize', args, this);
		return args;
	},
	
	validateRecord: function(record){
		var val = record.get(this.dataIndex).clearTime(true).getTime();
		
		if(this.dates.on.checked && val != this.getFieldValue('on').clearTime(true).getTime())
			return false;
		
		if(this.dates.before.checked && val >= this.getFieldValue('before').clearTime(true).getTime())
			return false;
		
		if(this.dates.after.checked && val <= this.getFieldValue('after').clearTime(true).getTime())
			return false;
			
		return true;
	},
	getDisplayValue: function(){		
		if(this.getValue().on) return this.getFormatedDate(this.getValue().on);		
		if(this.getValue().after && this.getValue().before) return "From: "+this.getFormatedDate(this.getValue().after)+", To: "+this.getFormatedDate(this.getValue().before);
		if(this.getValue().after) return "From: "+this.getFormatedDate(this.getValue().after)
		if(this.getValue().before) return "To: "+this.getFormatedDate(this.getValue().before)
		
	},
	getFormatedDate: function(d){
		return d.getFullYear()+"-"+(d.getMonth()+1)+'-'+d.getDate();
	}
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid-filtering/ux/grid/filter/DateFilter.js  ]*/

Ext.ux.grid.filter.ListFilter = Ext.extend(Ext.ux.grid.filter.Filter, {
	labelField:  'text',
	loadingText: 'Loading...',
	loadOnShow:  true,
	value:       [],
	loaded:      false,
	phpMode:     false,
	
	init: function(){
		this.menu.add('<span class="loading-indicator">' + this.loadingText + '</span>');
		
		if(this.store){
			if(this.loadOnShow)
				this.menu.on('show', this.onMenuLoad, this);
			
		} else if(this.options){			
			var options = [];
			for(var i=0, len=this.options.length; i<len; i++){
				var value = this.options[i];
				switch(Ext.type(value)){
					case 'array':  options.push(value); break;
					case 'object': options.push([value.id, value[this.labelField]]); break;
					case 'string': options.push([value, value]); break;
				}
			}
			
			this.store = new Ext.data.Store({
				reader: new Ext.data.ArrayReader({id: 0}, ['id', this.labelField])
			});
			this.options = options;			
			this.menu.on('show', this.onMenuLoad, this);
			
		}
		this.store.on('load', this.onLoad, this);
		
		this.bindShowAdapter();
	},
	onRender:function(){
		this.onMenuLoad();
	},
	
	/**
	 * Lists will initially show a 'loading' item while the data is retrieved from the store. In some cases the
	 * loaded data will result in a list that goes off the screen to the right (as placement calculations were done
	 * with the loading item). This adaptor will allow show to be called with no arguments to show with the previous
	 * arguments and thusly recalculate the width and potentially hang the menu from the left.
	 * 
	 */
	bindShowAdapter: function(){
		var oShow    = this.menu.show;
		var lastArgs = null;
		this.menu.show = function(){
			if(arguments.length == 0){
				oShow.apply(this, lastArgs);
			} else {
				lastArgs = arguments;
				oShow.apply(this, arguments);
			}
		};
	},
	
	onMenuLoad: function(){
		if(!this.loaded){
			if(this.options)
				this.store.loadData(this.options);
			else{				
				this.store.load();
			}
		}
	},
	
	onLoad: function(store, records){		
		var visible = this.menu.isVisible();
		this.menu.hide(false);		
		this.menu.removeAll();		
		var gid = this.single ? Ext.id() : null;
		for(var i=0, len=records.length; i<len; i++){			
			var item = new Ext.menu.CheckItem({
				text:    records[i].get(this.labelField), 
				group:   gid, 
				checked: this.value.indexOf(records[i].id.toString()) > -1,
				hideOnClick: false});
			
			item.itemId = records[i].id;
			item.on('checkchange', this.checkChange, this);
						
			this.menu.add(item);
		}
		
		this.setActive(this.isActivatable());
		this.loaded = true;
		
		if(visible)
			this.menu.show(); //Adaptor will re-invoke with previous arguments
	},
	
	checkChange: function(item, checked){
		var value = [];
		this.menu.items.each(function(item){
			if(item.checked)
				value.push(item.itemId);
		},this);
		this.value = value;
		
		this.setActive(this.isActivatable());
		this.fireEvent("update", this);
	},
	
	isActivatable: function(){
		return this.value.length > 0;
	},
	
	setValue: function(value){		
		var value = this.value = [].concat(value);
		
		if(this.loaded){
			this.menu.items.each(function(item){
				item.setChecked(false, true);
				for(var i=0, len=value.length; i<len; i++){
					
					if(item.itemId == value[i]) 
						item.setChecked(true, true);
				}
			}, this);
		}
			
		this.fireEvent("update", this);
	},
	
	getValue: function(){		
		return this.value.toString();
	},
	
	serialize: function(){
    var args = {type: 'list', value: this.phpMode ? this.value.join(',') : this.value};
    this.fireEvent('serialize', args, this);
		return args;
	},
	
	validateRecord: function(record){
		return this.getValue().indexOf(record.get(this.dataIndex)) > -1;
	},
    //Get display value
    getDisplayValue: function(){
    	var values = new Array();
    	var displayValues = [];    	
    	v = this.getValue();    	
    	var options = this.options;    	
    	if(v.toString().match(/,/)){
    		values = v.toString().split(",");
    	}else{
    		values[0] = v;
    	}
    	for(var i=0;i<values.length;i++){
    		for(var key in options){
    			if(options[key][0] == values[i]){
    				displayValues.push(options[key][1]);
    			}
    		}        	
    	}	
    	return displayValues.join(",");
    }
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid-filtering/ux/grid/filter/ListFilter.js  ]*/

Ext.ux.grid.filter.NumericFilter = Ext.extend(Ext.ux.grid.filter.Filter, {
	init: function(){
		this.menu = new Ext.ux.menu.RangeMenu();
		
		this.menu.on("update", this.fireUpdate, this);
	},
	
	fireUpdate: function(){
		this.setActive(this.isActivatable());
		this.fireEvent("update", this);
	},
	
	isActivatable: function(){
		var value = this.menu.getValue();
		return value.eq !== undefined || value.gt !== undefined || value.lt !== undefined || value.ne !== undefined;
	},
	
	setValue: function(value){		
		this.menu.setValue(value);
	},
	
	getValue: function(){
		return this.menu.getValue();
	},
	
	serialize: function(){
		var args = [];
		var values = this.menu.getValue();
		for(var key in values)
			args.push({type: 'numeric', comparison: key, value: values[key]});

		this.fireEvent('serialize', args, this);
		return args;
	},
	
	validateRecord: function(record){
		var val    = record.get(this.dataIndex),
			values = this.menu.getValue();
			
		if(values.eq != undefined && val != values.eq)
			return false;
		
		if(values.lt != undefined && val >= values.lt)
			return false;
		
		if(values.gt != undefined && val <= values.gt)
			return false;
			
		return true;
	},
	getDisplayValue: function(){		
		if(this.getValue().eq) return this.getValue().eq;		
		if(this.getValue().ne) return "!= "+this.getValue().ne;
		if(this.getValue().gt && this.getValue().lt) return this.getValue().gt+" &lt; x &lt; "+this.getValue().lt;
		if(this.getValue().gt) return "&gt;"+this.getValue().gt;
		if(this.getValue().lt) return "&lt;"+this.getValue().lt;
		
	}
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid-filtering/ux/grid/filter/NumericFilter.js  ]*/

Ext.ux.grid.filter.StringFilter = Ext.extend(Ext.ux.grid.filter.Filter, {
	updateBuffer: 0,
	icon: 'ux-gridfilter-text-icon',
	
	init: function(){
		var value = this.value = new Ext.ux.menu.EditableItem({iconCls: this.icon});
		value.on('keyup', this.onKeyUp, this);
		this.menu.add(value);
		this.options = new Ext.ux.FilterOption(this,"string");
		this.updateTask = new Ext.util.DelayedTask(this.fireUpdate, this);
	},
	
	onKeyUp: function(event){
		
		if(event.getKey() == event.ENTER){			
			//this.menu.hide(true);
			//this.updateTask.delay(this.updateBuffer);
			this.fireUpdate();
			//return;
		}	
			
		//this.updateTask.delay(this.updateBuffer);
	},
	
	isActivatable: function(){
		return this.value.getValue().length > 0;
	},
	
	fireUpdate: function(){		
		if(this.active)
			this.fireEvent("update", this);
			
		this.setActive(this.isActivatable());
	},
	
	setValue: function(value){
		this.value.setValue(value);
		this.fireEvent("update", this);
	},
	
	getValue: function(){
		return this.value.getValue();
	},
	
	serialize: function(){
		var args = {type: 'string', value: this.getValue(), options: this.getFilterOptions()};
		this.fireEvent('serialize', args, this);
		return args;
	},
	
	validateRecord: function(record){
		var val = record.get(this.dataIndex);
		
		if(typeof val != "string")
			return this.getValue().length == 0;
		/**
		 * perform multiple strings to be filtered.
		 * separated by a semicolon (;)
		 */	
		var arr = [];
		var key = this.getValue().toLowerCase();
		arr = key.split(";");
		arr.push(key);
		var match = false;
		for(var i=0;i<arr.length;i++){
			match = (val.toLowerCase().indexOf(arr[i]) > -1);
			if(match) break;
		}
		return match;		
		//return val.toLowerCase().indexOf(this.getValue().toLowerCase()) > -1;
	}
});


/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid-filtering/ux/grid/filter/StringFilter.js  ]*/

/**
 * Text filter: drill down on a previously applied filter in long texts
 *
 * @author: Prakash Paudel
 * 
 */
Ext.ux.grid.filter.TextFilter = Ext.extend(Ext.ux.grid.filter.StringFilter, {
    texts:{
        addnew:"Add New",            
        and:"Containing all in result",
        or: "Containing any in result",
        not: "Not containing in result",       
        restart: "Clear this filter and restart",
	removeLast: "Remove last applied filter"
    },
    filterQueue:[],
    init: function(){
        var value = this.value = new Ext.ux.menu.EditableItem({iconCls: this.icon,hidden:true});
            value.on('keyup', this.onKeyUp, this);
            this.menu.add(value);		
            this.updateTask = new Ext.util.DelayedTask(this.fireUpdate, this);
            this.reconfigureMenu();
            this.reconfigureInputFields();
    },    
    reconfigureInputFields: function(){
        this.menu.items.each(function(item){
            if(!item.hidden && item.editor){
                this.menu.remove(item)
            }
        },this);
        this.addInputField();            
    },
    addInputField: function(){
        var newField = new Ext.ux.menu.EditableItem({iconCls: this.icon});
        this.menu.insert(0,newField);
	newField.editor.focus();
    },
    getInputValues: function(){
        var values = [];
        this.menu.items.each(function(item){
            if(!item.hidden && item.editor){
		if(item.getValue()) values.push(item.getValue());
            }
        },this);
        return values;
    },
    reconfigureMenu: function(){
        var addNew = new Ext.menu.Item({
            text: this.texts.addnew,
            iconCls:"icon-plus",
            scope:this,
            hideOnClick:false,
            handler: function(){
                this.addInputField();
            }
        });
        var andButton = new Ext.menu.Item({
            text: this.texts.and,
            scope:this,
	    icon:"/images/famfamfam/bullet_green.png",
            handler: function(){
                this.addToFilterQueue("and");
            }
        });
        
        var orButton = new Ext.menu.Item({
            text:this.texts.or,
            scope:this,
	    icon:"/images/famfamfam/bullet_yellow.png",
            handler: function(){
                this.addToFilterQueue("or");
            }
        });
        
        var notButton = new Ext.menu.Item({
            text: this.texts.not,
            scope: this,
	    icon:"/images/famfamfam/bullet_red.png",
            handler: function(){
                this.addToFilterQueue("not");
            }
        });
        
        this.resetButton = new Ext.menu.Item({
            text: this.texts.restart,
            scope:this,
	    disabled:true,
	    hideOnClick:false,
	    icon:"/images/famfamfam/arrow_refresh.png",
            handler: function(){
                this.resetFilterQueue();
            }
        });
	this.removeLast = new Ext.menu.Item({
		text: this.texts.removeLast,
		scope:this,
		disabled:true,
		icon:"/images/famfamfam/arrow_undo.png",
		handler: function(){
			this.removeLastQueue();
		}
	});
        //Add the items to menu   
        this.menu.add(addNew);        
        this.menu.add(new Ext.menu.Separator());
        this.menu.add(andButton);
        this.menu.add(orButton);
        this.menu.add(notButton);
        this.menu.add(new Ext.menu.Separator());
        this.menu.add(this.resetButton);
	this.menu.add(this.removeLast);
    },
    makeChanges: function(type,value){
	if(!value) value = this.getInputValues(); 
	this.filterQueue.push({
            keys: value,
            type: type
        });	
	this.resetButton.setDisabled(false);
	this.removeLast.setDisabled(false);
	this.removeLast.setText(this.texts.removeLast+" ("+this.filterQueue.length+")");
    },
    setValue: function(value){
	if(!Ext.isJsonString(value)){
		this.filterQueue = [];
		this.makeChanges("and",[value]);
	}	
	this.value.setValue(value);
	this.fireEvent("update", this);
    },
    addToFilterQueue: function(type){
        this.makeChanges(type);
	this.execute();
    },
    execute: function(){
	this.setValue(Ext.util.JSON.encode(this.filterQueue));            
        this.reconfigureInputFields();
        this.fireUpdate();
	this.removeLast.setText(this.texts.removeLast+" ("+this.filterQueue.length+")");
    },
    resetFilterQueue: function(){
        this.reconfigureInputFields();
        this.filterQueue = [];
        this.setValue('');
	this.resetButton.setDisabled(true);
    },
    removeLastQueue: function(){
	if(this.filterQueue.length){
		this.filterQueue.pop();
	}
	if(!this.filterQueue.length){
		this.removeLast.setDisabled(true);
	}
	this.execute();
    }, 
    serialize: function(){
            var args = {type: 'text', value: this.getValue()};
            this.fireEvent('serialize', args, this);
            return args;
    },
    setActive: function(active, suppressEvent){
	if(this.active != active){
		this.active = active;
		if(suppressEvent !== true){
			this.fireEvent(active ? 'activate' : 'deactivate', this);			
		}
		if(!active) this.filterQueue = [];
	}	
	this.hideTask.delay(2000);
    },
    _format: function(text){
	return " <span style='color:#79a3d7'>"+text+"</span> ";
    },
    _enclose: function(arr,join){
	if(arr.length > 1)
	return this._format("[")+arr.join(this._format(join.toUpperCase()))+this._format("]");
	else
	return arr.join(this._format(join.toUpperCase()));
    },
    isJsonString: function(string){
	var rc = null;
	try{
		rc=new RegExp('^("(\\\\.|[^"\\\\\\n\\r])*?"|[,:{}\\[\\]0-9.\\-+Eaeflnr-u \\n\\r\\t])+?$')
	}catch(z){
		rc=/^(true|false|null|\[.*\]|\{.*\}|".*"|\d+|\d+\.\d+)$/
	}
	return rc.test(string);
    },
    getDisplayValue: function(){
        var value = this.getValue();
        var temp = [];
        if(value == null || value == "") return '';
	if(!Ext.isJsonString(value)) return value;
        value = Ext.util.JSON.decode(value);	
        for(i in value){
            v = value[i];
            if(!v.keys && !v.type) continue;
	    if(v.type == "not"){
		var notArray = [];
		Ext.each(v.keys,function(k){
			notArray.push(this._format("NOT")+k);
		},this);
		temp.push(this._enclose(notArray,"and"));
	    }else{
		temp.push(this._enclose(v.keys,v.type));
	    }            
        }
	return this._enclose(temp,"and");
    }
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid-filtering/ux/grid/filter/TextFilter.js  ]*/

Ext.namespace('Ext.ux.plugins'); 
/**
 * Ext.ux.plugins.IndexSearch plugin for Ext.grid.GridPanel
 *
 * @author  Prakash Paudel
 * @date    January 14, 2010
 *
 * @class Ext.ux.plugins.IndexSearch
 * @extends Ext.util.Observable
 */
Ext.ux.plugins.IndexSearch = function(config) {
    Ext.apply(this, config);
    Ext.ux.plugins.IndexSearch.superclass.constructor.call(this);
}; 

Ext.extend(Ext.ux.plugins.IndexSearch, Ext.util.Observable, {	
    init:function(grid) {
		Ext.apply(grid,{			
			onRender:grid.onRender.createSequence(function(ct, position){
				Ext.DomHelper.append(grid.container,{tag:'div',id:'_unique_search_panel'})
				this.initialCount = 0;
				this.facetGridServer = new Ext.grid.GridPanel({										
					listeners:{
						click: function(e){			 
							var t = e.getTarget();							
							if(t.className != 'x-grid3-header'){
					            var r = e.getRelatedTarget();
					            var v = this.view;
					            var ci = v.findCellIndex(t.parentNode);
					            var ri = v.findRowIndex(t);					            
					           
					            //alert(ci); alert(ri);            
					            if(ci === false || ri === false) return ;
					            var cell = this.getView().getCell(ri,ci);			          
					            
					            if(t.className == 'ux-grid-filter'){				            	
					            	var filtersObj = grid.filters;
					            	var filter = filtersObj.filters.get('reporter');
					            	//Get the data in cell
					            	var data = this.getView().getCell(ri,ci).innerHTML;					            	
					            	var valueNode = Ext.DomQuery.selectNode(".ux-grid-filter-hidden-value",this.getView().getCell(ri,ci));					            	
					            	var finalValue = valueNode.innerHTML;
					            	filter.setValue(finalValue);	
					            	filter.setActive(true);
					            	
					            }	
					        }          
				            
						}
					},
					store: new Ext.data.JsonStore({					   
					    url: '/loganalysis/facetJson?by=ip',					   
					    root: 'rows',					    
					    fields: ['facet_name', 'facet_count','facet_id'],
					    autoLoad:false
					}),
				    colModel: new Ext.grid.ColumnModel({
				        defaults: {
				            width: 120,
				            sortable: true
				        },
				        columns: [
				            {id: 'server', header: 'Server', width: 200, sortable: true, sortType:"asIp", dataIndex: 'facet_name'},
				            {header: 'Count', dataIndex: 'facet_count', sortable:true, sortType:"asInt"}
				        ]
				    }),	
				    height:227,
				  
				    autoScroll:true,
				    sm: new Ext.grid.RowSelectionModel({singleSelect:true})
				})
				this.facetGridSev = new Ext.grid.GridPanel({
					listeners:{
					click: function(e){			 
						var t = e.getTarget();							
						if(t.className != 'x-grid3-header'){
				            var r = e.getRelatedTarget();
				            var v = this.view;
				            var ci = v.findCellIndex(t.parentNode);
				            var ri = v.findRowIndex(t);					            
				           
				            //alert(ci); alert(ri);            
				            if(ci === false || ri === false) return ;
				            var cell = this.getView().getCell(ri,ci);			          
				            
				            if(t.className == 'ux-grid-filter'){				            	
				            	var filtersObj = grid.filters;
				            	var filter = filtersObj.filters.get('sev');
				            	//Get the data in cell
				            	var data = this.getView().getCell(ri,ci).innerHTML;					            	
				            	var valueNode = Ext.DomQuery.selectNode(".ux-grid-filter-hidden-value",this.getView().getCell(ri,ci));					            	
				            	var finalValue = valueNode.innerHTML;
				            	filter.setValue(finalValue);	
				            	filter.setActive(true);
				            	
				            }	
				        }          
			            
					}
				},
					store: new Ext.data.JsonStore({					   
					    url: '/loganalysis/facetJson?by=sev',					   
					    root: 'rows',					    
					    fields: ['facet_name', 'facet_count','facet_id'],
					    autoLoad:false
					}),
				    colModel: new Ext.grid.ColumnModel({
				        defaults: {
				            width: 120,
				            sortable: true
				        },
				        columns: [
				            {id: 'severity', header: 'Severity', width: 200, sortable: true, sortType:"asIp", dataIndex: 'facet_name'},
				            {header: 'Count', dataIndex: 'facet_count', sortable:true, sortType:"asInt"}
				        ]
				    }),	
				    height:227,
				   
				    autoScroll:true,
				    sm: new Ext.grid.RowSelectionModel({singleSelect:true})
				})
				function renderBtn(val, p, record) {  
			        var contentId = Ext.id();
			        createGridButton.defer(1, this, [val, contentId, record]);
			        return('<div id="' + contentId + '"></div>');
			    }
				function createGridButton(value, contentid, record) {
			        new Ext.Button({text: 'Filter', handler : function(btn, e) {			            
			        	var id = record.get('facet_id');
			        	grid.filters.getFilter('reporter').setValue("s-0-"+id)
			        	grid.filters.getFilter('reporter').setActive(true)
			            
			        }}).render(document.body, contentid);
			    }
				this.searchPanel = new Ext.Panel({
					//title:'Faceted Result',
					height:270,
					layout:'column',
					autoWidth:true,
					collapsible:true,
					 hidden:true,
					 hideMode:'visibility',
					//collapsed:true,
					animCollapse:true,
					titleCollapse:true,
					headerAsText:true,
					frame:true,
					applyTo:'_unique_search_panel',
					items: [{
						title:'Grouped by server',
						items:this.facetGridServer,
						columnWidth:.49
					},{
						html:'&nbsp',
						columnWidth:.02
					},{
						title:'Grouped by severity',
						items:this.facetGridSev,
						columnWidth:.47
					}]
				})				
				
				grid.getStore().on('load',function(){
					if(!grid.getStore().getCount()){
						this.facetGridServer.getStore().removeAll();
						this.facetGridSev.getStore().removeAll();
						return;
					}
					var ls = grid.getStore().lastOptions;
					var bkp = ls.params.start;
					ls.params.start = 0;
					var json = Ext.util.JSON.encode(ls.params);
					ls.params.start = bkp;
					if(json == this.initialCount && ls.params.start){
						return;
					}
					
					this.initialCount = json;
					this.searchPanel.show();
					this.facetGridServer.getStore().reload();
					this.facetGridServer.getEl().mask('Getting data for group by server');
					this.facetGridSev.getEl().mask('Getting data for group by severity');			
					
				},this);
				this.facetGridServer.getStore().on("load",function(){
					this.facetGridServer.getEl().unmask();
					this.facetGridSev.getStore().reload();						
				},this)
				this.facetGridSev.getStore().on("load",function(){this.facetGridSev.getEl().unmask()},this)
			})
		});
    }	
});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/grid/Ext.ux.plugins.IndexSearch.js  ]*/

// vim: ts=4:sw=4:nu:fdc=4:nospell
/**
 * RowActions plugin for Ext grid
 *
 * Contains renderer for icons and fires events when an icon is clicked
 *
 * @author    Ing. Jozef Sakáloš
 * @date      22. March 2008
 * @version   $Id: Ext.ux.GridRowActions.js 150 2008-04-08 21:50:58Z jozo $
 *
 * @license Ext.ux.GridRowActions is licensed under the terms of
 * the Open Source LGPL 3.0 license.  Commercial use is permitted to the extent
 * that the code/component(s) do NOT become part of another Open Source or Commercially
 * licensed development library or toolkit without explicit permission.
 * 
 * License details: http://www.gnu.org/licenses/lgpl.html
 */

/**
 * @class Ext.ux.GridRowActions
 * @extends Ext.util.Observable
 *
 * CSS rules from Ext.ux.RowActions.css are mandatory
 *
 * Important general information: Actions are identified by iconCls. Wherever an <i>action</i>
 * is referenced (event argument, callback argument), the iconCls of clicked icon is used.
 * In another words, action identifier === iconCls.
 *
 * Creates new RowActions plugin
 * @constructor
 * @param {Object} config The config object
 */

// add RegExp.escape if it has not been already added
if('function' !== typeof RegExp.escape) {
	RegExp.escape = function(s) {
		if('string' !== typeof s) {
			return s;
		}
		// Note: if pasting from forum, precede ]/\ with backslash manually
		return s.replace(/([.*+?\^=!:${}()|\[\]\/\\])/g, '\\$1');
	}; // eo function escape
}

Ext.ux.GridRowActions = function(config) {
	Ext.apply(this, config);

	// {{{
	this.addEvents(
		/**
		 * @event beforeaction
		 * Fires before action event. Return false to cancel the subsequent action event.
		 * @param {Ext.grid.GridPanel} grid
		 * @param {Ext.data.Record} record Record corresponding to row clicked
		 * @param {String} action Identifies the action icon clicked. Equals to icon css class name.
		 * @param {Integer} rowIndex Index of clicked grid row
		 * @param {Integer} colIndex Index of clicked grid column that contains all action icons
		 */
		 'beforeaction'
		/**
		 * @event action
		 * Fires when icon is clicked
		 * @param {Ext.grid.GridPanel} grid
		 * @param {Ext.data.Record} record Record corresponding to row clicked
		 * @param {String} action Identifies the action icon clicked. Equals to icon css class name.
		 * @param {Integer} rowIndex Index of clicked grid row
		 * @param {Integer} colIndex Index of clicked grid column that contains all action icons
		 */
		,'action'
		/**
		 * @event beforegroupaction
		 * Fires before group action event. Return false to cancel the subsequent groupaction event.
		 * @param {Ext.grid.GridPanel} grid
		 * @param {Array} records Array of records in this group
		 * @param {String} action Identifies the action icon clicked. Equals to icon css class name.
		 * @param {String} groupId Identifies the group clicked
		 */
		,'beforegroupaction'
		/**
		 * @event groupaction
		 * Fires when icon in a group header is clicked
		 * @param {Ext.grid.GridPanel} grid
		 * @param {Array} records Array of records in this group
		 * @param {String} action Identifies the action icon clicked. Equals to icon css class name.
		 * @param {String} groupId Identifies the group clicked
		 */
		,'groupaction'
	);
	// }}}

	// call parent
	Ext.ux.GridRowActions.superclass.constructor.call(this);
};

Ext.extend(Ext.ux.GridRowActions, Ext.util.Observable, {

	// configuration options
	// {{{
	/**
	 * @cfg {Array} actions Mandatory. Array of action configuration objects. The following
	 * configuration options of action are recognized:
	 *
	 * - @cfg {Function} callback Optional. Function to call if the action icon is clicked.
	 *   This function is called with same signature as action event and in its original scope.
	 *   If you need to call it in different scope or with another signature use 
	 *   createCallback or createDelegate functions. Works for statically defined actions. Use
	 *   callbacks configuration options for store bound actions.
	 *
	 * - @cfg {Function} cb Shortcut for callback.
	 *
	 * - @cfg {String} iconIndex Optional, however either iconIndex or iconCls must be
	 *   configured. Field name of the field of the grid store record that contains
	 *   css class of the icon to show. If configured, shown icons can vary depending
	 *   of the value of this field.
	 *
	 * - @cfg {String} iconCls. css class of the icon to show. It is ignored if iconIndex is
	 *   configured. Use this if you want static icons that are not base on the values in the record.
	 *
	 * - @cfg {Boolean} hide Optional. True to hide this action while still have a space in 
	 *   the grid column allocated to it. IMO, it doesn't make too much sense, use hideIndex instead.
	 *
	 * - @cfg (string} hideIndex Optional. Field name of the field of the grid store record that
	 *   contains hide flag (falsie [null, '', 0, false, undefined] to show, anything else to hide).
	 *
	 * - @cfg {String} qtipIndex Optional. Field name of the field of the grid store record that 
	 *   contains tooltip text. If configured, the tooltip texts are taken from the store.
	 *
	 * - @cfg {String} tooltip Optional. Tooltip text to use as icon tooltip. It is ignored if 
	 *   qtipIndex is configured. Use this if you want static tooltips that are not taken from the store.
	 *
	 * - @cfg {String} qtip Synonym for tooltip
	 *
	 * - @cfg {String} textIndex Optional. Field name of the field of the grids store record
	 *   that contains text to display on the right side of the icon. If configured, the text
	 *   shown is taken from record.
	 *
	 * - @cfg {String} text Optional. Text to display on the right side of the icon. Use this
	 *   if you want static text that are not taken from record. Ignored if textIndex is set.
	 *
	 * - @cfg {String} style Optional. Style to apply to action icon container.
	 */

	/**
	 * @cfg {String} actionEvnet Event to trigger actions, e.g. click, dblclick, mouseover (defaults to 'click')
	 */
	 actionEvent:'click'

	/**
	 * @cfg {Boolean} autoWidth true to calculate field width for iconic actions only.
	 */
	,autoWidth:true

	/**
	 * @cfg {Array} groupActions Array of action to use for group headers of grouping grids.
	 * These actions support static icons, texts and tooltips same way as actions. There is one
	 * more action config recognized:
	 * - @cfg {String} align Set it to 'left' to place action icon next to the group header text.
	 *   (defaults to undefined = icons are placed at the right side of the group header.
	 */

	/**
	 * @cfg {Object} callbacks iconCls keyed object that contains callback functions. For example:
	 * callbacks:{
	 *      'icon-open':function(...) {...}
	 *     ,'icon-save':function(...) {...}
	 * }
	 */

	/**
	 * @cfg {String} header Actions column header
	 */
	,header:''
	
	/**
	 * @cfg {Boolean} isColumn
	 * Tell ColumnModel that we are column. Do not touch!
	 * @private
	 */
	,isColumn:true
	
	/**
	 * @cfg {Boolean} keepSelection
	 * Set it to true if you do not want action clicks to affect selected row(s) (defaults to false).
	 * By default, when user clicks an action icon the clicked row is selected and the action events are fired.
	 * If this option is true then the current selection is not affected, only the action events are fired.
	 */
	,keepSelection:false

	/**
	 * @cfg {Boolean} menuDisabled No sense to display header menu for this column
	 */
	,menuDisabled:true

	/**
	 * @cfg {Boolean} sortable Usually it has no sense to sort by this column
	 */
	,sortable:false

	/**
	 * @cfg {String} tplGroup Template for group actions
	 * @private
	 */
	,tplGroup:
		 '<tpl for="actions">'
		+'<div class="ux-grow-action-item<tpl if="\'right\'===align"> ux-action-right</tpl> '
		+'{cls}" style="{style}" qtip="{qtip}">{text}</div>'
		+'</tpl>'

	/**
	 * @cfg {String} tplRow Template for row actions with a url
	 * @private
	 */
	,tplRow:
		 '<div class="ux-row-action">'
		+'<tpl for="actions">'
		+'{url_start}'
		+'<div class="ux-row-action-item {cls} <tpl if="text">'
		+'ux-row-action-text</tpl>" style="{hide}{style}" qtip="{qtip}">'
		+'<tpl if="text"><span qtip="{qtip}">{text}</span></tpl></div>'
		+'{url_end}'
		+'</tpl>'
		+'</div>'
	
	/**
	 * @private {Number} widthIntercept constant used for auto-width calculation
	 */
	,widthIntercept:4

	/**
	 * @private {Number} widthSlope constant used for auto-width calculation
	 */
	,widthSlope:21
	// }}}

	// methods
	// {{{
	/**
	 * Init function
	 * @param {Ext.grid.GridPanel} grid Grid this plugin is in
	 */
	,init:function(grid) {
		/**
		 * Find the effective actions		 * 
		 */
		var effectiveActionsCount = 0;			
		for(var i=0;i<this.actions.length;i++){
			if(this.actions[i].hidden) continue; effectiveActionsCount++
		};
		
		this.grid = grid;
		/**
		 * Change the renderer method of the conditional_row_action  
		 * 
		 */
		var cm = grid.getColumnModel();
		var columns = cm.getColumnsBy(function(c){
			return c.dataIndex == "conditional_row_action";
		});
		if(columns && columns[0]){
			columns[0].renderer = function(value){return value?"<span style='color:green'>Yes</span>":"<span style='color:red'>No</span>"}
		}
		/***********************************************************/
		// the actions column must have an id for Ext 3.x
		this.id = this.id || Ext.id();

		// for Ext 3.x compatibility
		var lookup = grid.getColumnModel().lookup;
		delete(lookup[undefined]);
		lookup[this.id] = this;
		
		// {{{
		// setup template
		if(!this.tpl) {
			this.tpl = this.processActions(this.actions);
		} // eo template setup
		// }}}
		//console.log(this.tpl);
		// calculate width		
		// calculate width
		if(this.autoWidth) {			
			this.width =  this.widthSlope * effectiveActionsCount + this.widthIntercept;			
			this.fixed = true;
			if(this.width < 45) this.width = 45;
		}

		// body click handler
		var view = grid.getView();
		var cfg = {scope:this};
		cfg[this.actionEvent] = this.onClick;
		grid.on({
			render:{scope:this, fn:function() {
				view.mainBody.on(cfg);
			}}
		});

		// setup renderer
		if(!this.renderer) {
			this.renderer = function(value, cell, record, row, col, store) {
				cell.css += (cell.css ? ' ' : '') + 'ux-row-action-cell';
				var allow_modify = record.get('conditional_row_action');				
				if(allow_modify != false){					
					return this.tpl.apply(this.getData(value, cell, record, row, col, store));
				}
				
			}.createDelegate(this);
		}

		// actions in grouping grids support
		if(view.groupTextTpl && this.groupActions) {
			view.interceptMouse = view.interceptMouse.createInterceptor(function(e) {
				if(e.getTarget('.ux-grow-action-item')) {
					return false;
				}
			});
			view.groupTextTpl = 
				 '<div class="ux-grow-action-text">' + view.groupTextTpl +'</div>' 
				+this.processActions(this.groupActions, this.tplGroup).apply()
			;
		}
		
	} // eo function init
	// }}}
	// {{{
	/**
	 * Returns data to apply to template. Override this if needed.
	 * @param {Mixed} value 
	 * @param {Object} cell object to set some attributes of the grid cell
	 * @param {Ext.data.Record} record from which the data is extracted
	 * @param {Number} row row index
	 * @param {Number} col col index
	 * @param {Ext.data.Store} store object from which the record is extracted
	 * @returns {Object} data to apply to template
	 */
	,getData:function(value, cell, record, row, col, store) {
		return record.data || {};
	} // eo function getData
	// }}}
	// {{{
	/**
	 * Processes actions configs and returns template.
	 * @param {Array} actions
	 * @param {String} template Optional. Template to use for one action item.
	 * @return {String}
	 * @private
	 */
	,processActions:function(actions, template) {
		var acts = [];

		// actions loop
		Ext.each(actions, function(a, i) {
			if(a.hidden) return;
			// save callback
			if(a.iconCls && 'function' === typeof (a.callback || a.cb)) {
				this.callbacks = this.callbacks || {};
				this.callbacks[a.iconCls] = a.callback || a.cb;
			}
			if(!a.message) a.message = a.confirmMsg;
			//if(a.message) a.confirm = true;
			if(!a.message) a.message = "Are you sure to perform this operation?";
			
			if(a.icon){
				if(a.style)
				a.style += ";background-image:url("+a.icon+");background-repeat:no-repeat;";
				else
				a.style = "background-image:url("+a.icon+");background-repeat:no-repeat;";
			}
			// data for intermediate template
			
			//load attribute
			a.load = a.load? a.load : 'center';
			
			/*
			 * Add expand action in row action
			 * row action containing name expand triggers is action
			 */
			
			if(a.script){
				var urlStart = a.urlIndex ? ('<tpl if="this.isUrl(' + a.urlIndex + ')"><a href="javascript:void(0);" onclick="'+a.script+'">') : '';
			}else if(a.name&&a.name.match("_expand$")){
				
				/**
				* add af-expand-row to current iconCls or create a new one
				*/
				if(a.iconCls)a.iconCls += " icon-expand-row af-expand-row"
				else a.iconCls = " icon-expand-row af-expand-row";
				/**
				* add af-confirm-row to current iconCls, if is the be confirmed first
				*/
				if(a.confirm)a.iconCls += "  af-confirm-row";
				
				var urlStart = a.urlIndex ? ('<tpl if="this.isUrl(' + a.urlIndex + ')"><a href="'+afApp.urlPrefix+'{' + a.urlIndex + '}" confirmmsg="'+a.confirmMsg+'">') : '';
				
			}else{
				var urlStart = a.urlIndex ? ('<tpl if="this.isUrl(' + a.urlIndex + ')"><a href="<tpl if="!'+a.confirm+'&&this.isLoadCenter(\''+a.load+'\')">'+afApp.urlPrefix+'/'+afApp.sharpPrefix+'#</tpl>{' + a.urlIndex + '}" <tpl if="'+a.confirm+'">onclick="Ext.Msg.confirm(\'Confirmation\',\''+a.message+'\', function(btn){if (btn==\'yes\'){ afApp.load(\''+afApp.urlPrefix+'{' + a.urlIndex + '}\',\'' + a.load + '\'); return false; }else{ return true;}});return false;"</tpl>>') : '';
			}			
			
			/*
			 * Support for post {Ajax call for the url}
			 */
			
			var ajaxCall = '';
			
			if(a.post && a.name && !a.name.match("_expand")){
				ajaxCall = 'Ext.Ajax.request({'+ 
						'url: "'+afApp.urlPrefix+'{'+a.urlIndex+'}",'+
						'method:"post",'+						
						'success: Ext.ux.GridRowActions.onActionSuccess,'+
						'failure: Ext.ux.GridRowActions.onActionFailure'+
					'});'+
				';';
				urlStart = a.urlIndex ? ('<tpl if="this.isUrl(' + a.urlIndex + ')"><a href="'+afApp.urlPrefix+'{'+a.urlIndex+'}" <tpl if="!'+a.confirm+'">onclick=\''+ajaxCall+'; return false;\'</tpl><tpl if="'+a.confirm+'">onclick=\'Ext.Msg.confirm("Confirmation","'+a.message+'", function(btn){if (btn=="yes"){ '+ajaxCall+' }}); return false;\'</tpl>>') : '';
			}
			
			
			/**
			 * Support for the popup widget option
			 */
			if(a.popup){
				
				a.popupSettings=escape(a.popupSettings);
				
				urlStart = a.urlIndex ? ('<tpl if="this.isUrl(' + a.urlIndex + ')"><a href="'+afApp.urlPrefix+'{'+a.urlIndex+'}" <tpl if="!'+a.confirm+'">onclick=\'afApp.widgetPopup("{'+a.urlIndex+'}","","","'+a.popupSettings+'"); return false;\'</tpl><tpl if="'+a.confirm+'">onclick=\'Ext.Msg.confirm("Confirmation","'+a.message+'", function(btn){if (btn=="yes"){ afApp.widgetPopup("'+afApp.urlPrefix+'{'+a.urlIndex+'}"); }}); return false;\'</tpl>>') : '';
			}
			/***************************************************************************/
			
			
			var o = {
				 cls:a.iconIndex ? '{' + a.iconIndex + '}' : (a.iconCls ? a.iconCls : '')
				,qtip:a.qtipIndex ? '{' + a.qtipIndex + '}' : (a.tooltip || a.qtip ? a.tooltip || a.qtip : '')
				,text:a.textIndex ? '{' + a.textIndex + '}' : (a.text ? a.text : '')
				,url_start:urlStart
				,url_end:a.urlIndex ? ('</a></tpl>') : ''
				,hide:a.hideIndex ? '<tpl if="' + a.hideIndex + '">visibility:hidden;</tpl>' : (a.hide ? 'visibility:hidden;' : '')
				,align:a.align || 'right'
				,style:a.style ? a.style : ''
				,confirmMsg:a.confirmMsg ? a.confirmMsg :''
			};
			acts.push(o);

		}, this); // eo actions loop
				
		var xt = new Ext.XTemplate(template || this.tplRow,{
				isUrl : function(url)
				{
					if(url.length>0){return true;}else{return false;}
				}
			}
		);
		
		var xt2 = new Ext.XTemplate(xt.apply({actions:acts}),{
				isUrl : function(url)
				{
					if(url.length>0){return true;}else{return false;}
				},
				isLoadCenter :function(load)
				{
					if(load=='center'){return true;}else{return false;}
				}
			}
		);
		
		return xt2;

	} // eo function processActions
	// }}}
	// {{{
	
	/*
	 * Insert expand div in the row if it does not have. If it has already, hide/unhide it on request
	 * 
	 * methods for functionality:
	 * 	1. expandRequest
	 *  2. findByClassName
	 *  3. isRowExpanded
	 *  4. getExpandedDiv
	 *  
	 *  @author: Prakash Paudel
	 */
	,expandRequest:function(e){
		e.stopEvent();
		var row = e.getTarget('.x-grid3-row');	
		var grid = this.grid;
		var obj = this;
		var url = e.getTarget().parentNode;
		var link = e.getTarget();
		var extlink= Ext.get(link);
		
		if(!this.isRowExpanded(row)){
			var mask = new Ext.LoadMask(Ext.get("body"), {msg: "<b>Getting data from server.....</b> <br>Please wait..",removeMask:true});
			mask.show();
			var ajax = Ext.Ajax.request({
				url: url,
				method:"POST",
				success:function(response){
					var rc = null;
					try{rc=new RegExp('^("(\\\\.|[^"\\\\\\n\\r])*?"|[,:{}\\[\\]0-9.\\-+Eaeflnr-u \\n\\r\\t])+?$')}
				    catch(z){rc=/^(true|false|null|\[.*\]|\{.*\}|".*"|\d+|\d+\.\d+)$/}
				    if(rc.test(response.responseText)){
				    	json = Ext.util.JSON.decode(response.responseText);
				    	if(!json.success){
				    		Ext.Msg.alert("Error",json.message);
				    		mask.hide();
				    		return;
				    	}
				    	if(json.html){
				    		Ext.DomHelper.append(row,{tag:"div",cls:"imm-row-expand",style:"padding:5px;",html:json.html})
				    	}
				    	if(json.script){
				    		eval(json.script);
				    	}
				    	
				    }else{
				    	Ext.DomHelper.append(row,{tag:"div",cls:"imm-row-expand",style:"padding:5px;",html:response.responseText})
					}
					
					extlink.removeClass('icon-expand-row');
					extlink.addClass('icon-collapse-row');										
					mask.hide();
				},
				failure:function(){
					mask.hide();
					Ext.Msg.alert("Error !","Some error has occured while connecting to server. <br>Please try again.");
				}
			});			
		}else{
			var div = this.getExpandedDiv(row);
			if(div.style.display != "none"){
				div.style.display = "none";
				extlink.addClass('icon-expand-row');
				extlink.removeClass('icon-collapse-row');
			}else{
				div.style.display = "block";
				extlink.removeClass('icon-expand-row');
				extlink.addClass('icon-collapse-row');							
			}
		}
		
	},
	/*
	 * Cross browser compatibility for the elements getting by className
	 * This method is alternative for the getElementsByClassName [incompatible with IE]
	 */
	findByClassName:function(el,className){
		var elements = el.getElementsByTagName("div");
		var els = new Array();
		for(var i=0;i<elements.length;i++){
			if(elements[i].className == className){
				els.push(elements[i]);
			}
		}
		return els;
	},
	isRowExpanded: function(row){		
		return this.findByClassName(row,"imm-row-expand")[0]?true:false;		
	},
	getExpandedDiv: function(row){
		return this.isRowExpanded(row)?this.findByClassName(row,"imm-row-expand")[0]:null;
	}
	/****************************************************************************************************/
	
	/**
	 * Grid body actionEvent event handler
	 * @private
	 */
	,onClick:function(e, target) {

		var view = this.grid.getView();
		var action = false;

		// handle row action click
		var row = e.getTarget('.x-grid3-row');
		
		if(e.getTarget(".af-expand-row")){
			
			if(e.getTarget(".af-confirm-row"))
			{			
				var obj=this;
				
				Ext.Msg.confirm('Confirmation',e.getTarget(".af-confirm-row").parentNode.getAttribute('confirmmsg'), function(btn){if (btn=='yes'){ obj.expandRequest(e); return false; }else{ return true;}});
				
				e.stopEvent();
			}
			else
			{
				this.expandRequest(e);return false;
			}
		}
		
		var col = view.findCellIndex(target.parentNode.parentNode);

		var t = e.getTarget('.ux-row-action-item');
		if(t) {
			action = t.className.replace(/ux-row-action-item /, '');
			
			if(action) {
				action = action.replace(/ ux-row-action-text/, '');
				action = action.trim();
			}
		}
		if(false !== row && false !== col && false !== action) {
			var record = this.grid.store.getAt(row.rowIndex);
			
			// call callback if any
			if(this.callbacks && 'function' === typeof this.callbacks[action]) {
				this.callbacks[action](this.grid, record, action, row.rowIndex, col);
			}

			// fire events
			if(true !== this.eventsSuspended && false === this.fireEvent('beforeaction', this.grid, record, action, row.rowIndex, col)) {
				return;
			}
			else if(true !== this.eventsSuspended) {
				this.fireEvent('action', this.grid, record, action, row.rowIndex, col);
			}

		}

		// handle group action click
		t = e.getTarget('.ux-grow-action-item');
		if(t) {
			// get groupId
			var group = view.findGroup(target);
			var groupId = group ? group.id.replace(/ext-gen[0-9]+-gp-/, '') : null;

			// get matching records
			var records;
			if(groupId) {
				var re = new RegExp(groupId);
				records = this.grid.store.queryBy(function(r) {
					return r._groupId.match(re);
				});
				records = records ? records.items : [];
			}
			action = t.className.replace(/ux-grow-action-item (ux-action-right )*/, '');

			// call callback if any
			if('function' === typeof this.callbacks[action]) {
				this.callbacks[action](this.grid, records, action, groupId);
			}

			// fire events
			if(true !== this.eventsSuspended && false === this.fireEvent('beforegroupaction', this.grid, records, action, groupId)) {
				return false;
			}
			this.fireEvent('groupaction', this.grid, records, action, groupId);
		}
	} // eo function onClick
	// }}}

	
    /**
     * @private
     * Clean up. Remove any listeners.
     */
    ,destroy: function() {
        this.purgeListeners();
    }	
});

/**
 * A handler for an JSON response.
 */
Ext.ux.GridRowActions.onActionSuccess = function(response, options) {
	response = Ext.decode(response.responseText);
	if(!response.success) {
		return Ext.ux.GridRowActions.onActionFailure(response, options);
	}
	
	if(response.executeAfter)
	{
		eval(response.executeAfter);
	}

	if(response.message) {
		Ext.Msg.alert('Success', response.message, function(){
			if(response.redirect) {
				afApp.load(response.redirect,response.load);
			}
		});
	} else {
		if(response.redirect) {
			afApp.load(response.redirect,response.load);
		}
	}
}

/**
 * A handler for an Ajax failure or a unsuccessful response.
 */
Ext.ux.GridRowActions.onActionFailure = function(response, options) {
	if(response.responseText) {
		response = Ext.decode(response.responseText);
	}
	var message = response.message || 'Unable to do the operation.';
	Ext.Msg.alert('Failure', message, function(){
		if(response.redirect) {
			afApp.load(response.redirect,response.load);
		}
	});
}

// registre xtype
Ext.reg('rowactions', Ext.ux.GridRowActions);

// eof

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/rowactionsImm/js/Ext.ux.GridRowActions.js  ]*/

/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */


Ext.DataView.LabelEditor = function(cfg, field){
    Ext.DataView.LabelEditor.superclass.constructor.call(this,
        field || new Ext.form.TextField({
            allowBlank: false,
            growMin:90,
            growMax:240,
            grow:true,
            selectOnFocus:true
        }), cfg
    );
};

Ext.extend(Ext.DataView.LabelEditor, Ext.Editor, {
    alignment: "tl-tl",
    hideEl : false,
    cls: "x-small-editor",
    shim: false,
    completeOnEnter: true,
    cancelOnEsc: true,
    labelSelector: 'span.x-editable',

    init : function(view){
        this.view = view;
        view.on('render', this.initEditor, this);
        this.on('complete', this.onSave, this);
    },

    initEditor : function(){
        this.view.getEl().on('mousedown', this.onMouseDown, this, {delegate: this.labelSelector});
    },

    onMouseDown : function(e, target){
        if(!e.ctrlKey && !e.shiftKey){
            var item = this.view.findItemFromChild(target);
            e.stopEvent();
            var record = this.view.store.getAt(this.view.indexOf(item));
            this.startEdit(target, record.data[this.dataIndex]);
            this.activeRecord = record;
        }else{
            e.preventDefault();
        }
    },

    onSave : function(ed, value){
        this.activeRecord.set(this.dataIndex, value);
    }
});


Ext.DataView.DragSelector = function(cfg){
    cfg = cfg || {};
    var view, regions, proxy, tracker;
    var rs, bodyRegion, dragRegion = new Ext.lib.Region(0,0,0,0);
    var dragSafe = cfg.dragSafe === true;

    this.init = function(dataView){
        view = dataView;
        view.on('render', onRender);
    };

    function fillRegions(){
        rs = [];
        view.all.each(function(el){
            rs[rs.length] = el.getRegion();
        });
        bodyRegion = view.el.getRegion();
    }

    function cancelClick(){
        return false;
    }

    function onBeforeStart(e){
        return !dragSafe || e.target == view.el.dom;
    }

    function onStart(e){
        view.on('containerclick', cancelClick, view, {single:true});
        if(!proxy){
            proxy = view.el.createChild({cls:'x-view-selector'});
        }else{
            proxy.setDisplayed('block');
        }
        fillRegions();
        view.clearSelections();
    }

    function onDrag(e){
        var startXY = tracker.startXY;
        var xy = tracker.getXY();

        var x = Math.min(startXY[0], xy[0]);
        var y = Math.min(startXY[1], xy[1]);
        var w = Math.abs(startXY[0] - xy[0]);
        var h = Math.abs(startXY[1] - xy[1]);

        dragRegion.left = x;
        dragRegion.top = y;
        dragRegion.right = x+w;
        dragRegion.bottom = y+h;

        dragRegion.constrainTo(bodyRegion);
        proxy.setRegion(dragRegion);

        for(var i = 0, len = rs.length; i < len; i++){
            var r = rs[i], sel = dragRegion.intersect(r);
            if(sel && !r.selected){
                r.selected = true;
                view.select(i, true);
            }else if(!sel && r.selected){
                r.selected = false;
                view.deselect(i);
            }
        }
    }

    function onEnd(e){
        if(proxy){
            proxy.setDisplayed(false);
        }
    }

    function onRender(view){
        tracker = new Ext.dd.DragTracker({
            onBeforeStart: onBeforeStart,
            onStart: onStart,
            onDrag: onDrag,
            onEnd: onEnd
        });
        tracker.initEl(view.el);
    }
};

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/view/data-view-plugins.js  ]*/

Ext.ns('Ext.ux');

/**
 * Converts an invalid JSON to an error response.
 */
Ext.ux.RobustSubmitAction = Ext.extend(Ext.form.Action.Submit, {
    constructor : function(form, options) {
        Ext.ux.RobustSubmitAction.superclass.constructor.call(this, form, options);
    },
    type : 'robustsubmit',

    handleResponse : function(response){
        if(this.form.errorReader){
            return Ext.ux.RobustSubmitAction.superclass.handleResponse.call(this, response);
        }

        try {
            return Ext.decode(response.responseText);
        } catch (e) {
            return {
                success: false,
                message: 'Invalid response: ' + response.responseText
            };
        }
    }
});

Ext.form.Action.ACTION_TYPES['robustsubmit'] = Ext.ux.RobustSubmitAction;


/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/submit/RobustSubmitAction.js  ]*/

/*
 * JS for html cheat to make the html div height synchronized 
 * with the panel container
 */
function panelHtmlCheat(div){	
	var ref = document.getElementById("center_panel");
	var ref = ref.childNodes[1];
	ref = ref.firstChild;
	ref = ref.firstChild;
	ref = ref.firstChild;
	if(ref.childNodes[1]){
		ref = ref.childNodes[1];
	}else{
		ref = ref.firstChild;
	}
	div.style.height = parseInt(ref.style.height) - 65 + "px";
	
}

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/js/custom/cheatJS.js  ]*/

/**
 * immune custom comet like script
 * @author radu
 */

Ext.Comet = function(config) {
	this.events = {
		receive:true
	};
	Ext.apply(this, config);
};

Ext.extend(Ext.Comet, Ext.util.Observable, {

	id : 'comet',
	_intervalId : null,
	request : null,
	interval : 200, //in miliseconds
	lastTextPosition: 0,
	autoReconnect : false,
	reconnectIntervalOnFailure : 5000,
	url : null,
	timeout : 30000, //in miliseconds

	start : function() {
		this.request = Ext.Ajax.request({
			url:this.url,
			timeout:this.timeout,
			callback:this.requestCallback,
			scope:this
		});
		this._intervalId = setInterval(this.watch.createDelegate(this),
			this.interval);
	},

	requestCallback : function(o, success, r) {
		this.watch();
		this.stop();
		if (this.autoReconnect) {
			if (success) {
				this.start();
			} else {
				this.start.defer(this.reconnectIntervalOnFailure, this);
			}
		}
	},

	watch : function() {
		if(typeof(this.request.conn)!="unknown"&&typeof(this.request.conn.responseText)!="unknown")
		{
			var text = this.request.conn.responseText;
			if (text.length == this.lastTextPosition) { return; }
			var last = text.substring(this.lastTextPosition);
			this.lastTextPosition = text.length;
			var lasts = last.split("\n");
			var nbInfos = lasts.length;
			var steps = new Array();
			for (i = 0; i < nbInfos; i++) {
				if (lasts[i] === "") { continue; }
				steps.push(Ext.util.JSON.decode(lasts[i]));
			}
			
			this.fireEvent("receive", steps);
		}
	},
		
	stop : function() {
		clearInterval(this._intervalId);
		this.request.conn.abort();
	}


});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/comet/Ext.Comet.js  ]*/

/**
 * immune custom updater using some comet like script
 * @author radu
 */

Ext.ux.Updater = function(config) {
	Ext.apply(this, config);
};

Ext.extend(Ext.ux.Updater, Ext.util.Observable, {
	url:null,
	interval:200, //in miliseconds
	timeout:300000, //in miliseconds
	width:250,
	errors:{
		noStep: null,
		title:null
	},
	
	start : function()
	{
		this.comet = new Ext.Comet({url:this.url, interval:this.interval, timeout:this.timeout});
		this.comet.on("receive", this.onReceive, this);
		this.comet.start();
		this.updateMsg({title:'Waiting...',msg:'Waiting for server response !',percent:'0'});
	},
			
	onReceive : function(r)
	{
		var nbInfos = r.length;
		for (i = 0; i < nbInfos; i++) {
			if(!r[i].step)
			{
				this.errors.noStep = this.errors.noStep || 'There is an error in the Updater! No step defined !';
				
				this.createErrorMsg({msg:this.errors.noStep});
                continue;
			}
					
			if(r[i].step=='start')
			{
				this.updateMsg(r[i]);	
			}
			else if(r[i].step=='in')
			{
				this.updateMsg(r[i]);
			}
			else if(r[i].step=='error')
			{
				this.createErrorMsg(r[i]);	
			}
			else if(r[i].step=='stop')
			{
				this.hideMsg(r[i]);
			}
		}
	},
	
	createMsg : function(r)
	{		
		var percentText=this.getPercentText(r);
		var percentValue=this.getPercentValue(r);
		this.msg=Ext.Msg.show({
			title: r.title,
			msg: r.msg,
			buttons: false,
			progress: true,
			closable: false,
			minWidth: this.width,
			progressText: percentText
		});
		this.msg.updateProgress(percentValue, percentText, r.msg);
	},
	
	createErrorMsg : function(r)
	{
		if(this.msg)
		{
			this.msg.hide();
			this.msg = null;
		}
		
		if(r.msg)
		{
			this.errors.title = this.errors.title || 'Error';
			Ext.Msg.alert(this.errors.title, r.msg);
		}		
		
	},
	
	hideMsg : function(r)
	{
		this.updateMsg(r);
		
		var updater = this;
		r.hideAfter = (r.hideAfter*1000) || 2000;
		window.setTimeout(function() {
			updater.msg.hide();
			updater.msg = null;
			if (r.redirect) {
				afApp.load(r.redirect);
			}
		}, r.hideAfter);
	},
	
	updateMsg : function(r)
	{
		if(!this.msg) {
			this.createMsg(r);
		} else {
			var percentText=this.getPercentText(r);
			var percentValue=this.getPercentValue(r);
			
			if(percentValue==1) {
				percentText+=' done';
			}
			
			this.msg.updateProgress(percentValue,percentText,r.msg);
			if(r.title) {
				this.msg.getDialog().setTitle(r.title);
			}
		}
	},
	
	getPercentText : function(r)
	{
		return r.percent+' %';
	},
	
	getPercentValue : function(r)
	{
		return r.percent/100;
	}

});

/*[  /var/www/projects/appflower_seedcontrol/plugins/appFlowerPlugin/batch/../web/extjs-3/plugins/comet/Ext.ux.Updater.js  ]*/

