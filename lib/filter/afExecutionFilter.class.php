<?php

/**
 * An execution filter that detects and renders
 * the action XML config automatically.
 */
class afExecutionFilter extends sfExecutionFilter {

    protected function executeAction($actionInstance) {   	
    	if(self::isExportRequest($actionInstance)) {
            $actionInstance->isPageComponent = true;
        } elseif(self::isFirstPageRequest($actionInstance)) {
            $request = $actionInstance->getRequest();
            if($request->getAttribute('af_first_page_request') !== true) {
                $request->setAttribute('af_first_page_request', true);
                $actionInstance->forward('appFlower', 'firstPage');
            }
        }
        
        $actionInstance->preExecute();
        $viewName = $actionInstance->execute($this->context->getRequest());
        $actionInstance->postExecute();
        
        $viewName = is_null($viewName) ? sfView::SUCCESS : $viewName;
        $viewName = self::interpretView($actionInstance, $viewName);
        return is_null($viewName) ? sfView::SUCCESS : $viewName;
    }

    /**
     * Recognizes if the viewName is a JSON array
     * or an XML config view.
     */
    private static function interpretView($actionInstance, $viewName) {
        if(is_array($viewName)) {
            return $actionInstance->renderText(json_encode($viewName));
        }

        if($viewName !== sfView::SUCCESS) {
            return $viewName;
        }

        if(self::isExportRequest($actionInstance)) {
            return afRenderingRouter::render(
                $actionInstance->getRequest(),
                $actionInstance->getModuleName(),
                $actionInstance->getActionName(),
                $actionInstance->getVarHolder()->getAll());
        }

        return self::layoutExtIfNeeded($actionInstance);
    }

    private static function layoutExtIfNeeded($actionInstance) {
        $viewName = sfView::SUCCESS;
        if(XmlParser::isLayoutStarted()) {
            return $viewName;
        }

        if(self::isAppFlowerAction($actionInstance)) {
        	$viewName = XmlParser::layoutExt($actionInstance);
        }

        return $viewName;
    }

    /**
     * Returns true for actions with XML config.
     */
    private static function isAppFlowerAction($actionInstance) {
        $afCU = new afConfigUtils($actionInstance->getModuleName());
        return $afCU->getConfigFilePath($actionInstance->getActionName().".xml");
    }

    /**
     * Returns true if the given action could be loaded with widget_load.
     * Wizards do not support that. They want to be displayed without menus.
     */
    private static function isWidgetAction($actionInstance) {
        $module = $actionInstance->getModuleName();
        $action = $actionInstance->getActionName();
        $doc = afConfigUtils::getOptionalDoc($module, $action);
        if (!$doc) {
            return false;
        }
        $view = afDomAccess::wrap($doc, 'view',
            new afVarScope($actionInstance->getVarHolder()->getAll()));
        return $view->get('@type') !== 'wizard';
    }

    private static function isExportRequest($actionInstance) {
        $format = $actionInstance->getRequestParameter('af_format');
        return !!$format;
    }

    /**
     * All AppFlower actions should be rendered by AJAX widget load.
     * Otherwise, it is assumed that it is a request for
     * the first page with menu and toolbar.
     *
     * Bookmarks /#/module/action use the first page.
     * The browser isn't sending the fragment to the server.
     * So the first page is rendered instead.
     */
    private static function isFirstPageRequest($actionInstance) {
        return ($actionInstance->getRequest()->isMethod('GET') &&
            !$actionInstance->getRequest()->isXmlHttpRequest() &&
            !afExtjsAjaxLoadWidgets::isWidgetRequest() &&
            self::isWidgetAction($actionInstance));
    }
}