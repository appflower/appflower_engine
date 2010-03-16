/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 *
 * http://extjs.com/license
 */

/**
 * @class Ext.YearPicker
 * @extends Ext.Component
 * Simple date picker class.
 * @constructor
 * Create a new YearPicker
 * @param {Object} config The config object
 */
Ext.YearPicker = Ext.extend(Ext.Component, {
    /**
     * @cfg {String} okText
     * The text to display on the ok button
     */
    okText : " OK ", //   to give the user extra clicking room
    /**
     * @cfg {String} todayTip
     * The tooltip to display for the button that selects the current date (defaults to "{current date} (Spacebar)")
     */
    todayTip : "{0} (Spacebar)",
    /**
     * @cfg {String} minText
     * The error text to display if the minDate validation fails (defaults to "This date is before the minimum date")
     */
    minText : "This date is before the minimum date",
    /**
     * @cfg {String} maxText
     * The error text to display if the maxDate validation fails (defaults to "This date is after the maximum date")
     */
    maxText : "This date is after the maximum date",
    /**
     * @cfg {String} format
     * The default date format string which can be overriden for localization support.  The format must be
     * valid according to {@link Date#parseDate} (defaults to 'm/d/y').
     */
    format : "m/01/y",
    /**
     * @cfg {String} disabledDaysText
     * The tooltip to display when the date falls on a disabled day (defaults to "Disabled")
     */
    disabledDaysText : "Disabled",
    /**
     * @cfg {String} disabledDatesText
     * The tooltip text to display when the date falls on a disabled date (defaults to "Disabled")
     */
    disabledDatesText : "Disabled",
    /**
     * @cfg {Boolean} constrainToViewport
     * <b>Deprecated</b> (not currently used). True to constrain the date picker to the viewport (defaults to true)
     */
    constrainToViewport : true,
    /**
     * @cfg {Array} monthNames
     * An array of textual month names which can be overriden for localization support (defaults to Date.monthNames)
     */
    monthNames : Date.monthNames,
    /**
     * @cfg {Array} dayNames
     * An array of textual day names which can be overriden for localization support (defaults to Date.dayNames)
     */
    dayNames : Date.dayNames,
    /**
     * @cfg {String} nextText
     * The next month navigation button tooltip (defaults to 'Next Month (Control+Right)')
     */
    nextText: 'Next Month (Control+Right)',
    /**
     * @cfg {String} prevText
     * The previous month navigation button tooltip (defaults to 'Previous Month (Control+Left)')
     */
    prevText: 'Previous Month (Control+Left)',
    /**
     * @cfg {String} monthYearText
     * The header month selector tooltip (defaults to 'Choose a month (Control+Up/Down to move years)')
     */
    monthYearText: 'Choose a month (Control+Up/Down to move years)',
    /**
     * @cfg {Date} minDate
     * Minimum allowable date (JavaScript date object, defaults to null)
     */
    /**
     * @cfg {Date} maxDate
     * Maximum allowable date (JavaScript date object, defaults to null)
     */
    /* * Not implemented yet
     * @cfg {Array} disabledDays
     * An array of days to disable, 0-based. For example, [0, 6] disables Sunday and Saturday (defaults to null).
     */
    /* * Not implemented yet
     * @cfg {RegExp} disabledDatesRE
     * JavaScript regular expression used to disable a pattern of dates (defaults to null).  The {@link #disabledDates}
     * config will generate this regex internally, but if you specify disabledDatesRE it will take precedence over the
     * disabledDates value.
     */
    /* * Not implemented yet
     * @cfg {Array} disabledDates
     * An array of "dates" to disable, as strings. These strings will be used to build a dynamic regular
     * expression so they are very powerful. Some examples:
     * <ul>
     * <li>["03/08/2003", "09/16/2003"] would disable those exact dates</li>
     * <li>["03/08", "09/16"] would disable those days for every year</li>
     * <li>["^03/08"] would only match the beginning (useful if you are using short years)</li>
     * <li>["03/../2006"] would disable every day in March 2006</li>
     * <li>["^03"] would disable every day in every March</li>
     * </ul>
     * Note that the format of the dates included in the array should exactly match the {@link #format} config.
     * In order to support regular expressions, if you are using a date format that has "." in it, you will have to
     * escape the dot when restricting dates. For example: ["03\\.08\\.03"].
     */

    // private
    initComponent : function(){
        Ext.YearPicker.superclass.initComponent.call(this);

        this.value = this.value ?
                 this.value.clearTime() : new Date().clearTime();

        this.addEvents(
            /**
             * @event select
             * Fires when a date is selected
             * @param {YearPicker} this
             * @param {Date} date The selected date
             */
            'select'
        );

        if(this.handler){
            this.on("select", this.handler,  this.scope || this);
        }

        //this.initDisabledDays();
    },

    // private
    /*initDisabledDays : function(){
        if(!this.disabledDatesRE && this.disabledDates){
            var dd = this.disabledDates;
            var re = "(?:";
            for(var i = 0; i < dd.length; i++){
                re += dd[i];
                if(i != dd.length-1) re += "|";
            }
            this.disabledDatesRE = new RegExp(re + ")");
        }
    },*/

    /**
     * Replaces any existing disabled dates with new values and refreshes the YearPicker.
     * @param {Array/RegExp} disabledDates An array of date strings (see the {@link #disabledDates} config
     * for details on supported values), or a JavaScript regular expression used to disable a pattern of dates.
     */
    /*setDisabledDates : function(dd){
        if(Ext.isArray(dd)){
            this.disabledDates = dd;
            this.disabledDatesRE = null;
        }else{
            this.disabledDatesRE = dd;
        }
        this.initDisabledDays();
        this.update(this.value, true);
    },*/

    /**
     * Replaces any existing disabled days (by index, 0-6) with new values and refreshes the YearPicker.
     * @param {Array} disabledDays An array of disabled day indexes. See the {@link #disabledDays} config
     * for details on supported values.
     */
    /*setDisabledDays : function(dd){
        this.disabledDays = dd;
        this.update(this.value, true);
    },*/

    /**
     * Replaces any existing {@link #minDate} with the new value and refreshes the YearPicker.
     * @param {Date} value The minimum date that can be selected
     */
    setMinDate : function(dt){
        this.minDate = dt;
        this.update(this.value, true);
    },

    /**
     * Replaces any existing {@link #maxDate} with the new value and refreshes the YearPicker.
     * @param {Date} value The maximum date that can be selected
     */
    setMaxDate : function(dt){
        this.maxDate = dt;
        this.update(this.value, true);
    },

    /**
     * Sets the value of the date field
     * @param {Date} value The date to set
     */
    setValue : function(value){
        var old = this.value;
        this.value = value.clearTime(true);
        if(this.el){
            this.update(this.value);
        }
    },

    /**
     * Gets the current selected value of the date field
     * @return {Date} The selected date
     */
    getValue : function(){
        return this.value;
    },

    // private
    focus : function(){
        if(this.el){
            this.update(this.activeDate);
        }
    },

    // private
    onRender : function(container, position){
        var m = ['<table class="x-month-mp" border="0" cellspacing="0">'];
        for(var i = 0; i < 6; i++){
            m.push(
                /*'<tr class="x-month-mp-month"><td class="x-date-mp-month"><a href="#">', this.monthNames[i].substr(0, 3), '</a></td>',
                '<td class="x-date-mp-month x-date-mp-sep"><a href="#">', this.monthNames[i+6].substr(0, 3), '</a></td>',*/
                i == 0 ?
                '<td class="x-date-mp-ybtn" align="center"><a class="x-date-mp-prev"></a></td><td class="x-date-mp-ybtn" align="center"><a class="x-date-mp-next"></a></td></tr>' :
                '<td class="x-date-mp-year"><a href="#"></a></td><td class="x-date-mp-year"><a href="#"></a></td></tr>'
            );
        }
        m.push(
            '<tr><td class="x-date-bottom" colspan="4" style="text-align:center;"></td></tr></table>'
        );

        var el = document.createElement("div");
        el.className = "x-date-picker";
        el.innerHTML = m.join("");

        container.dom.insertBefore(el, position);
        this.el = Ext.get(el);

        this.yearPicker = this.el.down('table.x-month-mp');
        this.yearPicker.enableDisplayMode('block');
        this.mpMonths = this.yearPicker.select('td.x-date-mp-month');
        this.mpYears = this.yearPicker.select('td.x-date-mp-year');

        this.mpMonths.each(function(m, a, i){
            i += 1;
            if((i%2) == 0){
                m.dom.xmonth = 5 + Math.round(i * .5);
            }else{
                m.dom.xmonth = Math.round((i-1) * .5);
            }
        });


        if(Ext.isIE){
            this.el.repaint();
        }

        this.yearPicker.on('click', this.onMonthClick, this);
        this.yearPicker.on('dblclick', this.onMonthDblClick, this);


        this.okBtn = new Ext.Button({
            renderTo: this.el.child("td.x-date-bottom", true),
            text: this.okText,
            handler: this.selectOk,
            scope: this
        });


        this.mpSelMonth = (this.activeDate || this.value).getMonth();
        this.mpSelYear = (this.activeDate || this.value).getFullYear();
        this.updateMPMonth();
        this.updateMPYear();
        this.update(this.value);
        
        this.on('select',function(a,b){
					
			//radu
			var value=this.getValue();
			value=this.myFormatDate(value);	
					
			if(value&&value!='-'&&this.url)
			{
				//radu: redirect to some specific url based on value
				document.location.href=this.url+value;					
			}
			
		},this);
        
    },

    // private
    updateMPYear : function(){
        this.mpyear = this.mpSelYear;
        var ys = this.mpYears.elements;
        for(var i = 1; i <= 10; i++){
            var td = ys[i-1], y2;
            if((i%2) == 0){
                y2 = this.mpSelYear + Math.round(i * .5);
                td.firstChild.innerHTML = y2;
                td.xyear = y2;
            }else{
                y2 = this.mpSelYear - (5-Math.round(i * .5));
                td.firstChild.innerHTML = y2;
                td.xyear = y2;
            }
            this.mpYears.item(i-1)[y2 == this.mpSelYear ? 'addClass' : 'removeClass']('x-date-mp-sel');
            if ((this.maxDate&&(this.maxDate.getFullYear() < y2)) ||  (this.minDate&&(this.minDate.getFullYear() > y2))) {
                Ext.get(ys[i-1].firstChild).addClass('x-date-mp-disabled');
            } else {
                Ext.get(ys[i-1].firstChild).removeClass('x-date-mp-disabled');
            }
        }
    },

    // private
    updateMPMonth : function(){
        var sm = this.mpSelMonth;
        var sy = this.mpSelYear;
        var maxDate = this.maxDate;
        var minDate = this.minDate;
        this.mpMonths.each(function(m, a, i){
            m[m.dom.xmonth == sm ? 'addClass' : 'removeClass']('x-date-mp-sel');
            if (
                (
                    maxDate &&
                    (maxDate.getFullYear() < sy)
                ) ||
                (
                    maxDate &&
                    (maxDate.getFullYear() == sy) &&
                    (maxDate.getMonth() < m.dom.xmonth)
                ) ||
                (
                    minDate &&
                    (minDate.getFullYear() > sy)
                ) ||
                (
                    minDate &&
                    (minDate.getFullYear() == sy) &&
                    (minDate.getMonth() > m.dom.xmonth)
                )
            ) {
                Ext.get(m.dom.firstChild).addClass('x-date-mp-disabled');
            } else {
                Ext.get(m.dom.firstChild).removeClass('x-date-mp-disabled');
            }
        });
        if (
            (
                maxDate &&
                (maxDate.getFullYear() < sy)
            ) ||
            (
                maxDate &&
                (maxDate.getFullYear() == sy) &&
                (maxDate.getMonth() < sm)
            ) ||
            (
                minDate &&
                (minDate.getFullYear() > sy)
            ) ||
            (
                minDate &&
                (minDate.getFullYear() == sy) &&
                (minDate.getMonth() > sm)
            )
        ) {
            this.okBtn.disable();
        }else {
            this.okBtn.enable();
        }
    },


    // private
    onMonthClick : function(e, t){
        if(Ext.fly(t).hasClass('x-date-mp-disabled')){
            return;
        }
        e.stopEvent();
        var el = new Ext.Element(t), pn;
        if(pn = el.up('td.x-date-mp-month', 2)){
            this.mpMonths.removeClass('x-date-mp-sel');
            pn.addClass('x-date-mp-sel');
            this.mpSelMonth = pn.dom.xmonth;
            this.updateMPMonth();
        }
        else if(pn = el.up('td.x-date-mp-year', 2)){
            this.mpYears.removeClass('x-date-mp-sel');
            pn.addClass('x-date-mp-sel');
            this.mpSelYear = pn.dom.xyear;
            this.updateMPMonth();
        }
        else if(el.is('a.x-date-mp-prev')){
            this.mpSelYear = this.mpyear-10;
            this.mpYears.removeClass('x-date-mp-sel');
            this.updateMPYear();
            this.updateMPMonth();
        }
        else if(el.is('a.x-date-mp-next')){
            this.mpSelYear = this.mpyear+10;
            this.mpYears.removeClass('x-date-mp-sel');
            this.updateMPYear();
            this.updateMPMonth();
        }
    },

    // private
    onMonthDblClick : function(e, t){
        if(Ext.fly(t).hasClass('x-date-mp-disabled')){
            return;
        }
        e.stopEvent();
        var el = new Ext.Element(t), pn;
        if(pn = el.up('td.x-date-mp-month', 2)){
            this.mpSelMonth = pn.dom.xmonth;
            this.selectOk();
        }
        else if(pn = el.up('td.x-date-mp-year', 2)){
            this.mpSelYear = pn.dom.xyear;
            this.selectOk();
        }
    },

    // private
    selectOk : function(){
        var d = new Date(this.mpSelYear, this.mpSelMonth, 1);
        if(d.getMonth() != this.mpSelMonth){
            // "fix" the JS rolling date conversion if needed
            d = new Date(this.mpSelYear, this.mpSelMonth, 1).getLastDateOfMonth();
        }
        this.update(d);
        this.setValue(d);
        this.fireEvent("select", this, this.value);
    },

    // private
    update : function(date){
        this.activeDate = date;

        if(!this.internalRender){
            var main = this.el.dom.firstChild;
            var w = main.offsetWidth;
            this.el.setWidth(w + this.el.getBorderWidth("lr"));
            Ext.fly(main).setWidth(w);
            this.internalRender = true;
            // opera does not respect the auto grow header center column
            // then, after it gets a width opera refuses to recalculate
            // without a second pass
            if(Ext.isOpera && !this.secondPass){
                main.rows[0].cells[1].style.width = (w - (main.rows[0].cells[0].offsetWidth+main.rows[0].cells[2].offsetWidth)) + "px";
                this.secondPass = true;
                this.update.defer(10, this, [date]);
            }
        }
    },

    // private
    beforeDestroy : function() {
        if(this.rendered){
            Ext.destroy(this.mbtn, this.okBtn);
        }
    },
    
    myFormatDate : function(date){
		return Ext.isDate(date) ? Ext.util.Format.date(date, 'Y') : date;
	}

    /**
     * @cfg {String} autoEl @hide
     */
});
Ext.reg('yearpicker', Ext.YearPicker);

/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 *
 * http://extjs.com/license
 */

/**
 * @class Ext.menu.YearMenu
 * @extends Ext.menu.Menu
 * A menu containing a {@link Ext.menu.DateItem} component (which provides a date picker).
 * @constructor
 * Creates a new YearMenu
 * @param {Object} config Configuration options
 */
Ext.menu.YearMenu = function(config){
    Ext.menu.YearMenu.superclass.constructor.call(this, config);
    this.plain = true;
    var di = new Ext.menu.YearItem(config);
    this.add(di);
    /**
     * The {@link Ext.YearPicker} instance for this YearMenu
     * @type YearPicker
     */
    this.picker = di.picker;
    /**
     * @event select
     * @param {MonthPicker} picker
     * @param {Date} date
     */
    this.relayEvents(di, ["select"]);

};
Ext.extend(Ext.menu.YearMenu, Ext.menu.Menu, {
    cls:'x-date-menu',

    // private
    beforeDestroy : function() {
        this.picker.destroy();
    }
});

/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 *
 * http://extjs.com/license
 */

/**
 * @class Ext.menu.YearItem
 * @extends Ext.menu.Adapter
 * A menu item that wraps the {@link Ext.YearPicker} component.
 * @constructor
 * Creates a new YearItem
 * @param {Object} config Configuration options
 */
Ext.menu.YearItem = function(config){
    Ext.menu.YearItem.superclass.constructor.call(this, new Ext.YearPicker(config), config);
    /** The Ext.YearPicker object @type Ext.YearPicker */
    this.picker = this.component;
    this.addEvents('select');

    this.picker.on("render", function(picker){
        picker.getEl().swallowEvent("click");
        picker.container.addClass("x-menu-date-item");
    });

    this.picker.on("select", this.onSelect, this);
};

Ext.extend(Ext.menu.YearItem, Ext.menu.Adapter, {
    // private
    onSelect : function(picker, date){
        this.fireEvent("select", this, date, picker);
        Ext.menu.YearItem.superclass.handleClick.call(this);
    }
});

/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 *
 * http://extjs.com/license
 */

/**
 * @class Ext.form.YearField
 * @extends Ext.form.TriggerField
 * Provides a date input field with a {@link Ext.YearPicker} dropdown and automatic date validation.
* @constructor
* Create a new YearField
* @param {Object} config
 */
Ext.form.YearField = Ext.extend(Ext.form.TriggerField,  {
    /**
     * @cfg {String} format
     * The default date format string which can be overriden for localization support.  The format must be
     * valid according to {@link Date#parseDate} (defaults to 'm/d/Y').
     */
    format : "Y",
    /**
     * @cfg {String} altFormats
     * Multiple date formats separated by "|" to try when parsing a user input value and it doesn't match the defined
     * format (defaults to 'm/d/Y|n/j/Y|n/j/y|m/j/y|n/d/y|m/j/Y|n/d/Y|m-d-y|m-d-Y|m/d|m-d|md|mdy|mdY|d|Y-m-d').
     */
    altFormats : "m/Y|m-Y|mY|m/01/Y|m-01-Y|m01Y|m/d/Y|n/j/Y|n/j/y|m/j/y|n/d/y|m/j/Y|n/d/Y|m-d-y|m-d-Y|m/d|m-d|md|mdy|mdY|d|Y-m-d",
    /**
     * @cfg {String} disabledDaysText
     * The tooltip to display when the date falls on a disabled day (defaults to 'Disabled')
     */
    disabledDaysText : "Disabled",
    /**
     * @cfg {String} disabledDatesText
     * The tooltip text to display when the date falls on a disabled date (defaults to 'Disabled')
     */
    disabledDatesText : "Disabled",
    /**
     * @cfg {String} minText
     * The error text to display when the date in the cell is before minValue (defaults to
     * 'The date in this field must be after {minValue}').
     */
    minText : "The date in this field must be equal to or after {0}",
    /**
     * @cfg {String} maxText
     * The error text to display when the date in the cell is after maxValue (defaults to
     * 'The date in this field must be before {maxValue}').
     */
    maxText : "The date in this field must be equal to or before {0}",
    /**
     * @cfg {String} invalidText
     * The error text to display when the date in the field is invalid (defaults to
     * '{value} is not a valid date - it must be in the format {format}').
     */
    invalidText : "{0} is not a valid date - it must be in the format {1}",
    /**
     * @cfg {String} triggerClass
     * An additional CSS class used to style the trigger button.  The trigger will always get the
     * class 'x-form-trigger' and triggerClass will be <b>appended</b> if specified (defaults to 'x-form-date-trigger'
     * which displays a calendar icon).
     */
    triggerClass : 'x-form-date-trigger',
    /**
     * @cfg {Date/String} minValue
     * The minimum allowed date. Can be either a Javascript date object or a string date in a
     * valid format (defaults to null).
     */
    /**
     * @cfg {Date/String} maxValue
     * The maximum allowed date. Can be either a Javascript date object or a string date in a
     * valid format (defaults to null).
     */
    /* * Not implemented yet
     * @cfg {Array} disabledDays
     * An array of days to disable, 0 based. For example, [0, 6] disables Sunday and Saturday (defaults to null).
     */
    /* * Not implemented yet
     * @cfg {Array} disabledDates
     * An array of "dates" to disable, as strings. These strings will be used to build a dynamic regular
     * expression so they are very powerful. Some examples:
     * <ul>
     * <li>["03/08/2003", "09/16/2003"] would disable those exact dates</li>
     * <li>["03/08", "09/16"] would disable those days for every year</li>
     * <li>["^03/08"] would only match the beginning (useful if you are using short years)</li>
     * <li>["03/../2006"] would disable every day in March 2006</li>
     * <li>["^03"] would disable every day in every March</li>
     * </ul>
     * Note that the format of the dates included in the array should exactly match the {@link #format} config.
     * In order to support regular expressions, if you are using a date format that has "." in it, you will have to
     * escape the dot when restricting dates. For example: ["03\\.08\\.03"].
     */
    /**
     * @cfg {String/Object} autoCreate
     * A DomHelper element spec, or true for a default element spec (defaults to
     * {tag: "input", type: "text", size: "10", autocomplete: "off"})
     */

    // private
    defaultAutoCreate : {tag: "input", type: "text", size: "10", autocomplete: "off"},

    initComponent : function(){
        Ext.form.YearField.superclass.initComponent.call(this);
        if(typeof this.minValue == "string"){
            this.minValue = this.parseDate(this.minValue);
        }
        if(typeof this.maxValue == "string"){
            this.maxValue = this.parseDate(this.maxValue);
        }
        this.ddMatch = null;
        //this.initDisabledDays();
    },

    // private
    /*initDisabledDays : function(){
        if(this.disabledDates){
            var dd = this.disabledDates;
            var re = "(?:";
            for(var i = 0; i < dd.length; i++){
                re += dd[i];
                if(i != dd.length-1) re += "|";
            }
            this.disabledDatesRE = new RegExp(re + ")");
        }
    },*/

    /**
     * Replaces any existing disabled dates with new values and refreshes the MonthPicker.
     * @param {Array} disabledDates An array of date strings (see the {@link #disabledDates} config
     * for details on supported values) used to disable a pattern of dates.
     */
    /*setDisabledDates : function(dd){
        this.disabledDates = dd;
        this.initDisabledDays();
        if(this.menu){
            this.menu.picker.setDisabledDates(this.disabledDatesRE);
        }
    },*/

    /**
     * Replaces any existing disabled days (by index, 0-6) with new values and refreshes the MonthPicker.
     * @param {Array} disabledDays An array of disabled day indexes. See the {@link #disabledDays} config
     * for details on supported values.
     */
    /*setDisabledDays : function(dd){
        this.disabledDays = dd;
        if(this.menu){
            this.menu.picker.setDisabledDays(dd);
        }
    },*/

    /**
     * Replaces any existing {@link #minValue} with the new value and refreshes the MonthPicker.
     * @param {Date} value The minimum date that can be selected
     */
    setMinValue : function(dt){
        this.minValue = (typeof dt == "string" ? this.parseDate(dt) : dt);
        if(this.menu){
            this.menu.picker.setMinDate(this.minValue);
        }
    },

    /**
     * Replaces any existing {@link #maxValue} with the new value and refreshes the MonthPicker.
     * @param {Date} value The maximum date that can be selected
     */
    setMaxValue : function(dt){
        this.maxValue = (typeof dt == "string" ? this.parseDate(dt) : dt);
        if(this.menu){
            this.menu.picker.setMaxDate(this.maxValue);
        }
    },

    // private
    validateValue : function(value){
        value = this.formatDate(value);
        if(!Ext.form.YearField.superclass.validateValue.call(this, value)){
            return false;
        }
        if(value.length < 1){ // if it's blank and textfield didn't flag it then it's valid
             return true;
        }
        var svalue = value;
        value = this.parseDate(value);
        if(!value){
            this.markInvalid(String.format(this.invalidText, svalue, this.format));
            return false;
        }
        var time = value.getTime();
        if(this.minValue && time < this.minValue.getTime()){
            this.markInvalid(String.format(this.minText, this.formatDate(this.minValue)));
            return false;
        }
        if(this.maxValue && time > this.maxValue.getTime()){
            this.markInvalid(String.format(this.maxText, this.formatDate(this.maxValue)));
            return false;
        }
        if(this.disabledDays){
            var day = value.getDay();
            for(var i = 0; i < this.disabledDays.length; i++) {
            	if(day === this.disabledDays[i]){
            	    this.markInvalid(this.disabledDaysText);
                    return false;
            	}
            }
        }
        var fvalue = this.formatDate(value);
        if(this.ddMatch && this.ddMatch.test(fvalue)){
            this.markInvalid(String.format(this.disabledDatesText, fvalue));
            return false;
        }
        return true;
    },

    // private
    // Provides logic to override the default TriggerField.validateBlur which just returns true
    validateBlur : function(){
        return !this.menu || !this.menu.isVisible();
    },

    /**
     * Returns the current date value of the date field.
     * @return {Date} The date value
     */
    getValue : function(){
        return this.parseDate(Ext.form.YearField.superclass.getValue.call(this)) || "";
    },

    /**
     * Sets the value of the date field.  You can pass a date object or any string that can be parsed into a valid
     * date, using YearField.format as the date format, according to the same rules as {@link Date#parseDate}
     * (the default format used is "m/d/Y").
     * <br />Usage:
     * <pre><code>
//All of these calls set the same date value (May 4, 2006)

//Pass a date object:
var dt = new Date('5/4/2006');
dateField.setValue(dt);

//Pass a date string (default format):
dateField.setValue('05/04/2006');

//Pass a date string (custom format):
dateField.format = 'Y-m-d';
dateField.setValue('2006-05-04');
</code></pre>
     * @param {String/Date} date The date or valid date string
     */
    setValue : function(date){
        Ext.form.YearField.superclass.setValue.call(this, this.formatDate(this.parseDate(date)));
    },

    // private
    parseDate : function(value){
        if(!value || Ext.isDate(value)){
            return value;
        }
        var v = Date.parseDate(value, this.format);
        if(!v && this.altFormats){
            if(!this.altFormatsArray){
                this.altFormatsArray = this.altFormats.split("|");
            }
            for(var i = 0, len = this.altFormatsArray.length; i < len && !v; i++){
                v = Date.parseDate(value, this.altFormatsArray[i]);
            }
        }
        return v;
    },

    // private
    onDestroy : function(){
        if(this.menu) {
            this.menu.destroy();
        }
        if(this.wrap){
            this.wrap.remove();
        }
        Ext.form.YearField.superclass.onDestroy.call(this);
    },

    // private
    formatDate : function(date){
        return Ext.isDate(date) ? date.dateFormat(this.format) : date;
    },

    // private
    menuListeners : {
        select: function(m, d){
            this.setValue(d);
            this.fireEvent('select', this, d);
        },
        show : function(){ // retain focus styling
            this.onFocus();
        },
        hide : function(){
            this.focus.defer(10, this);
            var ml = this.menuListeners;
            this.menu.un("select", ml.select,  this);
            this.menu.un("show", ml.show,  this);
            this.menu.un("hide", ml.hide,  this);
        }
    },

    /**
     * @method onTriggerClick
     * @hide
     */
    // private
    // Implements the default empty TriggerField.onTriggerClick function to display the MonthPicker
    onTriggerClick : function(){
        if(this.disabled){
            return;
        }
        if(this.menu == null){
            this.menu = new Ext.menu.YearMenu();
        }
        Ext.apply(this.menu.picker,  {
            minDate : this.minValue,
            maxDate : this.maxValue,
            disabledDatesRE : this.ddMatch,
            disabledDatesText : this.disabledDatesText,
            disabledDays : this.disabledDays,
            disabledDaysText : this.disabledDaysText,
            format : this.format,
            showToday : this.showToday,
            minText : String.format(this.minText, this.formatDate(this.minValue)),
            maxText : String.format(this.maxText, this.formatDate(this.maxValue)),
            url:this.url
        });
        this.menu.on(Ext.apply({}, this.menuListeners, {
            scope:this
        }));
        this.menu.picker.setValue(this.getValue() || new Date());
        this.menu.show(this.el, "tl-bl?");
    },

    // private
    beforeBlur : function(){
        var v = this.parseDate(this.getRawValue());
        if(v){
            this.setValue(v);
        }
    }

    /**
     * @cfg {Boolean} grow @hide
     */
    /**
     * @cfg {Number} growMin @hide
     */
    /**
     * @cfg {Number} growMax @hide
     */
    /**
     * @hide
     * @method autoSize
     */
});
Ext.reg('yearfield', Ext.form.YearField);