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
            //TODO: use the FK column getter for FKs.
            $colPhpname = $col->getPhpName();
        } else {
            $colPhpname = sfInflector::camelize($column);
        }
        return new afMethodGetter('get'.$colPhpname);
    }

    public function extractColumns($objects) {
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

