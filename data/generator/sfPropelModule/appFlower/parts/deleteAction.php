  public function executeDelete(sfWebRequest $request)
  {
	$selections = $request->getParameter("selections");
	$all = $request->getParameter("all");
	$c = new Criteria();

	 if($selections) {
		$selections = json_decode($selections);
		$ids = array();
		foreach($selections as $obj) {
			$ids[] = $obj->id;
		}
		$c->add(<?php echo $this->getModelClass()."Peer";?>::ID,$ids,Criteria::IN);
	} else {
		if(!$all) {
			$objects = array($this->getObject($request->getParameter("id")));
		}
		
	}
	
	if(!$objects) {
		$objects = <?php echo $this->getModelClass()."Peer";?>::doSelect($c);
	}

	foreach($objects as $object) {
	
		if($object) {
			 $this->dispatcher->notify(new sfEvent($this, 'admin.delete_object', array('object' => $object)));
	
	         $object->delete();
		} 
	
	}
	
	if(!empty($objects)) {
		$result = array('success' => true,'message'=> 'The item(s) were deleted successfully.', redirect => '<?php echo $this->getModuleName() ?>/list');
	} else {
		$result = array('success' => false,'message'=> 'The operation has failed, an erroc occured!');
	}
	
	$result = json_encode($result);
    return $this->renderText($result);
  
  }
