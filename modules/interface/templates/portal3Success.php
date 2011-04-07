<script type="text/javascript" src="http://extra.amcharts.com/public/swfobject.js"></script>
<script type="text/javascript">
	var so = new SWFObject("http://extra.amcharts.com/public/amline.swf", "amline", "333", "200", "8");
	so.addParam("wmode","transparent");
	so.addVariable("path", "amline/");
	so.addVariable("chart_settings", encodeURIComponent("<settings><font>Tahoma</font><hide_bullets_count>18</hide_bullets_count><background><alpha>90</alpha><border_alpha>10</border_alpha></background><plot_area><margins><left>50</left><right>40</right><bottom>65</bottom></margins></plot_area><grid><x><alpha>10</alpha><approx_count>9</approx_count></x><y_left><alpha>10</alpha></y_left></grid><axes><x><width>1</width><color>0D8ECF</color></x><y_left><width>1</width><color>0D8ECF</color></y_left></axes><indicator><color>0D8ECF</color><x_balloon_text_color>FFFFFF</x_balloon_text_color><line_alpha>50</line_alpha><selection_color>0D8ECF</selection_color><selection_alpha>20</selection_alpha></indicator><zoom_out_button><text_color_hover>FF0F00</text_color_hover></zoom_out_button><help><button><color>FCD202</color><text_color>000000</text_color><text_color_hover>FF0F00</text_color_hover></button><balloon><text><![CDATA[Click on the graph to turn on/off value baloon <br/><br/>Click on legend key to show/hide graph<br/><br/>Mark the area you wish to enlarge]]></text><color>FCD202</color><text_color>000000</text_color></balloon></help><graphs><graph gid='0'><title>Anomaly</title><color>0D8ECF</color><color_hover>FF0F00</color_hover><selected>0</selected></graph><graph gid='1'><title>Smoothed</title><color>B0DE09</color><color_hover>FF0F00</color_hover><line_width>2</line_width><fill_alpha>30</fill_alpha><bullet>round</bullet></graph></graphs><labels><label lid='0'><text><![CDATA[<b>Temperature anomaly</b>]]></text><y>25</y><text_size>13</text_size><align>center</align></label></labels></settings>"));
	so.addVariable("chart_data", encodeURIComponent("<chart><series><value xid='0'>1850</value><value xid='1'>1851</value><value xid='2'>1852</value><value xid='3'>1853</value><value xid='4'>1854</value><value xid='5'>1855</value><value xid='6'>1856</value><value xid='7'>1857</value><value xid='8'>1858</value></series><graphs><graph gid='0'><value xid='0'>-0.447</value><value xid='1'>-0.292</value><value xid='2'>-0.294</value><value xid='3'>-0.336</value><value xid='4'>-0.308</value><value xid='5'>-0.323</value><value xid='6'>-0.405</value><value xid='7'>-0.502</value><value xid='8'>-0.512</value></graph></graphs></chart>"));
	
</script>

<?php
//unique id for the current xml page
$idXml='interface/portal3';
$extjsPortalStateObj=ExtJsPortalStatePeer::retrieveByIdXml($idXml);
if(!$extjsPortalStateObj)
{
	//default values for layout & columns
	$config=new stdClass();
	$config->layoutType=ExtJsPortalStatePeer::TYPE_NORMAL;
	$config->idXml=$idXml;
	$config->content[0]['portalLayoutType']='[50,25,25]';
	/**
	 * unique ids for different widgets on each column
	 * 
	 * $config->content[number_of_item_inside_the_content][column_number][widget_number]->idxml='unique id for xml widget';
	 * number_of_item_inside_the_content will be 0 for ExtJsPortalStatePeer::TYPE_NORMAL
	 * and 0..n for ExtJsPortalStatePeer::TYPE_TABBED
	 */
	$config->content[0]['portalColumns'][0][0]->idxml='interface/somegrid';
	$config->content[0]['portalColumns'][1][0]->idxml='interface/someform';
	$config->content[0]['portalColumns'][2][0]->idxml='interface/somepanel';
	
	$extjsPortalStateObj=ExtJsPortalStatePeer::createOrUpdateState($config);	
}

/**
 * retrieve layout type & columns configuration & columns size; columns configuration & columns size is used on the bottom of this page
 * those are retrieved for the first item in $extjsPortalStateObj->content, because type is ExtJsPortalStatePeer::TYPE_NORMAL
 */
$layoutType=$extjsPortalStateObj->getLayoutType();
$portalLayoutType=$extjsPortalStateObj->getPortalLayoutType();
$portalColumns=$extjsPortalStateObj->getColumns();
$portalColumnsSize=$extjsPortalStateObj->getColumnsSize();

/**
 * create a tools object, which has an item that intanciate the layout selector
 */
$toolsA=new afExtjsTools();
$toolsA=new afExtjsTools();
switch ($layoutType)
{
	case ExtJsPortalStatePeer::TYPE_NORMAL:
		$toolsA->addItem(array('id'=>'gear','handler'=>array('parameters'=>'e,target,portal','source'=>"
		var layouts=[[100],[50,50],[25,75],[75,25],[33,33,33],[50,25,25],[25,50,25],[25,25,25,25],[40,20,20,20]]; 
		var menu=new Ext.menu.Menu([
									{text: 'Layout Selector', handler:function(){
											portal.showLayoutSelector(target,'Layout Selector',layouts);
										},icon: '/images/famfamfam/application_tile_horizontal.png' 
									},
									{text: 'Widget Selector', handler:function(){
											portal.showWidgetSelector(target,'Widget Selector');									
										},icon: '/images/famfamfam/application_side_boxes.png'
									}
								   ]);
		menu.showAt(e.getXY());")));
		break;
	case ExtJsPortalStatePeer::TYPE_TABBED:
		$toolsA->addItem(array('id'=>'gear','handler'=>array('parameters'=>'e,target,panel','source'=>"
		var layouts=[[100],[50,50],[25,75],[75,25],[33,33,33],[50,25,25],[25,50,25],[25,25,25,25],[40,20,20,20]];
		var tabpanel=panel.items.items[0];
		var menu=new Ext.menu.Menu([
									{text: 'Layout Selector', handler:function(){
											tabpanel.getActiveTab().items.items[0].showLayoutSelector(target,'Layout Selector for '+tabpanel.getActiveTab().title,layouts);
										},icon: '/images/famfamfam/application_tile_horizontal.png' 
									},
									{text: 'Widget Selector', handler:function(){
											tabpanel.getActiveTab().items.items[0].showWidgetSelector(target,'Widget Selector for '+tabpanel.getActiveTab().title);									
										},icon: '/images/famfamfam/application_side_boxes.png'
									}
								   ]);
		menu.showAt(e.getXY());")));
		break;
}

$layout=new afExtjsPortalLayout(array('id'=>'center_panel','tools'=>$toolsA,'idxml'=>$idXml,'layoutType'=>$layoutType,'portalLayoutType'=>$portalLayoutType,'portalWidgets'=>array(array('title'=>'Some widgets','widgets'=>array('/loganalysis/logSearch','/appliance_system/editEmail')))));

$html='<b>Lorem ipsum dolor sit amet</b>, consectetur adipiscing elit. Ut est neque, feugiat venenatis elementum a, tincidunt non massa. Cras sagittis, augue nec porttitor scelerisque, elit lorem ornare massa, eu euismod odio massa vitae justo. Mauris erat nunc, luctus tincidunt lacinia ac, sagittis id risus. Mauris ut quam nisl. Mauris tortor eros, tincidunt sit amet fringilla lacinia, faucibus vel augue. Sed dolor felis, faucibus nec elementum at, cursus in magna. Nam erat nibh, auctor fermentum convallis id, ornare vitae urna. Ut placerat elementum felis. Donec quis libero mauris, vitae vehicula mauris. Donec sit amet urna id justo tempus aliquam. Duis aliquam gravida dictum. Nullam ac nibh eros. Donec lacinia risus id velit congue sed placerat nibh fringilla. Vivamus condimentum varius lacus et facilisis. Curabitur sed tellus sit amet diam dictum ornare. Donec dui lacus, vehicula sit amet semper a, auctor sed sem. Nam pulvinar iaculis libero sed varius. Quisque volutpat posuere sapien quis condimentum.';

$layout->setTitle('Dashboard');

$tools=new afExtjsTools();
$tools->addItem(array('id'=>'gear','handler'=>array('parameters'=>'e,target,panel','source'=>"console.log(panel);")));
$tools->addItem(array('id'=>'close','handler'=>array('parameters'=>'e,target,panel','source'=>"panel.ownerCt.remove(panel, true);")));

/**
 * GRID TREE
 */
/**
 * USE:
 * pager = true/false => activate/disable pagination
 * stateful = true/false => activate/disable state restore from cookies
 */
$grid=new afExtjsGrid(array('idxml'=>'interface/somegrid','autoHeight'=>true,'root_title'=>'Companies','tree'=>true,'pager'=>false,'select'=>true,'portal'=>true/*,'stateful'=>false*/));
$grid->addHelp($html);
/**
 * columns
 */
$grid->addColumn(array('name'=>'company','label'=>'Company','sort'=>'ASC','sortable'=>true,'hidden'=>false,'hideable'=>true));
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
/*new afExtjsButton($grid,array('label'=>'Send Selections to some url','handlers'=>array('click'=>array('parameters'=>'field,event','source'=>'Ext.Ajax.request({ url: "/interface/gridtreeJsonButton", method:"post", params:{"selections":'.$grid->privateName.'.getSelectionModel().getSelectionsJSON()}, success:function(response, options){response=Ext.decode(response.responseText);if(response.message){Ext.Msg.alert("Success",response.message);}},failure: function(response,options) {if(response.message){Ext.Msg.alert("Failure",response.message);}}});'))));
*/
$grid->end();

/**
 * SOME DOUBLE TREE DATA
 */
$double_tree_options=array(array("text"=>"Group 1","value"=>"G1","leaf"=>false,"iconCls"=>"folder","children"=>array(array("text"=>"Item1","value"=>"item 1","leaf"=>true,"iconCls"=>"file"),array("text"=>"Item2","value"=>"item 2","leaf"=>true,"iconCls"=>"file"))),array("text"=>"Group 2","value"=>"G2","leaf"=>false,"iconCls"=>"folder","children"=>array(array("text"=>"Item3","value"=>"item 3","leaf"=>true,"iconCls"=>"file"),array("text"=>"Item4","value"=>"item 4","leaf"=>true,"iconCls"=>"file"),array("text"=>"Item5","value"=>"item 5","leaf"=>true,"iconCls"=>"file"))));

$double_tree_selected=array(array("text"=>"Group 1","value"=>"G1","leaf"=>false,"iconCls"=>"folder","children"=>array(array("text"=>"Item11","value"=>"item 11","leaf"=>true,"iconCls"=>"file"))));

/**
 * WINDOW FORM
 */

$form0=new afExtjsForm(array('action'=>'/interface/test','frame'=>true));

$fieldset=$form0->startFieldset(array('legend'=>'Fieldset from second form'));

new afExtjsFieldDoubleTree($fieldset,array('name'=>'my_double_tree','label'=>'My double tree','help'=>'test help','comment'=>'comment on double tree','state'=>'editable','fromLegend'=>'Options grouped','toLegend'=>'Selected grouped','options'=>$double_tree_options,'selected'=>$double_tree_selected));

new afExtjsFieldInput($fieldset,array('name'=>'my_name','label'=>'My Name','value'=>'RaduX','help'=>"'+field.value+'",'comment'=>'comment on the upper field','handlers'=>array('change'=>array('parameters'=>'field','source'=>'alert(field.value);'))));

new afExtjsFieldMultiCombo($fieldset,array('name'=>'my_multi_combo','label'=>'My multi combo','help'=>"multi combo box",'comment'=>'comment for multi combo','options'=>array('1'=>'Value 1','2'=>'Value 2','3'=>'Value 3'),'selected'=>array('1','2'),'state'=>'editable','clear'=>true));

$form0->endFieldset($fieldset);

new afExtjsSubmitButton($form0,array('action'=>'/interface/test'));

new afExtjsResetButton($form0);

new afExtjsButton($form0,array('label'=>'Just a normal button','handlers'=>array('click'=>array('parameters'=>'field,event','source'=>'alert(field.name);'))));

new afExtjsLinkButton($form0,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));

new afExtjsLinkButton($form0,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));

$form0->end();

/**
 * A FORM IN THE COLUMN
 */

$form=new afExtjsForm(array('idxml'=>'interface/someform','action'=>'/interface/test','portal'=>true,'tools'=>$tools,/*'autoWidth'=>true*/));
$form->addHelp($html);
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
//$combo=new afExtjsFieldCombo($fieldset,array('name'=>'my_combo_button','label'=>'My combo button','help'=>"combo box with button",'comment'=>'comment for combo w button','options'=>array('a'=>'Value A','b'=>'Value B'),'selected'=>'b','button'=>array('text'=>'Trigger','icon'=>'/images/famfamfam/cancel.png'),'window'=>array('title'=>'Window Title','component'=>$form0,'className'=>'ServerPeer','methodName'=>'getAllAsOptions')));

/**
 * combo with autocomplete
 */
$combo_auto=new afExtjsFieldCombo($fieldset,array('name'=>'my_combo_autocomplete','label'=>'My autocomplete combo','help'=>"combo box",'comment'=>'comment for combo','proxy'=>array('fields'=>array('var_name'),'selectedIndex'=>'var_name','url'=>'/interface/jsonAutocomplete','limit'=>10,'minChars'=>3),'template'=>'<h3>{var_name}</h3>'));

$date=new afExtjsFieldDateTime($fieldset,array('name'=>'my_date','label'=>'My Date','value'=>'','comment'=>'comment on the upper field','type'=>'date'));

$dayplus=new afExtjsFieldDateTime($fieldset,array('name'=>'my_day','label'=>'My day','value'=>'','comment'=>'comment on the upper field','type'=>'dayplus','url'=>'/interface/form?date_interval='));

$weekplus=new afExtjsFieldDateTime($fieldset,array('name'=>'my_week','label'=>'My week','value'=>'','comment'=>'comment on the upper field','type'=>'weekplus','url'=>'/interface/form?date_interval='));

$monthplus=new afExtjsFieldDateTime($fieldset,array('name'=>'my_month', 'value'=>'01/06/2008,02/06/2009','label'=>'My month','comment'=>'comment on the upper field','type'=>'monthplus','url'=>'/interface/form?date_interval='));

/**
 * STATIC FIELD, you can add html to the value; if you want to submit the value add attribute submitValue:true
 */
$static=new afExtjsFieldStatic($fieldset,array('name'=>'my_static','label'=>'My static','value'=>'<img src="/images/famfamfam/accept.png" border="0"> <b>test</b>','comment'=>'comment on the upper field','submitValue'=>false));

$datetime=new afExtjsFieldDateTime($fieldset,array('name'=>'my_datetime','label'=>'My DateTime','value'=>'','comment'=>'comment on the upper field','type'=>'datetime'));

$double_multicombo=new afExtjsFieldDoubleMultiCombo($fieldset,array('name'=>'my_double_multi_combo','label'=>'My double multi combo','help'=>"double multi combo box",'comment'=>'comment for double multi combo','options'=>array('1'=>'Value 1','2'=>'Value 2','3'=>'Value 3'),'selected'=>array('4'=>'Value 4'),'state'=>'editable','clear'=>true,'fromLegend'=>'Options','toLegend'=>'Selected'));

$double_tree=new afExtjsFieldDoubleTree($fieldset,array('name'=>'my_double_tree','label'=>'My double tree','help'=>'test help','comment'=>'comment on double tree','state'=>'editable','fromLegend'=>'Options grouped','toLegend'=>'Selected grouped','options'=>$double_tree_options,'selected'=>$double_tree_selected));

$input=new afExtjsFieldInput($fieldset,array('name'=>'my_name','label'=>'My Name','value'=>'Radu','help'=>"'+field.value+'",'comment'=>'comment on the upper field','handlers'=>array('change'=>array('parameters'=>'field','source'=>'alert(field.value);'))));

$hidden=new afExtjsFieldHidden($fieldset,array('name'=>'my_hidden','value'=>'hiddenRadu'));

$textarea=new afExtjsFieldTextarea($fieldset,array('name'=>'my_textarea','label'=>'My Textarea','value'=>'textarea','comment'=>'my textarea comment','rich'=>false));

$password=new afExtjsFieldPassword($fieldset,array('name'=>'my_pass','label'=>'My Pass','value'=>'Radu','help'=>"password",'comment'=>'comment on the upper field'));

$file=new afExtjsFieldFile($fieldset,array('name'=>'my_file','label'=>'My File','help'=>"file",'comment'=>'comment file on the upper field'));

$form->endFieldset($fieldset);

$fieldset1=$form->startFieldset(array('legend'=>'Fieldset 2'));

/**
 * CHANGED TO a and b values, to see that selected works fine
 */
$combo=new afExtjsFieldCombo($fieldset1,array('name'=>'my_combo','label'=>'My combo','help'=>"combo box",'comment'=>'comment for combo','options'=>array('a'=>'Value A','b'=>'Value B'),'selected'=>'b'));

$radiogroup=$fieldset1->startGroup('radio',array('label'=>'My radio group','comment'=>'comment for radio group','help'=>'help tooltip on the radio group'));

$radio1=new afExtjsFieldRadio($radiogroup,array('name'=>'radio','label'=>'Value = 1','value'=>'1','checked'=>true));
$radio2=new afExtjsFieldRadio($radiogroup,array('name'=>'radio','label'=>'Value = 2','value'=>'2'));

$fieldset1->endGroup($radiogroup);

$checkboxgroup=$form->startGroup('checkbox',array('label'=>'My checkbox group','comment'=>'comment for checkbox group','help'=>'help tooltip on the checkbox'));

$checkbox1=new afExtjsFieldCheckbox($checkboxgroup,array('name'=>'check1','label'=>'Check 1','value'=>'1','checked'=>true));
$checkbox2=new afExtjsFieldCheckbox($checkboxgroup,array('name'=>'checkbox2','label'=>'Check 2','value'=>'2'));

$form->endGroup($checkboxgroup);

$checkbox3=new afExtjsFieldCheckbox($fieldset1,array('name'=>'check3','label'=>'Check 3','value'=>'3','checked'=>true));

/**
 * CHANGED TO a, b and c values, to see that selected works fine
 */
$multicombo=new afExtjsFieldMultiCombo($fieldset1,array('name'=>'my_multi_combo','label'=>'My multi combo','help'=>"multi combo box",'comment'=>'comment for multi combo','options'=>array('a'=>'Value A','b'=>'Value B','c'=>'Value C'),'selected'=>array('b','c'),'state'=>'editable','clear'=>true));

$form->endFieldset($fieldset1);

new afExtjsSubmitButton($form,array('action'=>'/interface/test','afterSuccess'=>'Ext.Ajax.request({ url: "/interface/gridtreeJsonButton", method:"post", params:{"selections":'.$grid->privateName.'.getSelectionModel().getSelectionsJSON()}, success:function(response, options){response=Ext.decode(response.responseText);if(response.message){Ext.Msg.alert("Success",response.message);}},failure: function(response,options) {if(response.message){Ext.Msg.alert("Failure",response.message);}}});'));

new afExtjsResetButton($form);

new afExtjsButton($form,array('label'=>'Just a normal button','handlers'=>array('click'=>array('parameters'=>'field,event','source'=>'alert(field.name);'))));

new afExtjsLinkButton($form,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk','icon'=>'/images/famfamfam/cancel.png'));

new afExtjsLinkButton($form,array('label'=>'www.immune.dk','url'=>'http://www.immune.dk'));

$form->end();

                
/**
 * simple html panel
 */

$panel=new afExtjsPanel(array('idxml'=>'interface/somepanel','title'=>'Some Html Panel','autoScroll'=>'true','border'=>false,'header'=>true,'style'=>'','autoHeight'=>true,'autoEnd'=>false,'portal'=>true,'autoWidth'=>true));
$panel->addHelp($html);
$panel->addMember(array('html'=>'<div id="amchart3"></div>'));
$layout->addInitMethodSource("so.write('amchart3');");
$panel->end();

/**
 * associations are only to be used in this example
 * in real life example, just use the idxml from the added widget
 */
$associations=array('interface/somepanel'=>$panel,'interface/someform'=>$form,'interface/somegrid'=>$grid);

foreach ($portalColumns as $k=>$widgets)
{
	//instanciate a column
	${'column'.$k}=$layout->startColumn(array('columnWidth'=>($portalColumnsSize[$k]/100)));
	
	foreach ($widgets as $widget)
	{
		${'column'.$k}->addItem($associations[$widget->idxml]);
	}
	
	//end the instanciation of a column
	$layout->endColumn(${'column'.$k});
}

/**
 * the old code for constructing the columns
 */
/*$column1=$layout->startColumn(array('columnWidth'=>'0.50'));
$column1->addItem($grid);
$layout->endColumn($column1);

$column2=$layout->startColumn(array('columnWidth'=>'0.25'));
$column2->addItem($form);
$layout->endColumn($column2);

$column3=$layout->startColumn(array('columnWidth'=>'0.25'));
$column3->addItem($panel);
$layout->endColumn($column3);*/

$layout->end();

?>