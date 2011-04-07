<script type="text/javascript" src="http://extra.amcharts.com/public/swfobject.js"></script>
<script type="text/javascript">
	var so = new SWFObject("http://extra.amcharts.com/public/amline.swf", "amline", "333", "200", "8");
	so.addParam("wmode","transparent");
	so.addVariable("path", "amline/");
	so.addVariable("chart_settings", encodeURIComponent("<settings><font>Tahoma</font><hide_bullets_count>18</hide_bullets_count><background><alpha>90</alpha><border_alpha>10</border_alpha></background><plot_area><margins><left>50</left><right>40</right><bottom>65</bottom></margins></plot_area><grid><x><alpha>10</alpha><approx_count>9</approx_count></x><y_left><alpha>10</alpha></y_left></grid><axes><x><width>1</width><color>0D8ECF</color></x><y_left><width>1</width><color>0D8ECF</color></y_left></axes><indicator><color>0D8ECF</color><x_balloon_text_color>FFFFFF</x_balloon_text_color><line_alpha>50</line_alpha><selection_color>0D8ECF</selection_color><selection_alpha>20</selection_alpha></indicator><zoom_out_button><text_color_hover>FF0F00</text_color_hover></zoom_out_button><help><button><color>FCD202</color><text_color>000000</text_color><text_color_hover>FF0F00</text_color_hover></button><balloon><text><![CDATA[Click on the graph to turn on/off value baloon <br/><br/>Click on legend key to show/hide graph<br/><br/>Mark the area you wish to enlarge]]></text><color>FCD202</color><text_color>000000</text_color></balloon></help><graphs><graph gid='0'><title>Anomaly</title><color>0D8ECF</color><color_hover>FF0F00</color_hover><selected>0</selected></graph><graph gid='1'><title>Smoothed</title><color>B0DE09</color><color_hover>FF0F00</color_hover><line_width>2</line_width><fill_alpha>30</fill_alpha><bullet>round</bullet></graph></graphs><labels><label lid='0'><text><![CDATA[<b>Temperature anomaly</b>]]></text><y>25</y><text_size>13</text_size><align>center</align></label></labels></settings>"));
	so.addVariable("chart_data", encodeURIComponent("<chart><series><value xid='0'>1850</value><value xid='1'>1851</value><value xid='2'>1852</value><value xid='3'>1853</value><value xid='4'>1854</value><value xid='5'>1855</value><value xid='6'>1856</value><value xid='7'>1857</value><value xid='8'>1858</value></series><graphs><graph gid='0'><value xid='0'>-0.447</value><value xid='1'>-0.292</value><value xid='2'>-0.294</value><value xid='3'>-0.336</value><value xid='4'>-0.308</value><value xid='5'>-0.323</value><value xid='6'>-0.405</value><value xid='7'>-0.502</value><value xid='8'>-0.512</value></graph></graphs></chart>"));
	
</script>

<?php
$layout=new afExtjsPortalLayout(array('id'=>'center_panel','title'=>'Dashboard'));

$tools=new afExtjsTools();
$tools->addItem(array('id'=>'gear','handler'=>array('source'=>"Ext.Msg.alert('Message', 'The Settings tool was clicked.');")));
$tools->addItem(array('id'=>'close','handler'=>array('parameters'=>'e,target,panel','source'=>"panel.ownerCt.remove(panel, true);")));

$column1=$layout->startColumn();

$column1->addItem(array('title'=> 'Panel 1',
                    	'tools'=> $tools,
                    	'html'=> '<div id="amchart"></div>'));
                    	
$layout->addInitMethodSource("so.write('amchart');");

$layout->endColumn($column1);

$column2=$layout->startColumn();

$column2->addItem(array('title'=> 'Panel 2',
                    	'tools'=> $tools,
                    	'html'=> '<div id="amchart2"></div>'));
                    	
$layout->addInitMethodSource("so.write('amchart2');");

$layout->endColumn($column2);

$column3=$layout->startColumn();

$column3->addItem(array('title'=> 'Panel 3',
                    	'tools'=> $tools,
                    	'html'=> '<div id="amchart3"></div>'));
                    	
$layout->addInitMethodSource("so.write('amchart3');");

$layout->endColumn($column3);

$layout->end();

?>