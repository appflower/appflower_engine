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

    /**
     * Returns the initial value for form field.
     */
    public static function getFieldValue(afDomAccess $field, $object) {
        $value = $field->get("@value");
        $selected = $field->get("@selected");

        if(!$value) {
            if($field->get("@type") == "checkbox" || $field->get("@type") == "radio") {
                $value = ($field->get("@checked")) ? "yes" : "no";
            } else {
                $source = $field->wrapAll("value");
                if(!empty($source)) {
                    $orm = $source[0]->get("source@name");
                    if($orm) {
                        $method = "get".sfInflector::camelize($field->get("@name"));
                        if(method_exists($object,$method)) {
                            $value = $object->$method();    
                        }
                    } else {
                        $class = $source[0]->get("class");
                        $method = $source[0]->get("method@name");
                        $tmp = $source[0]->wrapAll("method/param");
                        $params = array();
                        
                        foreach($tmp as $t) {
                            $params[] = $t->get("");
                        }
                        
                        $result = call_user_func_array(array($class,$method),$params);
                        
                        if($field->get("@type") == "combo" || $field->get("@type") == "extendedCombo" || $field->get("@type") == "multicombo") {
                            if(isset($result[$selected])) {
                                $value = $result[$selected];    
                            }
                        } else if($field->get("@type") == "doublemulticombo" || $field->get("@type") == "doubletree") {
                            foreach($result[1] as $r) {
                                $value .= $r.",";
                            }
                            $value = trim($value,",");
                        }
                    }
                }
            }
        }

        return $value;
    }
}

