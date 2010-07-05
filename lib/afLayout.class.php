<?php

/**
 * A way to define layout parameters.
 * The parameters will be used only when using an ExtJs display format.
 * Other formats could ignore them.
 */
class afLayout {
    private static $initSource = '';
    private static $layout = null;

    public static function addInitSource($js) {
        if (self::$layout) {
            self::$layout->addInitMethodSource($js);
        } else {
            self::$initSource .= $js;
        }
    }

    public static function registerLayout($layout) {
        if (self::$layout) {
            throw new Exception('Layout is already registered.');
        }

        self::$layout = $layout;
        self::$layout->addInitMethodSource(self::$initSource);
        self::$initSource = null;
    }
}
