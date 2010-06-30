<?php

class afAuthenticDatamaker {
    // A form is considered outdated after 2 days.
    // We don't want to accept form submissions with old validators.
    private static $MAX_AGE_SECONDS = 172800;

    const MSG_SEPARATOR = '~';

    /**
     * Signs the data to prevent their alternation.
     * The signed result will be valid only for a limited number of hours.
     */
    public static function encode($data, $timestamp=null) {
        if(!$timestamp) {
            $timestamp = time();
        }

        $message = json_encode(array($timestamp, $data));
        return self::plainEncode($message);
    }

    /**
     * Signs the message with site secret and an extra key.
     * Changing the site secret or the extra key will invalidate
     * the signed message.
     */
    public static function plainEncode($message, $extraKey='') {
        $key = self::getSiteSecret();
        $hmac = self::hmacHash($message, $key.$extraKey);
        return $hmac.self::MSG_SEPARATOR.$message;
    }

    /**
     * Returns a decoded valid message or null.
     */
    public static function plainDecode($input, $extraKey='') {
        $parts = explode(self::MSG_SEPARATOR, $input, 2);
        if(count($parts) !== 2) {
            return null;
        }

        $key = self::getSiteSecret();
        list($hmac, $message) = $parts;
        $expectedHmac = self::hmacHash($message, $key.$extraKey);
        if($hmac !== $expectedHmac) {
            return null;
        }

        return $message;
    }

    /**
     * Returns decoded valid data or null.
     */
    public static function decode($input) {
        $message = self::plainDecode($input);
        if($message === null) {
            return null;
        }

        list($timestamp, $data) = json_decode($message, true);
        if($timestamp + self::$MAX_AGE_SECONDS < time()) {
            // The message is expired.
            return null;
        }

        return $data;
    }

    /**
     * Returns a secret private key.
     * It throws an exception if the secret key isn't set for this site.
     */
    public static function getSiteSecret() {
        $secret = sfConfig::get('app_appFlower_siteSecret');
        if (!$secret) {
            throw new sfConfigurationException('Configure app_appFlower_siteSecret.');
        }
        return $secret;
    }

    /**
     * Returns a binary hmac wrapped in URL-safe base64.
     */
    private static function hmacHash($data, $key) {
        $binary = self::hmacSha1($data, $key, true);
        $base64 = base64_encode($binary);
        $base64 = rtrim($base64, '=');
        return strtr($base64, '+/', '-_');
    }

    /**
     * A hmac function for PHP installations without hash_hmac().
     */
    private static function hmacSha1($data, $key, $raw_output=false) {
        $algo = 'sha1';
        $pack = 'H'.strlen($algo('test'));
        $size = 64;
        $opad = str_repeat(chr(0x5C), $size);
        $ipad = str_repeat(chr(0x36), $size);

        if (strlen($key) > $size) {
            $key = str_pad(pack($pack, $algo($key)), $size, chr(0x00));
        } else {
            $key = str_pad($key, $size, chr(0x00));
        }

        for ($i = 0; $i < strlen($key) - 1; $i++) {
            $opad[$i] = $opad[$i] ^ $key[$i];
            $ipad[$i] = $ipad[$i] ^ $key[$i];
        }

        return $algo($opad.pack($pack, $algo($ipad.$data)), $raw_output);
    }
}
