<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="title" content="Immune Security Manager" />
<meta name="robots" content="index, follow" />
<meta name="description" content="Security Manager" />
<meta name="keywords" content="Immune, security, manager" />
<meta name="language" content="en" />
<title>Immune Security Manager</title>
<script type="text/javascript" src="/appFlowerPlugin/extjs-2/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="/appFlowerPlugin/extjs-2/ext-all-debug.js"></script>
<script type="text/javascript" src="/appFlowerPlugin/extjs-2/examples/form/Ext.ux.plugins.HelpText.js"></script>

<script type="text/javascript" src="/js/custom/cheatJS.js"></script>
<script type="text/javascript" src="/appFlowerPlugin/extjs-2/examples/grid/Ext.ux.GridColorView.js"></script>
<script type="text/javascript" src="/appFlowerPlugin/extjs-2/examples/grid/Ext.ux.GroupingColorView.js"></script>
<script type="text/javascript" src="/appFlowerPlugin/extjs-2/examples/grid/filter/ux/grid/GridFilters.js"></script>
<script type="text/javascript" src="/appFlowerPlugin/extjs-2/examples/grid/filter/ux/grid/filter/Filter.js"></script>
<script type="text/javascript" src="/appFlowerPlugin/extjs-2/examples/grid/filter/ux/grid/filter/StringFilter.js"></script>
<script type="text/javascript" src="/appFlowerPlugin/extjs-2/examples/grid/filter/ux/grid/filter/DateFilter.js"></script>
<script type="text/javascript" src="/appFlowerPlugin/extjs-2/examples/grid/filter/ux/grid/filter/ListFilter.js"></script>
<script type="text/javascript" src="/appFlowerPlugin/extjs-2/examples/grid/filter/ux/grid/filter/NumericFilter.js"></script>

<script type="text/javascript" src="/appFlowerPlugin/extjs-2/examples/grid/filter/ux/grid/filter/BooleanFilter.js"></script>
<script type="text/javascript" src="/appFlowerPlugin/extjs-2/examples/grid/filter/ux/menu/EditableItem.js"></script>
<script type="text/javascript" src="/appFlowerPlugin/extjs-2/examples/grid/filter/ux/menu/RangeMenu.js"></script>
<script type="text/javascript" src="/appFlowerPlugin/extjs-2/examples/grid/RowExpander.js"></script>
<script type="text/javascript" src="/appFlowerPlugin/extjs-2/examples/rowactionsImm/js/Ext.ux.GridRowActions.js"></script>
<script type="text/javascript" src="/appFlowerPlugin/extjs-2/examples/treegrid/Ext.ux.CheckboxSelectionModel.js"></script>
<script type="text/javascript" src="/appFlowerPlugin/extjs-2/examples/treegrid/TreeGrid.js"></script>
<script type="text/javascript" src="/appFlowerPlugin/extjs-2/examples/form/Ext.ux.form.Combo.js"></script>
<script type="text/javascript" src="/appFlowerPlugin/extjs-2/examples/form/Ext.ux.plugins.ExtendedComboBox.js"></script>

<script type="text/javascript" src="/appFlowerPlugin/extjs-2/examples/datepickerplus/Ext.ux.datepickerplus.js"></script>
<script type="text/javascript" src="/appFlowerPlugin/build/widgets/form/Label-min.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="/appFlowerPlugin/extjs-2/resources/css/ext-all.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/appFlowerPlugin/patches/fixes.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/my-extjs.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/appFlowerPlugin/extjs-2/examples/rowactionsImm/css/Ext.ux.GridRowActions.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/appFlowerPlugin/extjs-2/examples/rowactionsImm/css/icons.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/appFlowerPlugin/extjs-2/examples/treegrid/css/TreeGrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/appFlowerPlugin/extjs-2/examples/datepickerplus/datepickerplus.css" />
</head>

<body id="body" lang="en">


<script type='text/javascript'>


// appFlower: v1.0
Ext.BLANK_IMAGE_URL = '/appFlowerPlugin/extjs-2/resources/images/default/s.gif';
Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
// application: App
var App = function () { 
var panel_m124Lu44eD4sa43L = new Ext.Panel ({
border: false,
header: false,
style: "background:transparent;",
idxml: false,
id: "panel_m124Lu44eD4sa43L",
getWidgetConfig: function () { var o={}; o.idxml=this.idxml || false; return o; },
autoScroll: true,
autoHeight: true,
frame: true,
items: [
{
html: "Search box will let you perform quick search of logs, events and netflow data.<br \/>\r\n\t\t\t  Use filters like <b>log:<\/b> to search for log messages, <b>event:<\/b> to search for events and <b>netflow:<\/b> to search for netflow data.<br \/>\r\n\t\t\t  You can <b>search for phrases by enclosing it in quotes.<\/b><br \/>\r\n\t\t\t  <b>Exclusion search<\/b> can be done by <b>prepending \"-\"<\/b> before search word or phrase.<br \/>\r\n\t\t\t  <br \/>\r\n\t\t\t  Example:<br \/>\r\n\t\t\t  <b>log: user -logout \"ssh login\" -\"ssh logout\"<\/b><br \/>\r\n\t\t\t  <br \/>\r\n\t\t\t  Above example will search for <b>log messages containing word \"user\" and phrase \"ssh login\" and not containing word \"logout\" and phrase \"ssh logout\"<\/b><br \/>\r\n\t\t\t   ",
border: false,
style: "text-align:left;"
}
]
});
var window_a55mm129SS66Gn93 = new Ext.Window ({
constrain: true,
layout: "fit",
width: 500,
height: 500,
closeAction: "hide",
plain: true,
modal: true,
id: "window_a55mm129SS66Gn93",
title: "Search help",
autoHeight: true,
items: [
panel_m124Lu44eD4sa43L
]
});
var panel_a8Si36ht98ec85ra = new Ext.Panel ({
border: false,
header: false,
style: "background:transparent;",
idxml: false,
id: "panel_a8Si36ht98ec85ra",
getWidgetConfig: function () { var o={}; o.idxml=this.idxml || false; return o; },
autoScroll: true,
autoHeight: true,
frame: true,
items: [
{
html: "This product is created by iMMUNE, Securing Business Values Copyright&copy; 2008<br><br>Your license information is<br>Software license no. 6241-4710971579<br><b>License is active !<\/b><br><br>To view End User License Agreement visit <a href=\"http:\/\/www.immune.dk\/cms\/en\/eula\" target=\"_blank\">http:\/\/www.immune.dk\/cms\/en\/eula<\/a><br><br><u>Documentation and Questions<\/u><br>If you have any questions about the product functionality, you can lookup the v4.0 Documentation Handbook at <a href=\"http:\/\/www.immune.dk\/logex_userguide_4_0\" target=\"_blank\">http:\/\/www.immune.dk\/logex_userguide_4_0<\/a> or ask your questions on the Forum at <a href=\"http:\/\/www.immune.dk\/forum\"  target=\"_blank\">http:\/\/www.immune.dk\/forum<\/a><br><br><u>Bug Reporting and Support<\/u><br>If you found a bug or need help with support, please go to our support system and report your findings and we will do our best to answer and\/or solve the issues. You can reach the support system at <a href=\"http:\/\/www.immune.dk\/ticket\" target=\"_blank\">http:\/\/www.immune.dk\/ticket<\/a>",
border: false,
style: "text-align:left;"
}
]
});
var window_W138Ar25en92Hi50 = new Ext.Window ({
constrain: true,
layout: "fit",
width: 500,
height: 500,
closeAction: "hide",
plain: true,
modal: true,
id: "window_W138Ar25en92Hi50",
title: "About",
autoHeight: true,
items: [
panel_a8Si36ht98ec85ra
]
});
var toolbar = new Ext.Toolbar ({
id: "toolbar",
items: [
{
text: "Dashboard",
tooltip: { text: "Your security overview",
title: "Security Dashboard" },
menu: {
items: [
{ text: "<b>Dashboard<\/b>",
handler: function () { window.location.href="/pages/dashboard"; } },
{ text: "by Latest Events",
handler: function () { window.location.href="/eventmanagement/listNewEvents"; } },
'-',
{ text: "Netflow Analysis",
menu: {
items: [
{ text: "Netflow Search",
handler: function () { window.location.href="/netflow/firewallSearch"; } },
{ text: "Netflow Overview",
handler: function () { window.location.href="/netflow/netflowoverview"; } },
{ text: "Top Source",
menu: {
items: [
{ text: "Overview",
handler: function () { window.location.href="/netflow/listTopSource"; } },
{ text: "Allowed",
handler: function () { window.location.href="/netflow/listTopSourceAllowed"; } },
{ text: "Blocked",
handler: function () { window.location.href="/netflow/listTopSourceBlocked"; } }
]
} },
{ text: "Top Destination",
menu: {
items: [
{ text: "Overview",
handler: function () { window.location.href="/netflow/listTopDestination"; } },
{ text: "Allowed",
handler: function () { window.location.href="/netflow/listTopDestinationAllowed"; } },
{ text: "Blocked",
handler: function () { window.location.href="/netflow/listTopDestinationBlocked"; } }
]
} },
{ text: "Top Port",
menu: {
items: [
{ text: "Overview",
handler: function () { window.location.href="/netflow/listTopPort"; } },
{ text: "Allowed",
handler: function () { window.location.href="/netflow/listTopPortAllowed"; } },
{ text: "Blocked",
handler: function () { window.location.href="/netflow/listTopPortBlocked"; } }
]
} },
{ text: "Top Protocol",
menu: {
items: [
{ text: "Overview",
handler: function () { window.location.href="/netflow/listTopProtocol"; } },
{ text: "Allowed",
handler: function () { window.location.href="/netflow/listTopProtocolAllowed"; } },
{ text: "Blocked",
handler: function () { window.location.href="/netflow/listTopProtocolBlocked"; } }
]
} },
{ text: "Netflow Connections",
handler: function () { window.location.href="/netflow/listConnection"; } }
]
} },
'-'
]
}
},
{
text: "Appliance Setup",
tooltip: { text: "Appliance Setup lets you configure the appliance system settings, software updates, licensing..",
title: "Appliance Setup" },
menu: {
items: [
{ text: "<b>Appliance Setup<\/b>",
handler: function () { window.location.href="/pages/appliancesetup"; } },
{ text: "Static Routes",
handler: function () { window.location.href="/appliance_system/listNetworkStaticRoute"; } },
'-',
{ text: "Services",
menu: {
items: [
{ text: "Email Messaging",
handler: function () { window.location.href="/appliance_system/editEmail"; } },
{ text: "Event Correlator",
handler: function () { window.location.href="/appliance_system/editEventCorrelator"; } },
{ text: "Event Processing",
handler: function () { window.location.href="/appliance_system/eventManager"; } },
{ text: "File Sharing (FTP)",
handler: function () { window.location.href="/appliance_system/editFtp"; } },
{ text: "Log Collector",
handler: function () { window.location.href="/appliance_system/editLog"; } },
{ text: "NADE Engine",
handler: function () { window.location.href="/appliance_system/editNade"; } },
{ text: "Network Monitor",
handler: function () { window.location.href="/appliance_system/editMonitorScanner"; } },
{ text: "Network Time Protocol (NTP)",
handler: function () { window.location.href="/appliance_system/editNtp"; } },
{ text: "Resource Monitoring (SNMP Fetcher)",
handler: function () { window.location.href="/appliance_system/editSnmpfetcher"; } },
{ text: "Secure Shell Access (SSH)",
handler: function () { window.location.href="/appliance_system/editSsh"; } },
{ text: "SNMP Service",
handler: function () { window.location.href="/appliance_system/editSnmp"; } }
]
} },
'-',
{ text: "SSL Certificate",
handler: function () { window.location.href="/appliance_system/sslWizard"; } },
'-',
{ text: "Software Updates",
handler: function () { window.location.href="/appliance_system/softwareupdate"; } },
{ text: "Software License",
handler: function () { window.location.href="/appliance_system/softwarelicense"; } },
{ text: "Reboot \/ Shutdown",
handler: function () { window.location.href="/appliance_system/showPowerManagement"; } },
{ text: "Support Connection",
handler: function () { window.location.href="/appliance_system/supportConnection"; } }
]
}
},
{
text: "Event Management",
tooltip: { text: "Viewing events triggered and configuring event rules",
title: "Event Management" },
menu: {
items: [
{ text: "<b>Event Search<\/b>",
handler: function () { window.location.href="/eventmanagement/eventSearch"; } },
{ text: "Event Viewing",
handler: function () { window.location.href="/eventmanagement/eventmanagement"; } },
'-',
{ text: "Event Configuration",
menu: {
items: [
{ text: "Event Policies",
handler: function () { window.location.href="/eventmanagement/listEventPolicy"; } },
{ text: "Event Correlation Rules",
handler: function () { window.location.href="/eventmanagement/listEventCorrelation"; } },
{ text: "Event Sources",
handler: function () { window.location.href="/eventmanagement/listEventSource"; } },
{ text: "Event Classification",
handler: function () { window.location.href="/eventmanagement/listEventCategory"; } }
]
} },
{ text: "Active Response",
menu: {
items: [
{ text: "Active Response Connectors",
handler: function () { window.location.href="/activeresponse/listActiveResponseConnector"; } }
]
} },
{ text: "NADE",
menu: {
items: [
{ text: "NADE Signatures",
handler: function () { window.location.href="/nade/listNadeSignature"; } },
{ text: "NADE Network Whitelist",
handler: function () { window.location.href="/nade/listNadeNetwork"; } }
]
} }
]
}
},
{
text: "Log Management",
tooltip: { text: "Log Management, view log messages, create new log signatures..",
title: "Log Management" },
menu: {
items: [
{ text: "<b>Log Search<\/b>",
handler: function () { window.location.href="/loganalysis/logSearch"; } },
{ text: "Log Browser",
handler: function () { window.location.href="/loganalysis/logbrowser"; } },
{ text: "Snmp Browser",
handler: function () { window.location.href="/loganalysis/snmpbrowser"; } },
'-',
{ text: "Signatures",
menu: {
items: [
{ text: "Log signatures",
handler: function () { window.location.href="/loganalysis/listLogSignature"; } },
{ text: "Firewall signatures",
handler: function () { window.location.href="/loganalysis/listLogFirewallSignature"; } }
]
} }
]
}
},
{
text: "ServiceMon",
tooltip: { text: "Network Monitoring",
title: "Monitoring" },
menu: {
items: [
{ text: "<b>Network Overview<\/b>",
handler: function () { window.location.href="/networkmonitor/networkmonitor"; } },
{ text: "Resource Monitoring",
menu: {
items: [
{ text: "SNMP Graphs",
handler: function () { window.location.href="/networkmonitor_snmp/listServerSnmpGraph"; } },
{ text: "SNMP Connectors",
handler: function () { window.location.href="/networkmonitor_snmp/listSnmpConnection"; } },
{ text: "SNMP Group",
handler: function () { window.location.href="/networkmonitor_snmp/listSnmpGroup"; } },
{ text: "SNMP Templates",
handler: function () { window.location.href="/networkmonitor_snmp/listSnmpPackage"; } }
]
} }
]
}
},
{
text: "Administration",
tooltip: { text: "Product Management, adding users, servers, objects etc.",
title: "Administration" },
menu: {
items: [
{ text: "<b>Administration<\/b>",
handler: function () { window.location.href="/pages/administration"; } },
{ text: "Server Management",
menu: {
items: [
{ text: "Server Settings",
handler: function () { window.location.href="/server/listServer"; } },
{ text: "Server Groups",
handler: function () { window.location.href="/server/listServerGroup"; } },
{ text: "Server Locations",
handler: function () { window.location.href="/server/listServerLocation"; } },
{ text: "Server Customers",
handler: function () { window.location.href="/server/listServerCustomer"; } },
{ text: "Servers (by group)",
handler: function () { window.location.href="/server/groupEdit"; } },
{ text: "Retention Policies",
handler: function () { window.location.href="/server/listRetentionPolicy"; } },
{ text: "Risk Assessment",
handler: function () { window.location.href="/server/listRiskAssessment"; } }
]
} },
{ text: "User Management",
menu: {
items: [
{ text: "Users",
handler: function () { window.location.href="/sfGuardUser/listUser"; } },
{ text: "Roles",
handler: function () { window.location.href="/sfGuardGroup/list"; } },
{ text: "Site Permissions",
handler: function () { window.location.href="/SfGuardPermission/list"; } },
{ text: "Object Permissions",
handler: function () { window.location.href="/object_permission/listObjectPermission"; } }
]
} },
{ text: "Object Mapping",
menu: {
items: [
{ text: "Network Addreses Group",
handler: function () { window.location.href="/networkabstract/listNetworkAddressGroup"; } },
{ text: "Network Addreses",
handler: function () { window.location.href="/networkabstract/listNetworkAddress"; } },
{ text: "Network Service Group",
handler: function () { window.location.href="/networkabstract/listNetworkServiceGroup"; } },
{ text: "Network Services",
handler: function () { window.location.href="/networkabstract/listNetworkService"; } },
{ text: "User Mapping",
handler: function () { window.location.href="/userabstract/listUserObject"; } },
{ text: "User Group Mapping",
handler: function () { window.location.href="/userabstract/listUserObjectGroup"; } }
]
} },
{ text: "Backup & Recovery",
menu: {
items: [
{ text: "Backup",
handler: function () { window.location.href="/backup_recovery/backup"; } },
{ text: "Recovery",
handler: function () { window.location.href="/backup_recovery/recovery"; } }
]
} }
]
}
},
{
text: "Incidents",
tooltip: { text: "Incident Management, keep track of security issues",
title: "Incidents" },
menu: {
items: [
{ text: "<b>Incident Management<\/b>",
handler: function () { window.location.href="/incidents/list"; } },
'-',
{ text: "Incident Types",
handler: function () { window.location.href="/incidents/listType"; } }
]
}
},
{
text: "Reports",
tooltip: { text: "Report Generation",
title: "Reports" },
menu: {
items: [
{ text: "<b>Reports<\/b>",
handler: function () { window.location.href="/pages/report"; } }
]
}
},
{
text: "Diagnostics",
tooltip: { text: "Diagnostic utilities and information for troubleshooting",
title: "Diagnostics" },
menu: {
items: [
{ text: "<b>System Health<\/b>",
handler: function () { window.location.href="/diagnostics/systemHealth"; } },
{ text: "Process status",
handler: function () { window.location.href="/diagnostics/processStatus"; } },
{ text: "Connectivity",
handler: function () { window.location.href="/diagnostics/connectivity"; } },
{ text: "Advanced",
menu: {
items: [
{ text: "Kernel settings",
handler: function () { window.location.href="/diagnostics/kernelSettings"; } },
{ text: "Network stack",
handler: function () { window.location.href="/diagnostics/networkStack"; } },
{ text: "Routing table",
handler: function () { window.location.href="/diagnostics/routingTable"; } },
{ text: "Database jobs",
handler: function () { window.location.href="/diagnostics/databaseJobs"; } }
]
} }
]
}
},
{
xtype: "tbfill"
},
{
xtype: "textfield",
width: 150,
plugins: [
Ext.ux.plugins.HelpText
],
labelStyle: "width:75px;font-size:11px;font-weight:bold;padding:0 3px 3px 0;",
name: "search_input",
id: "field_a58te2eG145ic97m",
listeners: { specialkey: function (field,event) { if(Ext.EventObject.getKey()==Ext.EventObject.ENTER){window.location.href='/eventmanagement/quickSearch?search='+field.getValue();} } },
emptyText: "search"
},
{
text: "<img src=\"\/images\/famfamfam\/help.png\" border=\"0\">",
tooltip: { text: "Search help",
title: "About search" },
listeners: { click: function (field,event) { window_a55mm129SS66Gn93.show(field); } }
},
{
xtype: "tbfill"
},
{
text: "<img src=\"\/images\/famfamfam\/user_go.png\" border=\"0\">",
handler: function () { window.location.href="/logout"; },
tooltip: { text: "Click to log out",
title: "admin" }
},
{
text: "<img src=\"\/images\/famfamfam\/information.png\" border=\"0\">",
tooltip: { text: "Information",
title: "About details" },
listeners: { click: function (field,event) { window_W138Ar25en92Hi50.show(field); } }
}
]
});
var north_panel = new Ext.Panel ({
id: "north_panel",
region: "north",
height: 32,
border: false,
bodyStyle: "background-color:#dfe8f6;"
});
var panel_c147aL18rl58be32 = new Ext.Panel ({
border: false,
header: false,
style: "padding:5px;",
idxml: false,
id: "panel_c147aL18rl58be32",
getWidgetConfig: function () { var o={}; o.idxml=this.idxml || false; return o; },
html: "<div style=\"white-space:normal;\">This widget lists all the available log signatures along with their types and severity. Selected signatures can be included in an event policy\tand applied to a server. The server then responds to only those log messages which is captured by the signatures included in the policy. New signatures can also be added from this widget. From this page you can also export your private signatures or import signatures from a xml file.<\/div>",
listeners: { render: function (panel) { if(panel.body){panel.body.dom.style.border='0px';panel.body.dom.style.background='transparent';} } }
});
var grid_actions_A94hS113in148iL1 = new Ext.ux.GridRowActions ({
header: "Actions",
groupActions: [],
actions: [
{
name: "edit",
iconCls: "icon-edit-record",
url: "\/loganalysis\/editLogSignature",
tooltip: "Edit Log Signature",
post: false,
updater: false,
permissions: "*",
urlIndex: "action1",
hideIndex: "hide1"
},
{
name: "delete",
iconCls: "icon-minus",
url: "\/loganalysis\/deleteLogSignature",
tooltip: "Delete Log Signature",
post: false,
updater: false,
permissions: "*",
confirm: true,
message: "Are you sure you would like to delete this item?",
urlIndex: "action2",
hideIndex: "hide2"
}
]
});
var button_e59ru83nZ52Gl1oF = new Ext.Button ({
disabled: false,
icon: "\/images\/famfamfam\/link.png",
handler: function () { window.location.href="/loganalysis/editLogSignature"; },
cls: "x-btn-text-icon",
text: "Add Signature",
name: "list2_Add Signature",
iconCls: "icon-plus",
post: false,
updater: false,
permissions: "*"
});
var button_r94Ss77is24ci25a = new Ext.Button ({
disabled: false,
icon: "\/images\/famfamfam\/link.png",
handler: function () { window.location.href="/loganalysis/xml"; },
cls: "x-btn-text-icon",
text: "Export Signature",
name: "list2_Export Signature",
iconCls: "icon-export",
post: false,
updater: false,
permissions: "*"
});
var button_H129ii16oo3Dt101 = new Ext.Button ({
disabled: false,
icon: "\/images\/famfamfam\/link.png",
handler: function () { window.location.href="/loganalysis/uploadSignature"; },
cls: "x-btn-text-icon",
text: "Import Signature",
name: "list2_Import Signature",
iconCls: "icon-import",
post: false,
updater: false,
permissions: "*"
});
var button_S107Da66tn85ti31 = new Ext.Button ({
disabled: false,
cls: "x-btn-text-icon",
text: "Share All Signatures",
listeners: { click: function (field,event) { 
								Ext.Msg.show({
								   title:"Confirmation Required",
								   msg: "Are you sure want to share all log signatures?",
								   buttons: Ext.Msg.YESNO,
								   fn: function(buttonId){if(buttonId == "yes"){
								Ext.Ajax.request({ 
									url: "/loganalysis/shareAllLogSignature",
									method:"post", 
									params:{}, 
									success:function(response, options){
										response=Ext.decode(response.responseText);
										if(response.message){
											var win=Ext.Msg.alert("Success",response.message);
											if(response.redirect){
												win.getDialog().on('hide',function(){
													win.getDialog().suspendEvents();
													window.location.href=response.redirect;
												});
											}
										}
									},
									failure: function(response,options) {
										if(response.message){
											Ext.Msg.alert("Failure",response.message);
										}
									}
								});
							}},
								   icon: Ext.MessageBox.QUESTION								   
								});
							 } },
icon: "\/images\/famfamfam\/arrow_out.png"
});
var button_p46rr39rt143Hk14 = new Ext.Button ({
disabled: false,
icon: "\/images\/famfamfam\/link.png",
cls: "x-btn-text-icon",
text: "Expanded View",
listeners: { click: function (field,event) { 
							if(field.getText() == 'Expanded View'){
								field.setText('List View');
								field.setIconClass('icon-list-view');						
								grid_G108lt48nl66eH12.getView().el.select('.x-grid3-cell-inner').setStyle({'white-space':'normal'})						
							}else{
								field.setText('Expanded View');	
								field.setIconClass('icon-expanded-view');						
								grid_G108lt48nl66eH12.getView().el.select('.x-grid3-cell-inner').setStyle({'white-space':'nowrap'})					
							}
						 } },
iconCls: "icon-expanded-view"
});
var reader_r39md146ce135aM6 = new Ext.data.JsonReader ({
id: "availability_id",
fields: [
{
name: "id",
type: "auto"
},
{
name: "name",
type: "auto"
},
{
name: "publisher_id",
type: "auto"
},
{
name: "vendor_id",
type: "auto"
},
{
name: "source_type",
type: "auto"
},
{
name: "source_name_id",
type: "auto"
},
{
name: "category_id",
type: "auto"
},
{
name: "confidentiality_id",
type: "auto"
},
{
name: "integrity_id",
type: "auto"
},
{
name: "availability_id",
type: "auto"
},
{
name: "action1",
type: "string"
},
{
name: "hide1",
type: "boolean"
},
{
name: "action2",
type: "string"
},
{
name: "hide2",
type: "boolean"
},
{
name: "message"
},
{
name: "redirect"
},
{
name: "_color",
type: "auto"
},
{
name: "_cell_color",
type: "auto"
},
{
name: "_selected",
type: "auto"
}
],
totalProperty: "totalCount",
root: "rows"
});
var store_a94Hs135ra79td6i = new Ext.data.GroupingStore ({
reader: reader_r39md146ce135aM6,
remoteSort: true,
proxy: new Ext.data.HttpProxy ({
url: "https:\/\/192.168.198.136\/parser\/listjson?uid=a37797a553836a836007f3d5f56da374",
method: "GET",
disableCaching: true
}),
listeners: { beforeload: function (object,options) { if(!Ext.isIE){grid_G108lt48nl66eH12.getEl().mask('Loading, please Wait...', 'x-mask-loading');} },
load: function (object,records,options) { if(records.length>0&&records[0].json.redirect&&records[0].json.message){var rec=records[0].json;var win=Ext.Msg.alert("Failure", rec.message);win.getDialog().on('hide',function(){window.location.href=rec.redirect;});}else{if(!Ext.isIE){grid_G108lt48nl66eH12.getEl().unmask();}} },
loadexception: function () { if(!Ext.isIE){grid_G108lt48nl66eH12.getEl().unmask();} } }
});
var pt_e98kt39FA123Dr18 = new Ext.PagingToolbar ({
store: store_a94Hs135ra79td6i,
displayInfo: true,
pageSize: 25
});
var filters_H39tK86eS75ZA103 = new Ext.ux.grid.GridFilters ({
filters: [
{ type: "string",
dataIndex: "id" },
{ type: "string",
dataIndex: "name" },
{ type: "string",
dataIndex: "publisher_id" },
{ type: "string",
dataIndex: "vendor_id" },
{ type: "string",
dataIndex: "source_type" },
{ type: "string",
dataIndex: "source_name_id" },
{ type: "string",
dataIndex: "category_id" },
{ type: "string",
dataIndex: "confidentiality_id" },
{ type: "string",
dataIndex: "integrity_id" },
{ type: "string",
dataIndex: "availability_id" }
]
});
var sm_n112An105sH140ma = new Ext.ux.CheckboxSelectionModel ();
var grid_G108lt48nl66eH12 = new Ext.grid.GridPanel ({
loadMask: true,
frame: false,
bodyStyle: "border: 1px solid #8db2e3;",
idxml: false,
anchor: "100%",
collapsible: true,
draggable: true,
cls: "x-portlet",
getWidgetConfig: function () { var o={};
			 o.idxml=this.idxml || false;
			 return o; },
fileUpload: false,
autoHeight: true,
clearGrouping: false,
tree: false,
select: true,
pager: true,
border: true,
display_visible: ["id","name","publisher_id","vendor_id","source_type","source_name_id","category_id","confidentiality_id","integrity_id","availability_id"],
id: "log_signatures",
action: "n\/a",
title: "Log Signatures",
forceFit: true,
tbar: [
button_e59ru83nZ52Gl1oF,
button_r94Ss77is24ci25a,
button_H129ii16oo3Dt101,
button_S107Da66tn85ti31,
{
xtype: "tbfill"
},
button_p46rr39rt143Hk14,
{
xtype: "tbfill"
},
{
text: "More Actions",
menu: {
items: [
{ text: "Select All",
icon: "\/images\/famfamfam\/flag_green.png",
listeners: { click: function () { grid_G108lt48nl66eH12.getSelectionModel().selectAll() } } },
{ text: "Deselect All",
icon: "\/images\/famfamfam\/flag_red.png",
listeners: { click: function () { grid_G108lt48nl66eH12.getSelectionModel().clearSelections() } } },
{ text: "Delete Selected",
icon: "\/images\/famfamfam\/cross.png",
listeners: { click: function (field,event) { 
							if(!grid_G108lt48nl66eH12.getStore().getCount()){
								Ext.Msg.alert("No Data In Grid","There is no data on grid.");
								return;
							}
														
								if(!grid_G108lt48nl66eH12.getSelectionModel().getCount()){
									Ext.Msg.alert("No items selected","Please select at least one item");
									return;
								}
							
								Ext.Msg.show({
								   title:"Confirmation Required",
								   msg: "Are you sure want to delete selected log signatures?",
								   buttons: Ext.Msg.YESNO,
								   fn: function(buttonId){if(buttonId == "yes"){
							Ext.Ajax.request({ 
								url: "/loganalysis/listActionsRemoveLogSignature",
								method:"post", 
								params:{"selections":grid_G108lt48nl66eH12.getSelectionModel().getSelectionsJSON()}, 
								success:function(response, options){
									response=Ext.decode(response.responseText);
									if(response.message){
										var win=Ext.Msg.alert("Success",response.message);
										if(response.redirect){
											win.getDialog().on('hide',function(){
												win.getDialog().suspendEvents();
												window.location.href=response.redirect;
											});
										}
									}
								},
								failure: function(response,options) {
									if(response.message){
										Ext.Msg.alert("Failure",response.message);
									}
								}
							});
						}},
								   icon: Ext.MessageBox.QUESTION								   
								});
							 } } },
{ text: "Delete All",
icon: "\/images\/famfamfam\/cross.png",
listeners: { click: function (field,event) { 
							if(!grid_G108lt48nl66eH12.getStore().getCount()){
								Ext.Msg.alert("No Data In Grid","There is no data on grid.");
								return;
							}
						
								Ext.Msg.show({
								   title:"Confirmation Required",
								   msg: "Are you sure want to delete all log signatures?",
								   buttons: Ext.Msg.YESNO,
								   fn: function(buttonId){if(buttonId == "yes"){
							Ext.Ajax.request({ 
								url: "/loganalysis/listActionsRemoveLogSignature/all",
								method:"post", 
								params:{"selections":grid_G108lt48nl66eH12.getSelectionModel().getSelectionsJSON()}, 
								success:function(response, options){
									response=Ext.decode(response.responseText);
									if(response.message){
										var win=Ext.Msg.alert("Success",response.message);
										if(response.redirect){
											win.getDialog().on('hide',function(){
												win.getDialog().suspendEvents();
												window.location.href=response.redirect;
											});
										}
									}
								},
								failure: function(response,options) {
									if(response.message){
										Ext.Msg.alert("Failure",response.message);
									}
								}
							});
						}},
								   icon: Ext.MessageBox.QUESTION								   
								});
							 } } },
{ text: "Share Selected",
icon: "\/images\/famfamfam\/arrow_out.png",
listeners: { click: function (field,event) { 
							if(!grid_G108lt48nl66eH12.getStore().getCount()){
								Ext.Msg.alert("No Data In Grid","There is no data on grid.");
								return;
							}
														
								if(!grid_G108lt48nl66eH12.getSelectionModel().getCount()){
									Ext.Msg.alert("No items selected","Please select at least one item");
									return;
								}
							
								Ext.Msg.show({
								   title:"Confirmation Required",
								   msg: "Are you sure want to share selected log signatures?",
								   buttons: Ext.Msg.YESNO,
								   fn: function(buttonId){if(buttonId == "yes"){
							Ext.Ajax.request({ 
								url: "/loganalysis/shareSelectedLogSignature",
								method:"post", 
								params:{"selections":grid_G108lt48nl66eH12.getSelectionModel().getSelectionsJSON()}, 
								success:function(response, options){
									response=Ext.decode(response.responseText);
									if(response.message){
										var win=Ext.Msg.alert("Success",response.message);
										if(response.redirect){
											win.getDialog().on('hide',function(){
												win.getDialog().suspendEvents();
												window.location.href=response.redirect;
											});
										}
									}
								},
								failure: function(response,options) {
									if(response.message){
										Ext.Msg.alert("Failure",response.message);
									}
								}
							});
						}},
								   icon: Ext.MessageBox.QUESTION								   
								});
							 } } }
]
}
}
],
view: new Ext.ux.GroupingColorView ({
forceFit: true,
groupTextTpl: " {text} ({[values.rs.length]} {[values.rs.length > 1 ? \"Items\" : \"Item\"]})"
}),
columns: [
{
dataIndex: "id",
header: "#ID",
sortable: true,
hidden: true,
hideable: true,
align: "left",
id: "id"
},
{
dataIndex: "name",
header: "Title",
sortable: true,
hidden: false,
hideable: true,
align: "left",
id: "name"
},
{
dataIndex: "publisher_id",
header: "Publisher",
sortable: true,
hidden: false,
hideable: true,
align: "left",
id: "publisher_id"
},
{
dataIndex: "vendor_id",
header: "#S.N",
sortable: true,
hidden: false,
hideable: true,
align: "left",
id: "vendor_id"
},
{
dataIndex: "source_type",
header: "Source type",
sortable: true,
hidden: false,
hideable: true,
align: "left",
id: "source_type"
},
{
dataIndex: "source_name_id",
header: "Source Name",
sortable: true,
hidden: false,
hideable: true,
align: "left",
id: "source_name_id"
},
{
dataIndex: "category_id",
header: "Event Classification",
sortable: true,
hidden: false,
hideable: true,
align: "left",
id: "category_id"
},
{
dataIndex: "confidentiality_id",
header: "Confidentiality",
sortable: true,
hidden: false,
hideable: true,
align: "left",
id: "confidentiality_id"
},
{
dataIndex: "integrity_id",
header: "Integrity",
sortable: true,
hidden: false,
hideable: true,
align: "left",
id: "integrity_id"
},
{
dataIndex: "availability_id",
header: "Availability",
sortable: true,
hidden: false,
hideable: true,
align: "left",
id: "availability_id"
},
grid_actions_A94hS113in148iL1,
sm_n112An105sH140ma
],
listeners: { click: function(e){			 
				var t = e.getTarget();				
				if(t.className != 'x-grid3-header'){
		            var r = e.getRelatedTarget();
		            var v = this.view;
		            var ci = v.findCellIndex(t.parentNode);
		            var ri = v.findRowIndex(t);	
		            var grid = this;
		            //alert(ci); alert(ri);            
		            if(ci === false || ri === false) return ;
		            var cell = this.getView().getCell(ri,ci);
		           
		            if(t.className == 'interactive-arrow-a'){
		            	ci = v.findCellIndex(t.parentNode.parentNode);
		            	var contextMenus = Array();
												
						grid.getSelectionModel().clearSelections();
						grid.getSelectionModel().selectRow(ri);		
						var fieldName = grid.getColumnModel().getDataIndex(ci);
						var record = grid.getSelectionModel().getSelected();
						var data = record.get(fieldName);					
						grid.getSelectionModel().clearSelections();
						var xy = e.getXY();
						var contextMenu = contextMenus[fieldName];
						if(contextMenu && data != ''){
							contextMenu.stack['text'] = data;
							contextMenu.stack['grid'] = grid;
							contextMenu.stack['ri'] = ri;
							contextMenu.stack['ci'] = ci;
							contextMenu.stack['cell'] = grid.getView().getCell(ri,ci);
							contextMenu.stack['cellDiv'] = grid.getView().getCell(ri,ci).getElementsByTagName('div')[0];			
							contextMenu.stack['rowDivs'] = grid.getView().getRow(ri).getElementsByTagName('div');
							contextMenu.showAt(xy);
						}		
		            }	
		        }          
	            
			},
mouseout: function(e){
				var t = e.getTarget();
				if(t.className != 'x-grid3-header'){
		            var r = e.getRelatedTarget();
		            var v = this.view;
		            var ci = v.findCellIndex(t);
		            var ri = v.findRowIndex(t);	            
		            if(ci === false || ri === false) return ;
		            var cell = this.getView().getCell(ri,ci);
		            if(cell){		            
		            	
		            	//Cross browser implementation
		            	var className = 'interactive-arrow-active';
		            	var tagName = 'span', _tags = cell.getElementsByTagName(tagName), _nodeList = [];
					    for (var i = 0, _tag; _tag = _tags[i++];) {
					        if (_tag.className.match(new RegExp('(\s|^)'+className+'(\s|$)'))) {
					            _nodeList.push(_tag);
					        }
					    }
					    //.............................................................................						            	
		            
			            var arrowDiv = _nodeList[0];
			            if(arrowDiv){
			            	arrowDiv.className = 'interactive-arrow';			            
			            }
		            }
	            }	            
			},
mouseover: function(e){			
				var t = e.getTarget();				
				if(t.className != 'x-grid3-header'){
		            var r = e.getRelatedTarget();
		            var v = this.view;
		            var ci = v.findCellIndex(t);
		            var ri = v.findRowIndex(t);
		           
		            if(ci === false || ri === false) return ;
		            var cell = this.getView().getCell(ri,ci);		           		           
		            if(cell){		            
		            	
		            	//Cross browser implementation
		            	var className = 'interactive-arrow';
		            	var tagName = 'span', _tags = cell.getElementsByTagName(tagName), _nodeList = [];
					    for (var i = 0, _tag; _tag = _tags[i++];) {
					        if (_tag.className.match(new RegExp('(\s|^)'+className+'(\s|$)'))) {
					            _nodeList.push(_tag);
					        }
					    }
					    //.............................................................................
							            	
		            
			            var arrowDiv = _nodeList[0];
			            if(arrowDiv){
			            	arrowDiv.className = 'interactive-arrow-active';			            	
			            }
		            }
	            }
	            
			},
render: function () { 
			this.store.load({
				params:{
					start:0, 
					limit:25
				}
			});
				var gcm = grid_G108lt48nl66eH12.getColumnModel();
				if(gcm.getColumnHeader(gcm.getColumnCount()-1) == '<div class="x-grid3-hd-checker" id="hd-checker">&#160;</div>') 
				gcm.moveColumn(gcm.getColumnCount()-1,0);
				 } },
plugins: [
filters_H39tK86eS75ZA103,
grid_actions_A94hS113in148iL1
],
store: store_a94Hs135ra79td6i,
bbar: pt_e98kt39FA123Dr18,
sm: sm_n112An105sH140ma
});
var center_panel = new Ext.Panel ({
id: "center_panel",
title: "Log Signatures",
autoScroll: true,
width: "auto",
frame: true,
collapsible: true,
style: "padding-right:5px;",
tools: [
{
id: "gear",
handler: function () { Ext.Msg.alert('Message', 'The Settings tool was clicked.'); }
},
{
id: "close",
handler: function (e,target,panel) { panel.ownerCt.remove(panel, true); }
}
],
tbar: [
panel_c147aL18rl58be32
],
listeners: { render: function () { if(this.getTopToolbar()&&this.getTopToolbar().container){this.getTopToolbar().container.addClass('tbarBottomBorderFix');} } },
items: [
grid_G108lt48nl66eH12
],
region: "center"
});
var reader_i3ne16sn145At72S = new Ext.data.JsonReader ({
id: "_id",
fields: [
{
name: "name",
type: "auto"
},
{
name: "message"
},
{
name: "redirect"
},
{
name: "_id",
type: "int"
},
{
name: "_parent",
type: "auto"
},
{
name: "_is_leaf",
type: "bool"
},
{
name: "_color",
type: "auto"
},
{
name: "_cell_color",
type: "auto"
},
{
name: "_buttonOnColumn",
type: "auto"
},
{
name: "_buttonText",
type: "auto"
},
{
name: "_buttonDescription",
type: "auto"
}
],
totalProperty: "totalCount",
root: "rows"
});
var store_h103iD86eA127oD1 = new Ext.ux.maximgb.tg.AdjacencyListStore ({
reader: reader_i3ne16sn145At72S,
remoteSort: true,
proxy: new Ext.data.HttpProxy ({
url: "https:\/\/192.168.198.136\/parser\/listjson?uid=b7709fe2a09249afbcb2ab7b246d9b87",
method: "GET",
disableCaching: true
}),
listeners: { beforeload: function (object,options) { if(!Ext.isIE){grid_S126ms59oL66Ka12.getEl().mask('Loading, please Wait...', 'x-mask-loading');} },
load: function (object,records,options) { if(records.length>0&&records[0].json.redirect&&records[0].json.message){var rec=records[0].json;var win=Ext.Msg.alert("Failure", rec.message);win.getDialog().on('hide',function(){window.location.href=rec.redirect;});}else{if(!Ext.isIE){grid_S126ms59oL66Ka12.getEl().unmask();}} },
loadexception: function () { if(!Ext.isIE){grid_S126ms59oL66Ka12.getEl().unmask();} } }
});
var filters_D1ep31BG138Gk71H = new Ext.ux.grid.GridFilters ({
filters: [
{ type: "string",
dataIndex: "name" }
]
});
var grid_S126ms59oL66Ka12 = new Ext.ux.maximgb.tg.GridPanel ({
loadMask: true,
frame: false,
bodyStyle: "",
idxml: false,
getWidgetConfig: function () { var o={};
			 o.idxml=this.idxml || false;
			 return o; },
fileUpload: false,
autoHeight: true,
clearGrouping: false,
tree: true,
select: false,
pager: false,
border: false,
portal: false,
display_visible: ["name"],
id: "servers",
action: "n\/a",
iconCls: "server",
title: "Servers",
root_title: "Servers",
tools: null,
forceFit: true,
viewConfig: {
forceFit: true
},
columns: [
{
dataIndex: "name",
header: "Group",
sortable: false,
width: 150,
hidden: false,
hideable: true,
align: "left",
id: "name"
}
],
master_column_id: "name",
listeners: { click: function(e){			 
				var t = e.getTarget();				
				if(t.className != 'x-grid3-header'){
		            var r = e.getRelatedTarget();
		            var v = this.view;
		            var ci = v.findCellIndex(t.parentNode);
		            var ri = v.findRowIndex(t);	
		            var grid = this;
		            //alert(ci); alert(ri);            
		            if(ci === false || ri === false) return ;
		            var cell = this.getView().getCell(ri,ci);
		           
		            if(t.className == 'interactive-arrow-a'){
		            	ci = v.findCellIndex(t.parentNode.parentNode);
		            	var contextMenus = Array();
												
						grid.getSelectionModel().clearSelections();
						grid.getSelectionModel().selectRow(ri);		
						var fieldName = grid.getColumnModel().getDataIndex(ci);
						var record = grid.getSelectionModel().getSelected();
						var data = record.get(fieldName);					
						grid.getSelectionModel().clearSelections();
						var xy = e.getXY();
						var contextMenu = contextMenus[fieldName];
						if(contextMenu && data != ''){
							contextMenu.stack['text'] = data;
							contextMenu.stack['grid'] = grid;
							contextMenu.stack['ri'] = ri;
							contextMenu.stack['ci'] = ci;
							contextMenu.stack['cell'] = grid.getView().getCell(ri,ci);
							contextMenu.stack['cellDiv'] = grid.getView().getCell(ri,ci).getElementsByTagName('div')[0];			
							contextMenu.stack['rowDivs'] = grid.getView().getRow(ri).getElementsByTagName('div');
							contextMenu.showAt(xy);
						}		
		            }	
		        }          
	            
			},
mouseout: function(e){
				var t = e.getTarget();
				if(t.className != 'x-grid3-header'){
		            var r = e.getRelatedTarget();
		            var v = this.view;
		            var ci = v.findCellIndex(t);
		            var ri = v.findRowIndex(t);	            
		            if(ci === false || ri === false) return ;
		            var cell = this.getView().getCell(ri,ci);
		            if(cell){		            
		            	
		            	//Cross browser implementation
		            	var className = 'interactive-arrow-active';
		            	var tagName = 'span', _tags = cell.getElementsByTagName(tagName), _nodeList = [];
					    for (var i = 0, _tag; _tag = _tags[i++];) {
					        if (_tag.className.match(new RegExp('(\s|^)'+className+'(\s|$)'))) {
					            _nodeList.push(_tag);
					        }
					    }
					    //.............................................................................						            	
		            
			            var arrowDiv = _nodeList[0];
			            if(arrowDiv){
			            	arrowDiv.className = 'interactive-arrow';			            
			            }
		            }
	            }	            
			},
mouseover: function(e){			
				var t = e.getTarget();				
				if(t.className != 'x-grid3-header'){
		            var r = e.getRelatedTarget();
		            var v = this.view;
		            var ci = v.findCellIndex(t);
		            var ri = v.findRowIndex(t);
		           
		            if(ci === false || ri === false) return ;
		            var cell = this.getView().getCell(ri,ci);		           		           
		            if(cell){		            
		            	
		            	//Cross browser implementation
		            	var className = 'interactive-arrow';
		            	var tagName = 'span', _tags = cell.getElementsByTagName(tagName), _nodeList = [];
					    for (var i = 0, _tag; _tag = _tags[i++];) {
					        if (_tag.className.match(new RegExp('(\s|^)'+className+'(\s|$)'))) {
					            _nodeList.push(_tag);
					        }
					    }
					    //.............................................................................
							            	
		            
			            var arrowDiv = _nodeList[0];
			            if(arrowDiv){
			            	arrowDiv.className = 'interactive-arrow-active';			            	
			            }
		            }
	            }
	            
			},
render: function () { 
			this.store.load({
				params:{
					start:0, 
					limit:20
				}
			}); } },
plugins: [
filters_D1ep31BG138Gk71H
],
store: store_h103iD86eA127oD1
});
var panel_o59eG50ci85sW80n = new Ext.Panel ({
border: false,
header: false,
style: "padding:5px;",
idxml: false,
id: "panel_o59eG50ci85sW80n",
getWidgetConfig: function () { var o={}; o.idxml=this.idxml || false; return o; },
html: "<div style=\"white-space:normal;\">The Audit Log widget lets you know about the data that has been changed in the database. It shows the information about database table name, user who made the changes, the record that has been changed, the IP of the server from where the data has been changed etc. so that you can verify the authenticity of the data. It lets you know what is going wrong with the data stored, if any anynomous user changes the data.<\/div>",
listeners: { render: function (panel) { if(panel.body){panel.body.dom.style.border='0px';panel.body.dom.style.background='transparent';} } }
});
var button_t146cH147kx109me = new Ext.Button ({
disabled: false,
icon: "\/images\/famfamfam\/user_suit.png",
handler: function () { window.location.href="/audit/list"; },
cls: "x-btn-text-icon",
text: "Audit Log",
name: "list2_Audit Log",
post: false,
updater: false,
permissions: "*"
});
var reader_k81rA141Fs16ah53 = new Ext.data.JsonReader ({
id: "updated_at",
fields: [
{
name: "id",
type: "auto"
},
{
name: "table_id",
type: "auto"
},
{
name: "table_n",
type: "auto"
},
{
name: "module",
type: "auto"
},
{
name: "changes",
type: "auto"
},
{
name: "user_commit_msg",
type: "auto"
},
{
name: "user_id",
type: "auto"
},
{
name: "ip",
type: "auto"
},
{
name: "updated_at",
type: "auto"
},
{
name: "message"
},
{
name: "redirect"
},
{
name: "_color",
type: "auto"
},
{
name: "_cell_color",
type: "auto"
}
],
totalProperty: "totalCount",
root: "rows"
});
var store_S65aZ95Sm80mk12a = new Ext.data.GroupingStore ({
sortInfo: {
field: "updated_at",
direction: "DESC"
},
reader: reader_k81rA141Fs16ah53,
remoteSort: true,
proxy: new Ext.data.HttpProxy ({
url: "https:\/\/192.168.198.136\/parser\/listjson?uid=b408ada590a8fe904dad3e221934a501",
method: "GET",
disableCaching: true
}),
listeners: { beforeload: function (object,options) { if(!Ext.isIE){grid_G36am106Zn133mp1.getEl().mask('Loading, please Wait...', 'x-mask-loading');} },
load: function (object,records,options) { if(records.length>0&&records[0].json.redirect&&records[0].json.message){var rec=records[0].json;var win=Ext.Msg.alert("Failure", rec.message);win.getDialog().on('hide',function(){window.location.href=rec.redirect;});}else{if(!Ext.isIE){grid_G36am106Zn133mp1.getEl().unmask();}} },
loadexception: function () { if(!Ext.isIE){grid_G36am106Zn133mp1.getEl().unmask();} } }
});
var filters_m138sr61Hs117bS1 = new Ext.ux.grid.GridFilters ({
filters: [
{ type: "string",
dataIndex: "id" },
{ type: "string",
dataIndex: "table_id" },
{ type: "string",
dataIndex: "table_n" },
{ type: "string",
dataIndex: "module" },
{ type: "string",
dataIndex: "changes" },
{ type: "string",
dataIndex: "user_commit_msg" },
{ type: "string",
dataIndex: "user_id" },
{ type: "string",
dataIndex: "ip" },
{ type: "string",
dataIndex: "updated_at" }
]
});
var grid_G36am106Zn133mp1 = new Ext.grid.GridPanel ({
loadMask: true,
frame: false,
bodyStyle: "border: 1px solid #8db2e3;",
idxml: false,
anchor: "100%",
collapsible: true,
draggable: true,
cls: "x-portlet",
getWidgetConfig: function () { var o={};
			 o.idxml=this.idxml || false;
			 return o; },
fileUpload: false,
autoHeight: true,
clearGrouping: false,
tree: false,
select: false,
pager: false,
border: true,
display_visible: ["id","table_id","table_n","module","changes","user_commit_msg","user_id","ip","updated_at"],
id: "audit_trails",
action: "n\/a",
title: "",
forceFit: true,
tbar: [
button_t146cH147kx109me
],
view: new Ext.ux.GroupingColorView ({
forceFit: true,
groupTextTpl: " {text} ({[values.rs.length]} {[values.rs.length > 1 ? \"Items\" : \"Item\"]})"
}),
columns: [
{
dataIndex: "id",
header: "No.",
sortable: false,
hidden: false,
hideable: true,
align: "left",
id: "id"
},
{
dataIndex: "table_id",
header: "Table id",
sortable: true,
hidden: false,
hideable: true,
align: "left",
id: "table_id"
},
{
dataIndex: "table_n",
header: "Table Name",
sortable: false,
hidden: false,
hideable: true,
align: "left",
id: "table_n"
},
{
dataIndex: "module",
header: "Module",
sortable: false,
hidden: false,
hideable: true,
align: "left",
id: "module"
},
{
dataIndex: "changes",
header: "Changes",
sortable: false,
hidden: false,
hideable: true,
align: "left",
id: "changes"
},
{
dataIndex: "user_commit_msg",
header: "User Msg",
sortable: false,
hidden: false,
hideable: true,
align: "left",
id: "user_commit_msg"
},
{
dataIndex: "user_id",
header: "User",
sortable: false,
hidden: false,
hideable: true,
align: "left",
id: "user_id"
},
{
dataIndex: "ip",
header: "IP",
sortable: false,
hidden: false,
hideable: true,
align: "left",
id: "ip"
},
{
dataIndex: "updated_at",
header: "Updated At",
sortable: false,
hidden: false,
hideable: true,
align: "left",
id: "updated_at"
}
],
listeners: { click: function(e){			 
				var t = e.getTarget();				
				if(t.className != 'x-grid3-header'){
		            var r = e.getRelatedTarget();
		            var v = this.view;
		            var ci = v.findCellIndex(t.parentNode);
		            var ri = v.findRowIndex(t);	
		            var grid = this;
		            //alert(ci); alert(ri);            
		            if(ci === false || ri === false) return ;
		            var cell = this.getView().getCell(ri,ci);
		           
		            if(t.className == 'interactive-arrow-a'){
		            	ci = v.findCellIndex(t.parentNode.parentNode);
		            	var contextMenus = Array();
												
						grid.getSelectionModel().clearSelections();
						grid.getSelectionModel().selectRow(ri);		
						var fieldName = grid.getColumnModel().getDataIndex(ci);
						var record = grid.getSelectionModel().getSelected();
						var data = record.get(fieldName);					
						grid.getSelectionModel().clearSelections();
						var xy = e.getXY();
						var contextMenu = contextMenus[fieldName];
						if(contextMenu && data != ''){
							contextMenu.stack['text'] = data;
							contextMenu.stack['grid'] = grid;
							contextMenu.stack['ri'] = ri;
							contextMenu.stack['ci'] = ci;
							contextMenu.stack['cell'] = grid.getView().getCell(ri,ci);
							contextMenu.stack['cellDiv'] = grid.getView().getCell(ri,ci).getElementsByTagName('div')[0];			
							contextMenu.stack['rowDivs'] = grid.getView().getRow(ri).getElementsByTagName('div');
							contextMenu.showAt(xy);
						}		
		            }	
		        }          
	            
			},
mouseout: function(e){
				var t = e.getTarget();
				if(t.className != 'x-grid3-header'){
		            var r = e.getRelatedTarget();
		            var v = this.view;
		            var ci = v.findCellIndex(t);
		            var ri = v.findRowIndex(t);	            
		            if(ci === false || ri === false) return ;
		            var cell = this.getView().getCell(ri,ci);
		            if(cell){		            
		            	
		            	//Cross browser implementation
		            	var className = 'interactive-arrow-active';
		            	var tagName = 'span', _tags = cell.getElementsByTagName(tagName), _nodeList = [];
					    for (var i = 0, _tag; _tag = _tags[i++];) {
					        if (_tag.className.match(new RegExp('(\s|^)'+className+'(\s|$)'))) {
					            _nodeList.push(_tag);
					        }
					    }
					    //.............................................................................						            	
		            
			            var arrowDiv = _nodeList[0];
			            if(arrowDiv){
			            	arrowDiv.className = 'interactive-arrow';			            
			            }
		            }
	            }	            
			},
mouseover: function(e){			
				var t = e.getTarget();				
				if(t.className != 'x-grid3-header'){
		            var r = e.getRelatedTarget();
		            var v = this.view;
		            var ci = v.findCellIndex(t);
		            var ri = v.findRowIndex(t);
		           
		            if(ci === false || ri === false) return ;
		            var cell = this.getView().getCell(ri,ci);		           		           
		            if(cell){		            
		            	
		            	//Cross browser implementation
		            	var className = 'interactive-arrow';
		            	var tagName = 'span', _tags = cell.getElementsByTagName(tagName), _nodeList = [];
					    for (var i = 0, _tag; _tag = _tags[i++];) {
					        if (_tag.className.match(new RegExp('(\s|^)'+className+'(\s|$)'))) {
					            _nodeList.push(_tag);
					        }
					    }
					    //.............................................................................
							            	
		            
			            var arrowDiv = _nodeList[0];
			            if(arrowDiv){
			            	arrowDiv.className = 'interactive-arrow-active';			            	
			            }
		            }
	            }
	            
			},
render: function () { 
			this.store.load({
				params:{
					start:0, 
					limit:3
				}
			}); } },
plugins: [
filters_m138sr61Hs117bS1
],
store: store_S65aZ95Sm80mk12a
});
var south_panel = new Ext.Panel ({
id: "south_panel",
title: " ",
height: 150,
minHeight: 0,
split: true,
collapsible: true,
tools: [

],
items: [
grid_G36am106Zn133mp1
],
region: "south"
});
var panel_H94si129sr104hh1 = new Ext.Panel ({
border: false,
header: true,
style: "padding:0px;",
idxml: false,
id: "panel_H94si129sr104hh1",
getWidgetConfig: function () { var o={}; o.idxml=this.idxml || false; return o; },
layout: "absolute",
title: "Time Navigation",
autoScroll: true,
iconCls: "calendar",
bodyStyle: "padding:5px;",
autoHeight: true,
autoEnd: false,
width: 200,
items: [
{
xtype: "combowcolors",
forceSelection: true,
disableKeyFilter: true,
mode: "local",
triggerAction: "all",
width: 50,
store: [["day","Daily","#FFFFFF"],["week","Weekly","#FFFFFF"],["month","Monthly","#FFFFFF"],["year","Yearly","#FFFFFF"]],
value: "week",
plugins: [
Ext.ux.plugins.ExtendedComboBox,
Ext.ux.plugins.HelpText
],
resizable: true,
selectOnFocus: true,
hideLabel: true,
editable: false,
labelStyle: "width:75px;font-size:11px;font-weight:bold;padding:0 3px 3px 0;",
id: "field_D141jZ110De56hF3",
listeners: { select: function (field) { window.location.href='/eventmanagement/timeInterval?type='+field.value; } },
style: "margin-left:120px;",
emptyText: "select time interval",
typeAhead: true
},
{
xtype: "datefieldplus",
width: 140,
renderTodayButton: false,
showToday: false,
multiSelection: true,
format: "d\/m\/Y",
startDay: 1,
selectionType: "week",
maxSelectionDays: 7,
url: "\/loganalysis\/listLogSignature?viewdate=viewdate&viewdates[type]=week&viewdates[period]=",
plugins: [
Ext.ux.plugins.HelpText
],
labelStyle: "width:75px;font-size:11px;font-weight:bold;padding:0 3px 3px 0;",
id: "field_t43rS55mr44si35s",
value: ["26\/10\/2009","27\/10\/2009","28\/10\/2009","29\/10\/2009","30\/10\/2009","31\/10\/2009","01\/11\/2009"]
}
],
listeners: { expand: function (panel) { panel.body.dom.parentNode.style.paddingTop='5px';panel.body.dom.parentNode.style.fontFamily='arial,tahoma,helvetica,sans-serif';panel.body.dom.parentNode.style.fontSize='11px';panel.body.dom.firstChild.style.width='130px';panel.body.dom.firstChild.style.margin='0 auto';panel.body.dom.childNodes[1].style.width='150px';panel.body.dom.childNodes[1].style.margin='0 auto'; } }
});
var west_panel = new Ext.Panel ({
id: "west_panel",
region: "west",
title: "Navigation",
width: 220,
minWidth: 220,
split: true,
collapsible: true,
layout: "accordion",
items: [
grid_S126ms59oL66Ka12,
panel_H94si129sr104hh1,
{
title: "My Profile",
autoScroll: true,
border: false,
iconCls: "user",
html: "\r\n\t<style type=\"text\/css\">\r\n\t#westpanel_link{\r\n\t\tmargin-bottom:10px;\r\n\t}\r\n\t\/*#westpanel_link a:link, #westpanel_link a:active, #westpanel_link a:visited{\r\n\t\tcolor:#0000ff;\r\n\t\tdisplay:\"block\";\r\n\t\ttext-decoration:none;\r\n\t\tfont-wight:normal;\r\n\t}\r\n\t#westpanel_link a:hover{\r\n\t\tcolor:#aa0000;\t\t\r\n\t}\r\n\t#west_panel a:link, #west_panel a:active, #west_panel a:visited{\r\n\t\tcolor:#0000ff;\r\n\t\tdisplay:block;\r\n\t\ttext-decoration:none;\r\n\t\tfont-wight:normal;\r\n\t}*\/\r\n\ta{\r\n\t\tcolor:#0000ff;\t\t\r\n\t\ttext-decoration:none;\r\n\t\tfont-wight:normal;\r\n\t}\r\n\ta:hover{\r\n\t\tcolor:#ff0000;\t\t\r\n\t}\r\n\t\r\n\t#west_panel #servers .x-grid3-header{\r\n\t\tdisplay:none;\r\n\t}\r\n\t.nowrapgridcell{\r\n\t  white-space: normal !important;\r\n\t}\r\n\t<\/style> \r\n\t<div id=\"westpanel_link\"><div style=\"background-color:#f8f8f8; padding:3px; border:1px solid #ddd\"><b>Welcome, Administrator<\/b><br>\r\n\tUsername: admin<br>\r\n\tLast Login: 2009-10-27 12:06:23<br>\r\n\t<a href=\"\/user\/myprofile\">[Edit My Profile]<\/a>\r\n\t<\/div>\r\n\t<div style=\"background-color:#f8f8f8; padding:3px; margin-top:10px; border:1px solid #ddd\"><b>Roles assigned to the user:<\/b><li>admin<\/li><\/div>\r\n\t<div style=\"background-color:#f8f8f8; padding:3px; margin-top:10px; border:1px solid #ddd\"><b>Widget help is enabled <img src=\"\/images\/famfamfam\/accept.png\" width=10><\/b> <a href=\"\/user\/widget\/disable\"> [Disable]<\/a><\/b><\/div>\r\n\t\r\n\t\r\n\t\r\n\t<div style=\"background-color:#f8f8f8; padding:3px; margin-top:10px; border:1px solid #ddd\"><font color=#cc0000>The user is not associated with any servers neither as 'Primary Contact' nor as 'Secondary Contact'<\/font> \r\n\t<\/div>\r\n",
listeners: { expand: function (panel) { panel.body.dom.parentNode.style.padding='5px';panel.body.dom.parentNode.style.fontFamily='arial,tahoma,helvetica,sans-serif';panel.body.dom.parentNode.style.fontSize='11px' } }
}
]
});
var viewport = new Ext.Viewport ({
layout: "border",
id: "viewport",
items: [
north_panel,
west_panel,
center_panel,
south_panel
]
});
return {
 init: function () { 
	    Ext.QuickTips.init();
	    Ext.apply(Ext.QuickTips.getQuickTip(), {
		    trackMouse: true
		});
		Ext.form.Field.prototype.msgTarget = 'side';
		toolbar.render(document.body); },
getViewport: function () { return viewport; } 

}}();

Ext.onReady(App.init, App);


</script>

</body>
</html>
