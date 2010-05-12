<?php

class afPropelSource implements afIDataSource {
    private
        $class,
        $extractor,
        $criteria,
        $start = 0,
        $limit = null,
        $pager = null,
        $sortColumn = null,
        $sortDir = 'ASC',
        $initialized = false;

    public function __construct($extractor) {
        $this->class = $extractor->getClass();
        $this->extractor = $extractor;
        $this->criteria = new Criteria();
    }

    public function setCriteria($criteria) {
        $this->initialized = false;
        $this->criteria = $criteria;
    }

    public function setLimit($limit) {
        $this->initialized = false;
        if($limit === null) {
            $this->limit = null;
        } else {
            $this->limit = max(0, $limit);
        }
    }

    public function setStart($start) {
        $this->initialized = false;
        $this->start = max(0, $start);
    }

    public function setSort($column, $sortDir='ASC') {
        $this->initialized = false;
        $this->sortColumn = $column;
        $this->sortDir = $sortDir;
    }

    public function getTotalCount() {
        $this->init();
        return $this->pager->getNbResults();
    }

    public function getRows() {
        // Propel would consider limit=0 to mean limit=unlimited.
        if($this->limit === 0) {
            return array();
        }

        $this->init();
        $objects = $this->pager->getResults();
        return $this->extractor->extractColumns($objects);
    }

    private function init() {
        if($this->initialized) {
            return;
        }
        $this->initialized = true;

        if($this->sortColumn) {
            $this->criteria->clearOrderByColumns();
            if($this->sortDir === 'DESC') {
                $this->criteria->addDescendingOrderByColumn($this->sortColumn);
            } else {
                $this->criteria->addAscendingOrderByColumn($this->sortColumn);
            }
        }

        $this->pager = new sfPropelPager($this->class, $this->limit);
        $this->pager->setCriteria($this->criteria);
        //TODO: set the other properties
        //$this->pager->setPeerMethod($selectMethod);

        $this->pager->init();
        // The offset have to be set after the pager init
        // to allow to set any offset.
        $c = $this->pager->getCriteria();
        $c->setOffset($this->start);
    }
}

