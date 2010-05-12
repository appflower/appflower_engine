<?php

class afStaticSource implements afIDataSource {
    private
        $callback,
        $params,
        $start = 0,
        $limit = null,
        $sortColumn = null,
        $sortDir = 'ASC',
        $results = null;

    public function __construct($callback, $params) {
        $this->callback = $callback;
        $this->params = $params;
    }

    public function setLimit($limit) {
        if($limit === null) {
            $this->limit = null;
        } else {
            $this->limit = max(0, $limit);
        }
    }

    public function setStart($start) {
        $this->start = max(0, $start);
    }

    public function setSort($column, $sortDir='ASC') {
        $this->sortColumn = $column;
        $this->sortDir = $sortDir;
    }

    public function getTotalCount() {
        $this->init();
        return count($this->results);
    }

    public function getRows() {
        $this->init();

        if($this->sortColumn) {
            RowCmp::sortByColumn($this->results,
                $this->sortColumn, $this->sortDir);
        }

        // The slicing have to be done after sorting.
        if($this->limit !== null) {
            $rows = array_slice($this->results, $this->start, $this->limit);
        } else {
            $rows = array_slice($this->results, $this->start);
        }
        return $rows;
    }

    private function init() {
        if($this->results !== null) {
            return;
        }

        // Results will be a numeric array of values.
        $this->results = array_values(
            call_user_func_array($this->callback, $this->params));
    }
}

