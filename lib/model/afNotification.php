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
}
