<?php

class Console {
    private static $startedAt = null;
    private static $last;
    public static $profilingEnabled = false;

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
        if(!self::$profilingEnabled) {
            return;
        }

        $path = $_SERVER['REQUEST_URI'];
        if(StringUtil::startsWith($path, '/parser/listjson')) {
            $memory = apc_fetch('Console::memory');
            if($memory !== false) {
                list(self::$startedAt, self::$last) = $memory;
            }
        }


        $now = microtime(true);
        if(self::$startedAt === null) {
            self::$startedAt = $now;
            self::$last = $now;
        }

        $totalMillis = ($now - self::$startedAt) * 1000;
        $passedMillis = ($now - self::$last) * 1000;
        file_put_contents('php://stderr', sprintf("%dms (%dms) till %s, %s\n",
            $totalMillis, $passedMillis, $point, $path));
        self::$last = $now;
        apc_store('Console::memory', array(self::$startedAt, self::$last));
    }
}
