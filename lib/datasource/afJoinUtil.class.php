<?php

class afJoinUtil {
    public static function chooseJoins($criteria, $class,
            $selectedColumns, $referencedTables) {
        list($duplication, $selectedFCols, $excludedFCols) =
            self::getForeignColsSelection(
                $class, $selectedColumns, $referencedTables);
        if(count($selectedFCols) === 0) {
            return 'doSelect';
        }

        if(!$duplication && count($excludedFCols) === 0) {
            return 'doSelectJoinAll';
        }

        if(count($selectedFCols) === 1) {
            $col = $selectedFCols[0];
            return 'doSelectJoin'.afMetaDb::getRelatedAffix($col);
        }

        if(!$duplication && count($excludedFCols) === 1) {
            $col = $excludedFCols[0];
            return 'doSelectJoinAllExcept'.afMetaDb::getRelatedAffix($col);
        }

        foreach($selectedFCols as $fcol) {
            $criteria->addJoin($fcol->getFullyQualifiedName(),
                $fcol->getRelatedTableName().'.'.$fcol->getRelatedColumnName(),
                Criteria::LEFT_JOIN);
        }
        return 'doSelect';
    }

    private static function getForeignColsSelection($class,
            $selectedColumns, $refTables) {
        $tableMap = afMetaDb::getTableMap($class);
        $duplication = false;
        $joined = array();
        $selectedFCols = array();
        $excludedFCols = array();
        foreach($tableMap->getColumns() as $col) {
            $relatedTable = $col->getRelatedTableName();
            if($relatedTable) {
                if(in_array($relatedTable, $refTables) ||
                    in_array(strtolower($col->getName()), $selectedColumns)) {

                    if(isset($joined[$relatedTable])) {
                        // It prevents multiple joins to the same table.
                        $duplication = true;
                        $excludedFCols[] = $col;
                    } else {
                        $joined[$relatedTable] = true;
                        $selectedFCols[] = $col;
                    }
                } else {
                    $excludedFCols[] = $col;
                }
            }
        }

        return array($duplication, $selectedFCols, $excludedFCols);
    }
}

