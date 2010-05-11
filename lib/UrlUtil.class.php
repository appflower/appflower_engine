<?php

class UrlUtil {
    /**
     * Returns a new url with the given param added.
     */
    public static function addParam($url, $param, $value) {
        $suffix = sprintf('%s=%s', $param, urlencode($value));
        if (StringUtil::isIn('?', $url)) {
            return $url.'&'.$suffix;
        } else {
            return $url.'?'.$suffix;
        }
    }

    /**
     * A faster link_to() for simple usages.
     */
    public static function link($name, $url) {
        if(!StringUtil::startsWith($url, '/')) {
            $url = '/'.$url;
        }
        return '<a href="'.$url.'">'.htmlspecialchars($name).'</a>';
    }
}
