<?php
$startmenu = new afExtjsStartMenu(array('title'=>'AppFlower'));

$startmenu->addTool(array('text'=>'Logout','iconCls'=>'logout','handler'=>''));

$dashboard = new afExtjsStartMenuButton($startmenu,array('icon'=>'/images/famfamfam/house_go.png','label'=>'Dashboard','handler'=>'afApp.widgetPopup(\'/\');','tooltip'=>array('text'=>'Your overview', 'title'=>'Project Dashboard')));$dashboard->end();

$startmenu->end();
?>
