<?php

class afEditJsonRenderer {
    public static function renderEdit($request, $module, $action, afDomAccess $view) {
        $fields = $view->wrapAll('fields/field');
        $submitUrl = self::getSubmitUrl($module, $action, $view);
        $validators = afEditView::getValidators($fields);
        $fileTypes = afEditView::getFileTypes($fields);

        $result = array();
        $result['success'] = true;
        $result['af_submitUrl'] = $request->getUriPrefix().$submitUrl;
        $instance = afEditShowRenderer::fetchDataInstance($view);
        foreach(self::getFieldValues($instance, $fields) as $name => $value) {
            $result[sprintf('edit[%s]', $name)] = $value;
        }
        $result['af_formcfg'] = self::buildFormcfg($submitUrl, $validators, $fileTypes);
        return afOutput::renderText(json_encode($result));
    }

    public static function buildFormcfg($submitUrl, $validators, $fileTypes, $wizard=false) {
        $formcfg = array('url'=>$submitUrl, 'validators'=>$validators, 'fileTypes'=>$fileTypes);
        if ($wizard) {
            $formcfg['wizard'] = true;
        }
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
            if (StringUtil::endsWith(strtolower($field->get('@type')), 'combo')) {
                $values[$field->get('@name').'_value'] = $field->get('@selected', null);
            } else {
                $values[$field->get('@name')] = afEditView::getFieldValue($field, $instance);
            }
        }
        return $values;
    }
}

