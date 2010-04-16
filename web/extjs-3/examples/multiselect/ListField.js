Ext.ux.ListField = Ext.extend(Ext.form.Field,  {
    dataFields:[],
    data:[],
    width:100,
    height:200,
    displayField:'value',
    valueField:'key',
    allowBlank:true,
    minLength:0,
    maxLength:Number.MAX_VALUE,
    blankText:Ext.form.TextField.prototype.blankText,
    minLengthText:'Minimum {0} item(s) required',
    maxLengthText:'Maximum {0} item(s) allowed',
    delimiter:',',
    backupStore:null,
    // private
    defaultAutoCreate : {tag: "div"},
    
    // private
    initComponent: function(){
        Ext.ux.ListField.superclass.initComponent.call(this);
        this.addEvents({
            'dblclick' : true,
            'click' : true,
            'change' : true,
            'drop' : true
        });     
    },
    
    // private
    onRender: function(ct, position){
        Ext.ux.ListField.superclass.onRender.call(this, ct, position);
        var cls = 'ux-mselect';
        this.inputField = new Ext.form.TextField({
        	hideLabel:true,
        	width:120        	
        });
        this.inputField.on('specialkey',this.onAdd,this);
        var addButton = new Ext.Button({
        	iconCls: 'listfield-add-button'
        });
        addButton.on('click',this.onAdd,this);
        var clearButton = new Ext.Button({
        	text: "Clear"        	
        });
        clearButton.on('click', this.onClear, this);
        
        var resetButton = new Ext.Button({
        	text: "Reset"
        });
        resetButton.on('click',this.onReset,this);
        
        var fs = new Ext.form.FieldSet({
            renderTo:this.el,
            title:this.legend,
            height:this.height,
            width:this.width,
            style:"padding:0;",
            tbar:[this.inputField,addButton,'->',clearButton]
        });
        
        //if(!this.legend)fs.el.down('.'+fs.headerCls).remove();
        fs.body.addClass(cls);

        var tpl = '<tpl for="."><div style="border-bottom:1px dashed #ddd" class="' + cls + '-item';
        if(Ext.isIE || Ext.isIE7){
            tpl+='" unselectable=on';
        }else{
            tpl+=' x-unselectable"';
        }
        tpl+='><span class="listfield-remove-icon"><a class="listfield-remove-icon-a" href="#">&nbsp;</a></span>{' + this.displayField + '}</div></tpl>';

        if(!this.store){
            this.store = new Ext.data.SimpleStore({
                fields: this.dataFields,
                data : this.data
            });
        }
        
       
        this.view = new Ext.ux.DDView({
            //multiSelect: true, 
            store: this.store, 
            selectedClass: cls+"-selected", 
            tpl:tpl,
            allowDup:this.allowDup, 
            copy: this.copy, 
            allowTrash: this.allowTrash, 
            dragGroup: this.dragGroup, 
            dropGroup: this.dropGroup, 
            itemSelector:"."+cls+"-item",
            isFormField:true, 
            applyTo:fs.body,
            appendOnly:this.appendOnly,
            sortField:this.sortField, 
            sortDir:this.sortDir
            
        });  
        fs.add(this.view);
        
        this.view.on('click', this.onClick, this);        
        this.hiddenName = this.name;
        var hiddenTag={tag: "input", type: "hidden", value: "", name:this.name};
        if (this.isFormField) { 
            this.hiddenField = this.el.createChild(hiddenTag);
        } else {
            this.hiddenField = Ext.get(document.body).createChild(hiddenTag);
        }        
        this.hiddenField.dom.value = this.getList();
        fs.doLayout();        
    },    
    /*
     * Internal components events
     */
    onAdd: function(ct,e){    	
    	var val = null;
    	if(ct instanceof Ext.Button){
    		val = this.inputField.getValue();    	
    	}else{
    		if(!e) return;
	    	if(e.getKey()==e.ENTER && ct.getXType()=='textfield'){
	    		val = ct.getValue();    		
	    	}else{
	    		return;	
	    	}
    	}
    	if(!val) return;
    	if(this.ifExists(val)){
    		Ext.Msg.alert("Error","Item already exists in list"); return;
    	}
    	//this.view.store.add(new Ext.data.Record.create([val,val]))
    	var data = Ext.data.Record.create(['key','value']);    	
    	this.view.store.add(new data({key:val,value:val}))
    	this.setList(this.getList());
    	this.inputField.focus()
    },
    ifExists: function(val){
    	for(var i=0;i<this.view.store.getCount();i++){
    		if(this.view.store.getAt(i).data.value == val) return true;
    	}
    	return false;
    },
    onClear: function(){
    	this.view.store.removeAll();
    	this.setList(this.getList());
    	this.inputField.focus();
    },
    onReset: function(){
    	this.view.bindStore = this.backupStore;
    	this.inputField.focus();
    },
    /*************************************************/
    getList: function(){
        var returnArray = [];
        var c = this.view.store.getCount();
        
        for (var i=0; i<c; i++) {
            returnArray.push(this.store.getAt(i).get(this.valueField));
        }
        return returnArray.join(this.delimiter);
    },
    // private
    onClick: function(vw, index, node, e) {
    	if(e.target.className == "listfield-remove-icon-a"){
    		this.view.store.remove(this.view.store.getAt(index));
    	}
        this.setList(this.getList())
    },
    setList: function(){
    	this.hiddenField.dom.value = this.getList();
    }
});

Ext.reg("listfield", Ext.ux.ListField);
