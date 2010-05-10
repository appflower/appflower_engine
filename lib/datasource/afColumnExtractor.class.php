<?php

class afColumnExtractor {
    private
        $getters;

    public function __construct($class, $selectedColumns) {
        $this->getters = self::prepareGetters($class, $selectedColumns);
    }

    private function prepareGetters($class, $selectedColumns) {
        $peer = constant($class.'::PEER');
        $tableMap = call_user_func(array($peer, 'getTableMap'));
        $getters = array();
        foreach($selectedColumns as $column) {
            $getters[$column] = self::createGetter($tableMap, $column);
        }

        return $getters;
    }

    private static function createGetter($tableMap, $column) {
        if($tableMap->containsColumn($column)) {
            $col = $tableMap->getColumn($column);
            if($col->getRelatedTableName()) {
                $methodName = afMetaDb::getForeignMethodName($col);
                $getter = new afStringMethodGetter($methodName);
            } else {
                $methodName = 'get'.$col->getPhpName();
                if($col->isTemporal()) {
                    $getter = new afDatetimeGetter($methodName);
                } else {
                    $getter = new afMethodGetter($methodName);
                }
            }
        } else {
            $methodName = 'get'.sfInflector::camelize($column);
            $getter = new afMethodGetter($methodName);
        }
        return $getter;
    }

    /**
     * Extracts an array of values from each object.
     */
    public function extractColumns($objects) {
        // The getters are already prepared,
        // no expensive logic is done inside of the loop.
        $rows = array();
        foreach($objects as $obj) {
            $row = array();
            foreach($this->getters as $column => $getter) {
                $row[$column] = $getter->getFrom($obj);
            }
            $rows[] = $row;
        }
        return $rows;
    }
}

