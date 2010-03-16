Ext.ns("Ext.ux.plugins");
Ext.ux.plugins.DayTimeSelect = Ext.extend(Ext.form.Field, {
    border:true,
    width:250,
    dayTimeFieldWidth:60,
    fromFieldWidth:60,
    toFieldWidth:60,
    buttonWidth:50,
    listWidth:200,
    layout:'form',
    hidden:true,
  
    prepareValue:function(dataType,dataArray){
    	da = Array();
    	j = 0;
    	for(i=0;i<dataArray.length;i++){
    		if(dataArray[i] != '' && dataArray[i] != null){
    			da[j] = dataArray[i];
    			j++;
    		}
    	}
    	if(this.returnType == 'json'){
    		var returnJSON = Array();
    		returnJSON[0] = dataType;
    		returnJSON[1] = da;
    		json = Ext.util.JSON.encode(returnJSON); 
    		this.setValue(json);
    	}else{
    		commaS = '';
    		
    		for(i=0;i<dataArray.length;i++){
	    		if(dataArray[i] != '' && dataArray[i] != null){
	    			commaS += dataArray[i];
	    			if(i != (dataArray.length-1)) commaS += ",";
	    		}	    		
    		}
    		if(commaS != "") commaS = dataType+","+commaS;
    		this.setValue(commaS);
    	}
    },
    resizeComponents:function(type){    	
    	if(type == 'auto'){    		
    		var fw = this.width - 30;
    		//Upper row widths
    		this.fromFieldWidth = Math.floor((25/100)*fw);
    		this.toFieldWidth = Math.floor((25/100)*fw);
    		this.buttonWidth = Math.floor((20/100)*fw);
    		var t = this.fromFieldWidth + this.toFieldWidth + this.buttonWidth + 30;
    		this.dayTimeFieldWidth = this.width - t;    		
    		//Lower row widths
    		this.listWidth = this.width - this.buttonWidth - 5;     			
    	}
    }    
    ,initComponent:function() {
        var config = {
  	
        };
        Ext.apply(this, config);

        Ext.ux.plugins.DayTimeSelect.superclass.initComponent.apply(this, arguments);
    }     
    ,onRender:function(ct,position){
    	//this.el.style.display = "none";
    	this.resizeComponents('auto');
    	
        Ext.ux.plugins.DayTimeSelect.superclass.onRender.apply(this, arguments);
        var randomN = Math.floor(Math.random()*10000000);
        var dataType = 'day';
        var dataArray = Array();
        mainDiv = Ext.DomHelper.insertFirst(ct, {tag: 'div', style:'width:'+this.width+'px;float:left'});
		topRowDiv = Ext.DomHelper.append(mainDiv,{tag:'div',style:'width:inherit;float:left;'});
		dayTimeDiv = Ext.DomHelper.append(topRowDiv,{tag:'div',style:'width:'+this.dayTimeFieldWidth+'px;float:left; margin-right:15px;'});
		fromDiv = Ext.DomHelper.append(topRowDiv,{tag:'div',style:'float:left;width:'+this.fromFieldWidth+'px;'});
		hyphenDiv = Ext.DomHelper.append(topRowDiv,{tag:'div',style:'text-align:center;float:left;width:10px;',html:'-'});						
		toDiv = Ext.DomHelper.append(topRowDiv,{tag:'div',style:'float:left;margin-right:5px;width:'+this.toFieldWidth+'px;'});
		okButtonDiv = Ext.DomHelper.append(topRowDiv,{tag:'div',style:'float:right;width:'+this.buttonWidth+'px;'});
		clearDiv = Ext.DomHelper.append(mainDiv,{tag:'div',style:'clear:both'});
	    clearDiv.id = 'daytimeselect_'+randomN+'_cleardiv0_';
		
		dayStore = new Ext.data.SimpleStore({				        
	        fields: [
	            'valueField',
	            'displayField'
	        ],
	        data: [
			        ['1', '1'], ['2', '2'],['3', '3'], ['4', '4'],['5', '5'], ['6', '6'],['7', '7'], ['8', '8'], ['9', '9'],['10', '10'],
			        ['11', '11'], ['12', '12'],['13', '13'], ['14', '14'],['15', '15'], ['16', '16'],['17', '17'], ['18', '18'], ['19', '19'],['20', '20'],
			        ['21', '21'], ['22', '22'],['23', '23'], ['24', '24'],['25', '25'], ['26', '26'],['27', '27'], ['28', '28'], ['29', '29'],['30', '30'],['31', '31']
			      ]
	    });
	    timeStore = new Ext.data.SimpleStore({				        
	        fields: [
	            'valueField',
	            'displayField'
	        ],
	        data: [
			        ['1', '1'], ['2', '2'],['3', '3'], ['4', '4'],['5', '5'], ['6', '6'],['7', '7'], ['8', '8'], ['9', '9'],['10', '10'],
			        ['11', '11'], ['12', '12'],['13', '13'], ['14', '14'],['15', '15'], ['16', '16'],['17', '17'], ['18', '18'], ['19', '19'],['20', '20'],
			        ['21', '21'], ['22', '22'],['23', '23'], ['24', '24']
			      ]
	    });
		dayTimeCombo = new Ext.form.ComboBox({
			typeAhead: true,
			id:'daytimeselect_daytimecombo_'+randomN,
		    triggerAction: 'all',
		    width:this.dayTimeFieldWidth,
		    lazyRender:true,
		    mode: 'local',
		    store: new Ext.data.SimpleStore({				        
		        fields: [
		            'valueField',
		            'displayField'
		        ],
		        data: [['day', 'Daily'], ['time', 'Hourly']]
		    }),
		    listeners:{
		    	select:function(){				    		
		    		if(this.getValue()=="time"){				    		
		    			Ext.getCmp('daytimeselect_fromcombo_'+randomN).bindStore(timeStore);
		    			Ext.getCmp('daytimeselect_tocombo_'+randomN).bindStore(timeStore);
		    		}
		    		if(this.getValue()=="day"){				    		
		    			Ext.getCmp('daytimeselect_fromcombo_'+randomN).bindStore(dayStore);
		    			Ext.getCmp('daytimeselect_tocombo_'+randomN).bindStore(dayStore);
		    		}
		    		if(!dataArray.length)
		    		dataType = this.getValue();
		    	}
		    },
		    valueField: 'valueField',
		    displayField: 'displayField'
		});
		
		dayTimeCombo.setValue('day');
		dayTimeCombo.render(dayTimeDiv);
		
		fromCombo = new Ext.form.ComboBox({
			typeAhead: true,
			id:'daytimeselect_fromcombo_'+randomN,
		    triggerAction: 'all',
		    width:this.fromFieldWidth,
		    lazyRender:true,
		    mode: 'local',
		    store: dayStore,
		    valueField: 'valueField',
		    displayField: 'displayField',
		    emptyText:'From',
		   
		});
		
		//fromCombo.setValue('day');
		fromCombo.render(fromDiv);
		
		toCombo = new Ext.form.ComboBox({
			typeAhead: true,
			id:'daytimeselect_tocombo_'+randomN,
		    triggerAction: 'all',
		    width:this.toFieldWidth,
		    lazyRender:true,
		    mode: 'local',
		    store: dayStore,
		    valueField: 'valueField',
		    displayField: 'displayField',
		    emptyText:'To',
		   
		});
		
		//toCombo.setValue('day');
		toCombo.render(toDiv);
		
		okButton = new Ext.Button({
			text:"Add",
			parent:this,
			width:this.buttonWidth,
			count:0,
			t_item:0,
			listeners:{
				click:function(){
					if(Ext.getCmp('daytimeselect_daytimecombo_'+randomN).getValue() == "" || Ext.getCmp('daytimeselect_fromcombo_'+randomN).getValue() == "" || Ext.getCmp('daytimeselect_tocombo_'+randomN).getValue() == ""){
						Ext.Msg.show({
						   title:'Error !',
						   msg: 'Invalid Selections',
						   buttons: Ext.Msg.OK,
						   //fn: processResult,
						   icon: Ext.MessageBox.ERROR
						});
						return;
					}
					//Uncomment if from should be less than to value
					/*if(Ext.getCmp('daytimeselect_fromcombo_'+randomN).getValue() > Ext.getCmp('daytimeselect_tocombo_'+randomN).getValue()){
						Ext.Msg.show({
						   title:'Error !',
						   msg: 'Invalid range !<br>From value is greater than To value',
						   buttons: Ext.Msg.OK,
						   //fn: processResult,
						   icon: Ext.MessageBox.ERROR
						});
						return;
					}*/
					if(this.t_item && dataType != Ext.getCmp('daytimeselect_daytimecombo_'+randomN).getValue()){
						Ext.Msg.show({
						   title:'Error !',
						   msg: 'Invalid data type for this series',
						   buttons: Ext.Msg.OK,
						   //fn: processResult,
						   icon: Ext.MessageBox.ERROR
						});
						return;
					}							
					text = Ext.getCmp('daytimeselect_fromcombo_'+randomN).getValue()+"-"+Ext.getCmp('daytimeselect_tocombo_'+randomN).getValue();
					var found = false;
					for(var i = 0; i<dataArray.length; i++) {
						if(dataArray[i] == text) {
							found = true;
						}
					}
					if(found){
						Ext.Msg.show({
						   title:'Error !',
						   msg: 'Data already in list',
						   buttons: Ext.Msg.OK,
						   //fn: processResult,
						   icon: Ext.MessageBox.ERROR
						});
						return;
					}
					dataArray[this.count] = Ext.getCmp('daytimeselect_fromcombo_'+randomN).getValue()+"-"+Ext.getCmp('daytimeselect_tocombo_'+randomN).getValue();
					this.t_item++;
					this.parent.prepareValue(dataType,dataArray);
					
					//alert(dataArray.length);
					//alert(dataType)
					
					
	        		clearDiv = Ext.DomHelper.append(mainDiv,{tag:'div',style:'width:inherit;clear:both;font-size:0px;height:3px'});
	        		clearDiv.id = 'daytimeselect_'+randomN+'_cleardiv_'+this.count;
	        		
					listRowDiv = Ext.DomHelper.append(mainDiv,{tag:'div',style:'width:inherit;float:left;'});
					listRowDiv.id = 'daytimeselect_'+randomN+'_listrowdiv_'+this.count;
					
					listDiv = Ext.DomHelper.append(listRowDiv,{tag:'div',style:'width:'+this.parent.listWidth+'px; margin-right:5px;float:left;'});
					listDiv.id = 'daytimeselect_'+randomN+'_listdiv_'+this.count;
					
					removeButtonDiv = Ext.DomHelper.append(listRowDiv,{tag:'div',style:'width:inherit;float:right; width:'+this.parent.buttonWidth+'px;'});
					removeButtonDiv.id = 'daytimeselect_'+randomN+'_removebuttondiv_'+this.count;
				    
				    clearDiv = Ext.DomHelper.append(mainDiv,{tag:'div',style:'width:inherit;clear:both;font-size:0px;height:3px'});
	        		clearDiv.id = 'daytimeselect_'+randomN+'_cleardiv_'+this.count;
	        		
	        		    	
					list = new Ext.form.TextField({
						bodyStyle:'background-color:#f5f5f5',
						height:20,
						width:this.parent.listWidth,
						readOnly:true
					});
					list.setValue(text);
					list.render(listDiv);
					removeButton = new Ext.Button({
						text:"DEL",
						id:'daytimeselect_'+randomN+'_removebutton_'+this.count,
						c:this.count,
						t_item:this.t_item,
						parent:this,
						
						listeners:{
							click:function(){
								dataArray[this.c] = '';
								Ext.DomHelper.applyStyles(Ext.get('daytimeselect_'+randomN+'_listrowdiv_'+this.c),'display:none');
								Ext.DomHelper.applyStyles(Ext.get('daytimeselect_'+randomN+'_cleardiv_'+this.c),'display:none');
								this.parent.t_item--;
								if(this.parent.t_item == 0) dataArray = new Array();
								this.parent.parent.prepareValue(dataType,dataArray);
							}
						}
					});				
					removeButton.render(removeButtonDiv);
					this.count++;
				}
			}
		});				
		okButton.render(okButtonDiv);	
		data = this.value;
    	dataArr = data.split(',');
    	dataType = dataArr[0];
    	dayTimeCombo.setValue(dataType);
    	for(i=1;i<dataArr.length;i++){
    		valArr = dataArr[i].split("-");
    		fromCombo.setValue(valArr[0]);
    		toCombo.setValue(valArr[1]);
    		okButton.fireEvent('click');
    		//dataArray[i-1] = dataArr[i];
    		//okButton.count = i-1;
    	}
    }

});
Ext.reg('daytimeselect', Ext.ux.plugins.DayTimeSelect); 