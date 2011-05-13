<div id="toolbar"></div>
<?php
  use_helper ( 'sfExtjs2' );

  $private = array ( );
  $public = array ( );

  $sfExtjs2Plugin = new sfExtjs2Plugin( array ('theme' => 'blue' ), array ('css' => array('/css/my-extjs.css') ) );
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
  
  $private['center_panel']=$sfExtjs2Plugin->GridPanel(
  	array
	    (
			'id'=>'center_panel',
			'region'=>'center',
			'title'=>'Dashboard',
			'autoScroll'=>'true',
			'width'       => '100%',
			'frame'=>'true',
			'height'      => '500',
			'collapsible'=>'true',
	        'style'=>'margin:5px 0 0 5px;',
			'cm' => $sfExtjs2Plugin->asVar ( 'cm' ),
			'ds' => $sfExtjs2Plugin->asVar ( 'ds' ),
			'autoExpandColumn'  => 'extra',
			'bbar' => $sfExtjs2Plugin->asVar ( 'pt' ),
			'tools'=>array(
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
    ds.load({params:{start: 0, limit: 15}});
    " );

  $sfExtjs2Plugin->beginApplication ( array ('name' => 'App', 'private' => $private, 'public' => $public ) );
  $sfExtjs2Plugin->endApplication ();

  $sfExtjs2Plugin->initApplication ( 'App' );
  $sfExtjs2Plugin->end ();

?>