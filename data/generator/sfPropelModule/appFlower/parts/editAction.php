 
  public function executeEdit(sfWebRequest $request)
  {
  
  	$instance = false;
  	$this->id = $this->getRequestParameter("id",false);
  	
  	if($this->id) {
  		$instance = $this->getObject($this->id);
  	}
  	
  	<?php 
  	
  	$columns = $this->getTableMap()->getColumns();
  	
  	foreach($columns as $column) {
  		if($column->getRelatedName() != ".") {
  			echo "\$this->".strtolower($column->getName())." = (\$instance) ? \$instance->get".$column->getPhpName()."() : null;\n";
  		}
  	}
  	
  	?>
  	
  	$this->form = $this->configuration->getForm();
    $this-><?php echo $this->getSingularName() ?> = $this->form->getObject();
  
  	if($request->getMethodName() == "POST") {
  		$this->processForm($request, $this->form);
  	}
  	
    return XmlParser::layoutExt($this);
    
    
  }
