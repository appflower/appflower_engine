/**
Extended Item Selector
Ability to auto suggest
@author: Prakash Paudel
*/
Ext.ux.ItemSelectorAutoSuggest = Ext.extend(Ext.form.Field,  {
    msWidth:200,
    msHeight:300,
    hideNavIcons:false,
    imagePath:"",
    iconUp:"up2.gif",
    iconDown:"down2.gif",
    iconLeft:"left2.gif",
    iconRight:"right2.gif",
    iconTop:"top2.gif",
    iconBottom:"bottom2.gif",
    drawUpIcon:true,
    drawDownIcon:true,
    drawLeftIcon:true,
    drawRightIcon:true,
    drawTopIcon:true,
    drawBotIcon:true,
    url:null,
    fromStore:null,
    toStore:null,
    fromData:null, 
    toData:null,
    displayField:0,
    valueField:1,
    switchToFrom:false,
    allowDup:false,
    focusClass:undefined,
    delimiter:',',
    readOnly:false,
    toLegend:null,
    fromLegend:null,
    toSortField:null,
    fromSortField:null,
    toSortDir:'ASC',
    fromSortDir:'ASC',
    toTBar:null,
    fromTBar:null,
    bodyStyle:null,
    border:false,
    loadOnChange:true,
    defaultAutoCreate:{tag: "div"},
    keyword:null,
    randN: Math.floor(Math.random()*10000000),
    fromMultiselect:null,
    
    initComponent: function(){
        Ext.ux.ItemSelectorAutoSuggest.superclass.initComponent.call(this);
        this.addEvents({
            'rowdblclick' : true,
            'change' : true
        });         
    },
	onOkButtonClick: function(keyword,option){
		this.fromMultiselect.el.mask("Loading...");	
		this.fromStore.load({params:{like:keyword,option:option}});
		ms = this.fromMultiselect;
		this.fromStore.on('load',function(){ms.el.unmask();});	
	},
	onDataLoad:function(){
		ms = this.fromMultiselect;
		this.fromMultiselect.el.mask("Loading...");		
		this.fromMultiselect.store.load();
		this.fromMultiselect.store.on('load',function(){ms.el.unmask();});		
	},
    onRender: function(ct, position){
        Ext.ux.ItemSelectorAutoSuggest.superclass.onRender.call(this, ct, position);
              
		var AS_optioncombo = new Ext.form.ComboBox({	
			parent:this,		
			name: this.id+"_optioncombo_"+this.randN,
			store:new Ext.data.SimpleStore({
				fields:[
					'value','display'
				],
				data:[
					['start_with','Starts with'],['any','Match Any']
				]
			}),	
			listeners:{
				change: function(){
					if(AS_keyword.getValue() != AS_keyword.originalValue && AS_keyword.getValue() != "")
					this.parent.onOkButtonClick(AS_keyword.getValue(),AS_optioncombo.getValue())
				}
			},
			width:80,
			displayField:'display',
			valueField:'value',
			triggerAction:'all',
			mode:'local',
			lazyRender:true
		});
		AS_optioncombo.setValue('any')
		this.fromStore = new Ext.data.JsonStore({
			url: this.url,
			fields: [
				this.valueField,this.displayField
			]
		});	
		var AS_okbutton = new Ext.Button({
			name: this.id+"_okbutton_"+this.randN,
			text:"Go",
			parent:this,
			listeners:{
				click:function(){this.parent.onOkButtonClick(AS_keyword.getValue(),AS_optioncombo.getValue())}
			}			
		});
		var AS_listallbutton = new Ext.Button({
			name: this.id+"_listallbutton_"+this.randN,
			text:"List All",
			parent:this,
			listeners:{
				click:function(){this.parent.onOkButtonClick('%%','')}
			}			
		});

		if(this.loadOnChange){				
			var AS_keyword = new Ext.form.TextField({
				name: this.id+"_keyword_"+this.randN,
				id: this.id+"_keyword_"+this.randN,
				emptyText:"Filter...",
				parent:this,
				width:100,
				enableKeyEvents: true,
				listeners:{
					keyup: function(){this.parent.onOkButtonClick(AS_keyword.getValue(),AS_optioncombo.getValue())}
				}
				
			});			
			var AS_fromTBar = new Ext.Toolbar({
				items:[
					AS_optioncombo,AS_keyword,AS_listallbutton
				]
			});
		}else{			
			var AS_keyword = new Ext.form.TextField({
				name: this.id+"_keyword_"+this.randN,
				id: this.id+"_keyword_"+this.randN,
				emptyText:"Filter...",
				width:100
				
			});
			var AS_fromTBar = new Ext.Toolbar({
				items:[
					AS_optioncombo,AS_keyword,AS_okbutton,AS_listallbutton
				]
			});	
		}	
		
		this.fromTBar = AS_fromTBar;		
		
        this.fromMultiselect = new Ext.ux.Multiselect({
            legend: this.fromLegend,
            delimiter: this.delimiter,
            allowDup: this.allowDup,
            copy: this.allowDup,
            allowTrash: this.allowDup,
            dragGroup: this.readOnly ? null : "drop2-"+this.el.dom.id,
            dropGroup: this.readOnly ? null : "drop1-"+this.el.dom.id,
            width: this.msWidth,
            height: this.msHeight,
            dataFields: this.dataFields,
            data: this.fromData,
            displayField: this.displayField,
            valueField: this.valueField,
            store: this.fromStore,
            isFormField: false,
            tbar: this.fromTBar,
            appendOnly: true,
            sortField: this.fromSortField,
            sortDir: this.fromSortDir,
            id: this.id+"_from_multi_select_"+this.randN
        });
        this.fromMultiselect.on('dblclick', this.onRowDblClick, this);

        if (!this.toStore) {
            this.toStore = new Ext.data.SimpleStore({
                fields: this.dataFields,
                data : this.toData
            });
        }
        this.toStore.on('add', this.valueChanged, this);
        this.toStore.on('remove', this.valueChanged, this);
        this.toStore.on('load', this.valueChanged, this);

        this.toMultiselect = new Ext.ux.Multiselect({
            legend: this.toLegend,
            delimiter: this.delimiter,
            allowDup: this.allowDup,
            dragGroup: this.readOnly ? null : "drop1-"+this.el.dom.id,
            //dropGroup: this.readOnly ? null : "drop2-"+this.el.dom.id+(this.toSortField ? "" : ",drop1-"+this.el.dom.id),
            dropGroup: this.readOnly ? null : "drop2-"+this.el.dom.id+",drop1-"+this.el.dom.id,
            width: this.msWidth,
            height: this.msHeight,
            displayField: this.displayField,
            valueField: this.valueField,
            store: this.toStore,
            isFormField: false,
            tbar: this.toTBar,
            sortField: this.toSortField,
            sortDir: this.toSortDir
        });
        this.toMultiselect.on('dblclick', this.onRowDblClick, this);
                
        var p = new Ext.Panel({
            bodyStyle:this.bodyStyle,
            border:this.border,
            layout:"table",
            layoutConfig:{columns:3},
            width:530,

        });
        p.add(this.switchToFrom ? this.toMultiselect : this.fromMultiselect);
        var icons = new Ext.Panel({header:false});
        p.add(icons);
        p.add(this.switchToFrom ? this.fromMultiselect : this.toMultiselect);
        p.render(this.el);
        icons.el.down('.'+icons.bwrapCls).remove();

        if (this.imagePath!="" && this.imagePath.charAt(this.imagePath.length-1)!="/")
            this.imagePath+="/";
        this.iconUp = this.imagePath + (this.iconUp || 'up2.gif');
        this.iconDown = this.imagePath + (this.iconDown || 'down2.gif');
        this.iconLeft = this.imagePath + (this.iconLeft || 'left2.gif');
        this.iconRight = this.imagePath + (this.iconRight || 'right2.gif');
        this.iconTop = this.imagePath + (this.iconTop || 'top2.gif');
        this.iconBottom = this.imagePath + (this.iconBottom || 'bottom2.gif');
        var el=icons.getEl();
        if (!this.toSortField) {
            this.toTopIcon = el.createChild({tag:'img', src:this.iconTop, style:{cursor:'pointer', margin:'2px'}});
            el.createChild({tag: 'br'});
            this.upIcon = el.createChild({tag:'img', src:this.iconUp, style:{cursor:'pointer', margin:'2px'}});
            el.createChild({tag: 'br'});
        }
        this.addIcon = el.createChild({tag:'img', src:this.switchToFrom?this.iconLeft:this.iconRight, style:{cursor:'pointer', margin:'2px'}});
        el.createChild({tag: 'br'});
        this.removeIcon = el.createChild({tag:'img', src:this.switchToFrom?this.iconRight:this.iconLeft, style:{cursor:'pointer', margin:'2px'}});
        el.createChild({tag: 'br'});
        if (!this.toSortField) {
            this.downIcon = el.createChild({tag:'img', src:this.iconDown, style:{cursor:'pointer', margin:'2px'}});
            el.createChild({tag: 'br'});
            this.toBottomIcon = el.createChild({tag:'img', src:this.iconBottom, style:{cursor:'pointer', margin:'2px'}});
        }
        if (!this.readOnly) {
            if (!this.toSortField) {
                this.toTopIcon.on('click', this.toTop, this);
                this.upIcon.on('click', this.up, this);
                this.downIcon.on('click', this.down, this);
                this.toBottomIcon.on('click', this.toBottom, this);
            }
            this.addIcon.on('click', this.fromTo, this);
            this.removeIcon.on('click', this.toFrom, this);
        }
        if (!this.drawUpIcon || this.hideNavIcons) { this.upIcon.dom.style.display='none'; }
        if (!this.drawDownIcon || this.hideNavIcons) { this.downIcon.dom.style.display='none'; }
        if (!this.drawLeftIcon || this.hideNavIcons) { this.addIcon.dom.style.display='none'; }
        if (!this.drawRightIcon || this.hideNavIcons) { this.removeIcon.dom.style.display='none'; }
        if (!this.drawTopIcon || this.hideNavIcons) { this.toTopIcon.dom.style.display='none'; }
        if (!this.drawBotIcon || this.hideNavIcons) { this.toBottomIcon.dom.style.display='none'; }

        var tb = p.body.first();
        this.el.setWidth(p.body.first().getWidth());
        p.body.removeClass();
        
        this.hiddenName = this.name;
        var hiddenTag={tag: "input", type: "hidden", value: "", name:this.name};
        this.hiddenField = this.el.createChild(hiddenTag);
        this.valueChanged(this.toStore);        
       
		
        
        //Load data
        //this.fromStore.load();
        //this.fromStore.on('load',this.onDataLoad(this.fromMultiselect,''));
    },
    
    initValue:Ext.emptyFn,
    
    toTop : function() {
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        var records = [];
        if (selectionsArray.length > 0) {
            selectionsArray.sort();
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                records.push(record);
            }
            selectionsArray = [];
            for (var i=records.length-1; i>-1; i--) {
                record = records[i];
                this.toMultiselect.view.store.remove(record);
                this.toMultiselect.view.store.insert(0, record);
                selectionsArray.push(((records.length - 1) - i));
            }
        }
        this.toMultiselect.view.refresh();
        this.toMultiselect.view.select(selectionsArray);
    },

    toBottom : function() {
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        var records = [];
        if (selectionsArray.length > 0) {
            selectionsArray.sort();
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                records.push(record);
            }
            selectionsArray = [];
            for (var i=0; i<records.length; i++) {
                record = records[i];
                this.toMultiselect.view.store.remove(record);
                this.toMultiselect.view.store.add(record);
                selectionsArray.push((this.toMultiselect.view.store.getCount()) - (records.length - i));
            }
        }
        this.toMultiselect.view.refresh();
        this.toMultiselect.view.select(selectionsArray);
    },
    
    up : function() {
        var record = null;
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        selectionsArray.sort();
        var newSelectionsArray = [];
        if (selectionsArray.length > 0) {
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                if ((selectionsArray[i] - 1) >= 0) {
                    this.toMultiselect.view.store.remove(record);
                    this.toMultiselect.view.store.insert(selectionsArray[i] - 1, record);
                    newSelectionsArray.push(selectionsArray[i] - 1);
                }
            }
            this.toMultiselect.view.refresh();
            this.toMultiselect.view.select(newSelectionsArray);
        }
    },

    down : function() {
        var record = null;
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        selectionsArray.sort();
        selectionsArray.reverse();
        var newSelectionsArray = [];
        if (selectionsArray.length > 0) {
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                if ((selectionsArray[i] + 1) < this.toMultiselect.view.store.getCount()) {
                    this.toMultiselect.view.store.remove(record);
                    this.toMultiselect.view.store.insert(selectionsArray[i] + 1, record);
                    newSelectionsArray.push(selectionsArray[i] + 1);
                }
            }
            this.toMultiselect.view.refresh();
            this.toMultiselect.view.select(newSelectionsArray);
        }
    },
    
    fromTo : function() {
        var selectionsArray = this.fromMultiselect.view.getSelectedIndexes();
        var records = [];
        if (selectionsArray.length > 0) {
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.fromMultiselect.view.store.getAt(selectionsArray[i]);
                records.push(record);
            }
            if(!this.allowDup)selectionsArray = [];
            for (var i=0; i<records.length; i++) {
                record = records[i];
                if(this.allowDup){
                    var x=new Ext.data.Record();
                    record.id=x.id;
                    delete x;   
                    this.toMultiselect.view.store.add(record);
                }else{
                    this.fromMultiselect.view.store.remove(record);
                    this.toMultiselect.view.store.add(record);
                    selectionsArray.push((this.toMultiselect.view.store.getCount() - 1));
                }
            }
        }
        this.toMultiselect.view.refresh();
        this.fromMultiselect.view.refresh();
        if(this.toSortField)this.toMultiselect.store.sort(this.toSortField, this.toSortDir);
        if(this.allowDup)this.fromMultiselect.view.select(selectionsArray);
        else this.toMultiselect.view.select(selectionsArray);
    },
    
    toFrom : function() {
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        var records = [];
        if (selectionsArray.length > 0) {
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                records.push(record);
            }
            selectionsArray = [];
            for (var i=0; i<records.length; i++) {
                record = records[i];
                this.toMultiselect.view.store.remove(record);
                if(!this.allowDup){
                    this.fromMultiselect.view.store.add(record);
                    selectionsArray.push((this.fromMultiselect.view.store.getCount() - 1));
                }
            }
        }
        this.fromMultiselect.view.refresh();
        this.toMultiselect.view.refresh();
        if(this.fromSortField)this.fromMultiselect.store.sort(this.fromSortField, this.fromSortDir);
        this.fromMultiselect.view.select(selectionsArray);
    },
    
    valueChanged: function(store) {
        var record = null;
        var values = [];
        for (var i=0; i<store.getCount(); i++) {
            record = store.getAt(i);
            values.push(record.get(this.valueField));
        }
        this.hiddenField.dom.value = values.join(this.delimiter);
        this.fireEvent('change', this, this.getValue(), this.hiddenField.dom.value);
    },
    
    getValue : function() {
        return this.hiddenField.dom.value;
    },
    
    onRowDblClick : function(vw, index, node, e) {
        return this.fireEvent('rowdblclick', vw, index, node, e);
    },
    
    reset: function(){
        range = this.toMultiselect.store.getRange();
        this.toMultiselect.store.removeAll();
        if (!this.allowDup) {
            this.fromMultiselect.store.add(range);
            this.fromMultiselect.store.sort(this.displayField,'ASC');
        }
        this.valueChanged(this.toMultiselect.store);
    }
});

Ext.reg("itemselectorautosuggest", Ext.ux.ItemSelectorAutoSuggest);