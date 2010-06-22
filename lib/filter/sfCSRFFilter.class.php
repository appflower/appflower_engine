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
		$secret = afAuthenticDatamaker::getSiteSecret();

		$request = $this->getContext()->getRequest();
		$moduleName = $this->context->getActionStack()->getLastEntry()->getModuleName();
		$actionName = $this->context->getActionStack()->getLastEntry()->getActionName();
			
		// check only if request method is POST
		if (sfRequest::POST === $request->getMethod())
		{
			if(self::isPossibleCrossSiteSessionRiding($request))
			{
				$requestToken = $request->getParameter('_csrf_token');
								
				// error if no token or if token is not valid
				if (!in_array($moduleName,sfConfig::get('app_csrf_token_deactivatedModules', array()))&&(!$requestToken || md5($secret.session_id()) !== $requestToken))
				{
					throw new sfException('CSRF attack detected.');
				}
			}
		}
		else
		{
			if (strpos($actionName, 'delete') === 0) {
				throw new sfException('Only POST is allowed for write-making actions.');
			}
		}

		// provide the token to anyone interested
		$request->setAttribute('_csrf_token', md5($secret.session_id()));

		// execute next filter
		$filterChain->execute();
	}

	/**
	 * Returns true if the request could be initiated
	 * from another site and still using the user cookies.
	 */
	private static function isPossibleCrossSiteSessionRiding($request) {
		// Ajax calls are safe.
		// The X_REQUESTED_WITH header cannot be set without doing an Ajax call.
		// And Ajax calls cannot be cross-site.
		if($request->isXmlHttpRequest()) {
			return false;
		}

		// REST calls with a valid API key are OK.
		$apikey = $request->getParameter('af_apikey');
		if($apikey) {
			if (afApikeySecurityFilter::isCurrentUserKey($apikey)) {
				return false;
			}
		}

		return true;
	}
}
