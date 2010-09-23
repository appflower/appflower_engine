  public function executeUpdate(sfWebRequest $request)
  {
  
  	$id = $request->getParameter("edit[0][id]");
  
    $this-><?php echo $this->getSingularName() ?> = $this->getObject($id);
    $this->form = $this->configuration->getForm($this-><?php echo $this->getSingularName() ?>);

	$this->form->getWidgetSchema()->setNameFormat("edit[%s]");

    $vars = $request->getParameterHolder()->getAll();
   	$arr = array();

	   foreach($vars["edit"] as $n => $v) {
		   	if(is_array($v)) {
		       continue;
		    }
		    if(strstr($n,"_value")) {
		    	$k = str_replace("_value","",$n);
		    } else {
		    	$k = $n;
		    }
		    $arr[$k] = $v;
	  }

   $request->setParameter("edit",$arr);
	
   $result = $this->processForm($request, $this->form);

   return $this->renderText($result);
    
  }
