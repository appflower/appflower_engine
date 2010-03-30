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
     * Sets the default attributes on the element
     * if they are not set explicitly.
     */
    public function setDefaults($element) {
        $elName = $element->tagName;
        if(isset($this->defaults[$elName])) {
            self::setUnset($element, $this->defaults[$elName]);
        }

        $groupName = $elName.'Attributes';
        if(isset($this->defaults[$groupName])) {
            self::setUnset($element, $this->defaults[$groupName]);
        }

        //TODO: Set also the common attributes when $element[@parsable] is true.
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
