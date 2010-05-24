<?php

class afOutput {
    public function asCsv($row) {
        $cells = array();
        foreach($row as $value) {
            $cells[] = '"'.str_replace('"', '""', $value).'"';
        }
        return implode(',', $cells);
    }
}
