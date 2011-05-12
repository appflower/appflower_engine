Ext.ns('Ext.ux');

Ext.ux.ClassicFormPanel = function(config){Ext.ux.ClassicFormPanel.superclass.constructor.call(this, config)};
Ext.extend(Ext.ux.ClassicFormPanel, Ext.form.FormPanel, {
    onSubmit: Ext.emptyFn,
    submit: function(o) {
    	if(this.fileUpload) {
            this.getForm().getEl().dom.enctype = 'multipart/form-data';
        }
    	this.getForm().getEl().dom.action = o.url;
    	this.getForm().getEl().dom.method = o.method;
    	this.getForm().getEl().dom.submit();
    }
});