<?php
$layout=new ImmExtjsWCELayout();

/**
 * WEST PANEL TREE WITH FILTERS
 */
$filters=new ImmExtjsTree(array('title'=>'Filters'));
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


$layout->addItem('west',array('title'=>'Settings1',
									      		'autoScroll'=>'true',
									      		'border'=>'false',
									      		'iconCls'=>'settings',
									      		'html'=>'test2'      	
									      	));
$layout->addWestComponent(array('title'=>'Navigation West'));

/**
 * EAST PANEL TREE WITH FILTERS
 */
$filters=new ImmExtjsTree(array('title'=>'Filters'));
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
 * ADD THE FILTERS TO THE EAST REGION
 */
$layout->addItem('east',$filters);


$layout->addItem('east',array('title'=>'Settings1',
									      		'autoScroll'=>'true',
									      		'border'=>'false',
									      		'iconCls'=>'settings',
									      		'html'=>'test2'      	
									      	));
$layout->addEastComponent(array('title'=>'Navigation East'));
									      	
									      	
/**
 * FIRST FORM
 */

$form=new ImmExtjsForm(array('action'=>'/interface/test'/*,'fileUpload'=>true*/));

$fieldset=$form->startFieldset(array('legend'=>'Fieldset 1','collapsed'=>false));

$date=new ImmExtjsFieldDateTime($fieldset,array('name'=>'my_date','label'=>'My Date','value'=>'','comment'=>'comment on the upper field','time'=>false));

/**
 * STATIC FIELD, you can add html to the value; if you want to submit the value add attribute submitValue:true
 */
$static=new ImmExtjsFieldStatic($fieldset,array('name'=>'my_static','label'=>'My static','value'=>'<img src="/images/famfamfam/accept.png" border="0"> <b>test</b>','comment'=>'comment on the upper field','submitValue'=>false));

$datetime=new ImmExtjsFieldDateTime($fieldset,array('name'=>'my_datetime','label'=>'My DateTime','value'=>'','comment'=>'comment on the upper field'));

$double_multicombo=new ImmExtjsFieldDoubleMultiCombo($fieldset,array('name'=>'my_double_multi_combo','label'=>'My double multi combo','help'=>"double multi combo box",'comment'=>'comment for double multi combo','options'=>array('1'=>'Value 1','2'=>'Value 2','3'=>'Value 3'),'selected'=>array('4'=>'Value 4'),'state'=>'editable','clear'=>true,'fromLegend'=>'Options','toLegend'=>'Selected'));

$double_tree_options=array(array("text"=>"Group 1","value"=>"G1","leaf"=>false,"iconCls"=>"folder","children"=>array(array("text"=>"Item1","value"=>"item 1","leaf"=>true,"iconCls"=>"file"),array("text"=>"Item2","value"=>"item 2","leaf"=>true,"iconCls"=>"file"))),array("text"=>"Group 2","value"=>"G2","leaf"=>false,"iconCls"=>"folder","children"=>array(array("text"=>"Item3","value"=>"item 3","leaf"=>true,"iconCls"=>"file"),array("text"=>"Item4","value"=>"item 4","leaf"=>true,"iconCls"=>"file"),array("text"=>"Item5","value"=>"item 5","leaf"=>true,"iconCls"=>"file"))));

$double_tree=new ImmExtjsFieldDoubleTree($fieldset,array('name'=>'my_double_tree','label'=>'My double tree','help'=>'test help','comment'=>'comment on double tree','state'=>'editable','fromLegend'=>'Options grouped','toLegend'=>'Selected grouped','options'=>$double_tree_options));

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
$combo=new ImmExtjsFieldCombo($fieldset1,array('name'=>'my_combo','label'=>'My combo','help'=>"combo box",'comment'=>'comment for combo','options'=>array('a'=>'Value A','b'=>'Value B'),'selected'=>'b'));

$radiogroup=$fieldset1->startGroup('radio',array('label'=>'My radio group','comment'=>'comment for radio group','help'=>'help tooltip on the radio group'));

$radio1=new ImmExtjsFieldRadio($radiogroup,array('name'=>'radio','label'=>'Value = 1','value'=>'1','checked'=>true));
$radio2=new ImmExtjsFieldRadio($radiogroup,array('name'=>'radio','label'=>'Value = 2','value'=>'2'));

$fieldset1->endGroup($radiogroup);

$checkboxgroup=$fieldset1->startGroup('checkbox',array('label'=>'My checkbox group','comment'=>'comment for checkbox group','help'=>'help tooltip on the checkbox'));

$checkbox1=new ImmExtjsFieldCheckbox($checkboxgroup,array('name'=>'check1','label'=>'Check 1','value'=>'1','checked'=>true));
$checkbox2=new ImmExtjsFieldCheckbox($checkboxgroup,array('name'=>'checkbox2','label'=>'Check 2','value'=>'2'));

$fieldset1->endGroup($checkboxgroup);

$checkbox3=new ImmExtjsFieldCheckbox($fieldset1,array('name'=>'check3','label'=>'Check 3','value'=>'3','checked'=>true));

/**
 * CHANGED TO a, b and c values, to see that selected works fine
 */
$multicombo=new ImmExtjsFieldMultiCombo($fieldset1,array('name'=>'my_multi_combo','label'=>'My multi combo','help'=>"multi combo box",'comment'=>'comment for multi combo','options'=>array('a'=>'Value A','b'=>'Value B','c'=>'Value C'),'selected'=>array('b','c'),'state'=>'editable','clear'=>true));

$form->endFieldset($fieldset1);

new ImmExtjsSubmitButton($form,array('action'=>'/interface/test'));

new ImmExtjsResetButton($form);

new ImmExtjsButton($form,array('label'=>'Just a normal button','handlers'=>array('click'=>array('parameters'=>'field,event','source'=>'alert(field.name);'))));

new ImmExtjsLinkButton($form,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));

new ImmExtjsLinkButton($form,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));

$form->end();

$layout->addItem('center',$form);

/**
 * SECOND FORM
 */

$form1=new ImmExtjsForm(array('action'=>'/interface/test'));

$fieldset=$form1->startFieldset(array('legend'=>'Fieldset from second form'));

new ImmExtjsFieldMultiCombo($fieldset,array('name'=>'my_multi_combo','label'=>'My multi combo','help'=>"multi combo box",'comment'=>'comment for multi combo','options'=>array('1'=>'Value 1','2'=>'Value 2','3'=>'Value 3'),'selected'=>array('1','2'),'state'=>'editable','clear'=>true));

$form1->endFieldset($fieldset);

new ImmExtjsSubmitButton($form1,array('action'=>'/interface/test'));

new ImmExtjsResetButton($form1);

new ImmExtjsButton($form1,array('label'=>'Just a normal button','handlers'=>array('click'=>array('parameters'=>'field,event','source'=>'alert(field.name);'))));

new ImmExtjsLinkButton($form1,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));

new ImmExtjsLinkButton($form1,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));

$form1->end();

$layout->addItem('center',$form1);



$tools=new ImmExtjsTools();
$tools->addItem(array('id'=>'gear','handler'=>array('source'=>"Ext.Msg.alert('Message', 'The Settings tool was clicked.');")));
$tools->addItem(array('id'=>'close','handler'=>array('parameters'=>'e,target,panel','source'=>"panel.ownerCt.remove(panel, true);")));

$layout->addCenterComponent($tools,array('title'=>'Forms'));

$layout->end();

?>