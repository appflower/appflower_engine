Ext.ns('Ext.ux');

/**
 * Converts an invalid JSON to an error response.
 */
Ext.ux.RobustSubmitAction = Ext.extend(Ext.form.Action.Submit, {
    constructor : function(form, options) {
        Ext.ux.RobustSubmitAction.superclass.constructor.call(this, form, options);
    },
    type : 'robustsubmit',

    handleResponse : function(response){
        if(this.form.errorReader){
            return Ext.ux.RobustSubmitAction.superclass.handleResponse.call(this, response);
        }

        try {
            return Ext.decode(response.responseText);
        } catch (e) {
            return {
                success: false,
                message: 'Invalid response: ' + response.responseText
            };
        }
    }
});

Ext.form.Action.ACTION_TYPES['robustsubmit'] = Ext.ux.RobustSubmitAction;

