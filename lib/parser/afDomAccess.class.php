<?php

class afDomAccess {
    /**
     * Returns the first DOM element on the given path/to/element.
     * It return null when there is no such element in the doc.
     */
    public static function getElement($node, $path) {
        if($path === '') {
            return $node;
        }

        $parts = explode('/', $path);
        foreach($parts as $part) {
            $child = self::getChildElement($node, $part);
            if($child === null) {
                return null;
            }

            $node = $child;
        }
        return $node;
    }

    /**
     * Returns a text value at the given path.
     * The path could be a path/to/element or a path/to@attribute.
     */
    public static function getString($node, $path, $default=null) {
        return self::getValue($node, $path, $default);
    }

    private static function getValue($node, $path, $default=null) {
        $pathToAttribute = explode('@', $path);
        if(count($pathToAttribute) === 1) {
            $element = self::getElement($node, $path);
            if($element === null) {
                return $default;
            }
            return $element->textContent;
        }

        list($path, $attr) = $pathToAttribute;
        $element = self::getElement($node, $path);
        if($element === null) {
            return $default;
        }
        if(!$element->hasAttribute($attr)) {
            return $default;
        }

        return $element->getAttribute($attr);
    }

    /**
     * Returns the first found child element or null.
     */
    private static function getChildElement($node, $name) {
        foreach ($node->childNodes as $child) {
            if(!($child instanceof DOMElement)) {
                continue;
            }

            if($child->localName === $name) {
                return $child;
            }
        }
        return null;
    }
}
