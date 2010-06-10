<?php

/**
 * A factory for input field validators.
 */
class afValidatorFactory {
    /**
     * Returns a sfBaseValidator instance.
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
