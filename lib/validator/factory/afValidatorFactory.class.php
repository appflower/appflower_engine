<?php

/**
 * A factory for input field validators.
 */
class afValidatorFactory {
    /**
     * Returns a sfValidatorBase instance.
     */
    public static function createValidator($className, $params) {
        if(is_subclass_of($className, 'sfValidator')) {
            $validator = new $className(sfContext::getInstance(), $params);
            return new afCompat10ValidatorAdapter($validator);
        }

        list($options, $messages) = self::collectOptions($params);
        if($className === 'sfValidatorSchemaCompare') {
            $options['throw_global_error'] = true;
            return new $className(null, null, null, $options, $messages);
        } else {
            return new $className($options, $messages);
        }
    }

    /**
     * Returns a value to be validated by the given validator.
     */
    public static function prepareValue($field, sfValidatorBase $validator, Serializable $requestParams) {
        if($validator instanceof sfValidatorSchemaCompare) {
            $values = array();
            $prefix = preg_replace('/\[[^\]]+\]$/', '', $field);
            self::fillValue($values, $prefix, 'left_field', $validator,
                $requestParams);
            self::fillValue($values, $prefix, 'right_field', $validator,
                $requestParams);
            return $values;
        } else {
            return $requestParams->get($field);
        }
    }

    private static function fillValue(&$values, $prefix, $option, sfValidatorSchemaCompare $validator, sfParameterHolder $requestParams) {
        $inputName = $validator->getOption($option);
        $fieldName = sprintf('%s[%s]', $prefix, $inputName);
        $value = $requestParams->get($fieldName);
        $values[$inputName] = $value;
    }

    private static function collectOptions($params) {
        $options = array();
        $messages = array();
        foreach($params as $key => $param) {
            if(StringUtil::endsWith($key, '_error')) {
                $messages[preg_replace('/_error$/','',$key)] = $param;
            } else {
                $options[$key] = ($param === 'false')?false:$param;
            }
        }

        return array($options, $messages);
    }

}
