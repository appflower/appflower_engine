<?php

/**
 * A filter that catches all exceptions in POST requests
 * and converts them to a JSON error response.
 */
class ExceptionCatchingFilter extends sfFilter
{
	public function execute ($filterChain)
	{
            
		$request = $this->context->getRequest();
		if($request->isXmlHttpRequest() && $this->isFirstCall())
		{
                    
			try {
				$filterChain->execute();
			} catch (sfException $e) {
				throw $e;
			} catch (PropelException $e) {
                            $cause = $e->getCause();
                            $debugMode = sfConfig::get('sf_debug');
                            if ($cause->getCode() == 23000) {
                                if (!$debugMode) {
                                    $message = 'You are probably trying to delete or update a record that have other related records.';
                                }
                            }
                            if (!isset($message)) {
                                $message = $e->getMessage();
                            }
                            $this->injectErrorIntoResponse($message);
			} catch (Exception $e) {
                            $this->injectErrorIntoResponse($e->getMessage());
			}
		} else {
			$filterChain->execute();
		}
	}

        private function injectErrorIntoResponse($errorMessage)
        {
            $response = $this->context->getResponse();
            $params = array('success' => false,
                    'message' => 'Unable to do the action.<br/>Reason: '.$errorMessage);
            $response->setContent(json_encode($params));
        }
}
