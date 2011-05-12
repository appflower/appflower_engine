/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.onReady(function() {
    var form = new Ext.form.FormPanel({
        baseCls: 'x-plain',
        layout:'absolute',
        url:'save-form.php',
        defaultType: 'textfield',

        items: [{
            x: 0,
            y: 5,
            xtype:'label',
            text: 'Send To:'
        },{
            x: 60,
            y: 0,
            name: 'to',
            anchor:'100%'  // anchor width by percentage
        },{
            x: 0,
            y: 35,
            xtype:'label',
            text: 'Subject:'
        },{
            x: 60,
            y: 30,
            name: 'subject',
            anchor: '100%'  // anchor width by percentage
        },{
            x:0,
            y: 60,
            xtype: 'textarea',
            name: 'msg',
            anchor: '100% 100%'  // anchor width and height
        },
        {
		xtype: 'checkboxgroup',
		fieldLabel: 'My checkbox group',
		listeners: { render: function (field) { new Ext.ToolTip({target: field.getEl(),html: 'help tooltip on the checkbox'}); } },
		items: [
		{
		xtype: 'checkbox',
		boxLabel: 'Check 1',
		inputValue: 1,
		name: 'check1',
		id: 'field_e5Hi96rr116rZ132',
		checked: true
		},
		{
		xtype: 'checkbox',
		boxLabel: 'Check 2',
		inputValue: 2,
		name: 'checkbox2',
		id: 'field_s53rl17he132ma12'
		}
		]
		}]
    });

    var window = new Ext.Window({
        title: 'Resize Me',
        width: 500,
        height:300,
        minWidth: 300,
        minHeight: 200,
        layout: 'fit',
        plain:true,
        bodyStyle:'padding:5px;',
        buttonAlign:'center',
        items: form,

        buttons: [{
            text: 'Send'
        },{
            text: 'Cancel'
        }]
    });

    window.show();
});