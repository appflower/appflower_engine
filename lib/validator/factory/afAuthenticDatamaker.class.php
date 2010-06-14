<?php

class afAuthenticDatamaker {
    // A form is considered outdated after 2 days.
    // We don't want to accept form submissions with old validators.
    private static $MAX_AGE_SECONDS = 172800;

    /**
     * Signs the data to prevent their alternation.
     */
    public static function encode($data, $key, $timestamp=null) {
        if(!$timestamp) {
            $timestamp = time();
        }

        $message = json_encode(array($timestamp, $data));
        $hmac = self::hmacSha1($message, $key);
        return $hmac.','.$message;
    }

    /**
     * Returns decoded valid data or null.
     */
    public static function decode($input, $key) {
        $parts = explode(',', $input, 2);
        if(count($parts) !== 2) {
            return null;
        }

        list($hmac, $message) = $parts;
        $expectedHmac = self::hmacSha1($message, $key);
        if($hmac !== $expectedHmac) {
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

        $output = $algo($opad.pack($pack, $algo($ipad.$data)));

        return ($raw_output) ? pack($pack, $output) : $output;
    }
}
