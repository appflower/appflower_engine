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

    public function getDefaults($elName) {
        $elDefaults = array();
        if(isset($this->defaults[$elName])) {
            $elDefaults += $this->defaults[$elName];
        }
        $groupName = $elName.'Attributes';
        if(isset($this->defaults[$groupName])) {
            $elDefaults += $this->defaults[$groupName];
        }
        //TODO: Add also the common attributes when $element[@parsable] is true.

        return $elDefaults;
    }
}
