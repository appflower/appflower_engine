<?php

class XmlParserValidationFilter extends sfExecutionFilter
{

	public function execute ($filterChain)
	{
		$context = $this->context;
		if($this->isFirstCall() && $context->getRequest()->getMethod() == sfRequest::POST) {
			$actionInstance = $this->context->getActionStack()->getLastEntry()->getActionInstance();

			$validators = self::getValidators($context);
			if($validators === null) {
				$edit = $actionInstance->getRequestParameter('edit');
				$apikey = $actionInstance->getRequestParameter('af_apikey');
				if(!is_array($edit) && !$apikey) {
					// Normal AJAX POST requests and plain forms don't have
					// validators from the XML config.
					$validators = array();
				} else {
					self::renderErrors(array(), 'The form is outdated. Please, refresh it.');
				}
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
			}

			$this->checkFinalWizardStep();
			/* Disabled for now. LogInspect NullFilter needs to be
			 * able to accept non-array edit[] values first.
			 *
			self::removeIterationNumber(
				$this->context->getRequest()->getParameterHolder());
			 */
		}

		return $filterChain->execute();
	}

	/**
	 * Returns the right validators for this form or null.
	 */
	private static function getValidators($context) {
		$request = $context->getRequest();
		$encoded = $request->getParameter('af_formcfg');
		$formcfg = afAuthenticDatamaker::decode($encoded);
		if($formcfg === null) {
			return null;
		}

		$uri = $context->getRequest()->getUri();
		if(UrlUtil::getPathPart($formcfg['url']) !== UrlUtil::getPathPart($uri)) {
			// The given formcfg is for a different form.
			return null;
		}

		return $formcfg['validators'];
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

    /**
     * Copies values from edit[iterationNumber][] to edit[].
     * It keeps edit[iterationNumber][] to be also present
     * for backward compatibility.
     */
    private static function removeIterationNumber($paramHolder) {
        $params =& $paramHolder->getAll();
        if (!isset($params['edit']) || !is_array($params['edit'])) {
            return;
        }

        $edit =& $params['edit'];
        if(count($edit) === 1) {
            $values = array_values($edit);
            $submit = $values[0];
            if(is_array($submit)) {
                foreach($submit as $key => $value) {
                    $edit[$key] = $value;
                }
            }
        }
    }
}
