<?php
$layout=new ImmExtjsPanelLayout();

/**
 * FIRST GRID
 */

$tools=new ImmExtjsTools();
$tools->addItem(array('id'=>'gear','handler'=>array('source'=>"Ext.Msg.alert('Message', 'The Settings tool was clicked.');")));
$tools->addItem(array('id'=>'help','handler'=>array('parameters'=>'e,target,panel','source'=>"afApp.loadPopupHelp(panel.idxml);")));
$tools->addItem(array('id'=>'close','handler'=>array('parameters'=>'e,target,panel','source'=>"panel.ownerCt.remove(panel, true);")));


$grid=new ImmExtjsGrid(array('autoHeight'=>true,'clearGrouping'=>true,'title'=>'Title','frame'=>false,'idxml'=>'/interface/grid','remoteSort'=>true,'tools'=>$tools));
//$grid->addHelp('<b>Lorem ipsum dolor sit amet</b>, consectetur adipiscing elit. Ut est neque, feugiat venenatis elementum a, tincidunt non massa. Cras sagittis, augue nec porttitor scelerisque, elit lorem ornare massa, eu euismod odio massa vitae justo. Mauris erat nunc, luctus tincidunt lacinia ac, sagittis id risus. Mauris ut quam nisl. Mauris tortor eros, tincidunt sit amet fringilla lacinia, faucibus vel augue. Sed dolor felis, faucibus nec elementum at, cursus in magna. Nam erat nibh, auctor fermentum convallis id, ornare vitae urna. Ut placerat elementum felis. Donec quis libero mauris, vitae vehicula mauris. Donec sit amet urna id justo tempus aliquam. Duis aliquam gravida dictum. Nullam ac nibh eros. Donec lacinia risus id velit congue sed placerat nibh fringilla. Vivamus condimentum varius lacus et facilisis. Curabitur sed tellus sit amet diam dictum ornare. Donec dui lacus, vehicula sit amet semper a, auctor sed sem. Nam pulvinar iaculis libero sed varius. Quisque volutpat posuere sapien quis condimentum.');
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

new ImmExtjsLinkButton($grid,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));
new ImmExtjsLinkButton($grid,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));

$grid->end();

//$layout->addItem('center',$grid);

/**
 * ticket #1180
 */
$layout->setSouthTitleAndStyle('south title test','font-weight:bold;color:#cc0000;border:1px solid #cc0000;background-color:#cccc00;');

$html='<b>Lorem ipsum dolor sit amet</b>, consectetur adipiscing elit. Ut est neque, feugiat venenatis elementum a, tincidunt non massa. Cras sagittis, augue nec porttitor scelerisque, elit lorem ornare massa, eu euismod odio massa vitae justo. Mauris erat nunc, luctus tincidunt lacinia ac, sagittis id risus. Mauris ut quam nisl. Mauris tortor eros, tincidunt sit amet fringilla lacinia, faucibus vel augue. Sed dolor felis, faucibus nec elementum at, cursus in magna. Nam erat nibh, auctor fermentum convallis id, ornare vitae urna. Ut placerat elementum felis. Donec quis libero mauris, vitae vehicula mauris. Donec sit amet urna id justo tempus aliquam. Duis aliquam gravida dictum. Nullam ac nibh eros. Donec lacinia risus id velit congue sed placerat nibh fringilla. Vivamus condimentum varius lacus et facilisis. Curabitur sed tellus sit amet diam dictum ornare. Donec dui lacus, vehicula sit amet semper a, auctor sed sem. Nam pulvinar iaculis libero sed varius. Quisque volutpat posuere sapien quis condimentum.';

if(afWidgetHelpSettingsPeer::retrieveCurrent()->getWidgetHelpIsEnabled())
{
$layout->addHelp($html);
}

/**
* WEST PANEL TREE WITH FILTERS
*/
$filters=new ImmExtjsTree(array('title'=>'Filters','iconCls'=>'server'));
$root_node=$filters->startRoot(array('text'=>'Filters'));
$child=$root_node->addChild(array('text'=>'Incident Reports'));
$child1=$child->addChild(array('text'=>'Active','href'=>'https://192.168.80.128/incidents/list/filter/filter/report/active'));$child1->end();
$child->end();
$child=$root_node->addChild(array('text'=>'My Incidents'));
$child1=$child->addChild(array('text'=>'Assigned','href'=>'https://192.168.80.128/incidents/list?filters%5Bowner_id%5D=2&filter=filter&report=active'));$child1->end();
$child->end();
$filters->endRoot($root_node);
$filters->end();

/**
* ADD THE FILTERS TO THE WEST REGION
*/
$layout->addItem('west',$filters);

/**
* GRID TREE FOR WEST PANEL
* ticket #659 START
*/
/**
* USE:
* pager = true/false => activate/disable pagination
* stateful = true/false => activate/disable state restore from cookies
*/
//$grid=new ImmExtjsGrid(array('iconCls'=>'server','autoHeight'=>false,'title'=>'Tree Grid','root_title'=>'Companies','tree'=>true,'pager'=>false,'frame'=>false));
/**
* columns
*/
//$grid->addColumn(array('name'=>'company','label'=>'Company','sort'=>'ASC','sortable'=>true,'hidden'=>false,'hideable'=>true));
/**
* proxy
*
* REMEMBER:
* stateId attribute must be unique for each view, because with this id Extjs keeps in a cookie the state of start & limit attributes for listjson, see ticket #574; if stateId attribute is not defined, then the state is not kept !
*/
//$grid->setProxy(array('url'=>'/interface/jsonactionstree','limit'=>2/*,'stateId'=>'gdtree'*/));

//$grid->end();

//$layout->addItem('west',$grid);
/**
* ticket #659 END
*/


$layout->addItem('west',array('title'=>'Settings1',
'autoScroll'=>'true',
'border'=>'false',
'iconCls'=>'settings',
'html'=>'test2'      	
));

/**
 * SOME DOUBLE TREE DATA
 */
$double_tree_options=array(array("text"=>"Group 1","value"=>"G1","leaf"=>false,"iconCls"=>"folder","children"=>array(array("text"=>"Item1","value"=>"item 1","leaf"=>true,"iconCls"=>"file"),array("text"=>"Item2","value"=>"item 2","leaf"=>true,"iconCls"=>"file"))),array("text"=>"Group 2","value"=>"G2","leaf"=>false,"iconCls"=>"folder","children"=>array(array("text"=>"Item3","value"=>"item 3","leaf"=>true,"iconCls"=>"file"),array("text"=>"Item4","value"=>"item 4","leaf"=>true,"iconCls"=>"file"),array("text"=>"Item5","value"=>"item 5","leaf"=>true,"iconCls"=>"file"))));

$double_tree_selected=array(array("text"=>"Group 1","value"=>"G1","leaf"=>false,"iconCls"=>"folder","children"=>array(array("text"=>"Item11","value"=>"item 11","leaf"=>true,"iconCls"=>"file"))));


/**
 * WINDOW FORM
 */

$form0=new ImmExtjsForm(array('action'=>'/interface/test','frame'=>true));

$fieldset=$form0->startFieldset(array('legend'=>'Fieldset from second form'));

new ImmExtjsFieldDoubleTree($fieldset,array('name'=>'my_double_tree','label'=>'My double tree','help'=>'test help','comment'=>'comment on double tree','state'=>'editable','fromLegend'=>'Options grouped','toLegend'=>'Selected grouped','options'=>$double_tree_options,'selected'=>$double_tree_selected));

new ImmExtjsFieldInput($fieldset,array('name'=>'my_name','label'=>'My Name','value'=>'RaduX','help'=>"'+field.value+'",'comment'=>'comment on the upper field','handlers'=>array('change'=>array('parameters'=>'field','source'=>'alert(field.value);'))));

new ImmExtjsFieldMultiCombo($fieldset,array('name'=>'my_multi_combo','label'=>'My multi combo','help'=>"multi combo box",'comment'=>'comment for multi combo','options'=>array('1'=>'Value 1','2'=>'Value 2','3'=>'Value 3'),'selected'=>array('1','2'),'state'=>'editable','clear'=>true));

$form0->endFieldset($fieldset);

new ImmExtjsSubmitButton($form0,array('action'=>'/interface/test'));

new ImmExtjsResetButton($form0);

new ImmExtjsButton($form0,array('label'=>'Just a normal button','handlers'=>array('click'=>array('parameters'=>'field,event','source'=>'alert(field.name);'))));

new ImmExtjsLinkButton($form0,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));

new ImmExtjsLinkButton($form0,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));

$form0->end();

/**
 * FIRST FORM
 */

$form=new ImmExtjsForm(array(/*'labelAlign'=> 'top',*/'action'=>'/interface/test'/*,'fileUpload'=>true*/));
//$form->addHelp($html);


/**
 * COLUMNS IN FORM START, ticket #1002
 */
$columns=$form->startColumns();

/**
 * one half column
 */
$column1=$columns->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($column1,array('name'=>'my_datec1','label'=>'My Date 1','value'=>'','comment'=>'comment on the upper field','type'=>'date','helpType'=>'inline'));

$columns->endColumn($column1);

/**
 * one half column
 */
$column2=$columns->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($column2,array('name'=>'my_datec2','label'=>'My Date 2','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columns->endColumn($column2);

/**
 * one full column
 */
$column3=$columns->startColumn(array('columnWidth'=>1,'labelAlign'=> 'top'));

new ImmExtjsFieldTextarea($column3,array('name'=>'my_textareac3','label'=>'My Textarea','value'=>'textarea','comment'=>'my textarea comment','rich'=>false));

$columns->endColumn($column3);

/**
 * one half column
 */
$column4=$columns->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($column4,array('name'=>'my_datec3','label'=>'My Date 3','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columns->endColumn($column4);

/**
 * one half column
 */
$column5=$columns->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($column5,array('name'=>'my_datec4','label'=>'My Date 4','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columns->endColumn($column5);

$form->endColumns($columns);

/**
 * COLUMNS IN FORM END, ticket #1002
 */

/**
 * COLUMNS IN FORM FIELDSET START, ticket #1002
 */
$fieldsetC=$form->startFieldset(array('legend'=>'Fieldset With Columns','collapsed'=>false));
$columns=$fieldsetC->startColumns();

/**
 * one half column
 */
$column1=$columns->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($column1,array('name'=>'my_datec1','label'=>'My Date 1','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columns->endColumn($column1);

/**
 * one half column
 */
$column2=$columns->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($column2,array('name'=>'my_datec2','label'=>'My Date 2','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columns->endColumn($column2);

/**
 * one full column
 */
$column3=$columns->startColumn(array('columnWidth'=>1,'labelAlign'=> 'top'));

new ImmExtjsFieldTextarea($column3,array('name'=>'my_textareac3','label'=>'My Textarea','value'=>'textarea','comment'=>'my textarea comment','rich'=>false));

$columns->endColumn($column3);

/**
 * one half column
 */
$column4=$columns->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($column4,array('name'=>'my_datec3','label'=>'My Date 3','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columns->endColumn($column4);

/**
 * one half column
 */
$column5=$columns->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($column5,array('name'=>'my_datec4','label'=>'My Date 4','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columns->endColumn($column5);

$fieldsetC->endColumns($columns);

$form->endFieldset($fieldsetC);
/**
 * COLUMNS IN FORM FIELDSET END, ticket #1002
 */

/**
 * TABS IN FORM START, ticket #1003
 */
$tabs=$form->startTabs();

$tab1=$tabs->startTab(array('title'=>'Tab 1'));

new ImmExtjsFieldDateTime($tab1,array('name'=>'my_datet1','label'=>'My Date t1','value'=>'','comment'=>'comment on the upper field','type'=>'date','helpType'=>'inline'));

new ImmExtjsFieldInput($tab1,array('name'=>'my_namet1','label'=>'My Name t1','value'=>'Radu','help'=>"'+field.value+'",'comment'=>'comment on the upper field','handlers'=>array('change'=>array('parameters'=>'field','source'=>'alert(field.value);'))));

$checkboxgroup=$tab1->startGroup('checkbox',array('label'=>'My checkbox group','comment'=>'comment for checkbox group','help'=>'help tooltip on the checkbox'));

$checkbox1=new ImmExtjsFieldCheckbox($checkboxgroup,array('name'=>'check1','label'=>'Check 1','value'=>'1','checked'=>true));
$checkbox2=new ImmExtjsFieldCheckbox($checkboxgroup,array('name'=>'checkbox2','label'=>'Check 2','value'=>'2'));

$tab1->endGroup($checkboxgroup);

$radiogroup=$tab1->startGroup('radio',array('label'=>'My radio group','comment'=>'comment for radio group','help'=>'help tooltip on the radio group'));

$radio1=new ImmExtjsFieldRadio($radiogroup,array('name'=>'radio','label'=>'Value = 1','value'=>'1','checked'=>true));
$radio2=new ImmExtjsFieldRadio($radiogroup,array('name'=>'radio','label'=>'Value = 2','value'=>'2'));

$tab1->endGroup($radiogroup);

new ImmExtjsFieldInput($tab1,array('name'=>'my_namet1','label'=>'My Name t1','value'=>'Radu','help'=>"'+field.value+'",'comment'=>'comment on the upper field','handlers'=>array('change'=>array('parameters'=>'field','source'=>'alert(field.value);'))));

new ImmExtjsFieldDateTime($tab1,array('name'=>'my_datet1','label'=>'My Date t1','value'=>'','comment'=>'comment on the upper field','type'=>'date'));


/**
 * COLUMNS IN TAB1 START, ticket #1002,#1003
 */
$columns=$tab1->startColumns();

/**
 * one half column
 */
$column1=$columns->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($column1,array('name'=>'my_datec1','label'=>'My Date 1','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columns->endColumn($column1);

/**
 * one half column
 */
$column2=$columns->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($column2,array('name'=>'my_datec2','label'=>'My Date 2','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columns->endColumn($column2);

/**
 * one full column
 */
$column3=$columns->startColumn(array('columnWidth'=>1,'labelAlign'=> 'top'));

new ImmExtjsFieldTextarea($column3,array('name'=>'my_textareac3','label'=>'My Textarea','value'=>'textarea','comment'=>'my textarea comment','rich'=>false));

$columns->endColumn($column3);

/**
 * one half column
 */
$column4=$columns->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($column4,array('name'=>'my_datec3','label'=>'My Date 3','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columns->endColumn($column4);

/**
 * one half column
 */
$column5=$columns->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($column5,array('name'=>'my_datec4','label'=>'My Date 4','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columns->endColumn($column5);

$tab1->endColumns($columns);

/**
 * COLUMNS IN TAB1 END, ticket #1002,#1003
 */

$tabs->endTab($tab1);

$tab2=$tabs->startTab(array('title'=>'Tab 2'));

new ImmExtjsFieldInput($tab2,array('name'=>'my_namet2','label'=>'My Name t2','value'=>'Radu','help'=>"'+field.value+'",'comment'=>'comment on the upper field','handlers'=>array('change'=>array('parameters'=>'field','source'=>'alert(field.value);'))));

/**
 * columns inside a tab
 */
$columnsx=$tab2->startColumns();

/**
 * one half column
 */
$columnx1=$columnsx->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($columnx1,array('name'=>'my_datexc1','label'=>'My Date 1','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columnsx->endColumn($columnx1);

/**
 * one half column
 */
$columnx2=$columnsx->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($columnx2,array('name'=>'my_datexc2','label'=>'My Date 2','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columnsx->endColumn($columnx2);

$tab2->endColumns($columnsx);

/**
 * adding grid to tab
 */
$tab2->addMember($grid);

$tabs->endTab($tab2);

$form->endTabs($tabs);

/**
 * TABS IN FORM END, ticket #1003
 */

/**
 * TABS IN FORM with FIELDSET INSIDE START, ticket #1003
 */

$tabs=$form->startTabs();

$tab1=$tabs->startTab(array('title'=>'Tab 1'));

$fieldsett1=$tab1->startFieldset(array('legend'=>'Fieldset Tab 1','collapsed'=>false));

$columns=$fieldsett1->startColumns();

new ImmExtjsFieldDateTime($fieldsett1,array('name'=>'my_datet1','label'=>'My Date t1','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

new ImmExtjsFieldInput($fieldsett1,array('name'=>'my_namet1','label'=>'My Name t1','value'=>'Radu','help'=>"'+field.value+'",'comment'=>'comment on the upper field','handlers'=>array('change'=>array('parameters'=>'field','source'=>'alert(field.value);'))));

$radiogroup=$fieldsett1->startGroup('radio',array('label'=>'My radio group','comment'=>'comment for radio group','help'=>'help tooltip on the radio group'));

$radio1=new ImmExtjsFieldRadio($radiogroup,array('name'=>'radio','label'=>'Value = 1','value'=>'1','checked'=>true));
$radio2=new ImmExtjsFieldRadio($radiogroup,array('name'=>'radio','label'=>'Value = 2','value'=>'2'));

$fieldsett1->endGroup($radiogroup);

/**
 * columns inside a fieldset's tab
 */


/**
 * one half column
 */
$column1=$columns->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($column1,array('name'=>'my_datec1','label'=>'My Date 1','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columns->endColumn($column1);

/**
 * one half column
 */
$column2=$columns->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($column2,array('name'=>'my_datec2','label'=>'My Date 2','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columns->endColumn($column2);

/**
 * one full column
 */
$column3=$columns->startColumn(array('columnWidth'=>1,'labelAlign'=> 'top'));

new ImmExtjsFieldTextarea($column3,array('name'=>'my_textareac3','label'=>'My Textarea','value'=>'textarea','comment'=>'my textarea comment','rich'=>false));

$columns->endColumn($column3);

/**
 * one half column
 */
$column4=$columns->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($column4,array('name'=>'my_datec3','label'=>'My Date 3','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columns->endColumn($column4);

/**
 * one half column
 */
$column5=$columns->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($column5,array('name'=>'my_datec4','label'=>'My Date 4','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columns->endColumn($column5);

$fieldsett1->endColumns($columns);

/**
 * END columns inside a fieldset's tab
 */


$tab1->endFieldset($fieldsett1);

$tabs->endTab($tab1);

$tab2=$tabs->startTab(array('title'=>'Tab 2 Tamas'));

$fieldsett2=$tab2->startFieldset(array('legend'=>'Fieldset Tab 2','collapsed'=>false));

/**
 * columns inside a fieldset's tab
 */
$columnsft2=$fieldsett2->startColumns();


/**
 * one half column - ADDED BY TAMAS -
 */
$columnft99=$columnsft2->startColumn(array('labelAlign'=> 'top'));

$combo=new ImmExtjsFieldCombo($columnft99,array('name'=>'my_combo_button','label'=>'My combo button Tamas','help'=>"combo box with button",'comment'=>'comment for combo w button','options'=>array('a'=>'Value A','b'=>'Value B'),'selected'=>'b','button'=>array('text'=>'Trigger','icon'=>'/images/famfamfam/cancel.png'),'window'=>array('title'=>'Window Title','component'=>"test/popup",'className'=>'ServerPeer','methodName'=>'getAllAsOptions')));

$columnsft2->endColumn($columnft99);


/**
 * one half column
 */
$columnft100=$columnsft2->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($columnft100,array('name'=>'my_datet1','label'=>'My Date t1','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columnsft2->endColumn($columnft100);


/* - TAMAS END - */


/**
 * one full column
 */
$columnft20=$columnsft2->startColumn(array('columnWidth'=>1,'labelAlign'=> 'top'));


/* COMMENT THIS! */ 

new ImmExtjsFieldDateTime($columnft20,array('name'=>'my_datet1','label'=>'My Date t1','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

/* COMMENT THIS! */

$columnsft2->endColumn($columnft20);

/**
 * one half column
 */
$columnft21=$columnsft2->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($columnft21,array('name'=>'my_dateft2c1','label'=>'My Date 1','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columnsft2->endColumn($columnft21);

/**
 * one half column
 */
$columnft22=$columnsft2->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($columnft22,array('name'=>'my_dateft2c2','label'=>'My Date 2','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columnsft2->endColumn($columnft22);

/**
 * one full column
 */
$columnft23=$columnsft2->startColumn(array('columnWidth'=>1,'labelAlign'=> 'top'));

new ImmExtjsFieldTextarea($columnft23,array('name'=>'my_textareaft2c3','label'=>'My Textarea','value'=>'textarea','comment'=>'my textarea comment','rich'=>false));

$columnsft2->endColumn($columnft23);

/**
 * one half column
 */
$columnft24=$columns->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($columnft24,array('name'=>'my_dateft2c3','label'=>'My Date 3','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columnsft2->endColumn($columnft24);

/**
 * one half column
 */
$columnft25=$columnsft2->startColumn(array('labelAlign'=> 'top'));

new ImmExtjsFieldDateTime($columnft25,array('name'=>'my_dateft2c4','label'=>'My Date 4','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$columnsft2->endColumn($columnft25);

$fieldsett2->endColumns($columnsft2);

/**
 * END columns inside a fieldset's tab
 */


$tab2->endFieldset($fieldsett2);

$tabs->endTab($tab2);

$form->endTabs($tabs);

/**
 * TABS IN FORM with FIELDSET INSIDE END, ticket #1003
 */

$fieldset=$form->startFieldset(array('legend'=>'Fieldset 1','collapsed'=>false));

/**
 * combo with button, NEW CUSTOM WIDGET FIELD, complex behaviour
 * Explanations:
 * button:text => the button text
 * button:icon => the button icon
 *
 * window => the window that appears on button click
 * window:title => the window title
 * window:component => the window inner component, here is a form
 * window:class => the model class from where to retrieve new data for combo, after window close
 * window:method => the model method from where to retrieve new data for combo, after window close
 */
//$combo=new ImmExtjsFieldCombo($fieldset,array('name'=>'my_combo_button','label'=>'My combo button','help'=>"combo box with button",'comment'=>'comment for combo w button','options'=>array('a'=>'Value A','b'=>'Value B'),'selected'=>'b','button'=>array('text'=>'Trigger','icon'=>'/images/famfamfam/cancel.png'),'window'=>array('title'=>'Window Title','component'=>$form0,'className'=>'ServerPeer','methodName'=>'getAllAsOptions')));

/**
 * combo with autocomplete
 */
$combo_auto=new ImmExtjsFieldCombo($fieldset,array('name'=>'my_combo_autocomplete','label'=>'My autocomplete combo','help'=>"combo box",'comment'=>'comment for combo','proxy'=>array('fields'=>array('var_name'),'selectedIndex'=>'var_name','url'=>'/interface/jsonAutocomplete','limit'=>10,'minChars'=>3),'template'=>'<h3>{var_name}</h3>'));

$date=new ImmExtjsFieldDateTime($fieldset,array('name'=>'my_date','label'=>'My Date','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$dayplus=new ImmExtjsFieldDateTime($fieldset,array('name'=>'my_day','label'=>'My day','value'=>'','comment'=>'comment on the upper field','type'=>'dayplus','url'=>'/interface/form?date_interval='));

$weekplus=new ImmExtjsFieldDateTime($fieldset,array('name'=>'my_week','label'=>'My week','value'=>'','comment'=>'comment on the upper field','type'=>'weekplus','url'=>'/interface/form?date_interval='));

$monthplus=new ImmExtjsFieldDateTime($fieldset,array('name'=>'my_month', 'value'=>'01/06/2008,02/06/2009','label'=>'My month','comment'=>'comment on the upper field','type'=>'monthplus','url'=>'/interface/form?date_interval='));

/**
 * STATIC FIELD, you can add html to the value; if you want to submit the value add attribute submitValue:true
 */
$static=new ImmExtjsFieldStatic($fieldset,array('name'=>'my_static','label'=>'My static','value'=>'<img src="/images/famfamfam/accept.png" border="0"> <b>test</b>','comment'=>'comment on the upper field','submitValue'=>false));

$datetime=new ImmExtjsFieldDateTime($fieldset,array('name'=>'my_datetime','label'=>'My DateTime','value'=>'','comment'=>'comment on the upper field','type'=>'datetime'));

$double_multicombo=new ImmExtjsFieldDoubleMultiCombo($fieldset,array('name'=>'my_double_multi_combo','label'=>'My double multi combo','help'=>"double multi combo box",'comment'=>'comment for double multi combo','options'=>array('1'=>'Value 1','2'=>'Value 2','3'=>'Value 3'),'selected'=>array('4'=>'Value 4'),'state'=>'editable','clear'=>true,'fromLegend'=>'Options','toLegend'=>'Selected'));

$double_tree=new ImmExtjsFieldDoubleTree($fieldset,array('name'=>'my_double_tree','label'=>'My double tree','help'=>'test help','comment'=>'comment on double tree','state'=>'editable','fromLegend'=>'Options grouped','toLegend'=>'Selected grouped','options'=>$double_tree_options,'selected'=>$double_tree_selected));

$input=new ImmExtjsFieldInput($fieldset,array('name'=>'my_name','label'=>'My Name','value'=>'Radu','help'=>"'+field.value+'",'comment'=>'comment on the upper field','handlers'=>array('change'=>array('parameters'=>'field','source'=>'alert(field.value);'))));

$hidden=new ImmExtjsFieldHidden($fieldset,array('name'=>'my_hidden','value'=>'hiddenRadu'));

$textarea=new ImmExtjsFieldTextarea($fieldset,array('name'=>'my_textarea','label'=>'My Textarea','value'=>'textarea','comment'=>'my textarea comment','rich'=>false));

$password=new ImmExtjsFieldPassword($fieldset,array('name'=>'my_pass','label'=>'My Pass','value'=>'Radu','help'=>"password",'comment'=>'comment on the upper field'));

$file=new ImmExtjsFieldFile($fieldset,array('name'=>'my_file','label'=>'My File','help'=>"file",'comment'=>'comment file on the upper field'));

$form->endFieldset($fieldset);

$fieldset1=$form->startFieldset(array('legend'=>'Fieldset 2'));

/**
 * CHANGED TO a and b values, to see that selected works fine
 */
/**
 * combo with color in options
 */
$combo=new ImmExtjsFieldCombo($fieldset1,array('name'=>'my_combo','label'=>'My combo','help'=>"combo box",'comment'=>'comment for combo','options'=>array('a'=>array('text'=>'Value A','color'=>'#F8A281'),'b'=>array('text'=>'Value B','color'=>'#F8A285')),'selected'=>'b'));

/**
 * combo without color in options
 */
$combo=new ImmExtjsFieldCombo($fieldset1,array('name'=>'my_combo','label'=>'My combo','help'=>"combo box",'comment'=>'comment for combo','options'=>array('a'=>'Value A','b'=>'Value B'),'selected'=>'b'));

$radiogroup=$fieldset1->startGroup('radio',array('label'=>'My radio group','comment'=>'comment for radio group','help'=>'help tooltip on the radio group'));

$radio1=new ImmExtjsFieldRadio($radiogroup,array('name'=>'radio','label'=>'Value = 1','value'=>'1','checked'=>true));
$radio2=new ImmExtjsFieldRadio($radiogroup,array('name'=>'radio','label'=>'Value = 2','value'=>'2'));

$fieldset1->endGroup($radiogroup);

$checkboxgroup=$form->startGroup('checkbox',array('label'=>'My checkbox group','comment'=>'comment for checkbox group','help'=>'help tooltip on the checkbox'));

$checkbox1=new ImmExtjsFieldCheckbox($checkboxgroup,array('name'=>'check1','label'=>'Check 1','value'=>'1','checked'=>true));
$checkbox2=new ImmExtjsFieldCheckbox($checkboxgroup,array('name'=>'checkbox2','label'=>'Check 2','value'=>'2'));

$form->endGroup($checkboxgroup);

$checkbox3=new ImmExtjsFieldCheckbox($fieldset1,array('name'=>'check3','label'=>'Check 3','value'=>'3','checked'=>true));

/**
 * CHANGED TO a, b and c values, to see that selected works fine
 */
$multicombo=new ImmExtjsFieldMultiCombo($fieldset1,array('name'=>'my_multi_combo','label'=>'My multi combo','help'=>"multi combo box",'comment'=>'comment for multi combo','options'=>array('a'=>'Value A','b'=>'Value B','c'=>'Value C'),'selected'=>array('b','c'),'state'=>'editable','clear'=>true));

$form->endFieldset($fieldset1);

/**
 * redirect attribute for ticket #770
 *
 * additional attribute: timeout, sets the submit timeout action in milisecs, default to 300000ms(300s)
 */
new ImmExtjsSubmitButton($form,array('action'=>'/interface/test'/*,'params'=>array('redirect'=>'/interface/grid','message'=>"You\'ll be redirected to grid !")*//*,'timeout'=>'300000'*/));

new ImmExtjsResetButton($form);

new ImmExtjsButton($form,array('label'=>'Just a normal button','handlers'=>array('click'=>array('parameters'=>'field,event','source'=>'alert(field.name);'))));

new ImmExtjsLinkButton($form,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));

new ImmExtjsLinkButton($form,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));

/**
 * create a window
 */
$win=new ImmExtjsWindow(array('title'=>'Second Form Popup'));

new ImmExtjsButton($form,array('label'=>'Trigger PopUp','handlers'=>array('click'=>array('parameters'=>'field,event','source'=>$win->privateName.'.show(field);'))));

/**
 * create an Updater widget
 *
 * url: from where to read
 *
 * DEFAULTS:
 * errors: you don't even need to edit those, cause those are the defaults ones
 * interval: the interval in miliseconds to read the current request, you don't even need to edit that, cause that is the default one
 * timeout: the timeout for current request in miliseconds to read the current request, you don't even need to edit that, cause that is the default one
 */
$updater=new ImmExtjsUpdater(array('url'=>'/interface/testUpdater','width'=>'500'/*,'errors'=>array('title'=>'Error','noStep'=>'There is an error in the Updater! No step defined !')*/,'interval'=>'100'/*,'timeout'=>'300000'*/));

new ImmExtjsButton($form,array('label'=>'Trigger Updater','handlers'=>array('click'=>array('parameters'=>'field,event','source'=>$updater->privateName.'.start();'))));

$form->end();

$layout->addItem('center',$form);

/**
 * SECOND FORM
 */

$form1=new ImmExtjsForm(array('action'=>'/interface/test','frame'=>true));

$fieldset=$form1->startFieldset(array('legend'=>'Fieldset from second form'));

new ImmExtjsFieldDoubleTree($fieldset,array('name'=>'my_double_tree','label'=>'My double tree','help'=>'test help','comment'=>'comment on double tree','state'=>'editable','fromLegend'=>'Options grouped','toLegend'=>'Selected grouped','options'=>$double_tree_options,'selected'=>$double_tree_selected));

new ImmExtjsFieldInput($fieldset,array('name'=>'my_name','label'=>'My Name','value'=>'Radu','help'=>"'+field.value+'",'comment'=>'comment on the upper field','handlers'=>array('change'=>array('parameters'=>'field','source'=>'alert(field.value);'))));

new ImmExtjsFieldMultiCombo($fieldset,array('name'=>'my_multi_combo','label'=>'My multi combo','help'=>"multi combo box",'comment'=>'comment for multi combo','options'=>array('1'=>'Value 1','2'=>'Value 2','3'=>'Value 3'),'selected'=>array('1','2'),'state'=>'editable','clear'=>true));

$form1->endFieldset($fieldset);

new ImmExtjsSubmitButton($form1,array('action'=>'/interface/test'));

new ImmExtjsResetButton($form1);

new ImmExtjsButton($form1,array('label'=>'Just a normal button','handlers'=>array('click'=>array('parameters'=>'field,event','source'=>'alert(field.name);'))));

new ImmExtjsLinkButton($form1,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));

new ImmExtjsLinkButton($form1,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));

new ImmExtjsButton($form1,array('label'=>'Json Trigger','handlers'=>array('click'=>array('parameters'=>'field,event','source'=>'Ext.Ajax.request({ url: "/interface/formJsonButton", method:"post", success:function(response, options){response=Ext.decode(response.responseText);if(response.message){Ext.Msg.alert("Success",response.message);}},failure: function(response,options) {if(response.message){Ext.Msg.alert("Failure",response.message);}}});'))));

$form1->end();

/**
 * add $form1 to window and end window object
 */
$win->addItem($form1);
$win->end();

//$layout->addItem('center',$form1);

$tools=new ImmExtjsTools();
$tools->addItem(array('id'=>'gear','handler'=>array('source'=>"Ext.Msg.alert('Message', 'The Settings tool was clicked.');")));
$tools->addItem(array('id'=>'close','handler'=>array('parameters'=>'e,target,panel','source'=>"panel.ownerCt.remove(panel, true);")));

$layout->addCenterComponent($tools,array('title'=>'Forms'));

$layout->end();

?>
