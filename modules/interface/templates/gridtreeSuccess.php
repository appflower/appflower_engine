<?php
$layout=new afExtjsPanelLayout();

/**
 * FIRST GRID
 */
/**
 * USE:
 * pager = true/false => activate/disable pagination
 * stateful = true/false => activate/disable state restore from cookies
 */
$grid=new afExtjsGrid(array('autoHeight'=>true,'root_title'=>'Companies','tree'=>true,'pager'=>false,'select'=>true,'frame'=>false/*,'stateful'=>false*/));
$grid->addHelp('test help');
/**
 * columns
 */
$grid->addColumn(array('name'=>'company','label'=>'Company','sort'=>'ASC','sortable'=>true,'hidden'=>false,'hideable'=>true,'align'=>'left'));
$grid->addColumn(array('name'=>'industry','label'=>'Industry','sortable'=>true));
/**
 * proxy
 * 
 * REMEMBER:
 * stateId attribute must be unique for each view, because with this id Extjs keeps in a cookie the state of start & limit attributes for listjson, see ticket #574; if stateId attribute is not defined, then the state is not kept !
 */
$grid->setProxy(array('url'=>'/interface/jsonactionstree','limit'=>2/*,'stateId'=>'gdtree'*/));
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
$grid->endRowActions($actions);

new afExtjsLinkButton($grid,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));
new afExtjsLinkButton($grid,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));
new afExtjsButton($grid,array('label'=>'Send Selections to some url','handlers'=>array('click'=>array('parameters'=>'field,event','source'=>'Ext.Ajax.request({ url: "/interface/gridtreeJsonButton", method:"post", params:{"selections":'.$grid->privateName.'.getSelectionModel().getSelectionsJSON(["company","industry","_buttonDescription"])}, success:function(response, options){response=Ext.decode(response.responseText);if(response.message){Ext.Msg.alert("Success",response.message);}},failure: function(response,options) {if(response.message){Ext.Msg.alert("Failure",response.message);}}});'))));

$grid->end();

$layout->addItem('center',$grid);


$tools=new afExtjsTools();
$tools->addItem(array('id'=>'gear','handler'=>array('source'=>"Ext.Msg.alert('Message', 'The Settings tool was clicked.');")));
$tools->addItem(array('id'=>'close','handler'=>array('parameters'=>'e,target,panel','source'=>"panel.ownerCt.remove(panel, true);")));

$layout->addCenterComponent($tools,array('title'=>'Tree Grid'));

$layout->end();

?>