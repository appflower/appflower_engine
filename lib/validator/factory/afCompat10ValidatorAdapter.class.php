<?php

/**
 * An adapter to present a sfValidator as a sfValidatorBase subclass.
 */
class afCompat10ValidatorAdapter extends sfValidatorBase {
    private $validator;

    public function __construct(sfValidator $validator) {
        parent::__construct();
        $this->validator = $validator;
    }

    protected function isEmpty($value) {
        // The handling of required values is left
        // on the given sfValidator.
        return false;
    }

    protected function doClean($value) {
        $result = $this->validator->execute($value, $error);
        if($result === false) {
            throw new sfValidatorError($this, $error);
        }

        return $value;
    }
}
