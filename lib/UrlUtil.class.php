<?php

class UrlUtil {
    /**
     * Returns a new url with the given param added.
     */
    public static function addParam($url, $param, $value) {
        $url = self::prepareForParams($url);
        return $url.http_build_query(array($param=>$value));
    }

    /**
     * Returns a new url with all the given params added.
     */
    public static function addParams($url, $params) {
        $url = self::prepareForParams($url);
        return $url.http_build_query($params);
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
    * A faster link_to() for simple usages. For html content
    */
    public static function linkHtml($name, $url) {
        $url = self::abs($url);
        return '<a href="'.$url.'">'.($name).'</a>';
    }

    /**
     * A link with an HTML in the name.
     */
    public static function htmlLink($name, $url) {
        $url = self::abs($url);
        return '<a href="'.$url.'">'.$name.'</a>';
    }

    /**
     * Returns an <a> tag to open the given widget.
     */
    public static function widgetLink($name, $url) {
        $url = self::abs($url);
        return '<a href="'.sfContext::getInstance()->getRequest()->getRelativeUrlRoot().'/#'.$url.'">'.htmlspecialchars($name).'</a>';
    }

    /**
     * Returns an <a> tag with the given HTML content.
     */
    public static function widgetHtmlLink($html, $url, $ajax = true) {
        $url = self::abs($url);
        return '<a href="'.sfContext::getInstance()->getRequest()->getRelativeUrlRoot().(($ajax) ? '/#' : '').$url.'">'.$html.'</a>';
        
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
     * returns relative internal url with /#
     */
    public static function widgetUrl($url,$layout='')
    {
    	return sfContext::getInstance()->getRequest()->getRelativeUrlRoot().'/'.$layout.'#'.$url;
    }
    
    /**
     * return absolute internal url with /#
     */
    public static function widgetAbsoluteUrl($url)
    {
    	return sfContext::getInstance()->getRequest()->getUriPrefix().sfContext::getInstance()->getRequest()->getRelativeUrlRoot().'/#'.$url;
    }
    
    /**
     * Return url for appFlower trunk, with /#
     */
    public static function url($url,$absolute=false)
    {
    	return ($absolute?sfContext::getInstance()->getRequest()->getUriPrefix():'').sfContext::getInstance()->getRequest()->getRelativeUrlRoot().'/#'.$url;
    }
}
