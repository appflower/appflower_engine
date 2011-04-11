/**
 * Text filter: drill down on a previously applied filter in long texts
 *
 * @author: Prakash Paudel
 * 
 */
Ext.ux.grid.filter.TextFilter = Ext.extend(Ext.ux.grid.filter.StringFilter, {
    texts:{
        addnew:"Add New",            
        and:"Containing all in result",
        or: "Containing any in result",
        not: "Not containing in result",       
        restart: "Clear this filter and restart",
	removeLast: "Remove last applied filter"
    },
    filterQueue:[],
    init: function(){
        var value = this.value = new Ext.ux.menu.EditableItem({iconCls: this.icon,hidden:true});
            value.on('keyup', this.onKeyUp, this);
            this.menu.add(value);		
            this.updateTask = new Ext.util.DelayedTask(this.fireUpdate, this);
            this.reconfigureMenu();
            this.reconfigureInputFields();
    },    
    reconfigureInputFields: function(){
        this.menu.items.each(function(item){
            if(!item.hidden && item.editor){
                this.menu.remove(item)
            }
        },this);
        this.addInputField();            
    },
    addInputField: function(){
        var newField = new Ext.ux.menu.EditableItem({iconCls: this.icon});
        this.menu.insert(0,newField);
	newField.editor.focus();
    },
    getInputValues: function(){
        var values = [];
        this.menu.items.each(function(item){
            if(!item.hidden && item.editor){
		if(item.getValue()) values.push(item.getValue());
            }
        },this);
        return values;
    },
    reconfigureMenu: function(){
        var addNew = new Ext.menu.Item({
            text: this.texts.addnew,
            iconCls:"icon-plus",
            scope:this,
            hideOnClick:false,
            handler: function(){
                this.addInputField();
            }
        });
        var andButton = new Ext.menu.Item({
            text: this.texts.and,
            scope:this,
	    icon:"/images/famfamfam/bullet_green.png",
            handler: function(){
                this.addToFilterQueue("and");
            }
        });
        
        var orButton = new Ext.menu.Item({
            text:this.texts.or,
            scope:this,
	    icon:"/images/famfamfam/bullet_yellow.png",
            handler: function(){
                this.addToFilterQueue("or");
            }
        });
        
        var notButton = new Ext.menu.Item({
            text: this.texts.not,
            scope: this,
	    icon:"/images/famfamfam/bullet_red.png",
            handler: function(){
                this.addToFilterQueue("not");
            }
        });
        
        this.resetButton = new Ext.menu.Item({
            text: this.texts.restart,
            scope:this,
	    disabled:true,
	    hideOnClick:false,
	    icon:"/images/famfamfam/arrow_refresh.png",
            handler: function(){
                this.resetFilterQueue();
            }
        });
	this.removeLast = new Ext.menu.Item({
		text: this.texts.removeLast,
		scope:this,
		disabled:true,
		icon:"/images/famfamfam/arrow_undo.png",
		handler: function(){
			this.removeLastQueue();
		}
	});
        //Add the items to menu   
        this.menu.add(addNew);        
        this.menu.add(new Ext.menu.Separator());
        this.menu.add(andButton);
        this.menu.add(orButton);
        this.menu.add(notButton);
        this.menu.add(new Ext.menu.Separator());
        this.menu.add(this.resetButton);
	this.menu.add(this.removeLast);
    },
    makeChanges: function(type,value){
	if(!value) value = this.getInputValues(); 
	this.filterQueue.push({
            keys: value,
            type: type
        });	
	this.resetButton.setDisabled(false);
	this.removeLast.setDisabled(false);
	this.removeLast.setText(this.texts.removeLast+" ("+this.filterQueue.length+")");
    },
    setValue: function(value){
	if(!Ext.isJsonString(value)){
		this.filterQueue = [];
		this.makeChanges("and",[value]);
	}	
	this.value.setValue(value);
	this.fireEvent("update", this);
    },
    addToFilterQueue: function(type){
        this.makeChanges(type);
	this.execute();
    },
    execute: function(){
	this.setValue(Ext.util.JSON.encode(this.filterQueue));            
        this.reconfigureInputFields();
        this.fireUpdate();
	this.removeLast.setText(this.texts.removeLast+" ("+this.filterQueue.length+")");
    },
    resetFilterQueue: function(){
        this.reconfigureInputFields();
        this.filterQueue = [];
        this.setValue('');
	this.resetButton.setDisabled(true);
    },
    removeLastQueue: function(){
	if(this.filterQueue.length){
		this.filterQueue.pop();
	}
	if(!this.filterQueue.length){
		this.removeLast.setDisabled(true);
	}
	this.execute();
    }, 
    serialize: function(){
            var args = {type: 'text', value: this.getValue()};
            this.fireEvent('serialize', args, this);
            return args;
    },
    setActive: function(active, suppressEvent){
	if(this.active != active){
		this.active = active;
		if(suppressEvent !== true){
			this.fireEvent(active ? 'activate' : 'deactivate', this);			
		}
		if(!active) this.filterQueue = [];
	}	
	this.hideTask.delay(2000);
    },
    _format: function(text){
	return " <span style='color:#79a3d7'>"+text+"</span> ";
    },
    _enclose: function(arr,join){
	if(arr.length > 1)
	return this._format("[")+arr.join(this._format(join.toUpperCase()))+this._format("]");
	else
	return arr.join(this._format(join.toUpperCase()));
    },
    isJsonString: function(string){
	var rc = null;
	try{
		rc=new RegExp('^("(\\\\.|[^"\\\\\\n\\r])*?"|[,:{}\\[\\]0-9.\\-+Eaeflnr-u \\n\\r\\t])+?$')
	}catch(z){
		rc=/^(true|false|null|\[.*\]|\{.*\}|".*"|\d+|\d+\.\d+)$/
	}
	return rc.test(string);
    },
    getDisplayValue: function(){
        var value = this.getValue();
        var temp = [];
        if(value == null || value == "") return '';
	if(!Ext.isJsonString(value)) return value;
        value = Ext.util.JSON.decode(value);	
        for(i in value){
            v = value[i];
            if(!v.keys && !v.type) continue;
	    if(v.type == "not"){
		var notArray = [];
		Ext.each(v.keys,function(k){
			notArray.push(this._format("NOT")+k);
		},this);
		temp.push(this._enclose(notArray,"and"));
	    }else{
		temp.push(this._enclose(v.keys,v.type));
	    }            
        }
	return this._enclose(temp,"and");
    }
});