<?php

class afColumnExtractor {
    private
        $class,
        $selectedColumns;

    public function __construct($class, $selectedColumns) {
        $this->class = $class;
        $this->init($selectedColumns);
    }

    private function init($selectedColumns) {
        $peer = constant($this->class.'::PEER');
        $tableMap = call_user_func(array($peer, 'getTableMap'));
        foreach($selectedColumns as $column) {
            if($tableMap->containsColumn($column)) {
                $col = $tableMap->getColumn($column);
                Console::debug('col', $column, $col->getRelatedColumnName());
            } else {
                Console::debug('php', $column);
            }
        }
    }

    public function extractColumns($objects) {
        $rows = array();
        foreach($objects as $obj) {
            $row = array();
            $rows[] = $row;
            //TODO: fetch to values
        }
        return $rows;
    }
}

