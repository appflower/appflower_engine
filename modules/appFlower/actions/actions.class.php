<?php

/**
 *
 * @package    appFlower
 * @subpackage plugin
 * @author     radu@immune.dk
 */
class appFlowerActions extends sfActions
{
	public function preExecute()
	{
		$this->realRoot=sfConfig::get('sf_root_dir');
		$this->immExtjs=ImmExtjs::getInstance();
	}	
	
	public function executeEditHelpSettings() 
    {		
		$widgetHelpSettings = afWidgetHelpSettingsPeer::retrieveCurrent();
		$type = $widgetHelpSettings->getHelpType();
		
		$this->opt0checked = ($type == 0) ? "true" : "false";
		$this->opt1checked = ($type == 1) ? "true" : "false";
		$this->opt2checked = ($type == 2) ? "true" : "false";
		
		return XmlParser::layoutExt($this);		
	}
		
	public function executeUpdateHelpSettings() 
	{		
		$widgetHelpSettings = afWidgetHelpSettingsPeer::retrieveCurrent();
		
		$widgetHelpSettings->setHelpType($this->getRequestParameter("fieldhelp"));
		$widgetHelpSettings->setPopupHelpIsEnabled($this->getRequestParameter("edit[0][popup]"));
		$widgetHelpSettings->setWidgetHelpIsEnabled($this->getRequestParameter("edit[0][widgethelp]"));
		$widgetHelpSettings->save();
			
		$info=json_encode(array('success'=>true,'message'=>'Your changes have been successfuly saved!'));	
		
		return $this->renderText($info);		
	}
	
	public function executePopupHelp() 
    {	
    	$idXml=$this->hasRequestParameter('idXml')?$this->getRequestParameter('idXml'):false;
    
    	if($idXml) {
    		$info['html'] = "<table border='0' cellpadding='0' cellspacing='0' id='whelp'><tr><th colspan=3><strong>Widget Help</strong></th></tr>";
    		
    		$xp = XmlParser::readDocumentByUri($idXml);	
    	
    		// View type
    		
    		$view = $xp->evaluate("//i:view")->item(0)->getAttribute("type");
    		
    		// Title
    		
    		$title = $xp->evaluate("//i:title")->item(0)->nodeValue;
    		
    		$wh = $xp->evaluate("//i:description");
    
    		if($wh->length) {
    			$info['html'] .= "<tr><td colspan=3>".$wh->item(0)->nodeValue."</td></tr>";
    		} else {
    			$info['html'] .= "<tr><td colspan=3>Not available..</td></tr>";
    		}
    		
    		if($view == "edit" || $view == "show") {
	    		$fields = $xp->evaluate("//i:field[@type!='hidden']");
	    		
	    		$info['html'] .= "<tr><th><strong>Field</strong></th><th><strong>Description</strong></th><th><strong>Tip</strong></th></tr>";
	    		
	    		$tmp = array();
	    		$found = false;
	    		
	    		foreach($fields as $t) {
	    			$comment = $xp->evaluate("./i:comment",$t);
	    			if($comment->length == 1) {
	    				$c = $comment->item(0)->nodeValue;
	    			} else {
	    				$c = "";
	    			}
	    			
	    			$tip = $xp->evaluate("./i:help",$t);
	    			if($tip->length == 1) {
	    				$h = $tip->item(0)->nodeValue;
	    			} else {
	    				$h = "";
	    			}
	    			
	    			if(trim($c) || trim($h)) {	
		    			$info['html'] .= "<tr><td>".$t->getAttribute("label")."</td><td>".$c."</td><td>".$h."</td></tr>";	
		    			$found = true; 
	    			}
	    		} 
	    		
	    		if(!$found) {
	    			$info['html'] .= "<tr><td colspan=2>Not available..</td></tr>";
	    		}
    		}
 
    		$info['html'] .= "</table>";
	    	$info['winConfig']['title']=$title." Help";
	    	$info['winConfig']['width']=500;
	    	$info['winConfig']['height']=300;
    		
    	}  	
    	
    	$info=json_encode($info);
		
		return $this->renderText($info);
	}
	
	/**
	 * adding selected widgets to the first column of the portal page/removing deselected widgets from portal page
	 */
	public function executeChangePortalWidgets()
	{
		$config=$this->hasRequestParameter('config')?$this->getRequestParameter('config'):false;
		$selections=$this->hasRequestParameter('selections')?json_decode($this->getRequestParameter('selections')):false;
		$portalWidgets=$this->hasRequestParameter('portalWidgets')?$this->getRequestParameter('portalWidgets'):false;
		
		if($selections)
		{
			foreach ($selections as $selectionsParam)
			{
				if(!empty($selectionsParam[0]))
				{
					$selectedWidgets[]=$selectionsParam[0];
				}
			}
		}
		else {
			$selectedWidgets=array();
		}
				
		if($config&&$portalWidgets)
		{
			/**
			 * getting the unique keys from selectedWidgets
			 */
			$selectedWidgets=array_unique($selectedWidgets);
			
			$portalWidgets=json_decode($portalWidgets);
			foreach ($portalWidgets as $pwi=>$portalWidgetsFielset)
			{
				foreach ($portalWidgetsFielset->widgets as $pfwi=>$widget)
				{
					$allWidgets[]=$widget;
				}
			}
			
			$allWidgets=array_unique($allWidgets);
						
			$unselectedWidgets=array();
			$unselectedWidgets=array_diff($allWidgets,$selectedWidgets);
			
			$config=json_decode($config);
			$content=get_object_vars($config->content);
			unset($config->content);
			$config->content[$config->layoutItem]=$content;
			unset($content);

			$afPortalStateObj=afPortalStatePeer::updateWidgetsToState($config,$selectedWidgets,$unselectedWidgets);
		}
		
		$info=array('success'=>true,'redirect'=>$this->getRequest()->getReferer());
		$info=json_encode($info);
		
		return $this->renderText($info);
	}
	
	/**
	 * retrieving description and images for some widgets
	 * @author radu
	 */
	public function executeRetrieveWidgetsInfo()
	{
		$config=$this->hasRequestParameter('config')?$this->getRequestParameter('config'):false;
		$portalWidgets=$this->hasRequestParameter('portalWidgets')?$this->getRequestParameter('portalWidgets'):false;
		
		if($config&&$portalWidgets)
		{		
			$config=json_decode($config);
			$content=get_object_vars($config->content);
			unset($config->content);
			$config->content[$config->layoutItem]=$content;
			unset($content);
			
			$portalWidgets=json_decode($portalWidgets);			
				
			$i=0;
			foreach ($portalWidgets as $pwi=>$portalWidgetsFielset)
			{
				if(empty($portalWidgetsFielset->widgets)) continue;
				$i++;
				
				$result['rows'][$i]['title']=$portalWidgetsFielset->title;
				$result['rows'][$i]['image']='';
				$result['rows'][$i]['widget']='';
				$result['rows'][$i]['description']='';
				$result['rows'][$i]['_selected']=false;
				$result['rows'][$i]['_is_leaf']=false;
				$result['rows'][$i]['_parent']=null;
				$result['rows'][$i]['_id']=$i;
				
				$j=$i;
				
				$parentChecked=false;
				
				foreach ($portalWidgetsFielset->widgets as $pfwi=>$widget)
				{
					$j++;
					
					$ma=explode('/',$widget);
					$image = $title = $description = '';
					$checked=afPortalStatePeer::searchForWidgetInState($config,$widget);
					
					$path = sfConfig::get("sf_root_dir").'/apps/'.$this->context->getConfiguration()->getApplication().'/modules/'.$ma[1].'/config/'.$ma[2].'.xml';
					$dom = new DOMDocument();
					if($dom->load($path))
					{
						foreach ($dom->documentElement->childNodes as $oChildNode) {
							if($oChildNode->nodeName=='i:title')
							{
								$title=trim($oChildNode->nodeValue);
							}
							if($oChildNode->nodeName=='i:description')
							{
								$description=trim($oChildNode->nodeValue);	
								$image=$oChildNode->getAttribute('image');								
							}							
						}
						/**
						 * if image exists ok, but the images are saved in /images/widgets 
						 * same as the widget title, so try to get image
						 */					
						if(empty($image) && file_exists(sfConfig::get("sf_root_dir")."/web/images/widgets/".$title.".PNG")){
							$image = "/images/widgets/".$title.".PNG";
						}						
						if(!isset($description)) {
							$description = "";
						}						
						$image=(empty($image)?'/appFlowerPlugin/images/defaultWidget.gif':$image);
						$image='<img src="'.$image.'" style="margin-right:5px; border:1px solid #99bbe8; padding:3px;float:left;">';
					}
					
					$result['rows'][$j]['title']=$title;
					$result['rows'][$j]['image']=$image;
					$result['rows'][$j]['widget']=$widget;
					$result['rows'][$j]['description']=$description;
					$result['rows'][$j]['_selected']=$checked;
					$result['rows'][$j]['_is_leaf']=true;
					$result['rows'][$j]['_parent']=$i;
					$result['rows'][$j]['_id']=$j;
					
					if($checked)
					{
						$parentChecked=true;
					}
				}
				
				$result['rows'][$i]['_selected']=$parentChecked;
				
				$i=$j;
			}
			
			$result['success']=true;
			$result['totalCount']=count($result['rows']);
			
			$result['rows']=array_values($result['rows']);
			
			$info=json_encode($result);
		}
		else {
			$info=json_encode(array('success'=>false,'message'=>'Retrieving widgets information wasn\'t successful!'));
		}
		
		return $this->renderText($info);
	}
	
	/**
	 * saving the portal state when drag-droping a widget inside the portal OR when portal layout is changed
	 * @author radu
	 */
	public function executeSavePortalState()
	{
		$config=$this->hasRequestParameter('config')?$this->getRequestParameter('config'):false;
		
		if($config)
		{
			$config=json_decode($config);
			$content=get_object_vars($config->content);
			unset($config->content);
			$config->content[$config->layoutItem]=$content;
			unset($content);
									
			$afPortalStateObj=afPortalStatePeer::createOrUpdateState($config);
						
			/*$result=array('message'=>'Portal state was saved successfuly!');*/
			$result=array();
		}
		else {
			$result=array('message'=>'There was an error while sending the data!');
		}
		
		$result=json_encode($result);
		return $this->renderText($result);
	}
	
	/**
	 * remove the tab portal state when tab is removed
	 * @author radu
	 */
	public function executeRemovePortalState()
	{
		$config=$this->hasRequestParameter('config')?$this->getRequestParameter('config'):false;
		
		if($config)
		{
			$config=json_decode($config);
			$content=get_object_vars($config->content);
			unset($config->content);
			$config->content[$config->layoutItem]=$content;
			unset($content);
									
			$afPortalStateObj=afPortalStatePeer::removeState($config);
						
			/*$result=array('message'=>'Portal state was saved successfuly!');*/
			$result=array();
		}
		else {
			$result=array('message'=>'There was an error while sending the data!');
		}
		
		$result=json_encode($result);
		return $this->renderText($result);
	}
	
	/**
	 * resets the portal state to the default one
	 * @author radu
	 */
	public function executeResetPortalState()
	{
		$config=$this->hasRequestParameter('config')?$this->getRequestParameter('config'):false;
		
		if($config)
		{
			$config=json_decode($config);
			$content=get_object_vars($config->content);
			unset($config->content);
			$config->content[$config->layoutItem]=$content;
			unset($content);
									
			$afPortalStateObj=afPortalStatePeer::deleteByIdXml($config->idXml);
						
			/*$result=array('message'=>'Portal state was saved successfuly!');*/
			$result=array();
		}
		else {
			$result=array('message'=>'There was an error while sending the data!');
		}
		
		$result=json_encode($result);
		return $this->renderText($result);
	}
	
	public function executeGetComboOptions()
	{
		if($this->hasRequestParameter('class')&&$this->hasRequestParameter('method'))
		{
			$class=$this->getRequestParameter('class');
			$method=$this->getRequestParameter('method');
			
			$result['store']=call_user_func(array($class,$method));
			
			$options=array();
			foreach ($result['store'] as $key=>$value)
			{
				$options[]=array($key,$value);
			}
			
			unset($result['store']);
			
			$result['store']=$options;
			
			$result = json_encode($result);
  		
  			return $this->renderText($result);
		}
		else return sfView::NONE;
	}
	
	public function executeListjsonAuditLog()
	{
		$start = $this->request->getParameterHolder()->has('start')?$this->request->getParameterHolder()->get('start'):0;
		$limit = $this->request->getParameterHolder()->has('limit')?$this->request->getParameterHolder()->get('limit'):20;
		$page=($start==0)?1:(ceil($start/$limit)+1);
		
		$c=new Criteria();
		$c->addDescendingOrderByColumn(AuditLogPeer::ID);
		$pager = new sfPropelPager('AuditLog', $limit);
		$pager->setPage($page);
		$pager->setCriteria($c);
		$pager->init();	
	  	
	  	$grid_data = new ImmExtjsGridData();
	  	$grid_data->totalCount = $pager->getNbResults();
	  	
	  	foreach($pager->getResults() as $object) {
	  		$item=Util::getPropelObjectAsArray($object);
	  		$item['user_id']=$object->getsfGuardUser()->getUsername();
	  		$grid_data->addRowData($item);			
		}
		
		return $this->renderText($grid_data->end());
	}
	
	public function executeCodepress($request)
	{
		$this->codepress_path=$this->immExtjs->getExamplesDir().'codepress/';
		
		$this->language=(($this->hasRequestParameter('language')&&$this->getRequestParameter('language')!='undefined')?$this->getRequestParameter('language'):'generic');
		
		return $this->renderPartial('codepress');		
	}
	
	public function executeFilecontent($request)
	{
		$file=$this->hasRequestParameter('file')?$this->getRequestParameter('file'):false;
		$code=$this->hasRequestParameter('code')?$this->getRequestParameter('code'):false;
				
		if($this->getRequest()->getMethod()==sfRequest::POST)
  		{
  			if($file&&$code)
  			{
  				$file=str_replace('root',$this->realRoot,$file);
  				
  				if(is_writable($file))
  				{
  					if(@file_put_contents($file,$code))
					{
						return $this->renderText('');
					}
					else {
						$this->redirect404();
					}
  				}
  				else {
					$this->redirect404();
				}
  			}
  			else {
				$this->redirect404();
			}  			
  		}
  		else {
		
			if($file)
			{
				$file=str_replace('root',$this->realRoot,$file);
				
				$file_content=@file_get_contents($file);
			
				if($file_content)
				{
					return $this->renderText($file_content);
				}
				else {
					$this->redirect404();
				}
			}
			else {
				$this->redirect404();
			}		
  		}
	}
	
	public function executeFiletree()
	{
		$filetree_command=new ImmExtjsFileTreeCommand($this->realRoot);
		
		return $this->renderText($filetree_command->end());
	}
	public function executeSaveFilter(){		
		$success = false;
		if($this->getRequest()->getMethod() == sfRequest::POST){
			$user = $this->getUser()->getGuardUser()->getId();
			$name = $this->getRequestParameter("name");
			$path = $this->getRequestParameter("path");
			$title = $this->getRequestParameter("title");
			$state = $this->getRequestParameter("state");
			
			$c = new Criteria();
			$c->add(afSaveFilterPeer::USER,$user);
			$c->add(afSaveFilterPeer::NAME,$name);
			$c->add(afSaveFilterPeer::PATH,$path);
			if(afSaveFilterPeer::doSelectOne($c)){
				$message = "The name '".$name."' already exists for ".$title."";
			}else{
				$obj = new afSaveFilter();
				$obj->setUser($user);
				$obj->setName($name);
				$obj->setPath($path);
				$obj->setTitle($title);
				$obj->setFilter($state);
				$obj->save();
				$success = true;
				$message = "Filter state saved successfully.";
			}
		}
		$result = array("success"=>$success,"message"=>$message);
		return $this->renderText(json_encode($result));		
	}
	public function executeListFilter(){
		$success = false;
		$data = array();
		if($this->getRequest()->getMethod() == sfRequest::POST){
			$user = $this->getUser()->getGuardUser()->getId();
			$title = $this->getRequestParameter("path");
			$c = new Criteria();
			$c->add(afSaveFilterPeer::USER,$user);
			$c->add(afSaveFilterPeer::TITLE,$title);
			$c->addAscendingOrderByColumn(afSaveFilterPeer::NAME);
			$objs = afSaveFilterPeer::doSelect($c);			
			foreach($objs as $obj){
				$data[] = array("id"=>$obj->getId(),"name"=>"<a href='#' style='color:#0000ff' qtip='Apply this filter to grid' class='ux-grid-filter-apply'>".$obj->getName()."</a>","filter"=>$obj->getFilter());			
			}
			$success=true;
		}
		$result = array("success"=>$success,"rows"=>$data);
		return $this->renderText(json_encode($result));		
	}
	public function executeRemoveFilter(){
		$success = false;
		$message = "Error has occured !";
		if($this->getRequest()->getMethod() == sfRequest::POST){
			$id = $this->getRequestParameter("id");
			$obj = afSaveFilterPeer::retrieveByPk($id);
			if($obj){
				$obj->delete();
				$message = "Filter deleted successfully.";
			}
			$success=true;
			
		}
		$result = array("success"=>$success,"message"=>$message,"redirect"=>"/user/filters");
		return $this->renderText(json_encode($result));		
	}
	public function executeNotifications(){
		if($this->getRequest()->getMethod() == sfRequest::POST){
			$this->limit = $this->getRequestParameter('limit');
		}
		//while(true){
			$objs = afNotificationPeer::getNotifications();
			//if($objs) break;
			//sleep(10);			
		//}
		//Notification::getTipOfTheDay();
					
		$return = array("success"=>true,"data"=>$objs);
		echo json_encode($return);		
		exit;
	}	
	public function executeNotificationDetails(){		
		if($this->getRequest()->getMethod() == sfRequest::POST){
			$id = $this->getRequestParameter("id");
			$obj = afNotificationPeer::retrieveByPk($id);
			if($obj){				
				return $this->renderText($this->getPartial("notification_detail",array('obj'=>$obj)));				
			}
		}
		exit;
	}
	public function executeRestoreSession(){
		
		$backup = $this->getUser()->getAttributeHolder()->getAll("SESSION_BACKUP");
		$inBtw = (array) $this->getUser()->getAttributeHolder()->getAll("IN_BETWEEN");		
		$this->getUser()->getAttributeHolder()->clear();
		foreach($backup['parameters'] as $key=>$value){
			$this->getUser()->getAttributeHolder()->add($value,$key);			
		}
		foreach($inBtw as $key=>$value){
			$this->getUser()->setAttribute($key,$value);
		}
		$this->getUser()->getAttributeHolder()->removeNamespace("SESSION_BACKUP");		
		$this->getUser()->getAttributeHolder()->removeNamespace("IN_BETWEEN");
		exit;
	}	
}
