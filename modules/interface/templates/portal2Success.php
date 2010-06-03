<?php
$layout=new ImmExtjsPortalLayout(array('id'=>'center_panel'));

$html='<b>Lorem ipsum dolor sit amet</b>, consectetur adipiscing elit. Ut est neque, feugiat venenatis elementum a, tincidunt non massa. Cras sagittis, augue nec porttitor scelerisque, elit lorem ornare massa, eu euismod odio massa vitae justo. Mauris erat nunc, luctus tincidunt lacinia ac, sagittis id risus. Mauris ut quam nisl. Mauris tortor eros, tincidunt sit amet fringilla lacinia, faucibus vel augue. Sed dolor felis, faucibus nec elementum at, cursus in magna. Nam erat nibh, auctor fermentum convallis id, ornare vitae urna. Ut placerat elementum felis. Donec quis libero mauris, vitae vehicula mauris. Donec sit amet urna id justo tempus aliquam. Duis aliquam gravida dictum. Nullam ac nibh eros. Donec lacinia risus id velit congue sed placerat nibh fringilla. Vivamus condimentum varius lacus et facilisis. Curabitur sed tellus sit amet diam dictum ornare. Donec dui lacus, vehicula sit amet semper a, auctor sed sem. Nam pulvinar iaculis libero sed varius. Quisque volutpat posuere sapien quis condimentum.';

if(sfContext::getInstance()->getUser()->getProfile()->getWidgetHelpIsEnabled())
{
	$layout->addHelp('<b>help text on top of everything</b>');
}

$layout->setTitle('Dashboard');

$tools=new ImmExtjsTools();
$tools->addItem(array('id'=>'gear','handler'=>array('parameters'=>'e,target,panel','source'=>"console.log(panel);")));
$tools->addItem(array('id'=>'close','handler'=>array('parameters'=>'e,target,panel','source'=>"panel.ownerCt.remove(panel, true);")));

$column1=$layout->startColumn(array('columnWidth'=>'0.98'));

/**
 * A GRID IN THE COLUMN
 */
$grid=new ImmExtjsGrid(array('autoHeight'=>true,'clearGrouping'=>false,'title'=>'Grid'/*,'portal'=>true*/,'tools'=>$tools));
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

/**
 * add the grid to the south panel
 */
$layout->addItem('south',$grid);
//$column1->addItem($grid);

/**
 * CUSTOM GRID
 */
$grid=new ImmExtjsGridCustom(array('autoHeight'=>true,'title'=>'Some images','portal'=>true,'tools'=>$tools/*,'stateful'=>false*/));
$grid->addHelp($html);
/**
 * proxy
 * 
 */
$grid->setProxy(array('url'=>'/interface/jsoncustomgrid'));

new ImmExtjsLinkButton($grid,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));
new ImmExtjsLinkButton($grid,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));

$grid->end();

$column1->addItem($grid);

/**
 * A FORM IN THE COLUMN
 */
$form=new ImmExtjsForm(array('action'=>'/interface/test','title'=>'Form','portal'=>true,'tools'=>$tools));
$form->addHelp($html);
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

//$layout->addInitMethodSource("");

$layout->endColumn($column1);

$layout->end();

?>