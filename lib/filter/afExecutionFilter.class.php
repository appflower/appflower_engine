<?php

/**
 * An execution filter that detects and renders
 * the action XML config automatically.
 */
class afExecutionFilter extends sfExecutionFilter {

    protected function executeAction($actionInstance) {
        $actionInstance->preExecute();
        $viewName = $actionInstance->execute($this->context->getRequest());
        $actionInstance->postExecute();

        $viewName = is_null($viewName) ? sfView::SUCCESS : $viewName;
        $viewName = self::interpretView($actionInstance, $viewName);
        return is_null($viewName) ? sfView::SUCCESS : $viewName;
    }

    /**
     * Uses XmlParser to render the view if the action has its XML config.
     */
    private static function interpretView($actionInstance, $viewName) {
        if($viewName !== sfView::SUCCESS) {
            return $viewName;
        }

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
}

