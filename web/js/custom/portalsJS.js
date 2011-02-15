/**
* Portals code extensions
* @author radu
*/
function Portals()
{
	var obj=this;
	
	this.input=false;
	this.window=false;
		
	this.createNewTab = function(target,tabpanel,portalWidgets){
		
		var buttons=new Array();
		
		buttons.push({
			text:'Create now',
			handler: function(){
				if(obj.input.getValue()!='')
				{
					var portal = new Ext.ux.Portal ({region: "center",portalLayoutType: "[100]",portalWidgets:portalWidgets,bodyBorder: false,style: "padding-right:5px;",bodyStyle: "overflow-x:hidden;overflow-y:hidden;padding-right:5px;",items: [{columnWidth: 1,style: "padding:10px 0 10px 10px;",items: []}]});									
					tabpanel.add({title:obj.input.getValue(),items:[portal]}).show();
					
					portal.showWidgetSelector(target,'Widget Selector for '+obj.input.getValue());
					
					obj.window.hide();
				}
			}
		});
		buttons.push({
			text: 'Cancel',
			handler: function(){
				obj.window.hide();
			}
		});
		
		this.showInputWindow(buttons);
	}
	
	this.removeTab = function (target,tabpanel){
		var tab=tabpanel.getActiveTab();
		var config=tab.items.items[0].getConfig();
		var tabIndex=tabpanel.items.indexOf(tab);
		
		if(tabIndex==0&&tabpanel.items.length==1)
		{
			Ext.Msg.alert("Failure","You can't remove the only tab in this portal!");
		}
		else
		{				
			Ext.Msg.confirm("Confirmation","Are you sure you would like to remove this tab?", function(btn){if (btn=="yes"){ 
				Ext.Ajax.request({ 
					url: "/appFlower/removePortalState", 
					method:"post", 
					params:{"config":config}, 
					success:function(response, options){
					/*	response=Ext.decode(response.responseText);if(response.message){Ext.Msg.alert("Success",response.message);}*/
						tabpanel.remove(tab,true);
						tabpanel.setActiveTab();
					},
					failure: function(response,options) {
						if(response.message){Ext.Msg.alert("Failure",response.message);}
					}				
				});		
			}});
		}
	}
	
	this.reset = function (target,portal){
		var config=portal.getConfig();
		
		Ext.Msg.confirm("Confirmation","<b>Are you sure you would like to reset the page?</b><br>This will reset the content to the default one!", function(btn){if (btn=="yes"){ 
				Ext.Ajax.request({ 
					url: "/appFlower/resetPortalState", 
					method:"post", 
					params:{"config":config}, 
					success:function(response, options){
						window.location.reload();
					},
					failure: function(response,options) {
						if(response.message){Ext.Msg.alert("Failure",response.message);}
					}				
				});		
			}});
	}
	
	this.changeTitle = function (target,tabpanel){
		var tab=tabpanel.getActiveTab();
		var buttons=new Array();
				
		buttons.push({
			text:'Save',
			handler: function(){
				if(obj.input.getValue()!=''&&obj.input.getValue()!=tab.title)
				{
					tab.setTitle(obj.input.getValue());
					
					var config=tab.items.items[0].getConfig();
					
					Ext.Ajax.request({ 
						url: "/appFlower/savePortalState", 
						method:"post", 
						params:{"config":config}, 
						success:function(response, options){
						/*	response=Ext.decode(response.responseText);if(response.message){Ext.Msg.alert("Success",response.message);}*/
						},
						failure: function(response,options) {
							if(response.message){Ext.Msg.alert("Failure",response.message);}
						}				
					});
					
					obj.window.hide();
				}
			}
		});
		buttons.push({
			text: 'Cancel',
			handler: function(){
				obj.window.hide();
			}
		});
		
		this.showInputWindow(buttons,tab.title);
	}
	
	this.showInputWindow = function (buttons,inputValue){
		if(!this.input)
		{
			this.input=new Ext.form.TextField({width:150,height:50,style:'font-size:20px;'});
		}
		if(!this.window)
		{
			this.window = new Ext.Window({layout:'fit',title:'Choose tab title',closeAction:'hide',width:200,height:100,plain: true,items: [obj.input],buttons: buttons});
		}
		
		if(inputValue)
		{
			this.input.setValue(inputValue);
		}
		
		this.window.show();
	}
	
	this.onTabChange = function (tabPanel,slug){
		tabPanel.afterLayoutOnceEvent=true;
		var uri = document.location.href.split('#');
		/**
		 * For IE fix
		 */		
		var check = Ext.isIE?slug:uri[uri.length-1];
		var toActivate=0;
		var activeItem = null;
		for(var i=0;i<tabPanel.items.items.length;i++){
			if(tabPanel.items.items[i].slug==check){
				toActivate=i;
				activeItem = tabPanel.items.items[i];
			}
		}      	
		tabPanel.activate(toActivate);
		if(activeItem) activeItem.doLayout();
	}
}