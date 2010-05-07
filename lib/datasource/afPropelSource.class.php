<?php

class afPropelSource implements afIDataSource {
    private
        $class,
        $selectedColumns,
        $criteria,
        $start = 0,
        $limit = 25,
        $pager = null,
        $initialized = false;

    public function __construct($class, $selectedColumns) {
        $this->class = $class;
        $this->selectedColumns = $selectedColumns;
        $this->criteria = new Criteria();
    }

    public function setCriteria($criteria) {
        $this->initialized = false;
        $this->criteria = criteria;
    }

    public function setLimit($limit) {
        $this->initialized = false;
        $this->limit = max(0, $limit);
    }

    public function setStart($start) {
        $this->initialized = false;
        $this->start = max(0, $start);
    }

    public function getTotalCount() {
        $this->init();
        return $this->pager->getNbResults();
    }

    public function getRows() {
        $this->init();
        $objects = $this->pager->getResults();
        //TODO: implement
        return $objects;
    }

    private function init() {
        if($this->initialized) {
            return;
        }
        $this->initialized = true;

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

