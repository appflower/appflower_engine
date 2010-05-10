<?php

class afDomAccess {
    private $node;

    private function __construct($node) {
        $this->node = $node;
    }

    /**
     * Creates a wrapper to access values on the given path.
     */
    public static function wrap($node, $path='') {
        return new afDomAccess(self::getElement($node, $path));
    }

    /**
     * Wraps all elements on the given paths.
     * It returns an array of the wrapped elements.
     */
    public function wrapAll($path) {
        $wrappers = array();
        $elements = self::getElements($this->node, $path);
        foreach($elements as $element) {
            $wrappers[] =  new afDomAccess($element);
        }
        return $wrappers;
    }

    /**
     * Returns a text value at the given path.
     * The path could be a path/to/element or a path/to@attribute.
     */
    public function get($path, $default='') {
        return self::getValue($this->node, $path, $default);
    }

    public function getBool($path, $default=false) {
        $value = self::getValue($this->node, $path, $default);
        return $value === true || $value === 'true';
    }



    /**
     * Returns the first DOM element on the given path/to/element.
     * It return null when there is no such element in the doc.
     */
    private static function getElement($node, $path) {
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
     * Returns the all DOM elements on the given path.
     */
    private static function getElements($node, $path) {
        $newToexpand = array($node);

        $parts = explode('/', $path);
        foreach($parts as $part) {
            $toexpand = $newToexpand;
            $newToexpand = array();
            foreach($toexpand as $node) {
                $children = self::getChildElements($node, $part);
                $newToexpand += $children;
            }
        }
        return $newToexpand;
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

    /**
     * Returns all child elements with the given name.
     */
    private static function getChildElements($node, $name) {
        $elements = array();
        foreach ($node->childNodes as $child) {
            if(!($child instanceof DOMElement)) {
                continue;
            }

            if($child->localName === $name) {
                $elements[] = $child;
            }
        }
        return $elements;
    }
}
