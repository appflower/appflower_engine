<?php

/**
 * A factory for input field validators.
 */
class afValidatorFactory {
    /**
     * Returns a sfBaseValidator instance.
     */
    public static function createValidator($className, $params) {
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
    public static function prepareValue($field, sfValidatorBase $validator, $requestParams) {
        if($validator instanceof sfValidatorSchemaCompare) {
            $values = array();
            $prefix = preg_replace('/\[[^\]]+\]$/', '', $field);
            self::fillValue($values, $prefix, 'left_field', $validator,
                $requestParams);
            self::fillValue($values, $prefix, 'right_field', $validator,
                $requestParams);
            return $values;
        } else {
            return sfToolkit13::getArrayValueForPath($requestParams,$field);
        }
    }

    private static function fillValue(&$values, $prefix, $option, sfValidatorSchemaCompare $validator, $requestParams) {
        $inputName = $validator->getOption($option);
        $fieldName = sprintf('%s[%s]', $prefix, $inputName);
        $value = sfToolkit13::getArrayValueForPath($requestParams,$fieldName);
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
