<?php
class ImmExtjsWidgets{	
	const DEFAULT_RELOAD_DELAY = 60000;
	const DEFAULT_RELOAD_STARTED = 'false';
	const DEFAULT_RELOAD_VISIBLE = 'true';

	/**
	 * Generates a JS code for a widget reload plugin.
	 */
	public static function getReloadPlugin($parse) {
		$widgetUrl = '/'.self::getWidgetUrl($parse);

		$started = ArrayUtil::get($parse, 'params', 'reload_started', self::DEFAULT_RELOAD_STARTED) === 'true';
		$visible = ArrayUtil::get($parse, 'params', 'reload_visible', self::DEFAULT_RELOAD_VISIBLE) === 'true';
		$delayMillis = ArrayUtil::get($parse, 'params', 'reload_delay', self::DEFAULT_RELOAD_DELAY);
		if(isset($parse['refresh']) && $parse['refresh'] > 0) {
			$started = true;
			$delayMillis = $parse['refresh'] * 1000;
		}

		$setting = self::getSetting($widgetUrl);
		$delayMillis = ArrayUtil::get($setting, 'reload', 'interval', $delayMillis / 1000) * 1000;  // The interval is in seconds.
		$started = ArrayUtil::get($setting, 'reload', 'started', $started);
		$visible = ArrayUtil::get($setting, 'reload', 'active', $visible);

		return self::generateReloadPlugin($delayMillis, $widgetUrl,
			$started, $visible);
	}

	/**
	 * Returns the "module/action" url for the current widget.
	 */
	public static function getWidgetUrl($parse) {
		$action = ArrayUtil::get($parse, 'component_name', null);
		if ($action) {
			$module = $parse['module'];
		} else {
			$context = sfContext::getInstance();
			$action = $context->getActionName();
			$module = $context->getModuleName();
		}
		return sprintf('%s/%s', $module, $action);
	}
	/**
	 * Returns the current request parameters, that are necessary for reloading conditional widgets
	 */
	private static function getNormalRequests(){
		$rps = sfContext::getInstance()->getRequest()->getRequestParameters();
		$myParams = array();
		foreach($rps as $key=>$val){
			if(is_string($val)){
				$myParams[$key] = $val; 
			}
		}
		return $myParams;		
	}
	/**
	 * Generates JS code for the reload plugin.
	 * The htmlUrl is needed only for non-grid widgets.
	 */
	private static function generateReloadPlugin($delayMillis, $htmlUrl, $startInitial, $reloadToolVisible) {
		ImmExtjs::getInstance()->setAddons(array('js' => array(ImmExtjs::getInstance()->getExamplesDir().'plugins/Ext.ux.plugins.RealtimeWidgetUpdate.js')));		
		return 'new Ext.ux.plugins.RealtimeWidgetUpdate({requestParams:'.json_encode(self::getNormalRequests()).',rate:'.$delayMillis.',url:'.json_encode($htmlUrl).',startInitial:'.($startInitial?"true":"false").',reloadToolVisible:'.($reloadToolVisible?"true":"false").'})';	
	}

	private static function getSetting($widgetUrl) {
		$name = $widgetUrl;
		if($name === false) {
			return array();
		}

		if(sfContext::getInstance()->getUser() && sfContext::getInstance()->getUser()->getGuardUser()) {
			$userId = sfContext::getInstance()->getUser()->getGuardUser()->getId();
		} else {
			return array();
		}

		$c = new Criteria();
		$c->add(afWidgetSettingPeer::USER,$userId);
		$c->add(afWidgetSettingPeer::NAME,$name);
		$obj = afWidgetSettingPeer::doSelectOne($c);
		if($obj === null){			
			return array();
		}
		$setting = json_decode($obj->getSetting(),true);
		return $setting;
	}
}
