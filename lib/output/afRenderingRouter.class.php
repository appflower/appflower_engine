<?php

class afRenderingRouter {
    public static function render($request, $module, $action, $actionVars) {
        $doc = afConfigUtils::getDoc($module, $action);
        $view = afDomAccess::wrap($doc, 'view', new afVarScope($actionVars));

        $viewType = $view->get('@type');
        if ($viewType === 'list') {
            return afListRenderer::renderList($request, $module, $action,
                $view);
        } elseif ($viewType === 'edit') {
            return afEditRenderer::renderEdit($request, $module, $action,
                $view);
        } else {
            throw new XmlParserException(
                'Unsupported view type for af_format rendering: '.$viewType);
        }
    }
}

