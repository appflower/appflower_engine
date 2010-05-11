<?php

/**
 * Utils to access a list view configuration.
 */
class afListView {
    private
        $view;

    public function __construct($view) {
        $this->view = $view;
    }

    public function getSelectedColumns() {
        $columns = $this->view->wrapAll('fields/column');
        $selectedColumns = array();
        foreach($columns as $column) {
            $selectedColumns[] = $column->get('@name');
        }

        $selectedColumns = array_merge($selectedColumns,
            $this->getRowActionSelectedColumns());
        return array_unique($selectedColumns);
    }

    private function getRowActionSelectedColumns() {
        $columns = array();
        $rowactions = $this->view->wrapAll('rowactions/action');
        foreach($rowactions as $action) {
            $params = explode(',', $action->get('@params', 'id'));
            $columns = array_merge($columns, $params);
        }
        return array_unique($columns);
    }
}

