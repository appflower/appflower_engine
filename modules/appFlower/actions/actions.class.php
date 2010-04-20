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
	
	/**
	 * adding selected widgets to the first column of the portal page/removing deselected widgets from portal page
	 */
	public function executeChangePortalWidgets()
	{
		$config=$this->hasRequestParameter('config')?$this->getRequestParameter('config'):false;
		$selectedWidgets=$this->hasRequestParameter('selectedWidgets')?$this->getRequestParameter('selectedWidgets'):array();
		$portalWidgets=$this->hasRequestParameter('portalWidgets')?$this->getRequestParameter('portalWidgets'):false;
		
		if($config&&$portalWidgets)
		{
			/**
			 * getting the unique keys from selectedWidgets
			 */
			$selectedWidgets=array_unique(array_keys($selectedWidgets));
			
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
		
		$this->redirect($this->getRequest()->getReferer());
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
					
			foreach ($portalWidgets as $pwi=>$portalWidgetsFielset)
			{
				foreach ($portalWidgetsFielset->widgets as $pfwi=>$widget)
				{
					$ma=explode('/',$widget);
					
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
						
						$portalWidgets[$pwi]->widgetsInfo[$pfwi]['title']=$title;
						if(!isset($description)) {
							$description = "";
						}
						$portalWidgets[$pwi]->widgetsInfo[$pfwi]['description']=$description;
						
						$image=(empty($image)?'/appFlowerPlugin/images/defaultWidget.gif':$image);
						
						$portalWidgets[$pwi]->widgetsInfo[$pfwi]['image']=$image;
					}
					
					$portalWidgets[$pwi]->widgetsInfo[$pfwi]['checked']=$checked;
				}
			}
			
			//add the last item as the csrf token
			$portalWidgets[]=sfContext::getInstance()->getRequest()->getAttribute('_csrf_token');
			
			$info=json_encode(array('success'=>true,'fieldsets'=>$portalWidgets));
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
}
