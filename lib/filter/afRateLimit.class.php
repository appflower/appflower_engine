<?php

/**
 * A set of function to limit the number of failed submits of a form.
 * The attacker is identified by his IP address.
 */
class afRateLimit {
    private static $SIN_TTL_SECONDS = 3600;
    private static $NEEDED_NUM_SINS = 3;

    /**
     * Returns false if captcha was needed and wasn't valid.
     */
    public static function verifyCaptchaIfNeeded($request, $fieldName) {
        if (!self::isCaptchaNeeded($request)) {
            return true;
        }

        $inputValue = $request->getParameter($fieldName);
        $captcha = new Captcha();
        if($inputValue && $captcha->verify($inputValue)) {
            self::clearSins($request);
            return true;
        } else {
            // Disallow to reuse the captcha.
            $captcha->Set('');
            return false;
        }
    }

    public static function isCaptchaNeeded($request) {
        if(!self::checkApcSupport()) {
            return false;
        }

        return self::getNumSins($request) >= self::$NEEDED_NUM_SINS;
    }

    public static function rememberSin($request) {
        if(!self::checkApcSupport()) {
            return;
        }

        $numSins = self::getNumSins($request);
        $key = self::getAttackerId($request);
        apc_store($key, $numSins + 1, self::$SIN_TTL_SECONDS);
    }

    private static function clearSins($request) {
        $key = self::getAttackerId($request);
        apc_delete($key);
    }

    private static function getNumSins($request) {
        $key = self::getAttackerId($request);
        $numSins = apc_fetch($key);
        if($numSins === false) {
            $numSins = 0;
        }
        return $numSins;
    }

    private static function getAttackerId($request) {
        if(!$request) {
            throw new Exception('invalid request');
        }
        return 'afRateLimit_'.$request->getRemoteAddress();
    }

    private static function checkApcSupport() {
        if (function_exists('apc_fetch')) {
            return true;
        }

        error_log('Install APC to support attack rate limiting!');
        return false;
    }
}
