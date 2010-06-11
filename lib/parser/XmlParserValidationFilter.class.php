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
		$errorMessage = null;

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
			$index = substr(trim($actionInstance->getRequestParameter('form_index')),4);
			$keys = array_keys($session);
			$post_index = array();
			$pattern = '/edit\['.$index.'\].*/';
			foreach($keys as $value){
				if(preg_match($pattern,$value)){
					$post_index[] = $value;
				}
			}
			foreach($post_index as $field){
				$tmp_field = $field;
				if(!$context->getRequest()->getParameterHolder()->has($field)) {
					$tmp_field = substr($field,0,-1)."_value]";
					if(!$context->getRequest()->getParameterHolder()->has($tmp_field)){
						$errors[] = array($field,'This field is missing');
						$errorMessage = 'Some form field(s) is missing';
						break;
					}
				}

				foreach($session[$field] as $class => $args) {
					$params = ArrayUtil::get($args, 'params', array());
					$validator = afValidatorFactory::createValidator(
						$class, $params);

					$value = afValidatorFactory::prepareValue($tmp_field,
						$validator, $context->getRequest()->getParameterHolder());
					try {
						$validator->clean($value);
					}
					catch(sfValidatorError $e) {
						$errors[] = array($field,$e->getMessage());
					}
				}
			}
			if(!empty($errors)) {
				self::renderErrors($errors, $errorMessage);
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
				}
			}

		}

		$filterChain->execute();

	}

    /**
     * Renders all validation errors in a JSON response.
     */
    private static function renderErrors($errors, $message=null) {
        if(!$message) {
            $message  = 'Validation error occured!';
        }

        $result = array('success' => false, 'message' => $message);
        foreach($errors as $error) {
            $result['errors'][$error[0]] = $error[1];
        }

        echo json_encode($result);
        exit;
    }
}
