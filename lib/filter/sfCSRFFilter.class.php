<?php

class sfCSRFFilter extends sfFilter
{
	/**
	 * Executes this filter.
	 *
	 * @param sfFilterChain A sfFilterChain instance
	 */
	public function execute($filterChain)
	{
		if (!$secret = $this->getParameter('secret'))
		{
			throw new sfConfigurationException('You must provide a "secret" option for the sfCSRFPlugin filter.');
		}

		$request = $this->getContext()->getRequest();
		// check only if request method is POST
		if (sfRequest::POST === $request->getMethod())
		{
			// Ajax calls are safe.
			// The X_REQUESTED_WITH header cannot be set without doing an Ajax call.
			// And Ajax calls cannot be cross-site.
			if(!$request->isXmlHttpRequest())
			{
				$requestToken = $request->getParameter('_csrf_token');

				// error if no token or if token is not valid
				if (!$requestToken || md5($secret.session_id()) != $requestToken)
				{
					throw new sfException('CSRF attack detected.');
				}
			}
		}
		else
		{
			$actionName = $this->context->getActionStack()->getLastEntry()->getActionName();
			if (strpos($actionName, 'delete') === 0) {
				throw new sfException('Only POST is allowed for write-making actions.');
			}
		}

		// provide the token to anyone interested
		$request->setAttribute('_csrf_token', md5($secret.session_id()));

		// execute next filter
		$filterChain->execute();
	}
}
