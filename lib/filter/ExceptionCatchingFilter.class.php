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
			} catch (Exception $e) {
				$response = $this->context->getResponse();
				$params = array('success' => false,
					'message' => 'Unable to do the action.<br/>Reason: '.$e->getMessage());
				$response->setContent(json_encode($params));
			}
		} else {
			$filterChain->execute();
		}
	}
}
