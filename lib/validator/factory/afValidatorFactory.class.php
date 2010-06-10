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

        //TODO: support any validator
    }
}
