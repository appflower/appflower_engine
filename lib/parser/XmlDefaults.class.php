<?php

class XmlDefaults {
    private $defaults;

    public function __construct($schemaXpath) {
        $this->defaults = self::parseDefaults($schemaXpath);
    }

    private static function parseDefaults($xpath) {
        $defaults = array();
        $attrs = $xpath->evaluate("//*[@default|@fixed]");
        foreach($attrs as $node) {
            $parent = $xpath->evaluate("..",$node)->item(0);
            while($parent->nodeName != "xs:attributeGroup" && $parent->nodeName != "xs:element") {
                $parent = $xpath->evaluate("..",$parent)->item(0);
            }

            unset($key);
            if($node->hasAttribute("default")) {
                $key = "default";
            } else if($node->hasAttribute("fixed")) {
                $key = "fixed";
            }

            if(isset($key)) {
                $defaults[$parent->getAttribute("name")][$node->getAttribute("name")] = $node->getAttribute($key);
            }
        }

        return $defaults;
    }

    /**
     * Sets default attributes in all descendants of the given tree.
     */
    public function setTreeDefaults($tree) {
        foreach ($tree->childNodes as $node) {
            if (!($node instanceof DOMElement)) {
                continue;
            }

            self::setElementDefaults($node);
            self::setTreeDefaults($node);
        }
    }

    /**
     * Sets the default attributes on the element
     * if they are not set explicitly.
     */
    public function setElementDefaults($element) {
        $elName = $element->tagName;
        $pos = strpos($elName, ':');
        if($pos !== false) {
            $elName = substr($elName, $pos + 1);
        }

        if(isset($this->defaults[$elName])) {
            self::setUnset($element, $this->defaults[$elName]);
        }

        $groupName = $elName.'Attributes';
        if(isset($this->defaults[$groupName])) {
            self::setUnset($element, $this->defaults[$groupName]);
        }

        if($element->hasAttribute('parsable')) {
            self::setUnset($element, $this->defaults['commonAttributes']);
        }
    }

    /**
     * Copies the attributes to the element
     * if the attributes are not set there already.
     */
    private function setUnset($element, $attrValues) {
        foreach($attrValues as $name => $value) {
            if(!$element->hasAttribute($name)) {
                $element->setAttribute($name, $value);
            }
        }
    }
}
