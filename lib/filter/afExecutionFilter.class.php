<?php

/**
 * An execution filter that detects and renders
 * the action XML config automatically.
 */
class afExecutionFilter extends sfExecutionFilter {

    protected function executeAction($actionInstance) {
        if(self::isListjsonRequest($actionInstance)) {
            $actionInstance->isPageComponent = true;
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

        if(self::isListjsonRequest($actionInstance)) {
            return afListRenderer::renderList(
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

        $configPath = afConfigUtils::getPath($actionInstance->getModuleName(),
            $actionInstance->getActionName());
        if(file_exists($configPath)) {
            $viewName = XmlParser::layoutExt($actionInstance);
        }

        return $viewName;
    }

    private static function isListjsonRequest($actionInstance) {
        $format = $actionInstance->getRequestParameter('af_format');
        return !!$format;
    }

    public static function getListjsonUrl($actionUrl) {
        return UrlUtil::abs($actionUrl.'?af_format=json');
    }
}

