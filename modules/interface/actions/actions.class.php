<?php

/**
 * interface actions.
 *
 * @package    manager
 * @subpackage interface
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class interfaceActions extends CustomActions
{
  public function executeWce()
  {
    $this->setLayout("layoutExtjs");
  }	
  
  public function executeTestExtjs3()
  {
    $this->setLayout("layoutExtjs");
  }	
  
  public function executeTest3()
  {
    $this->setLayout("layoutExtjs");
  }
  
  public function executeWcePortal()
  {
    $this->setLayout("layoutExtjs");
  }
	
  public function executeForm()
  {
    $this->setLayout("layoutExtjs");
  }	
  
  public function executeTest2()
  {
    $this->setLayout("layoutExtjs");
  }
  
  public function executePortal()
  {
  	
    $this->setLayout("layoutExtjs");
  }
  
  public function executePortal2()
  {
    $this->setLayout("layoutExtjs");
  }
  
  public function executePortal3()
  {
    $this->setLayout("layoutExtjs");
  }
  
  public function executePortal4()
  {
    $this->setLayout("layoutExtjs");
  }
  
  public function executeWizardStep1()
  {
    $this->setLayout("layoutExtjs");
  }
  
  public function executeWizardStep2()
  {
    $this->setLayout("layoutExtjs");
  }
  
  public function executeWizardStep3()
  {
    $this->setLayout("layoutExtjs");
  }
	
  public function executeGrid()
  {
    $this->setLayout("layoutExtjs");
  }
  
  public function executeGridtree()
  {
    $this->setLayout("layoutExtjs");
  }
  
  public function executeGridcustom()
  {
    $this->setLayout("layoutExtjs");
  }
  
  public function executeGridpanel()
  {
    
  }
  
  public function executeGridpanel2()
  {
    
  }
	
  public function executeTabpanel()
  {
    
  }
  
  public function executeFormpanel()
  {
    
  }
  
  public function executeFormpanel2()
  {
    
  }
  
  public function executeDragdroppanel()
  {
    
  }
   
  public function executeTest($request)
  {
  	if($this->getRequest()->getMethod()==sfRequest::POST)
  	{
  		/*if ($request->hasFiles())
		{
			foreach ($request->getFileNames() as $uploadedFile)
			{
				$fileName = $request->getFileName($uploadedFile);
				$fileSize = $request->getFileSize($uploadedFile);
				$fileType = $request->getFileType($uploadedFile);
				$fileError = $request->hasFileError($uploadedFile);*/
				//$uploadDir = sfConfig::get('sf_upload_dir');
				//$request->moveFile($uploadedFile, $uploadDir.'/'.$fileName);
			/*}
		}*/
  		
		//$result = array('success' => true,'message'=>serialize($_POST));
		
		if(isset($_POST['my_name'])&&strlen($_POST['my_name'])<4)
		{
			$errors['my_name']='My name has less than 4 chars !';
		}
		
		if(isset($_POST['my_multi_combo'])&&strlen($_POST['my_multi_combo'])<1)
		{
			$errors['my_multi_combo']='Select a combo value!';
		}
		
		if(isset($_POST['my_double_multi_combo'])&&strlen($_POST['my_double_multi_combo'])<1)
		{
			$errors['my_double_multi_combo']='Select a double multi combo value!';
		}
		
		if(isset($_POST['my_double_tree'])&&strlen($_POST['my_double_tree'])<1)
		{
			$errors['my_double_tree']='Select a double tree value!';
		}
		
		if(isset($_POST['my_textarea'])&&strlen($_POST['my_textarea'])<1)
		{
			$errors['my_textarea']='Write a value in my textarea!';
		}
		
		if(isset($_POST['my_combo_button_value'])&&strlen($_POST['my_combo_button_value'])<10)
		{
			$errors['my_combo_button_value']='Test error!';
		}
		
		/*if(isset($errors)&&count($errors)>0)
		{*/
			$result = array('success' => true/*,'errors'=>$errors*/,'message'=>'Are you sure you want to redirect?','redirect'=>'/interface/form','confirm'=>true);
		/*}*/
		
  		if(isset($_FILES['my_file'])&&$_FILES['my_file']['size']>0)
  		{
  			$result['message']='The file called '.$_FILES['my_file']['name'].' was uploaded !';
  		}
  		
	    $result = json_encode($result);
  		
  		return $this->renderText($result);
  	}
  }
   
  function executeJsonAutocomplete()
  {
  	$start = $this->request->getParameterHolder()->has('start')?$this->request->getParameterHolder()->get('start'):0;
	$limit = $this->request->getParameterHolder()->has('limit')?$this->request->getParameterHolder()->get('limit'):20;
	$page=($start==0)?1:(ceil($start/$limit)+1);
	
	$c=new Criteria();
  	$c->add(TimeZonesPeer::NAME,'%'.$this->getRequestParameter('query').'%',Criteria::LIKE);
	
	$pager = new sfPropelPager('TimeZones', $limit);
	$pager->setPage($page);
	$pager->setCriteria($c);
	$pager->init();	
  	
  	
  	if($pager->getNbResults()>0)
  	{
  		$grid_data=new ImmExtjsGridData();
  		$grid_data->totalCount=$pager->getNbResults();
  		
  		foreach($pager->getResults() as $object)
  		{  			
  			$grid_data->addRowData(array("var_name"=>$object->getName()));
  		}
  	}
  	
  	return $this->renderText($grid_data->end());
  }
  
  function executeJson() 
  {

    $data = array(
      array("id" => 1, "date" => "2008-12-12 16:00:50", "label" => "large", "amount" => 1, "extra" => "nothing"),
      array("id" => 2, "date" => "2008-12-12 16:00:55", "label" => "large", "amount" => 2, "extra" => "nothing")
    );

    $result = array(
      'total' => count($data),
      'data'  => $data
    );


    $result = json_encode($result);

    $this->getResponse()->setHttpHeader("X-JSON", '()'); // set a header, (although it is empty, it is nicer than without a correct header. Filling the header with the result will not be parsed by extjs as far as I have seen).
//    sfConfig::set('sf_web_debug', false); // set to false for speed-up (done automatically for production-environment)
    return $this->renderText($result);  // so return the result in the content, but without using symfony-templates.
  }
  
  function executeActions()
  {
  	$this->setLayout(false);
  }
  
  function executeFormJsonButton()
  {
  	$result['message']='A test message !';
  	
  	$result=json_encode($result);
  	
  	return $this->renderText($result);
  }
  
  function executeGridtreeJsonButton()
  {
  	$selections=$this->getRequestParameter('selections');
  	
  	//just json_decode
  	$decoded_selections=json_decode($selections);
  	
  	$result['message']=$selections;
  	
  	$result=json_encode($result);
  	
  	return $this->renderText($result);
  }
  
  function executeJsonactions()
  {
  	$c = new Criteria();
  	
  	$start = $this->request->getParameterHolder()->has('start')?$this->request->getParameterHolder()->get('start'):0;
	$limit = $this->request->getParameterHolder()->has('limit')?$this->request->getParameterHolder()->get('limit'):20;
	$page=($start==0)?1:(ceil($start/$limit)+1);
  		
	// pager
	$pager = new sfPropelPager('afsNotification', $limit);
	$pager->setPage($page);
	$pager->setCriteria($c);
	$pager->init();
  	
  	$grid_data=new ImmExtjsGridData();
  	$grid_data->totalCount=$pager->getNbResults();
  	
  	$i=0;
  	
  	foreach ($pager->getResults() as $notification)
  	{
  		$i++;
  		
  		$grid_data->addRowData(array("company"=>$notification->getIP().' test test test test test test test test1 test2 test3 test4 test5', "lastChange"=>'8/1 12:00am', "industry"=>$i/*'Manufacturing'*/, "action1"=>"https://192.168.0.129/interface/form", "action2"=>"https://192.168.0.129/interface/portal","_color"=>"#CC0000","_selected"=>true/*,"hide2"=>true*/));
  	}
  	
  	//$grid_data->addRowData(array("company"=>'some company', "lastChange"=>'8/1 12:00am', "industry"=>10/*'Manufacturing'*/, "action1"=>"https://192.168.0.129/interface/form", "action2"=>"https://192.168.0.129/interface/portal","_color"=>"#CC0000"/*,"hide2"=>true*/));
  	
  	//print_r($grid_data);
  	
	return $this->renderText($grid_data->end());
	
  }
  
  function executeJsonactionstree()
  {
  	$c = new Criteria();
  	
  	$start = $this->request->getParameterHolder()->has('start')?$this->request->getParameterHolder()->get('start'):0;
	$limit = $this->request->getParameterHolder()->has('limit')?$this->request->getParameterHolder()->get('limit'):20;
	$anode = $this->request->getParameterHolder()->has('anode')?$this->request->getParameterHolder()->get('anode'):null;
	$page=($start==0)?1:(ceil($start/$limit)+1);
  		
	// pager
	$pager = new sfPropelPager('afsNotification', $limit);
	$pager->setPage($page);
	$pager->setCriteria($c);
	$pager->init();
  	
  	$grid_data=new ImmExtjsGridData();
  	$grid_data->totalCount=$pager->getNbResults();
  	
  	foreach ($pager->getResults() as $notification)
  	{
  		$grid_data->addRowData(array("company"=>$notification->getIP(), "lastChange"=>'8/1 12:00am', "industry"=>'Manufacturing', "action1"=>"https://192.168.0.129/interface/form", "action2"=>"https://192.168.0.129/interface/portal","_id"=>(($anode==null)?$notification->getId():rand()),"_parent"=>$anode,"_is_leaf"=>(($anode==null)?false:true),'_color'=>(($anode==null)?'#ccc000':'#cc0000'),'_selected'=>true,"_buttonOnColumn"=>"company","_buttonText"=>"Show description","_buttonDescription"=>"It's just a description text 1 ".rand(0,99)."!"/*,"hide2"=>true*/));
  		
  	}
  	
  	if($anode==null)
  	{
  		$parent=rand();
  		
  		$grid_data->addRowData(array("company"=>'1 CHILD', "lastChange"=>'8/1 12:00am', "industry"=>'Manufacturing', "action1"=>"https://192.168.0.129/interface/form", "action2"=>"https://192.168.0.129/interface/portal","_id"=>$parent,"_parent"=>null,"_is_leaf"=>false,'_color'=>'#ccc000','_selected'=>true,"_buttonOnColumn"=>"company","_buttonText"=>"Show description","_buttonDescription"=>"It's just a description text 2 ".rand(0,99)."!"/*,"hide2"=>true*/));
  		
  		$child=rand();
  		
  		$grid_data->addRowData(array("company"=>'the only CHILD', "lastChange"=>'8/1 12:00am', "industry"=>'Manufacturing', "action1"=>"https://192.168.0.129/interface/form", "action2"=>"https://192.168.0.129/interface/portal","_id"=>$child,"_parent"=>$parent,"_is_leaf"=>true,'_color'=>'#ccc000','_selected'=>true,"_buttonOnColumn"=>"company","_buttonText"=>"Show description","_buttonDescription"=>"It's just a description text 3 ".rand(0,99)."!"/*,"hide2"=>true*/));
  		
  		$grid_data->addRowData(array("company"=>'the only CHILD 2', "lastChange"=>'8/1 12:00am', "industry"=>'Manufacturing', "action1"=>"https://192.168.0.129/interface/form", "action2"=>"https://192.168.0.129/interface/portal","_id"=>rand(),"_parent"=>$child,"_is_leaf"=>true,'_color'=>'#ccc000','_selected'=>true,"_buttonOnColumn"=>"company","_buttonText"=>"Show description","_buttonDescription"=>"It's just a description text 4 ".rand(0,99)."!"/*,"hide2"=>true*/));
  		$grid_data->addRowData(array("company"=>'the only CHILD 3', "lastChange"=>'8/1 12:00am', "industry"=>'Manufacturing', "action1"=>"https://192.168.0.129/interface/form", "action2"=>"https://192.168.0.129/interface/portal","_id"=>rand(),"_parent"=>$child,"_is_leaf"=>true,'_color'=>'#ccc000','_selected'=>true,"_buttonOnColumn"=>"company","_buttonText"=>"Show description","_buttonDescription"=>"It's just a description text 5 ".rand(0,99)."!"/*,"hide2"=>true*/));
  	}
  	
	return $this->renderText($grid_data->end());
	
  }
  
  function executeJsoncustomgrid()
  {
  	$grid_data=new ImmExtjsGridData();
  	
  	$files = sfFinder::type('file')->ignore_version_control()->maxdepth(1)->in('/usr/www/manager/web/images/famfamfam');
  	
  	sfProjectConfiguration::getActive()->loadHelpers(array('Asset','Tag'));
  	
  	for ($i=0;$i<30;$i++)
  	{
  		$grid_data->addRowData(array("html"=>image_tag(str_replace('/usr/www/manager/web','',$files[$i]),array('width'=>'50'))));
  	}
  	
	return $this->renderText($grid_data->end());
	
  }
  
  public function executeTree()
  {
  	$this->setLayout(false);
  }
  
  public function executeTwotree()
  {
  	$this->setLayout(false);
  }
  
  // from php manual page
	function formatBytes($val, $digits = 3, $mode = "SI", $bB = "B"){ //$mode == "SI"|"IEC", $bB == "b"|"B"
	   $si = array("", "K", "M", "G", "T", "P", "E", "Z", "Y");
	   $iec = array("", "Ki", "Mi", "Gi", "Ti", "Pi", "Ei", "Zi", "Yi");
	   switch(strtoupper($mode)) {
	       case "SI" : $factor = 1000; $symbols = $si; break;
	       case "IEC" : $factor = 1024; $symbols = $iec; break;
	       default : $factor = 1000; $symbols = $si; break;
	   }
	   switch($bB) {
	       case "b" : $val *= 8; break;
	       default : $bB = "B"; break;
	   }
	   for($i=0;$i<count($symbols)-1 && $val>=$factor;$i++)
	       $val /= $factor;
	   $p = strpos($val, ".");
	   if($p !== false && $p > $digits) $val = round($val);
	   elseif($p !== false) $val = round($val, $digits-$p);
	   return round($val, $digits) . " " . $symbols[$i] . $bB;
	}
  
  public function executeGetNodes()
  {
  	
	$nodes[] = array('text'=>'Group 1', 'value'=>'G1', 'leaf'=>false, 'iconCls'=>'folder', 'children'=>array(array('text'=>'Item1', 'value'=>'item 1', 'leaf'=>true, 'iconCls'=>'file'),array('text'=>'Item2', 'value'=>'item 2', 'leaf'=>true, 'iconCls'=>'file')));
	
	$result=json_encode($nodes);
	
	return $this->renderText($result);
  }

  public function executeFiletree()
  {
  	$real_root=SF_ROOT_DIR.'/web/filetree_test';
  	  	
	$cmd=$this->hasRequestParameter('cmd')?$this->getRequestParameter('cmd'):null;
		
	switch ($cmd)
	{
		case "get":
			$path=$this->hasRequestParameter('path')?str_replace('root',$real_root,$this->getRequestParameter('path')):null;
			$files = sfFinder::type('any')->ignore_version_control()->maxdepth(0)->in($path);
			 				
			if(count($files)>0)
			{
				foreach ($files as $file)
				{
					$result[]=array('text'=>basename($file),'leaf'=>(is_file($file)?true:false));
				}
			}
			else
			$result = array('success' => true);
			break;
		case "newdir":
			$dir=$this->hasRequestParameter('dir')?str_replace('root',$real_root,$this->getRequestParameter('dir')):null;
			
			if(Util::makeDirectory($dir))
			{
				$result = array('success' => true);
			}
			else
			$result = array('success' => false,'error'=>'Cannot create directory '.$this->getRequestParameter('dir'));
			break;
		case "delete":
			$file=$this->hasRequestParameter('file')?str_replace('root',$real_root,$this->getRequestParameter('file')):null;
			
			if(Util::removeResource($file))
			{			
				$result = array('success' => true);
			}
			else
			$result = array('success' => false,'error'=>'Cannot delete '.(is_file($file)?'file':'directory').' '.$this->getRequestParameter('file'));
			break;
		case "rename":
			$new=$this->hasRequestParameter('newname')?str_replace('root',$real_root,$this->getRequestParameter('newname')):null;
			$old=$this->hasRequestParameter('oldname')?str_replace('root',$real_root,$this->getRequestParameter('oldname')):null;
			
			if(Util::renameResource($old,$new))
			{			
				$result = array('success' => true);
			}
			else
			$result = array('success' => false,'error'=>'Cannot rename '.(is_file($old)?'file':'directory').' '.$this->getRequestParameter('oldname'));
			break;
		case "upload":
			$path=$this->hasRequestParameter('path')?str_replace('root',$real_root,$this->getRequestParameter('path')):null;
			
			if($this->getRequest()->hasFiles())
			{
				foreach ($_FILES as $file=>$params)
				{
					if($params['size']>0)
					{
						$extension = substr($params['name'],strrpos($params['name'], '.')+1);
						
						$fileName=Util::stripText(substr($params['name'],0,(strlen($params['name'])-strlen($extension)-1))).'.'.$extension;
			  			
			  			if(!$this->getRequest()->moveFile($file, $path.'/'.$fileName,0777))
			  			{
			  				$errors[$file]='File upload error';
			  			}
					} 		
				}
			}
			
			if(!isset($errors))
			{			
				$result = array('success' => true);
			}
			else
			$result = array('success' => false,'errors'=>$errors);
			break;
		default:
			$result = array('success' => true);
			break;
	}
	  		
	$result = json_encode($result);
	
	return $this->renderText($result);
  }
  
  public function executeTestUpdater()
  {   	  	
  	Util::serverPush(array('step'=>'start','title'=>'Tasks','msg'=>'Starting task1 '.date("H:i:s"),'percent'=>'0'));
  	Util::serverPush(array('step'=>'in','msg'=>'Task1 almost completed','percent'=>'50'));
  	Util::serverPush(array('step'=>'in','msg'=>'Task1 completed '.date("H:i:s"),'percent'=>'100'));
  	
  	//Util::serverPush(array('step'=>'error','msg'=>'SOME ERROR APPEARED !'));
  	Util::serverPush(array('step'=>'in','msg'=>'Task2 starting '.date("H:i:s"),'percent'=>'0'));
  	for ($i=1;$i<80;$i++)
  	{
  		Util::serverPush(array('step'=>'in','msg'=>'Task2 running','percent'=>($i/4)));
  	}
  	Util::serverPush(array('step'=>'in','msg'=>'Task2 running','percent'=>'20'));
  	Util::serverPush(array('step'=>'in','msg'=>'Task2 running','percent'=>'40'));
  	Util::serverPush(array('step'=>'in','msg'=>'Task2 running','percent'=>'60'));
  	Util::serverPush(array('step'=>'in','msg'=>'Task2 running','percent'=>'80'));
  	Util::serverPush(array('step'=>'in','msg'=>'Task2 finished '.date("H:i:s"),'percent'=>'100'));
  	
  	Util::serverPush(array('step'=>'in','msg'=>'Task3 starting '.date("H:i:s"),'percent'=>'0'));
  	Util::serverPush(array('step'=>'in','msg'=>'Task3 running','percent'=>'40'));
  	Util::serverPush(array('step'=>'in','msg'=>'Task3 running','percent'=>'80'));
  	Util::serverPush(array('step'=>'in','msg'=>'Task3 finished '.date("H:i:s"),'percent'=>'100'));
  	  	  	
  	Util::serverPush(array('step'=>'stop','msg'=>'Tasks finished '.date("H:i:s"),'percent'=>'100','hideAfter'=>3));
  	
  	die();
  }
}
