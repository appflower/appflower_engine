<?php

class UrlUtil {
    /**
     * Returns a new url with the given param added.
     */
    public static function addParam($url, $param, $value) {
        $suffix = sprintf('%s=%s', $param, $value);
        if (StringUtil::isIn('?', $url)) {
            return $url.'&'.$suffix;
        } else {
            return $url.'?'.$suffix;
        }
    }
}
