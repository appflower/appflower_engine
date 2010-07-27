<?php

class XmlParserValidationFilter extends sfExecutionFilter
{

	public function execute ($filterChain)
	{
		$context = $this->context;
		if($this->isFirstCall() && $context->getRequest()->getMethod() == sfRequest::POST) {
			$actionInstance = $this->context->getActionStack()->getLastEntry()->getActionInstance();

			$formcfg = self::getFormConfig($context);
			if($formcfg === null) {
				$edit = $actionInstance->getRequestParameter('edit');
				$apikey = $actionInstance->getRequestParameter('af_apikey');
				if(!is_array($edit) && !$apikey) {
					// Normal AJAX POST requests and plain forms don't have
					// validators from the XML config.
					$validators = array();
					$formcfg = array();
				} else {
					self::renderErrors(array(), 'The form is outdated. Please, refresh it.');
				}
			} else {
				$validators = $formcfg['validators'];
			}

			if(ArrayUtil::get($formcfg, 'wizard', false)) {
				afWizard::checkStepOrRenderError();
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

			if(ArrayUtil::get($formcfg, 'wizard', false)) {
				$this->updateWizardState();
			}
			self::removeIterationNumber(
				$this->context->getRequest()->getParameterHolder());
		}

		return $filterChain->execute();
	}

	/**
	 * Returns a valid af_formcfg for this form or null.
	 */
	private static function getFormConfig($context) {
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

		return $formcfg;
	}

	private function updateWizardState() {
		$context = $this->context;
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

		afWizard::takeStep();

		$post = $context->getRequest()->getParameterHolder()->getAll();
		$url = $context->getRequest()->getUri();

		if(!isset($post["step"])) {
			$step = $post["last"];
		} else {
			$step = $post["step"];
		}

		$status = XmlParser::updateSession($step);

		if($status === true || $status === 0) {
			$result = array('success' => true, 'message' => false, 'redirect' => $url, 'load'=>'page');
		} else {
			$result = array('success' => false, 'message' => "A file upload error has been detected: ".$upload_status[$status]."!");
		}


		echo json_encode($result);
		exit;
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

        self::supportOldEdit0($paramHolder);
    }

    /**
     * Adds the edit[0][] array if it is missing.
     * Some old actions could be expecting it instead of edit[].
     */
    private static function supportOldEdit0($paramHolder) {
        $params =& $paramHolder->getAll();
        if (!isset($params['edit']) || !is_array($params['edit'])) {
            return;
        }

        if(isset($params['edit'][0])) {
            return;
        }

        $copy = $params['edit'];
        $params['edit'][0] = $copy;
    }
}
