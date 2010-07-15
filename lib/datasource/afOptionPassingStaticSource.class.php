<?php

class afOptionPassingStaticSource extends afStaticSource {
    private $filters;

    public function setFilters($filters) {
        $this->filters = $filters;
    }

    protected function getResponse() {
        $options = array(
            'filter'=>$this->filters,
            'start'=>$this->start,
            'limit'=>$this->limit,
            'sort'=>$this->sortColumn,
            'dir'=>$this->sortDir);
        $params = $this->params;
        $params[] = $options;
        return afCall::funcArray($this->callback, $params);
    }
}

