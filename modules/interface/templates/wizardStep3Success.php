<?php
/**
 * ticket #1001
 * 
 * first, add 'centerType'=>'group' to wizard layout
 */
$layout=new ImmExtjsWizardLayout(array('id'=>'center_panel','centerType'=>'group'));

/**
 * display a confirmation dialog, on OK display the content of the layout, else redirect to some page
 */
/*$layout->attributes['listeners']['beforerender']=$layout->immExtjs->asMethod(array('parameters'=>'el','source'=>"Ext.Msg.confirm('Confirmation','Are you sure you want to proceed?', function(btn){if (btn=='yes'){ return true; }else{ window.location.href='/interface/form';return false;} });
"));*/

$layout->setTitle('Wizard Step 3');

$tools=new ImmExtjsTools();
$tools->addItem(array('id'=>'gear','handler'=>array('parameters'=>'e,target,panel','source'=>"console.log(panel);")));
$tools->addItem(array('id'=>'close','handler'=>array('parameters'=>'e,target,panel','source'=>"panel.ownerCt.remove(panel, true);")));

$group1=$layout->startGroup();

/**
 * A GRID IN THE COLUMN
 */

$grid=new ImmExtjsGrid(array('autoHeight'=>true,'clearGrouping'=>true,'title'=>'Grid'));
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

//new ImmExtjsLinkButton($grid,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));
//new ImmExtjsLinkButton($grid,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));

$grid->end();

/**
 * A GRID 2 IN THE COLUMN
 */

$grid2=new ImmExtjsGrid(array('autoHeight'=>true,'clearGrouping'=>true,'title'=>'Grid2'));
/**
 * columns
 */
$grid2->addColumn(array('name'=>'company','label'=>'Company','sort'=>'ASC','id'=>true,'width'=>40,'sortable'=>true));
$grid2->addColumn(array('name'=>'industry','label'=>'Industry','groupField'=>true,'width'=>20,'sortable'=>true));
/**
 * proxy
 */
$grid2->setProxy(array('url'=>'/interface/jsonactions','limit'=>3));
/**
 * row actions
 */
$actions=$grid2->startRowActions(/*array('header'=>'Actions')*/);
/**
 * action1
 */
$actions->addAction(array('iconCls'=>'icon-edit-record','tooltip'=>'Edit'/*,'text'=>'edit','style'=>''*/));
/**
 * action2
 */
$actions->addAction(array('iconCls'=>'icon-minus','tooltip'=>'Delete'/*,'text'=>'Delete','style'=>''*/));
$grid2->endRowActions($actions);

new ImmExtjsLinkButton($grid2,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));
new ImmExtjsLinkButton($grid2,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));

$grid2->end();

$group1->addItem($grid,array('title'=>'First grid','tabTip'=>'First grid'));
$group1->addItem($grid2,array('title'=>'Second grid','tabTip'=>'Second grid'));

$layout->endGroup($group1);


/**
 * start a second group
 */

$group2=$layout->startGroup();

/**
 * A GRID 3 IN THE COLUMN
 */

$grid3=new ImmExtjsGrid(array('autoHeight'=>true,'clearGrouping'=>true,'title'=>'Grid 3'));
/**
 * columns
 */
$grid3->addColumn(array('name'=>'company','label'=>'Company','sort'=>'ASC','id'=>true,'width'=>40,'sortable'=>true));
$grid3->addColumn(array('name'=>'industry','label'=>'Industry','groupField'=>true,'width'=>20,'sortable'=>true));
/**
 * proxy
 */
$grid3->setProxy(array('url'=>'/interface/jsonactions','limit'=>3));
/**
 * row actions
 */
$actions=$grid3->startRowActions(/*array('header'=>'Actions')*/);
/**
 * action1
 */
$actions->addAction(array('iconCls'=>'icon-edit-record','tooltip'=>'Edit'/*,'text'=>'edit','style'=>''*/));
/**
 * action2
 */
$actions->addAction(array('iconCls'=>'icon-minus','tooltip'=>'Delete'/*,'text'=>'Delete','style'=>''*/));
$grid3->endRowActions($actions);

new ImmExtjsLinkButton($grid3,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));
new ImmExtjsLinkButton($grid3,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));

$grid3->end();

$group2->addItem($grid3,array('title'=>'Third grid','tabTip'=>'Third grid'));

$layout->endGroup($group2);

new ImmExtjsLinkButton($layout,array('label'=>'Next (step2)','url'=>'https://192.168.198.136/interface/wizardStep2'));

new ImmExtjsLinkButton($layout,array('label'=>'Next (step2)','url'=>'https://192.168.198.136/interface/wizardStep2','iconPosition'=>'right'));

$layout->end();

?>