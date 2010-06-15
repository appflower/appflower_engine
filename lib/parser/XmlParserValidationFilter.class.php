<?php

class XmlParserValidationFilter extends sfExecutionFilter
{

	public function execute ($filterChain)
	{
		$context = $this->context;
		if($this->isFirstCall() && $context->getRequest()->getMethod() == sfRequest::POST) {
			$actionInstance = $this->context->getActionStack()->getLastEntry()->getActionInstance();

			$validators = $this->getValidators($context);
			if($validators === null) {
				self::renderErrors(array(), 'The form is outdated. Please, refresh it.');
			}

			$errors = array();
			$errorMessage = null;

			foreach($validators as $field => $fieldValidators){
				$tmp_field = $field;
				if(!$context->getRequest()->getParameterHolder()->has($field)) {
					$tmp_field = substr($field,0,-1)."_value]";
				}

				foreach($fieldValidators as $class => $args) {
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
				$this->checkFinalWizardStep();
			}
		}

		return $filterChain->execute();
	}

	/**
	 * Returns the right validators for this form or null.
	 */
	private function getValidators($context) {
		$request = $context->getRequest();
		$secret = $request->getAttribute('_csrf_secret');
		$encoded = $request->getParameter('af_formcfg');
		$formcfg = afAuthenticDatamaker::decode($encoded, $secret);
		if($formcfg === null) {
			return null;
		}

		$uri = $context->getRequest()->getUri();
		if(self::stripHost($formcfg['url']) !== self::stripHost($uri)) {
			// The given formcfg is for a different form.
			return null;
		}

		return $formcfg['validators'];
	}

	private function stripHost($url) {
		return preg_replace('@^https?://[^/]*@', '', $url);
	}

	private function checkFinalWizardStep() {
		$actionInstance = $this->context->getActionStack()->getLastEntry()->getActionInstance();
		$context = $this->context;

		$reflection = new ReflectionClass(get_class($actionInstance));
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
				return;
			}

			$status = XmlParser::updateSession($step);

			if($status === true || $status === 0) {
				$result = array('success' => true, 'message' => false, 'redirect' => $url);
			} else {
				$result = array('success' => false, 'message' => "A file upload error has been detected: ".$upload_status[$status]."!");
			}


			echo json_encode($result);
			exit;
		}
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
