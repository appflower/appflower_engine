<?php

class afColumnExtractor {
    private
        $class,
        $selectedColumns,
        $formatMethodPrefix,
        $formatConversion,
        $specialColumns = array('_color');

    public function __construct($class, $selectedColumns, $format='html') {
        $this->class = $class;
        /**
         * added new getter method for row color, if an object has method like
         * 
         * public function get_Color() { return 'green'; }
         * 
         * then row gets a background color
         * 
         * @author Radu Topala <radu@appflower.com>
         */
        $this->selectedColumns = array_merge($selectedColumns,$this->specialColumns);
        $camelFormat = sfInflector::camelize($format);
        $this->formatMethodPrefix = 'get'.$camelFormat;
        $this->formatConversion = call_user_func(
            array('afTo'.$camelFormat.'Conversion', 'getInstance'));
    }

    public function getClass() {
        return $this->class;
    }

    public function getSelectedColumns() {
        return $this->selectedColumns;
    }

    private function prepareGetters() {
        $tableMap = afMetaDb::getTableMap($this->class);
        $getters = array();
        foreach($this->selectedColumns as $column) {
            $getters[$column] = $this->createGetter($tableMap, $column);
        }

        return $getters;
    }

    private function createGetter($tableMap, $column) {
        if($tableMap->containsColumn($column)) {
            $col = $tableMap->getColumn($column);
            if($col->getRelatedTableName()) {
                $methodName = afMetaDb::getRelatedMethodName($col);
                $getter = $this->createMethodGetter($methodName);
            } else {
                $methodName = 'get'.$col->getPhpName();
                if($col->isTemporal()) {
                    $getter = new afDatetimeGetter($methodName);
                } else {
                    $getter = $this->createMethodGetter($methodName);
                }
            }
        } else {
            $methodName = 'get'.sfInflector::camelize($column);
            $getter = $this->createMethodGetter($methodName,in_array($column,$this->specialColumns));
        }
        return $getter;
    }

    private function createMethodGetter($methodName, $isSpecialColumn = false) {
        if(StringUtil::startsWith($methodName, $this->formatMethodPrefix)) {
            $conversion = null;
        } else {
            $formatMethod = preg_replace('/^get/',
                $this->formatMethodPrefix, $methodName, 1);
            if(method_exists($this->class, $formatMethod)) {
                $methodName = $formatMethod;
                $conversion = null;
            } else {
                $conversion = $this->formatConversion;
            }
        }

        return new afMethodGetter($methodName, $conversion, $isSpecialColumn);
    }

    /**
     * Extracts an array of values from each object.
     */
    public function extractColumns($objects) {
        // Once the getters are prepared,
        // no expensive logic is done inside of the loop.
        $getters = $this->prepareGetters();
        $rows = array();
        foreach($objects as $obj) {
            $row = array();
            foreach($getters as $column => $getter) {
                $value = $getter->getFrom($obj);
                if((in_array($column,$this->specialColumns)&&$value!=false)||!in_array($column,$this->specialColumns))
                {
                    $row[$column] = $value;
                }
            }
            $rows[] = $row;
        }
        return $rows;
    }
}

