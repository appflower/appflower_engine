<?php
$layout=new afExtjsWizardLayout(array('id'=>'center_panel'));

/**
 * display a confirmation dialog, on OK display the content of the layout, else redirect to some page
 */
$layout->attributes['listeners']['beforerender']=$layout->afExtjs->asMethod(array('parameters'=>'el','source'=>"Ext.Msg.confirm('Confirmation','Are you sure you want to proceed?', function(btn){if (btn=='yes'){ return true; }else{ window.location.href='/interface/form';return false;} });
"));

$layout->setTitle('Wizard Step 1');

$tools=new afExtjsTools();
$tools->addItem(array('id'=>'gear','handler'=>array('parameters'=>'e,target,panel','source'=>"console.log(panel);")));
$tools->addItem(array('id'=>'close','handler'=>array('parameters'=>'e,target,panel','source'=>"panel.ownerCt.remove(panel, true);")));

$column1=$layout->startColumn(array('columnWidth'=>'0.99'));

/**
 * A GRID IN THE COLUMN
 */

$grid=new afExtjsGrid(array('autoHeight'=>true,'clearGrouping'=>false,'title'=>'Grid','portal'=>true,'tools'=>$tools));
/**
 * columns
 */
$grid->addColumn(array('name'=>'company','label'=>'Company','sort'=>'ASC','id'=>true,'width'=>40,'sortable'=>true));
$grid->addColumn(array('name'=>'industry','label'=>'Industry','groupField'=>true,'width'=>20,'sortable'=>true));
/**
 * proxy
 */
$grid->setProxy(array('url'=>'/interface/jsonactions','limit'=>3));
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
$grid->endRowActions($actions);

new afExtjsLinkButton($grid,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));
new afExtjsLinkButton($grid,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));

$grid->end();

$column1->addItem($grid);

$layout->endColumn($column1);

new afExtjsLinkButton($layout,array('label'=>'Next (step2)','url'=>'https://192.168.198.129/interface/wizardStep2'));

new afExtjsLinkButton($layout,array('label'=>'Next (step2)','url'=>'https://192.168.198.129/interface/wizardStep2','iconPosition'=>'right'));

$layout->end();

?>