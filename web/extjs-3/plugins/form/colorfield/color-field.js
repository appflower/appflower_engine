/**
 * @class Ext.ux.ColorField
 * @extends Ext.form.TriggerField
 * Provides a color input field with a {@link Ext.ColorPalette} dropdown.
* @constructor
* Create a new ColorField
 * <br />Example:
 * <pre><code>
var color_field = new Ext.ux.ColorField({
	fieldLabel: 'Color',
	id: 'color',
	width: 175,
	allowBlank: false
});
</code></pre>
* @param {Object} config
 */
Ext.ux.ColorField = Ext.extend(Ext.form.TriggerField, {
	valueVisible : true,
	
    /**
     * @cfg {String} invalidText
     * The error to display when the color in the field is invalid (defaults to
     * '{value} is not a valid color - it must be in the format {format}').
     */
    invalidText : "'{0}' is not a valid color - it must be in a the hex format (# followed by 3 or 6 letters/numbers 0-9 A-F)",
    
    /**
     * @cfg {String} triggerClass
     * An additional CSS class used to style the trigger button.  The trigger will always get the
     * class 'x-form-trigger' and triggerClass will be <b>appended</b> if specified (defaults to 'x-form-color-trigger'
     * which displays a color wheel icon).
     */
    triggerClass : 'x-form-color-trigger',
    
    /**
     * @cfg {String/Object} autoCreate
     * A DomHelper element spec, or true for a default element spec (defaults to
     * {tag: "input", type: "text", size: "10", autocomplete: "off"})
     */

    // private
    defaultAutoCreate : {tag: "input", type: "text", size: "10", maxlength: "7", autocomplete: "off"},

    // Limit input to hex values
	maskRe : /[#a-f0-9]/i,
    
    /**
     * @cfg {Boolean} lazyInit <tt>true</tt> to not initialize the color-picker for this color-field 
     * until the trigger button was clicked (defaults to <tt>true</tt>)
     */
    lazyInit : true,
	
    /**
     * @property picker The color-picker
     * @type {Ext.ux.color.ColorPickerPanel}
     */
    
    /**
     * Ext template method.
     * @override
     * @private
     */
    initComponent : function() {
        Ext.ux.ColorField.superclass.initComponent.call(this);
        
        this.addEvents(
            /**
             * @event expand Fires when the color-picker is expanded
             * @param {Ext.form.ComboBox} color-picker
             */
            'expand',
            /**
             * @event collapse Fires when the color-picker is collapsed
             * @param {Ext.form.ComboBox} color-picker
             */
            'collapse'
        );
    },
    
    /**
     * Ext template method.
     * @override
     * @private
     */
    onRender : function(ct, position){
        Ext.ux.ColorField.superclass.onRender.call(this, ct, position);
        
        if (!this.lazyInit) {
            this.initPicker();
        }
    },

    /**
     * @override
     * @private
     */
    onDestroy : function(){
        Ext.destroy(this.picker);
        
        Ext.ux.ColorField.superclass.onDestroy.call(this);
    },
    
    /**
     * Inits the color-picker {@link #}.
     * @private
     */
    initPicker : function() {
        if (!this.picker) {
            var val = this.getValue();
        
			var spec = {
			    tag: 'div', 
			    style: {
			        position: 'absolute',
			        'z-index': '10000'
			    }
			};
        
            var pickerdiv = Ext.DomHelper.append(Ext.getBody(), spec);
            
			this.picker = new Ext.ux.color.ColorPickerPanel({
			    applyTo: pickerdiv,
			    hex: val.replace('#', ''),
			    mode: 'hue',
                hidden: true
			});
            
            this.mon(this.picker.okButton, 'click', this.pickColor, this);
        }
    },    
    
    /**
     * Picks up selected color from the color-picker.
     * @private
     */
    pickColor : function() {
        this.setValue(this.picker.hex.getValue());
        this.collapse();
    },
    
    /**
     * Returns true if the color-picker is expanded, else false.
     * @return {Boolean}
     */
    isExpanded : function() {
        return this.picker && this.picker.isVisible();
    },
    
    // private
    validateValue : function(value){    	
        if (!Ext.ux.ColorField.superclass.validateValue.call(this, value)) {
            return false;
        }
        
        // if it's blank and textfield didn't flag it then it's valid
        if (value.length < 1) { 
        	 this.setColor('');
        	 return true;
        }

        var parseOK = this.parseColor(value);

        if (!value || (parseOK == false)) {
            this.markInvalid(String.format(this.invalidText, value));
            return false;
        }
        
		this.setColor(value);
        
        return true;
    },

	/**
	 * Sets the current color and changes the background.
	 * Does *not* change the value of the field.
	 * @param {String} hex The color value.
	 */
	setColor : function(color) {
		if (color == '' || color == undefined) {
            color = (this.emptyText != '' && this.parseColor(this.emptyText)) ? this.emptyText : 'transparent';  
		}
        
		if (this.trigger) {
			if (this.valueVisible) {
				this.trigger.setStyle({
				    'background-color': color
				});
			} else {
				this.getEl().dom.style.backgroundColor = color;				
			}
            
			this.getEl().setStyle({'background-color': color});
            
		} else {
			this.on('render', function(){
				this.setColor(color);
			}, this);
		}
	},
	
    /**
     * Sets the value of the color field.  
     * You can pass a string that can be parsed into a valid HTML color,
     * i.e: 
     * <pre><code>
     * colorField.setValue('#FFFFFF'); 
     * </code></pre>
     * @param {String} color The color string
     */
    setValue : function(color) {
        Ext.ux.ColorField.superclass.setValue.call(this, this.formatColor(color));
		this.setColor(this.formatColor(color));
    },

    // private
    parseColor : function(value) {
		return (!value || (value.substring(0, 1) != '#')) 
                ? false : (value.length == 4 || value.length == 7 );
    },

    // private
    formatColor : function(value) {
		if (!value || this.parseColor(value)) {
			return value;
        }
		if (value.length == 3 || value.length == 6) {
			return '#' + value;
		}
        
        return '';
    },

    /**
     * Hides the color-picker if it is currently expanded. 
     * Fires the {@link #collapse} event on completion.
     * @protected
     */
    collapse : function(){
        if (!this.isExpanded()) {
            return;
        }
        
        this.picker.hide();
        
        Ext.getDoc().un('mousewheel', this.collapseIf, this);
        Ext.getDoc().un('mousedown', this.collapseIf, this);
        
        this.fireEvent('collapse', this);
    },    
    
    /**
     * @private
     * @param {EventObject} e
     */
    collapseIf : function(e) {
        if (!this.isDestroyed && !e.within(this.wrap) && !e.within(this.picker.el)) {
            this.collapse();
        }
    },    
    
    /**
     * Expands the color-picker if it is currently hidden. 
     * Fires the {@link #expand} event on completion.
     * @protected
     */
    expand : function() {
        if (this.isExpanded()) {
            return;
        }

        this.picker.show();
        this.picker.el.alignTo(this.el, 'bl-tl?');
        
        this.mon(Ext.getDoc(), {
            scope: this,
            mousewheel: this.collapseIf,
            mousedown: this.collapseIf
        });
        
        this.fireEvent('expand', this);
    },
    
    /**
     * Displays the ColorPalette.
     * @override {@link Ext.form.TriggerField#onTriggerClick}
     * @protected
     * @param {EventObject} e
     */
    onTriggerClick : function(e) { 
        if (this.readOnly || this.disabled) {
            return;
        }
        
        this.initPicker();
        
        if (this.isExpanded()) {
            this.collapse();
            this.el.focus();
        } else {
            this.onFocus();
            this.expand();
        }
    }
});

/**
 * @type colorfield
 */
Ext.reg('colorfield', Ext.ux.ColorField);