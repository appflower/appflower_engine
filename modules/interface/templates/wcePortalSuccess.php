<?php
$layout=new ImmExtjsWCEPortalLayout(array('id'=>'center_panel'));

$layout->setTitle('Dashboard');

$layout->addItem('west',array('title'=>'Settings1',
									      		'autoScroll'=>'true',
									      		'border'=>'false',
									      		'iconCls'=>'settings',
									      		'html'=>'test2'      	
									      	));
$layout->addWestComponent(array('title'=>'Navigation West'));

$layout->addItem('east',array('title'=>'Settings1',
									      		'autoScroll'=>'true',
									      		'border'=>'false',
									      		'iconCls'=>'settings',
									      		'html'=>'test2'      	
									      	));
$layout->addEastComponent(array('title'=>'Navigation East'));
									      	
									      	
$tools=new ImmExtjsTools();
$tools->addItem(array('id'=>'gear','handler'=>array('parameters'=>'e,target,panel','source'=>"console.log(panel);")));
$tools->addItem(array('id'=>'close','handler'=>array('parameters'=>'e,target,panel','source'=>"panel.ownerCt.remove(panel, true);")));

$column1=$layout->startColumn(array('columnWidth'=>'0.99'));

/**
 * A GRID IN THE COLUMN
 */

$grid=new ImmExtjsGrid(array('autoHeight'=>true,'clearGrouping'=>false,'title'=>'Grid','portal'=>true,'tools'=>$tools));
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

new ImmExtjsLinkButton($grid,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));
new ImmExtjsLinkButton($grid,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));

$grid->end();

$column1->addItem($grid);

/**
 * A FORM IN THE COLUMN
 */

$form=new ImmExtjsForm(array('action'=>'/interface/test','title'=>'Form','portal'=>true,'tools'=>$tools));

$fieldset=$form->startFieldset(array('legend'=>'Fieldset 1'));

$input=new ImmExtjsFieldInput($fieldset,array('name'=>'my_name','label'=>'My Name','value'=>'Radu','help'=>"'+field.value+'",'comment'=>'comment on the upper field','handlers'=>array('change'=>array('parameters'=>'field','source'=>'alert(field.value);'))));

$textarea=new ImmExtjsFieldTextarea($fieldset,array('name'=>'my_textarea','label'=>'My Textarea','value'=>'textarea','comment'=>'my textarea comment','rich'=>true));

$password=new ImmExtjsFieldPassword($fieldset,array('name'=>'my_pass','label'=>'My Pass','value'=>'Radu','help'=>"password",'comment'=>'comment on the upper field'));

$form->endFieldset($fieldset);

new ImmExtjsSubmitButton($form,array('action'=>'/interface/test'));

new ImmExtjsResetButton($form);

new ImmExtjsButton($form,array('label'=>'Just a normal button','handlers'=>array('click'=>array('parameters'=>'field,event','source'=>'alert(field.name);'))));

new ImmExtjsLinkButton($form,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));

new ImmExtjsLinkButton($form,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));

$form->end();

$column1->addItem($form);
                    	
$column1->addItem(array('title'=> 'Panel 1',
                    	'tools'=> $tools,
                    	'html'=> 'test'));


$layout->endColumn($column1);

$layout->end();

?>