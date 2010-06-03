<?php
$layout=new ImmExtjsWizardLayout(array('id'=>'center_panel'));

$layout->setTitle('Wizard Step 2');

$tools=new ImmExtjsTools();
$tools->addItem(array('id'=>'gear','handler'=>array('parameters'=>'e,target,panel','source'=>"console.log(panel);")));
$tools->addItem(array('id'=>'close','handler'=>array('parameters'=>'e,target,panel','source'=>"panel.ownerCt.remove(panel, true);")));

$column1=$layout->startColumn(array('columnWidth'=>'0.99'));

/**
 * A FORM IN THE COLUMN
 */

$form=new ImmExtjsForm(array('action'=>'/interface/test','title'=>'Form','portal'=>true,'tools'=>$tools,'classic'=>true));

$fieldset=$form->startFieldset(array('legend'=>'Fieldset 1'));

$input=new ImmExtjsFieldInput($fieldset,array('name'=>'my_name','label'=>'My Name','value'=>'Radu','help'=>"'+field.value+'",'comment'=>'comment on the upper field','handlers'=>array('change'=>array('parameters'=>'field','source'=>'alert(field.value);'))));

$textarea=new ImmExtjsFieldTextarea($fieldset,array('name'=>'my_textarea','label'=>'My Textarea','value'=>'textarea','comment'=>'my textarea comment','rich'=>true));

$password=new ImmExtjsFieldPassword($fieldset,array('name'=>'my_pass','label'=>'My Pass','value'=>'Radu','help'=>"password",'comment'=>'comment on the upper field'));

$form->endFieldset($fieldset);

//new ImmExtjsSubmitButton($form,array('action'=>'/interface/test'));

new ImmExtjsResetButton($form);

new ImmExtjsButton($form,array('label'=>'Just a normal button','handlers'=>array('click'=>array('parameters'=>'field,event','source'=>'alert(field.name);'))));

new ImmExtjsLinkButton($form,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));

new ImmExtjsLinkButton($form,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));

$form->end();

$column1->addItem($form);

$layout->endColumn($column1);

new ImmExtjsLinkButton($layout,array('label'=>'Previous (step1)','url'=>'https://192.168.198.129/interface/wizardStep1','disabled'=>true));

new ImmExtjsSubmitButton($layout,array('label'=>'Previous (step1&submit)','action'=>'/interface/wizardStep1','method'=>'get'),$form);

$layout->end();

?>