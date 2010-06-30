<?php

class afEditJsonRenderer {
    public static function renderEdit($request, $module, $action, afDomAccess $view) {
        $fields = $view->wrapAll('fields/field');
        $submitUrl = self::getSubmitUrl($module, $action, $view);
        $validators = afEditView::getValidators($fields);

        $result = array();
        $result['af_submitUrl'] = $request->getUriPrefix().$submitUrl;
        $result['af_formcfg'] = self::buildFormcfg($submitUrl, $validators);
        $instance = afEditShowRenderer::fetchDataInstance($view);
        foreach(self::getFieldValues($instance, $fields) as $name => $value) {
            $result[sprintf('edit[%s]', $name)] = $value;
        }
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

    private static function getFieldValues($instance, $fields) {
        $values = array();
        foreach($fields as $field) {
            $values[$field->get('@name')] = afEditView::getFieldValue($field, $instance);
        }
        return $values;
    }
}

