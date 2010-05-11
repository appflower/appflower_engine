<?php

class afStaticSource implements afIDataSource {
    private
        $callback,
        $params,
        $start = 0,
        $limit = 0,
        $results = null;

    public function __construct($callback, $params) {
        $this->callback = $callback;
        $this->params = $params;
    }

    public function setLimit($limit) {
        $this->limit = max(0, $limit);
    }

    public function setStart($start) {
        $this->start = max(0, $start);
    }

    public function getTotalCount() {
        $this->init();
        return count($this->results);
    }

    public function getRows() {
        $this->init();
        if($this->limit > 0) {
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

        $this->results = call_user_func_array($this->callback, $this->params);
    }
}

