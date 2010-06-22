<?php

class UrlUtil {
    /**
     * Returns a new url with the given param added.
     */
    public static function addParam($url, $param, $value) {
        $url = self::prepareForParams($url);
        return $url.sprintf('%s=%s', $param, urlencode($value));
    }

    /**
     * Returns a new url with all the given params added.
     */
    public static function addParams($url, $params) {
        $url = self::prepareForParams($url);
        $first = true;
        foreach($params as $param => $value) {
            if(!$first) {
                $url .= '&';
            }
            $url .= sprintf('%s=%s', $param, urlencode($value));
            $first = false;
        }
        return $url;
    }

    private static function prepareForParams($url) {
        if (StringUtil::isIn('?', $url)) {
            return $url.'&';
        } else {
            return $url.'?';
        }
    }

    /**
     * Returns the /path/to/something part.
     * The protocol, host and GET params are stripped out.
     */
    public static function getPathPart($url) {
        return preg_replace('@^https?://[^/]*([^?#]*).*$@', '$1', $url);
    }

    /**
     * A faster link_to() for simple usages.
     */
    public static function link($name, $url) {
        $url = self::abs($url);
        return '<a href="'.$url.'">'.htmlspecialchars($name).'</a>';
    }

    /**
     * Returns an /absolute URL.
     */
    public static function abs($url) {
        if(!StringUtil::startsWith($url, '/')) {
            $url = '/'.$url;
        }
        return $url;
    }
    
    /**
     * Return absolute url for appFlower trunk
     */
    public static function url($url)
    {
    	return sfContext::getInstance()->getRequest()->getUriPrefix().'/#'.$url;
    }
}
