<?php

/**
 * Utils to access an edit view configuration.
 */
class afEditView {
    public static function getValidators($fields) {
        $validators = array();
        foreach($fields as $field) {
            $fieldName = sprintf('edit[%s]', $field->get('@name'));
            $fieldValidators = $field->wrapAll('validator');
            foreach($fieldValidators as $fieldValidator) {
                $validatorName = $fieldValidator->get('@name');
                $params = self::getParams($fieldValidator);
                $cfg = array();
                if ($params) {
                    $cfg['params'] = $params;
                }
                $validators[$fieldName][$validatorName] = $cfg;
            }
        }
        return $validators;
    }

    /**
     * Returns all <i:param name="KEY">VALUE</i:param> key-value pairs
     * in an associative array.
     */
    public static function getParams($node, $path='param') {
        $result = array();
        $params = $node->wrapAll($path);
        foreach($params as $param) {
            $result[$param->get('@name')] = $param->get('');
        }
        return $result;
    }
}

