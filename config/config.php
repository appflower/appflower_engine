<?php
/**
 * Config to skip the certain table columns being logged in notificaiton...
 */
sfConfig::set('notification_skip_table_column',
	array(
		"sf_guard_user"=>array("algorithm","salt","password")
	)
);


/**
 * appFlower config
 */
sfConfig::set('sf_extjs2_version', 'v1.0');
sfConfig::set('sf_extjs2_comment', true);
#
# adapters
#
sfConfig::set('sf_extjs2_default_adapter', 'ext');
sfConfig::set('sf_extjs2_adapters',
  array(
    'jquery' => array(
      'adapter/jquery/jquery.js',
      'adapter/jquery/jquery-plugins.js',
      'adapter/jquery/ext-jquery-adapter.js'
    ),
    'prototype' => array(
      'adapter/prototype/prototype.js',
      'adapter/prototype/scriptaculous.js?load=effects.js',
      'adapter/prototype/ext-prototype-adapter.js'
    ),
    'yui' => array(
      'adapter/yui/yui-utilities.js',
      'adapter/yui/ext-yui-adapter.js'
    ),
    'ext' => array(
      'adapter/ext/ext-base.js' //-debug
    )
  )
);
sfConfig::set('sf_extjs3_default_adapter', sfConfig::get('sf_extjs2_default_adapter'));
sfConfig::set('sf_extjs3_adapters', sfConfig::get('sf_extjs2_adapters'));
#
# themes
#
sfConfig::set('sf_extjs2_default_theme', 'aero');
sfConfig::set('sf_extjs2_themes',
  array(
    'aero' => array( ),
    'gray' => array( 'xtheme-gray.css' )
  )
);
sfConfig::set('sf_extjs3_default_theme', 'blue');
sfConfig::set('sf_extjs3_themes',
  array(
    'blue' => array( 'xtheme-blue.css' )
  )
);
#
# base directories
#
sfConfig::set('sf_extjs2_plugin_dir', '/appFlowerPlugin/');
sfConfig::set('sf_extjs3_plugin_dir', sfConfig::get('sf_extjs2_plugin_dir'));

sfConfig::set('sf_extjs3_js_dir', '/appFlowerPlugin/extjs-3/');
sfConfig::set('sf_extjs3_examples_dir', '/appFlowerPlugin/extjs-3/examples/');
sfConfig::set('sf_extjs3_css_dir', '/appFlowerPlugin/extjs-3/resources/css/');
sfConfig::set('sf_extjs3_images_dir', '/appFlowerPlugin/extjs-3/resources/images/');
#
# spacer gif
#
sfConfig::set('sf_extjs2_spacer', sfConfig::get('sf_extjs2_images_dir').'default/s.gif');
sfConfig::set('sf_extjs3_spacer', sfConfig::get('sf_extjs3_images_dir').'default/s.gif');
#
# attributes which must handled as array
#
sfConfig::set('sf_extjs2_list_attributes', array('items', 'tbar', 'toTBar', 'bbar', 'buttons', 'plugins', 'fields', 'tools', 'actions', 'columns','children','filters'));
#
# array values that don't need quotes
#
sfConfig::set('sf_extjs2_quote_except',
  array(
    'key'   => array('renderer', 'store', 'defaults', 'plugins', 'cm', 'ds', 'view', 'tbar', 'bbar', 'scope', 'key', 'parentPanel', 'handler', 'columns'),
    'value' => array('true', 'false', 'new Ext.', 'function', 'Ext.', '__(', '{', 'this.')
  )
);
#
# mapping plugin method against class
#
sfConfig::set('sf_extjs2_classes',
  array(
    // data
    'JsonReader'    => 'Ext.data.JsonReader',
    'Store'         => 'Ext.data.Store',
    'SimpleStore'   => 'Ext.data.SimpleStore',
    'JsonStore'     => 'Ext.data.JsonStore',
    'GroupingStore' => 'Ext.data.GroupingStore',
    'AdjacencyListStore' => 'Ext.ux.maximgb.tg.AdjacencyListStore',
    'HttpProxy'     => 'Ext.data.HttpProxy',
    'Template'      => 'Ext.Template',
    'XTemplate'     => 'Ext.XTemplate',
    // widgets
    'BoxComponent'            => 'Ext.BoxComponent',
    'Button'                  => 'Ext.Button',
    'GridRowActions'          => 'Ext.ux.GridRowActions',
    'GridPanel'               => 'Ext.grid.GridPanel',
    'GridTreePanel'           => 'Ext.ux.maximgb.tg.GridPanel',
    'ColumnModel'             => 'Ext.grid.ColumnModel',
    'GridFilters'             => 'Ext.ux.grid.GridFilters',
    'GridView'                => 'Ext.grid.GridView',
    'GroupingView'            => 'Ext.grid.GroupingView',
    'GridColorView'           => 'Ext.ux.GridColorView',
    'GroupingColorView'       => 'Ext.ux.GroupingColorView',    
    'EditorGridPanel'         => 'Ext.grid.EditorGridPanel',
    'RowSelectionModel'       => 'Ext.grid.RowSelectionModel',
    'CheckboxSelectionModel'  => 'Ext.grid.CheckboxSelectionModel',
    'UxCheckboxSelectionModel'  => 'Ext.ux.CheckboxSelectionModel',
    'Panel'                   => 'Ext.Panel',
    'GmapPanel'				  => 'Ext.ux.GMapPanel',
    'Portal'				  => 'Ext.ux.Portal',
    'Portlet'				  => 'Ext.ux.Portlet',
    'TabPanel'                => 'Ext.TabPanel',
    'GroupTabPanel'           => 'Ext.ux.GroupTabPanel',
    'FormPanel'               => 'Ext.FormPanel',
    'ClassicFormPanel'        => 'Ext.ux.ClassicFormPanel',
    'Viewport'                => 'Ext.Viewport',
    'Window'                  => 'Ext.Window',
    'Updater'                 => 'Ext.ux.Updater',
    'FieldSet'                => 'Ext.form.FieldSet',
    'Hidden'                  => 'Ext.form.Hidden',
    'DateField'               => 'Ext.form.DateField',
    'TextField'               => 'Ext.form.TextField',
    'TimeField'               => 'Ext.form.TimeField',
    'HtmlEditor'              => 'Ext.form.HtmlEditor',
    'ComboBox'                => 'Ext.form.ComboBox',
    'Menu'                    => 'Ext.menu.Menu',
    'Item'	  		          => 'Ext.menu.Item',
    'MenuSeparator'	  		  => 'Ext.menu.Separator',
    'TextItem'                => 'Ext.menu.TextItem',
    'CheckItem' 	          => 'Ext.menu.CheckItem',
    'Toolbar'                 => 'Ext.Toolbar',
    'Item'                    => 'Ext.Toolbar.Item',
    'Fill'                    => 'Ext.Toolbar.Fill',
    'Separator'               => 'Ext.Toolbar.Separator',
    'Spacer'                  => 'Ext.Toolbar.Spacer',
    'PagingToolbar'           => 'Ext.PagingToolbar',
    'GridTreePagingToolbar'   => 'Ext.ux.maximgb.tg.PagingToolbar',
    'MessageBox'              => 'Ext.MessageBox',
    'KeyMap'                  => 'Ext.KeyMap',
    // tree stuff
    'TreePanel'               => 'Ext.tree.TreePanel',
    'FileTreePanel'           => 'Ext.ux.FileTreePanel',
    'TreeLoader'              => 'Ext.tree.TreeLoader',
    'Node'                    => 'Ext.data.Node',
    'TreeNode'                => 'Ext.tree.TreeNode',
    'AsyncTreeNode'           => 'Ext.tree.AsyncTreeNode',
    // base
    'Observable'              => 'Ext.util.Observable',
    'DataView'				  => 'Ext.DataView'
  )
);
#
# default setting for classes
#

#
# data
#
sfConfig::set('Ext.data.JsonReader',
  array(
    'class'       => 'Ext.data.JsonReader',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.data.Store',
  array(
    'class'       => 'Ext.data.Store',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.data.SimpleStore',
  array(
    'class'       => 'Ext.data.SimpleStore',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.data.JsonStore',
  array(
    'class'       => 'Ext.data.JsonStore',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.data.GroupingStore',
  array(
    'class'       => 'Ext.data.GroupingStore',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.ux.maximgb.tg.AdjacencyListStore',
  array(
    'class'       => 'Ext.ux.maximgb.tg.AdjacencyListStore',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.data.HttpProxy',
  array(
    'class'       => 'Ext.data.HttpProxy',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.Template',
  array(
    'class'       => 'Ext.Template',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.XTemplate',
  array(
    'class'       => 'Ext.XTemplate',
    'attributes'  => array()
  )
);

#
# widgets
#
sfConfig::set('Ext.BoxComponent',
  array(
    'class'       => 'Ext.BoxComponent',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.Button',
  array(
    'class'       => 'Ext.Button',
    'attributes'  => array()
  )
);

//Original:row actions from Saki
//Modified by rady@immune
sfConfig::set('Ext.ux.GridRowActions',
  array(
    'class'       => 'Ext.ux.GridRowActions',
    'attributes'  => array(
      'header'=>'Actions',
      'groupActions'=>array()
    )
  )
);

sfConfig::set('Ext.ux.grid.GridFilters',
  array(
    'class'       => 'Ext.ux.grid.GridFilters',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.grid.GridPanel',
  array(
    'class'       => 'Ext.grid.GridPanel',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.ux.maximgb.tg.GridPanel',
  array(
    'class'       => 'Ext.ux.maximgb.tg.GridPanel',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.grid.ColumnModel',
  array(
    'class'       => 'Ext.grid.ColumnModel',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.grid.GridView',
  array(
    'class'       => 'Ext.grid.GridView',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.grid.GroupingView',
  array(
    'class'       => 'Ext.grid.GroupingView',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.ux.GridColorView',
  array(
    'class'       => 'Ext.ux.GridColorView',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.ux.GroupingColorView',
  array(
    'class'       => 'Ext.ux.GroupingColorView',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.grid.EditorGridPanel',
  array(
    'class'       => 'Ext.grid.EditorGridPanel',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.grid.RowSelectionModel',
  array(
    'class'       => 'Ext.grid.RowSelectionModel',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.grid.CheckboxSelectionModel',
  array(
    'class'       => 'Ext.grid.CheckboxSelectionModel',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.ux.CheckboxSelectionModel',
  array(
    'class'       => 'Ext.ux.CheckboxSelectionModel',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.Panel',
  array(
    'class'       => 'Ext.Panel',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.ux.Portal',
  array(
    'class'       => 'Ext.ux.Portal',
    'attributes'  => array(
      'region'=>'center'
    )
  )
);

sfConfig::set('Ext.ux.Portlet',
  array(
    'class'       => 'Ext.ux.Portlet',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.TabPanel',
  array(
    'class'       => 'Ext.TabPanel',
    'attributes'  => array(
      'resizeTabs'      => true,
      'minTabWidth'     => 100,
      'tabWidth'        => 150,
      'enableTabScroll' => true,
      'defaults'        => '{ autoScroll: true }'
    )
  )
);

sfConfig::set('Ext.ux.GroupTabPanel',
  array(
    'class'       => 'Ext.ux.GroupTabPanel',
    'attributes'  => array(
      'tabWidth'=> 130,
      'activeGroup'=> 0,
    )
  )
);

sfConfig::set('Ext.FormPanel',
  array(
    'class'       => 'Ext.FormPanel',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.ux.ClassicFormPanel',
  array(
    'class'       => 'Ext.ux.ClassicFormPanel',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.Viewport',
  array(
    'class'       => 'Ext.Viewport',
    'attributes'  => array('layout' => 'border')
  )
);

sfConfig::set('Ext.Window',
  array(
    'class'       => 'Ext.Window',
    'attributes'  => array(
      'constrain'   => true,
      'layout'      => 'fit',
      'width'       => 500,
      'height'      => 300,
      'closeAction' => 'hide',
      'plain'       => true
    )
  )
);

/**
 * immune custom updater using some comet like script
 * @author radu
 */
sfConfig::set('Ext.ux.Updater',
  array(
    'class'       => 'Ext.ux.Updater',
    'attributes'  => array(
      'interval'      => '200'
    )
  )
);

sfConfig::set('Ext.form.FieldSet',
  array(
    'class'       => 'Ext.form.FieldSet',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.form.Hidden',
  array(
    'class'       => 'Ext.form.Hidden',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.form.DateField',
  array(
    'class'       => 'Ext.form.DateField',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.form.TextField',
  array(
    'class'       => 'Ext.form.TextField',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.form.TimeField',
  array(
    'class'       => 'Ext.form.TimeField',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.form.HtmlEditor',
  array(
    'class'       => 'Ext.form.HtmlEditor',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.form.ComboBox',
  array(
    'class'       => 'Ext.form.ComboBox',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.menu.Menu',
  array(
    'class'       => 'Ext.menu.Menu',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.menu.Item',
  array(
    'class'       => 'Ext.menu.Item',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.menu.Separator',
  array(
    'class'       => 'Ext.menu.Separator',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.menu.TextItem',
  array(
    'class'       => 'Ext.menu.TextItem',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.menu.CheckItem',
  array(
    'class'       => 'Ext.menu.CheckItem',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.Toolbar',
  array(
    'class'       => 'Ext.Toolbar',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.Toolbar.Item',
  array(
    'class'       => 'Ext.Toolbar.Item',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.Toolbar.Fill',
  array(
    'class'       => 'Ext.Toolbar.Fill',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.Toolbar.Separator',
  array(
    'class'       => 'Ext.Toolbar.Separator',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.Toolbar.Spacer',
  array(
    'class'       => 'Ext.Toolbar.Spacer',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.PagingToolbar',
  array(
    'class'       => 'Ext.PagingToolbar',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.ux.maximgb.tg.PagingToolbar',
  array(
    'class'       => 'Ext.ux.maximgb.tg.PagingToolbar',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.MessageBox',
  array(
    'class'       => 'Ext.MessageBox',
    'attributes'  => array()
  )
);


sfConfig::set('Ext.KeyMap',
  array(
    'class'       => 'Ext.KeyMap',
    'attributes'  => array()
  )
);


sfConfig::set('anonymousClass',
  array(
    'class'       => 'anonymousClass',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.ux.FileTreePanel',
  array(
    'class'       => 'Ext.ux.FileTreePanel',
    'attributes'  => array(
      'autoWidth'=>true,
	  'id'=>'ftp',
	  'rootPath'=>'root',
	  'rootVisible'=>true,
	  'rootText'=>'Home',
	  'autoScroll'=>true,
	  'enableProgress'=>false
    )
  )
);

sfConfig::set('Ext.tree.TreePanel',
  array(
    'class'       => 'Ext.tree.TreePanel',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.DataView',
  array(
    'class'       => 'Ext.DataView',
    'attributes'  => array()
  )
);

sfConfig::set('Ext.ux.GMapPanel',
  array(
    'class'       => 'Ext.ux.GMapPanel',
    'attributes'  => array()
  )
);

// normal sfExecutionFilter is used even when sfCompat10Plugin is enabled
sfConfig::set('sf_execution_filter', array('afExecutionFilter', array('execution')));
