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
            if($tableMap->containsColumn($column)) {
                $col = $tableMap->getColumn($column);
                $colPhpname = $col->getPhpName();
            } else {
                $colPhpname = sfInflector::camelize($column);
            }

            $getters[$column] = 'get'.$colPhpname;
        }

        return $getters;
    }

    public function extractColumns($objects) {
        $rows = array();
        foreach($objects as $obj) {
            $row = array();
            foreach($this->getters as $column => $getter) {
                $row[$column] = call_user_func(array($obj, $getter));
            }
            $rows[] = $row;
        }
        return $rows;
    }
}

