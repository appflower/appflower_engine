<?php

class Console {
    private static $startedAt = null;
    private static $last;
    private static $profilingEnabled = false;

    /**
     * Logs the given arguments to stderr.
     * Usage: debug($var1, $anArray, ...)
     */
    public static function debug() {
        $message = 'DEBUG:';
        foreach (func_get_args() as $arg) {
            $message .= ' '.var_export($arg, true);
        }
        error_log($message);
    }

    /**
     * Logs the number of milliseconds till this named point.
     */
    public static function profile($point) {
        if(!self::$profilingEnabled || ini_get('display_errors') !== 'on') {
            return;
        }

        $now = microtime(true);
        if(self::$startedAt === null) {
            self::$startedAt = $now;
            self::$last = $now;
        }

        $totalMillis = ($now - self::$startedAt) * 1000;
        $passedMillis = ($now - self::$last) * 1000;
        error_log(sprintf('%dms (%dms) till %s',
            $totalMillis, $passedMillis, $point));
        self::$last = $now;
    }
}
