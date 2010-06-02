<?php

class afNotification extends BaseafNotification
{
	public function getHtmlType(){
		return $this->getType();
	}
	public function getHtmlCategory(){
		return Notification::getCategoryName($this->getCategory());
	}
	public function getHtmlMessage(){
		return $this->getMessage();
	}
	public function getHtmlLinkMessage(){
		$click = "Ext.getBody().mask('Getting detail information..');Ext.Ajax.request({url:'/appFlower/notificationDetails?id=".$this->getId()."',method:'POST',success: function(response){var win = new Ext.Window({width:600,height:400,autoScroll:true,html:response.responseText}); win.show(); win.center();Ext.getBody().unmask();}})";
		return "<a href='javascript:void(0)' onclick=\"".($click)."\">".$this->getHtmlMessage()."</a>";
	}
}
