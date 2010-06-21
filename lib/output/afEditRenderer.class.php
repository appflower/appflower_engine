<?php

class afEditRenderer {
    public static function renderEdit($request, $module, $action, afDomAccess $view) {
        $fields = $view->wrapAll('fields/field');
        $submitUrl = self::getSubmitUrl($module, $action, $view);
        $validators = afEditView::getValidators($fields);

        $result = array();
        $result['af_formcfg'] = self::buildFormcfg($submitUrl, $validators);
        return afOutput::renderText(json_encode($result));
    }

    public static function buildFormcfg($submitUrl, $validators) {
        $formcfg = array('url'=>$submitUrl, 'validators'=>$validators);
        return afAuthenticDatamaker::encode($formcfg);
    }

    private static function getSubmitUrl($module, $action, $view) {
        $url = $view->get('fields@url');
        if (!$url) {
            $url = sfContext::getInstance()->getController()->genUrl(
                "$module/$action");
        }
        return UrlUtil::abs($url);
    }
}

