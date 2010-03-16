<?php

class XmlParserValidationFilter extends sfExecutionFilter
{
	
	public function execute ($filterChain)
	{		

		$actionInstance = $this->context->getActionStack()->getLastEntry()->getActionInstance();
		$session = $this->context->getUser()->getAttributeHolder()->getAll("parser/validation");
	
		$context = sfContext::getInstance();
		
		$reflection = new ReflectionClass(get_class($actionInstance));
		
		$errors = array();
		
		$upload_status = array
		(
		1 => "File is too large!",
		2 => "File is too large!",
		3 => "Partial upload..",
		4 => "No file was uploaded!",
		6 => "Internal error (tmp folder is missing)",
		7 => "Can't write file",
		8 => "An extension stopped the upload process!"
		);
		
		if($actionInstance->getRequest()->getMethod() == sfRequest::POST) {
			foreach($session as $field => $data) {				
				if(!$actionInstance->hasRequestParameter($field)) {
					$field = substr($field,0,-1)."_value]";
				}				
				
				$left_field = '';
				$operator = '';
				$right_field = '';
				
				if($context->getRequest()->getParameterHolder()->has($field)){			
					foreach($data as $class => $args) {
						$diff_call = false;
						if($class == 'sfValidatorSchemaCompare') {
							$diff_call = true;
						}
						if(strstr($class,"Validator") == "Validator") {
							$params = isset($args["params"]) ? $args["params"] : array();
							$obj = new $class($context,$params);
							$method = "execute";
						} else {
							$call = array("messages"=>array(),"options"=>array());
							
							foreach($args as $p) {
								if(is_array($p)){
									foreach($p as $key => $param) {
									if(strstr($key,"_error")) {
										$call["messages"][str_replace("_error","",$key)] = $param;
									} else if(!$diff_call) {
										$call["options"][$key] = $param=="false"?false:$param;
									} else {										
										if(!$left_field || !$operator || !$right_field) {
											if($key == 'left_field') {
												$left_field = $param;
											} else if($key == 'operator') {
												$operator = $param;
											  } else if($key == 'right_field') {
													$right_field = $param;
											}
										}	
									}								
								}
							}
							if($diff_call) {
								$call["options"]['throw_global_error'] = true;
							}
						}
					    
							$obj = !$diff_call ? new $class($call["options"],$call["messages"]) : new $class($left_field,$operator,$right_field,$call["options"],$call["messages"]) ;
							$method = "clean";							
						}
					
						$value = !$diff_call ? $actionInstance->getRequestParameter($field) : array($left_field=>$actionInstance->getRequestParameter($field),$right_field=>$actionInstance->getRequestParameter(str_replace($left_field,$right_field,$field)));
						$error = (isset($args["error"])) ? $args["error"] : ""; 
						
						try {
							if($obj->$method($value,$error) === false) {
								$errors[] = array($field,$error);
							}	
						}
						catch(sfValidatorError $e) {
							$errors[] = array($field,$e->getMessage());
						}					
					}	
				}
			}
		
			if(!empty($errors)) {
				$actionInstance->errors = $errors;
				if ($this->isFirstCall())
				{
					$actionInstance->forward('parser', 'errors');		
				}				
			} else {
				
				if($reflection->getMethod("execute".ucfirst($actionInstance->getActionName()))->isFinal()) {

					$post = $context->getRequest()->getParameterHolder()->getAll();
					$url = "/".$post["module"]."/".$post["action"]."?";
					
					foreach($post as $key => $value) {
						if($key == "module" || $key == "action" || $key == "edit" ||  $key == "selections") {
							continue;
						}	
						$url .= $key."=".$value."&";
						
					}
					
					if(!isset($post["step"])) {
						$step = $post["last"];
					} else {
						$step = $post["step"];
					}
					
					if($context->getActionName() == "saveJson") {
						$filterChain->execute();
						return true;
					}
					
					$status = XmlParser::updateSession($step);
					
					if($status === true || $status === 0) {
						$result = array('success' => true, 'message' => false, 'redirect' => $url);	
					} else {
						$result = array('success' => false, 'message' => "A file upload error has been detected: ".$upload_status[$status]."!");
					}
					
					
     				return $actionInstance->renderText(json_encode($result));
					
					//$actionInstance->forward('main', 'success');
				}
			}
			
		}
		
		$filterChain->execute();
		
	}
}
