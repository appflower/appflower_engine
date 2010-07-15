<?php

class afColumnExtractor {
    private
        $class,
        $selectedColumns,
        $formatMethodPrefix,
        $formatConversion;

    public function __construct($class, $selectedColumns, $format='html') {
        $this->class = $class;
        $this->selectedColumns = $selectedColumns;
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
            $getter = $this->createMethodGetter($methodName);
        }
        return $getter;
    }

    private function createMethodGetter($methodName) {
        $formatMethod = preg_replace('/^get/',
            $this->formatMethodPrefix, $methodName, 1);
        if(method_exists($this->class, $formatMethod)) {
            $methodName = $formatMethod;
            $conversion = null;
        } else {
            $conversion = $this->formatConversion;
        }

        return new afMethodGetter($methodName, $conversion);
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
                $row[$column] = $getter->getFrom($obj);
            }
            $rows[] = $row;
        }
        return $rows;
    }
}

