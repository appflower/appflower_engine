<div id="toolbar"></div>
<script type="text/javascript" src="http://extra.amcharts.com/public/swfobject.js"></script>
<script type="text/javascript">
	var so = new SWFObject("http://extra.amcharts.com/public/amline.swf", "amline", "300", "400", "8");
	so.addParam("wmode","transparent");
	so.addVariable("path", "amline/");
	so.addVariable("chart_settings", encodeURIComponent("<settings><font>Tahoma</font><hide_bullets_count>18</hide_bullets_count><background><alpha>90</alpha><border_alpha>10</border_alpha></background><plot_area><margins><left>50</left><right>40</right><bottom>65</bottom></margins></plot_area><grid><x><alpha>10</alpha><approx_count>9</approx_count></x><y_left><alpha>10</alpha></y_left></grid><axes><x><width>1</width><color>0D8ECF</color></x><y_left><width>1</width><color>0D8ECF</color></y_left></axes><indicator><color>0D8ECF</color><x_balloon_text_color>FFFFFF</x_balloon_text_color><line_alpha>50</line_alpha><selection_color>0D8ECF</selection_color><selection_alpha>20</selection_alpha></indicator><zoom_out_button><text_color_hover>FF0F00</text_color_hover></zoom_out_button><help><button><color>FCD202</color><text_color>000000</text_color><text_color_hover>FF0F00</text_color_hover></button><balloon><text><![CDATA[Click on the graph to turn on/off value baloon <br/><br/>Click on legend key to show/hide graph<br/><br/>Mark the area you wish to enlarge]]></text><color>FCD202</color><text_color>000000</text_color></balloon></help><graphs><graph gid='0'><title>Anomaly</title><color>0D8ECF</color><color_hover>FF0F00</color_hover><selected>0</selected></graph><graph gid='1'><title>Smoothed</title><color>B0DE09</color><color_hover>FF0F00</color_hover><line_width>2</line_width><fill_alpha>30</fill_alpha><bullet>round</bullet></graph></graphs><labels><label lid='0'><text><![CDATA[<b>Temperature anomaly</b>]]></text><y>25</y><text_size>13</text_size><align>center</align></label></labels></settings>"));
	so.addVariable("chart_data", encodeURIComponent("<chart><series><value xid='0'>1850</value><value xid='1'>1851</value><value xid='2'>1852</value><value xid='3'>1853</value><value xid='4'>1854</value><value xid='5'>1855</value><value xid='6'>1856</value><value xid='7'>1857</value><value xid='8'>1858</value></series><graphs><graph gid='0'><value xid='0'>-0.447</value><value xid='1'>-0.292</value><value xid='2'>-0.294</value><value xid='3'>-0.336</value><value xid='4'>-0.308</value><value xid='5'>-0.323</value><value xid='6'>-0.405</value><value xid='7'>-0.502</value><value xid='8'>-0.512</value></graph></graphs></chart>"));
</script>

<?php
  use_helper ( 'sfExtjs2' );
  
  $private = array ( );
  $public = array ( );

  $sfExtjs2Plugin = new sfExtjs2Plugin( array ('theme' => 'blue' ), array ('css' => array('/css/my-extjs.css','/sfExtjs2Plugin/extjs/examples/portal/portal.css'), 'js' => array('/sfExtjs2Plugin/extjs/examples/portal/Portal.js','/sfExtjs2Plugin/extjs/examples/portal/PortalColumn.js','/sfExtjs2Plugin/extjs/examples/portal/Portlet.js','/sfExtjs2Plugin/extjs/examples/portal/sample-grid.js') ) );
  $sfExtjs2Plugin->load();
  $sfExtjs2Plugin->begin();

  // **************************************
  // Application
  // **************************************

  $private['menu_dashboard'] = $sfExtjs2Plugin->Menu(
  	array(
  		'items'=> array(
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'<b>Dashboard</b>',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'by Event Classification',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'by Latest Events',
			  		'checkHandler'=>'onItemCheck'
			  	)
			),
			$sfExtjs2Plugin->MenuSeparator(),
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Firewall Analysis',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Log Archive',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Network View',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Event Classification',
			  		'checkHandler'=>'onItemCheck'
			  	)
			)
  		)
  	)
  );
  
  $private['menu_appliance_setup_services'] = $sfExtjs2Plugin->Menu(
  	array(
  		'items'=> array(
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'File Sharing (FTP)',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Email messaging',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Event Processing',
			  		'checkHandler'=>'onItemCheck'
			  	)
			),
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Log Collector',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'NADE Engine',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Resource Monitoring (SNMP)',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Secure Shell Access (SSH)',
			  		'checkHandler'=>'onItemCheck'
			  	)
			)
  		)
  	)
  );
  
  $private['menu_appliance_setup'] = $sfExtjs2Plugin->Menu(
  	array(
  		'items'=> array(
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'<b>Appliance Setup</b>',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Network Interfaces',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Static Routes',
			  		'checkHandler'=>'onItemCheck'
			  	)
			),
			$sfExtjs2Plugin->MenuSeparator(),
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Services',
			  		'menu'=>$sfExtjs2Plugin->asVar('menu_appliance_setup_services'),
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
			$sfExtjs2Plugin->MenuSeparator(),
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Software Updates',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Software License',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Reboot / Shutdown',
			  		'checkHandler'=>'onItemCheck'
			  	)
			)
  		)
  	)
  );
  
  $private['menu_log_management_intelligence'] = $sfExtjs2Plugin->Menu(
  	array(
  		'items'=> array(
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Event Correlation',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Event Signatures',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Firewall Signatures',
			  		'checkHandler'=>'onItemCheck'
			  	)
			),
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'NADE',
			  		'checkHandler'=>'onItemCheck'
			  	)
			)
  		)
  	)
  );
  
  $private['menu_log_management'] = $sfExtjs2Plugin->Menu(
  	array(
  		'items'=> array(
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'<b>Log Management</b>',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
			$sfExtjs2Plugin->MenuSeparator(),
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Event Policies',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
			$sfExtjs2Plugin->MenuSeparator(),
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Event Sources',
			  		'checkHandler'=>'onItemCheck'
			  	)
			),
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Event Classification',
			  		'checkHandler'=>'onItemCheck'
			  	)
			),
			$sfExtjs2Plugin->MenuSeparator(),
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Intelligence',
			  		'menu'=>$sfExtjs2Plugin->asVar('menu_log_management_intelligence'),
			  		'checkHandler'=>'onItemCheck'
			  	)
			)
  		)
  	)
  );
  
  $private['menu_administration_server_management'] = $sfExtjs2Plugin->Menu(
  	array(
  		'items'=> array(
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Server Settings',
			  		'checkHandler'=>'onItemCheck'
			  	)
			),
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Server Groups',
			  		'checkHandler'=>'onItemCheck'
			  	)
			),
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Server Locations',
			  		'checkHandler'=>'onItemCheck'
			  	)
			),
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Retention Policies',
			  		'checkHandler'=>'onItemCheck'
			  	)
			),
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Risk Assessment',
			  		'checkHandler'=>'onItemCheck'
			  	)
			)
  		)
  	)
  );
  
  $private['menu_administration_user_management'] = $sfExtjs2Plugin->Menu(
  	array(
  		'items'=> array(
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Users',
			  		'checkHandler'=>'onItemCheck'
			  	)
			),
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Roles',
			  		'checkHandler'=>'onItemCheck'
			  	)
			),
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Site Permissions',
			  		'checkHandler'=>'onItemCheck'
			  	)
			),
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Object Permissions',
			  		'checkHandler'=>'onItemCheck'
			  	)
			)
  		)
  	)
  );
  
  $private['menu_administration'] = $sfExtjs2Plugin->Menu(
  	array(
  		'items'=> array(
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'<b>Administration</b>',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Server Management',
			  		'menu'=>$sfExtjs2Plugin->asVar('menu_administration_server_management'),
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'User Management',
			  		'menu'=>$sfExtjs2Plugin->asVar('menu_administration_user_management'),
			  		'checkHandler'=>'onItemCheck'
			  	)
			)
  		)
  	)
  );
  
  $private['menu_diagnostics_advanced'] = $sfExtjs2Plugin->Menu(
  	array(
  		'items'=> array(
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Kernel settings',
			  		'checkHandler'=>'onItemCheck'
			  	)
			),
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Network stack',
			  		'checkHandler'=>'onItemCheck'
			  	)
			),
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Routing table',
			  		'checkHandler'=>'onItemCheck'
			  	)
			)
  		)
  	)
  );
  
  $private['menu_diagnostics'] = $sfExtjs2Plugin->Menu(
  	array(
  		'items'=> array(
  			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'<b>System Health</b>',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Process status',
			  		'checkHandler'=>'onItemCheck'
			  	)
			),
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Connectivity',
			  		'checkHandler'=>'onItemCheck'
			  	)
			), 
			$sfExtjs2Plugin->Item(
			  	array(
			  		'text'=>'Advanced',
			  		'menu'=>$sfExtjs2Plugin->asVar('menu_diagnostics_advanced'),
			  		'checkHandler'=>'onItemCheck'
			  	)
			)
  		)
  	)
  );
  
  $private['dashboard'] = $sfExtjs2Plugin->MenuButton(
  	array(
  		'text'=>'Dashboard',
  		'tooltip'=> $sfExtjs2Plugin->asAnonymousClass(array('text'=>'Shows the overview components, to let you easily spot the arising threats', 'title'=>'Security Dashboard')),
    	'id'=>'btn-dashboard',
        'menu'=> $sfExtjs2Plugin->asVar('menu_dashboard')
  	)
  );
  
  $private['appliance_setup'] = $sfExtjs2Plugin->MenuButton(
  	array(
  		'text'=>'Appliance Setup',
  		'tooltip'=> $sfExtjs2Plugin->asAnonymousClass(array('text'=>'Shows the overview components, to let you easily spot the arising threats', 'title'=>'Appliance Setup')),
    	'id'=>'btn-appliancesetup',
        'menu'=> $sfExtjs2Plugin->asVar('menu_appliance_setup')
  	)
  );
  
  $private['log_management'] = $sfExtjs2Plugin->MenuButton(
  	array(
  		'text'=>'Log Management',
  		'tooltip'=> $sfExtjs2Plugin->asAnonymousClass(array('text'=>'Shows the overview components, to let you easily spot the arising threats', 'title'=>'Log Management')),
    	'id'=>'btn-logmanagement',
        'menu'=> $sfExtjs2Plugin->asVar('menu_log_management')
  	)
  );
  
  $private['monitoring'] = $sfExtjs2Plugin->Button(
  	array(
  		'text'=>'Monitoring',
  		'tooltip'=> $sfExtjs2Plugin->asAnonymousClass(array('text'=>'Shows the overview components, to let you easily spot the arising threats', 'title'=>'Monitoring')),
    	'id'=>'btn-monitoring',
  	)
  );
  
  $private['administration'] = $sfExtjs2Plugin->MenuButton(
  	array(
  		'text'=>'Administration',
  		'tooltip'=> $sfExtjs2Plugin->asAnonymousClass(array('text'=>'Shows the overview components, to let you easily spot the arising threats', 'title'=>'Administration')),
    	'id'=>'btn-administration',
        'menu'=> $sfExtjs2Plugin->asVar('menu_administration')
  	)
  );
  
  $private['incidents'] = $sfExtjs2Plugin->Button(
  	array(
  		'text'=>'Incidents',
  		'tooltip'=> $sfExtjs2Plugin->asAnonymousClass(array('text'=>'Shows the overview components, to let you easily spot the arising threats', 'title'=>'Incidents')),
    	'id'=>'btn-incidents',
  	)
  );
  
  $private['diagnostics'] = $sfExtjs2Plugin->MenuButton(
  	array(
  		'text'=>'Diagnostics',
  		'tooltip'=> $sfExtjs2Plugin->asAnonymousClass(array('text'=>'Shows the overview components, to let you easily spot the arising threats', 'title'=>'Diagnostics')),
    	'id'=>'btn-diagnostics',
        'menu'=> $sfExtjs2Plugin->asVar('menu_diagnostics')
  	)
  );
  
  $private['search_field'] = $sfExtjs2Plugin->ComboBox(
  	array(
  		'fieldLabel' => "Text",
  		'name' => "combovalue",
    	'emptyText' => 'search, and you will find',    
    	'hiddenName' => "combovalue"
  	)
  );
  
  $private['toolbar'] = $sfExtjs2Plugin->Toolbar(
  	array(
  		$sfExtjs2Plugin->asVar('dashboard'),
  		$sfExtjs2Plugin->asVar('appliance_setup'),
  		$sfExtjs2Plugin->asVar('log_management'),
  		$sfExtjs2Plugin->asVar('monitoring'),
  		$sfExtjs2Plugin->asVar('administration'),
  		$sfExtjs2Plugin->asVar('incidents'),
  		$sfExtjs2Plugin->asVar('diagnostics'),
  		$sfExtjs2Plugin->Fill(),
  		$sfExtjs2Plugin->asVar('search_field')
  	)
  );

  $private['north_panel']=$sfExtjs2Plugin->Panel(
  	array
	    (
	      'id'=>'north_panel',
	      'region'=>'north',
	      'height'=>'26',
	      'border'=>'true'
	    )
  );
    
  $private['south_panel']=$sfExtjs2Plugin->Panel(
  	array
	    (
	      'id'=>'south_panel',
	      'title'=>'More information',
	      'region'=>'south',
	      'height'=>'100',
	      'minHeight'=>'0',
	      'maxHeight'=>'100',
	      'split'=>'true',
	      'collapsible'=>'true'
	    )
  );
  
  $private['west_panel']=$sfExtjs2Plugin->Panel(
  	array
	    (
	      'id'=>'west_panel',
	      'region'=>'west',
	      'title'=>'Navigation',
	      'width'=>'200',
	      'split'=>'true',
	      'collapsible'=>'true',
	      'layout'=>'accordion',
	      'items'=>array(
	      	$sfExtjs2Plugin->asAnonymousClass(array(
	      		'title'=>'Navigation',
	      		'autoScroll'=>'true',
	      		'border'=>'false',
	      		'iconCls'=>'nav',
	      		'html'=>'test'	      	
	      	)),
	      	$sfExtjs2Plugin->asAnonymousClass(array(
	      		'title'=>'Settings',
	      		'autoScroll'=>'true',
	      		'border'=>'false',
	      		'iconCls'=>'settings',
	      		'html'=>'test2'
	      	))
	      )
	    )
  );
  
  //json data store
  $private ['ds'] = $sfExtjs2Plugin->JsonStore (
    array (
      'url'     => 'https://192.168.80.128/interface/json',
      'id'      =>  'id',
      'totalProperty' => 'total',
      'root'      => 'data',
      'fields'    => array(
        array(
          'name'  => 'date'
        ),
        array(
          'name'  => 'label'
        ),
        array(
          'name'  => 'amount'
        ),
        array(
          'name'  => 'extra'
        ),
      ),
      'sortInfo'  => array(
        'field'   => 'label',
        'direction' => 'ASC'
      ),
      'remoteSort'=> true
    )
  );
  
  //paging toolbar to add to bbar property on GridPanel
  $private ['pt'] = $sfExtjs2Plugin->PagingToolbar (
    array(
      'store'       => $sfExtjs2Plugin->asVar ( 'ds' ),
      'pageSize'      => 15
    )
  );

  //column model
  $private ['cm'] = $sfExtjs2Plugin->ColumnModel (
    array (
      'parameters' => array (
        $sfExtjs2Plugin->asAnonymousClass (
          array (
            'id'=>'date',
            'header' => 'Date',
            'width' => 120,
            'sortable' => true,
            'dataIndex' => 'date'
          )
        ),
        $sfExtjs2Plugin->asAnonymousClass (
          array (
            'id'=>'label',
            'header' => 'Libel',
            'width' => 170,
            'sortable' => true,
            'dataIndex' => 'label'
          )
        ),
        $sfExtjs2Plugin->asAnonymousClass (
          array (
            'id'=>'amount',
            'header' => 'Price',
            'width' => 120,
            'sortable' => true,
            'dataIndex' => 'amount'
          )
        ),
        $sfExtjs2Plugin->asAnonymousClass (
          array (
            'id'=>'extra',
            'header' => 'Extra',
            'width' => 120,
            'sortable' => true,
            'dataIndex' => 'extra'
          )
        )
      )
    )
  );
  
  $tools=array(
	        $sfExtjs2Plugin->asAnonymousClass(
		          array(
		          	'id'=>'gear',
		          	'handler'=>$sfExtjs2Plugin->asMethod(
		          		array(
		          		'parameters'=>'',
		          		'source'=>"Ext.Msg.alert('Message', 'The Settings tool was clicked.');"
		          		)
		          	)
		          )
	        ),  
          	$sfExtjs2Plugin->asAnonymousClass(
		          array(
		          	'id'=>'close',
		          	'handler'=>$sfExtjs2Plugin->asMethod(
		          		array(
		          		'parameters'=>'e,target,panel',
		          		'source'=>"panel.ownerCt.remove(panel, true);"
		          		)
		          	)
		          )
	          )		          
          );
  
  $private['center_panel']=$sfExtjs2Plugin->Portal(
  	array
	    (
	      'id'=>'center_panel',
	      'title'=>'Dashboard',
	      'items'=>array(
	      	$sfExtjs2Plugin->asAnonymousClass(
	      		array(
	      			'columnWidth'=>'0.3',
                	'style'=>'padding:10px 0 10px 10px',
                	'items'=>array(
                			$sfExtjs2Plugin->asAnonymousClass(
                				array(
                					'title'=> 'Panel 1',
                    				'tools'=> $tools,
                    				'html'=> '<div id="amcharts_1233221819843">You need to upgrade your Flash Player</div>'	
                				)
                			),
                			$sfExtjs2Plugin->asAnonymousClass(
                				array(
                					'title'=> 'Panel 2',
                    				'tools'=> $tools,
                    				'items'=> array(
                    				$sfExtjs2Plugin->asVar('new SampleGrid([0, 2, 3])')
                    				)	
                				)
                			)
				       )
                	)	      		
      		),
      		$sfExtjs2Plugin->asAnonymousClass(
	      		array(
	      			'columnWidth'=>'.33',
                	'style'=>'padding:10px 0 10px 10px',
                	'items'=>array(
                			$sfExtjs2Plugin->asAnonymousClass(
                				array(
                					'title'=> 'Panel 3',
                    				'tools'=> $tools,
                    				'html'=> 'example 3'	
                				)
                			),
                			$sfExtjs2Plugin->asAnonymousClass(
                				array(
                					'title'=> 'Panel 4',
                    				'tools'=> $tools,
                    				'html'=> 'example 4'	
                				)
                			)
				       )
                	)	      		
      		)	      			      	
	      )
	  )
  );
  
  $private['viewport'] = $sfExtjs2Plugin->Viewport(
  	array
	(
	  'layout' => 'border',  'items'  => array
	  (
	    $sfExtjs2Plugin->asVar('north_panel'),
	    $sfExtjs2Plugin->asVar('west_panel'),
	    $sfExtjs2Plugin->asVar('center_panel'),
	    $sfExtjs2Plugin->asVar('south_panel')
	  )
	)
  );
  
  $public ['init'] = $sfExtjs2Plugin->asMethod ( "
    Ext.QuickTips.init();
    toolbar.render('toolbar');
    /*ds.load({params:{start: 0, limit: 15}});*/
    so.write('amcharts_1233221819843');
    " );

  $sfExtjs2Plugin->beginApplication ( array ('name' => 'App', 'private' => $private, 'public' => $public ) );
  $sfExtjs2Plugin->endApplication ();

  $sfExtjs2Plugin->initApplication ( 'App' );
  $sfExtjs2Plugin->end ();

?>