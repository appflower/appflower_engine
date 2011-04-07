<?php
$layout=new afExtjsPanelLayout();

/**
 * USE:
 * stateful = true/false => activate/disable state restore from cookies
 */
$grid=new afExtjsGridCustom(array('autoHeight'=>true,'title'=>'Title'/*,'stateful'=>false*/));
/**
 * proxy
 * 
 */
$grid->setProxy(array('url'=>'/interface/jsoncustomgrid'));

new afExtjsLinkButton($grid,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));
new afExtjsLinkButton($grid,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));

$grid->end();

$layout->addItem('center',$grid);


$tools=new afExtjsTools();
$tools->addItem(array('id'=>'gear','handler'=>array('source'=>"Ext.Msg.alert('Message', 'The Settings tool was clicked.');")));
$tools->addItem(array('id'=>'close','handler'=>array('parameters'=>'e,target,panel','source'=>"panel.ownerCt.remove(panel, true);")));

$layout->addCenterComponent($tools,array('title'=>'Custom Grid'));

$layout->end();

?>