<?php

class afSelectionSource {
    private
        $selections;

    public function __construct($selections) {
        self::removeActions($selections);
        $this->selections = $selections;
    }

    private static function removeActions(&$rows) {
        foreach($rows as &$row) {
            foreach($row as $key => $value) {
                if(preg_match('/^action\d+$/', $key) === 1) {
                    unset($row[$key]);
                }
            }
        }
    }

    public function getTotalCount() {
        return count($this->selections);
    }

    public function getRows() {
        return $this->selections;
    }
}

