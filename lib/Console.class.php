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

        $path = "";
        if(isset($_SERVER['REQUEST_URI'])) {
            $path = $_SERVER['REQUEST_URI'];
        }

        if(strpos($path, 'af_format=json') !== false) {
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
        file_put_contents('php://stderr', self::formatPoint(
            $totalMillis, $passedMillis, $point, $path));
        self::$last = $now;
        apc_store('Console::memory', array(self::$startedAt, self::$last));
    }

    public static function restartProfiling() {
        self::$startedAt = null;
        file_put_contents('php://stderr', "\n");
    }

    private static function formatPoint($totalMillis, $passedMillis, $point, $path) {
        return sprintf("%dms (%dms) till %s, %s\n",
            $totalMillis, $passedMillis, $point, $path);
    }

    public static function formatSvgPoint($totalMillis, $passedMillis, $point, $path) {
        $scale = 8.0;
        $fontSize = 16;
        $boundary = $fontSize;

        $output = '';
        if ($totalMillis == 0) {
            $output .= '<svg xmlns="http://www.w3.org/2000/svg">'."\n";
        } else {
            $height = $passedMillis/$scale;
            $output .= sprintf('<rect fill="yellow" x="0" y="%.3f" height="%.3f" width="20"/>'."\n",
                $totalMillis/$scale + $boundary - $height + 1, $height);
        }
        $output .= sprintf('<rect x="0" y="%.3f" height="1" width="28"/>'."\n",
                $totalMillis/$scale + $boundary);
        $output .= sprintf('<text x="30" y="%.3f" font-size="%s" font-family="Verdana">%dms (%dms) %s</text>'."\n",
            $totalMillis/$scale + $boundary, $fontSize,
            $totalMillis, $passedMillis, $point);

        return $output;
    }
}
