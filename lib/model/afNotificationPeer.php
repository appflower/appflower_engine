<?php

class afNotificationPeer extends BaseafNotificationPeer
{
	public static function getNotifications(){		
		$user_id = sfContext::getInstance()->getUser()->getProfile()->getUserId();
		$var = sfContext::getInstance()->getActionStack()->getLastEntry()->getActionInstance()->getVarHolder()->getAll();
		
		$cri = new Criteria();
		$cri->add(afNotifiedForPeer::USER,$user_id);
		$notified = afNotifiedForPeer::doSelectOne($cri);
		$notified_id = $notified?$notified->getNotificationId():0;
		
		$c = new Criteria();	
		
		/*
		 * Criteria for either created_by is null or not the current user
		 */
		$nC1 = $c->getNewCriterion(self::CREATED_BY,$user_id,Criteria::NOT_EQUAL);
		$nC2 = $c->getNewCriterion(self::CREATED_BY,null,Criteria::ISNULL);
		$nC1->addOr($nC2);
		$c->add($nC1);

		/*
		 * Criteria for either created_for is null or is the current user
		 */		
		$nC1 = $c->getNewCriterion(self::CREATED_FOR,$user_id);
		$nC2 = $c->getNewCriterion(self::CREATED_FOR,null,Criteria::ISNULL);
		$nC1->addOr($nC2);
		$c->add($nC1);
		
		/*
		 * Criteria for the notifications if not already shown
		 */
		$c->add(self::ID,$notified_id,Criteria::GREATER_THAN);
				
		if(self::doCount($c) <= Notification::WHEN_EXCEEDS){
			$c->setLimit(3);
		}		
		if(isset($var['limit'])){
			if(!$var['limit']) return array();
			if($var['limit'] < 0) return array();
			$c->setLimit((int)$var['limit']);
		}
		$objs = self::filterObjects(self::doSelect($c));		
		
		$arr = array();
		foreach($objs as $obj){			
			$source_for_window = "var win = new Ext.Window({title:'Notification Details',autoScroll:true,frame:true, bodyStyle:'background-color:#fff',width:600,height:400}).show(); var mask = new Ext.LoadMask(win.getEl(),{msg:'Loading details... Please wait...'}); mask.show(); Ext.Ajax.request({url:'/appFlower/notificationDetails',params:{id:".$obj->getId()."},success:function(response){win.getEl().select('.x-window-body').update(response.responseText,true);mask.hide();}}); ";
			$detailLink = $obj->getLog()?'<br><a style="float:right;color:#0000ff" href="#" onclick="'.$source_for_window.'">Click here for detail</a>':'';
			$msg = self::getDecoratedMessage($obj).$detailLink;				
			$arr[] = array('title'=>$obj->getTitle(),'message'=>$msg,'type'=>$obj->getType(),'duration'=>$obj->getDuration());
					
		}
		if(count($objs) > Notification::WHEN_EXCEEDS){
			$arr = array_slice($arr,count($obj)-Notification::MAX_SHOW_NOTIFICATIONS);				
			$msg = "You have ".(count($objs)-Notification::MAX_SHOW_NOTIFICATIONS)." more notifications. please <a href='/audit/listNotifications' style='color:#0000ff'>click here</a> to view them all.";
			$arr = array_reverse($arr);
			$arr[] = array('title'=>(count($objs)-Notification::MAX_SHOW_NOTIFICATIONS)." more notifications !!",'message'=>$msg,'type'=>"INFO",'duration'=>20);
			$arr = array_reverse($arr);					
		}				
		return $arr;
	}
	public static function filterObjects($objs){		
		$newObj = array();
		$permission = sfContext::getInstance()->getUser()->hasCredential("audit_log");			
		$show_only = sfConfig::get("app_growl_notification_notify");
		$new_show_only = array();	
		foreach($show_only as $so){
			$const = constant("Notification::".strtoupper($so)."_RELATED");
			$new_show_only[] = $const;
		}		
		if(!count($objs)) return array();
		$greatest_id = 0;
		foreach($objs as $obj){
			if($obj->getId() > $greatest_id) $greatest_id = $obj->getId();					
			if(in_array($obj->getCategory(),$new_show_only) && ($obj->getCategory() != Notification::AUDIT_RELATED || ($obj->getCategory() == Notification::AUDIT_RELATED && $permission == 1))){
				$newObj[] = $obj;
			}
		}
		$user_id = sfContext::getInstance()->getUser()->getProfile()->getUserId();
		$cri = new Criteria();
		$cri->add(afNotifiedForPeer::USER,$user_id);
		$notified = afNotifiedForPeer::doSelectOne($cri);
		if(!$notified){
			$notified = new afNotifiedFor();
			$notified->setUser($user_id);			
		}	
		$notified->setNotificationId($greatest_id);
		$notified->save();
		return $newObj;
	}
	public static function getDecoratedMessage($obj){
		$user = sfGuardUserPeer::retrieveByPk($obj->getCreatedBy());		
		$userString = $user?'   -'.$user->getUsername():'';		
		$source = '<div><span style="color:#666; display:block; line-height:20px;">'.$obj->getCreatedAt().$userString.'</span>'.$obj->getMessage().'</div>';
		return $source;		
	}
	public static function getAll(){
		$user_id = sfContext::getInstance()->getUser()->getProfile()->getUserId();
		$c = new Criteria();
		$c->add(self::PERSISTENT,true);
		$permission = sfContext::getInstance()->getUser()->hasCredential("audit_log");		
		$nC1 = $c->getNewCriterion(self::CATEGORY,Notification::AUDIT_RELATED,Criteria::NOT_EQUAL);
		$nC2 = $c->getNewCriterion(self::CATEGORY,Notification::AUDIT_RELATED*$permission,Criteria::EQUAL);
		$nC1->addOr($nC2);				
		$c->add($nC1);
		$nC1 = $c->getNewCriterion(self::CREATED_FOR,$user_id);
		$nC2 = $c->getNewCriterion(self::CREATED_FOR,null,Criteria::ISNULL);
		$nC1->addOr($nC2);
		$c->add($nC1);		
		return $c;
	}	
}
