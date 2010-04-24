<?php

/**
 * A cache of values.
 * The values are shared between PHP processes.
 * Any other request could read them. Don't store private data here.
 */
class PublicCache {
    /**
     * Caches the callback result.
     */
    public static function cache($callback, $params) {
        $key = serialize($callback).serialize($params);
        return self::cacheNamed($key, $callback, $params);
    }

    /**
     * Caches the callback result under a given unique key.
     */
    public static function cacheNamed($key, $callback, $params) {
        if (!function_exists('apc_fetch')) {
            return call_user_func_array($callback, $params);
        }

        $result = apc_fetch($key);
        if ($result !== false) {
            return $result;
        }

        //error_log("cache miss: $key");
        $result = call_user_func_array($callback, $params);
        apc_store($key, $result);
        return $result;
    }
}
