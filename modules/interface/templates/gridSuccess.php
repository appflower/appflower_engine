<?php
$layout=new afExtjsPanelLayout();
/**
 * FIRST GRID
 */

$tools=new afExtjsTools();
$tools->addItem(array('id'=>'gear','handler'=>array('source'=>"Ext.Msg.alert('Message', 'The Settings tool was clicked.');")));
$tools->addItem(array('id'=>'help','handler'=>array('parameters'=>'e,target,panel','source'=>"afApp.loadPopupHelp(panel.idxml);")));
$tools->addItem(array('id'=>'close','handler'=>array('parameters'=>'e,target,panel','source'=>"panel.ownerCt.remove(panel, true);")));


$grid=new afExtjsGrid(array('autoHeight'=>true,'clearGrouping'=>true,'title'=>'Title','frame'=>false,'idxml'=>'/interface/grid','remoteSort'=>true,'tools'=>$tools,'select'=>true));
$grid->addHelp('<b>Lorem ipsum dolor sit amet</b>, consectetur adipiscing elit. Ut est neque, feugiat venenatis elementum a, tincidunt non massa. Cras sagittis, augue nec porttitor scelerisque, elit lorem ornare massa, eu euismod odio massa vitae justo. Mauris erat nunc, luctus tincidunt lacinia ac, sagittis id risus. Mauris ut quam nisl. Mauris tortor eros, tincidunt sit amet fringilla lacinia, faucibus vel augue. Sed dolor felis, faucibus nec elementum at, cursus in magna. Nam erat nibh, auctor fermentum convallis id, ornare vitae urna. Ut placerat elementum felis. Donec quis libero mauris, vitae vehicula mauris. Donec sit amet urna id justo tempus aliquam. Duis aliquam gravida dictum. Nullam ac nibh eros. Donec lacinia risus id velit congue sed placerat nibh fringilla. Vivamus condimentum varius lacus et facilisis. Curabitur sed tellus sit amet diam dictum ornare. Donec dui lacus, vehicula sit amet semper a, auctor sed sem. Nam pulvinar iaculis libero sed varius. Quisque volutpat posuere sapien quis condimentum.');
/**
 * columns
 */
$grid->addColumn(array('name'=>'company','type'=>'string','label'=>'Company','sort'=>'ASC','id'=>true,'width'=>40,'sortable'=>true,'hidden'=>false,'hideable'=>true,'qtip'=>true,'align'=>'right','filter'=>array('type'=>'string')));
$grid->addColumn(array('name'=>'industry','type'=>'int','label'=>'Industry','groupField'=>true,'width'=>20,'sortable'=>true,'qtip'=>false,'filter'=>array('type'=>'numeric')));
/**
 * proxy
 * 
 * REMEMBER:
 * stateId attribute must be unique for each view, because with this id Extjs keeps in a cookie the state of start & limit attributes for listjson, see ticket #574; if stateId attribute is not defined, then the state is not kept !
 */
$grid->setProxy(array('url'=>'/interface/jsonactions','limit'=>2,'start'=>2));
/**
 * row actions
 */
$actions=$grid->startRowActions(/*array('header'=>'Actions')*/);
/**
 * action1
 */
$actions->addAction(array('iconCls'=>'icon-edit-record','tooltip'=>'Edit'/*,'text'=>'edit','style'=>''*/));
/**
 * action2
 */
$actions->addAction(array('iconCls'=>'icon-minus','tooltip'=>'Delete'/*,'text'=>'Delete','style'=>''*/));
/**
 * REMEMBER:
 * $grid->endRowActions($actions) is commented so you can see that if no actions are associated with the grid then the actions column doesn't appear
 */
//$grid->endRowActions($actions);

new afExtjsLinkButton($grid,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));
new afExtjsLinkButton($grid,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));

$grid->end();

$layout->addItem('center',$grid);


/**
 * SECOND GRID
 */


$grid1=new afExtjsGrid(array('autoHeight'=>true,'clearGrouping'=>false,'pager'=>false));
/**
 * columns
 */
$grid1->addColumn(array('name'=>'industry','type'=>'int','label'=>'Industry','groupField'=>false,'width'=>20,'sortable'=>true,'qtip'=>false));
$grid1->addColumn(array('name'=>'industry','type'=>'int','label'=>'Industry2','groupField'=>false,'width'=>20,'sortable'=>true,'qtip'=>false));
$grid1->addColumn(array('name'=>'company','label'=>'Company','sort'=>'ASC','id'=>true,'width'=>10,'sortable'=>true,'qtip'=>true));

/**
 * proxy
 * 
 * REMEMBER:
 * stateId attribute must be unique for each view, because with this id Extjs keeps in a cookie the state of start & limit attributes for listjson, see ticket #574; if stateId attribute is not defined, then the state is not kept !
 */
$grid1->setProxy(array('url'=>'/interface/jsonactions','limit'=>3,'stateId'=>'gd2'));
/**
 * row actions
 */
$actions=$grid1->startRowActions(/*array('header'=>'Actions')*/);
/**
 * action1
 */
$actions->addAction(array('iconCls'=>'icon-edit-record','tooltip'=>'Edit'/*,'text'=>'edit','style'=>''*/));
/**
 * action2
 * 
 * added confirm&message for action confirmation
 */
$actions->addAction(array('iconCls'=>'icon-minus','tooltip'=>'Delete','confirm'=>true,'message'=>'Are you sure you want to delete?'/*,'text'=>'Delete','style'=>''*/));
$grid1->endRowActions($actions);

new afExtjsLinkButton($grid1,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));
new afExtjsLinkButton($grid1,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));

$grid1->end();

/**
 * add the grid to the south panel
 */
$layout->addItem('center',$grid1);

$tools=new afExtjsTools();
$tools->addItem(array('id'=>'gear','handler'=>array('source'=>"Ext.Msg.alert('Message', 'The Settings tool was clicked.');")));
$tools->addItem(array('id'=>'help','handler'=>array('parameters'=>'e,target,panel','source'=>"console.log(panel);")));
$tools->addItem(array('id'=>'close','handler'=>array('parameters'=>'e,target,panel','source'=>"panel.ownerCt.remove(panel, true);")));

$layout->addCenterComponent($tools,array('title'=>'Grids'));

$layout->end();

?>