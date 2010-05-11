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

        $selectedColumns += $this->getRowActionSelectedColumns();
        $selectedColumns = array_unique($selectedColumns);
        return $selectedColumns;
    }

    private function getRowActionSelectedColumns() {
        $columns = array();
        $rowactions = $this->view->wrapAll('rowactions/action');
        foreach($rowactions as $action) {
            $columns += explode(',', $action->get('@params', 'id'));
        }
        return array_unique($columns);
    }
}

